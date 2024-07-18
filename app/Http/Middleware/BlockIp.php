<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;

class BlockIp
{
    // set IP addresses
    public $allowIps = ['94.134.138.4', '103.204.129.148', '127.0.0.1', '::1'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! in_array($request->ip(), $this->allowIps)) {
            return response()->json([
                'message' => "You don't have permission to access this website.",
            ], 401);
        }

        return $next($request);
    }
}
