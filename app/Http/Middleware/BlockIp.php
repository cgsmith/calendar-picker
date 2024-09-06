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
        $stringOfIps = (isset($_ENV['ALLOWED_IPS']) && is_string($_ENV['ALLOWED_IPS'])) ? $_ENV['ALLOWED_IPS'] : '';
        $allowedIPs = explode(separator: ',', string: $stringOfIps);
        array_walk($allowedIPs, function (&$value) {
            $value = str(trim($value));
        });

        $this->allowIps = $allowedIPs;
    }

    /**
     * Add the IP address to the error message if app.debug is true
     */
    private function addIpToDebug(Request $request): string
    {
        return config('app.debug') ? ' IP '.$request->ip() : '';
    }

    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! in_array($request->ip(), $this->allowIps)) {
            return response()->json([
                'message' => "You don't have permission to access this page.".$this->addIpToDebug($request),
            ], 401);
        }

        return $next($request);
    }
}
