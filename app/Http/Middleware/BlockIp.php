<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockIp
{
    /**
     * Set IP addresses in ALLOWED_IPS env variable
     */
    public array $allowIps = [];

    public function __construct()
    {
        // Explode list of IPs into an array and perform a trim on them
        $allowedIPs = explode(separator: ',', string: getenv('ALLOWED_IPS'));
        array_walk($allowedIPs, function (&$value) {
            $value = str(trim($value));
        });

        $this->allowIps = $allowedIPs;
    }

    /**
     * Add the IP address to the error message if app.debug is true
     *
     * @param Request $request
     * @return string
     */
    private function addIpToDebug(Request $request): string
    {
        return config('app.debug') ? ' IP ' . $request->ip() : '';
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!in_array($request->ip(), $this->allowIps)) {
            return response()->json([
                'message' => "You don't have permission to access this page." . $this->addIpToDebug($request),
            ], 401);
        }

        return $next($request);
    }
}
