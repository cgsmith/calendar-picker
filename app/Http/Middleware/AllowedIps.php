<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AllowedIps
{
    /**
     * Set IP addresses in ALLOWED_IPS env variable
     * - If empty, all IPs are allowed
     */
    public array $ips = [];

    public function __construct()
    {
        // Explode list of IPs into an array and perform a trim on them
        $ips = explode(separator: ',', string: Config::get('app.allowed_ips'));

        foreach ($ips as $ip) {
            $trimmedValue = trim($ip);
            if (! empty($trimmedValue)) {
                $this->ips[] = str($trimmedValue);
            }
        }
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
        if (count($this->ips) === 0) {
            return $next($request);
        }

        if (! in_array($request->ip(), $this->ips)) {
            return response()->json([
                'message' => "You don't have permission to access this page.".$this->addIpToDebug($request),
            ], 401);
        }

        return $next($request);
    }
}
