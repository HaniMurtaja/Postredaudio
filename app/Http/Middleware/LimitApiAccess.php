<?php

namespace App\Http\Middleware;

use App\Models\ApprovedIp;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LimitApiAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // if (app()->environment() !== 'local') {
        $allowedIps = ApprovedIp::pluck('address')->toArray();
        $allowedOrigin = explode(',', config('app.origin_urls'));

        Log::info("Request HTTP CLIENT IP: " . (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : '-'));
        Log::info("Request HTTP X Forwarded for: " . (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '-'));
        Log::info("Request Remote Addre: " . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '-'));


        // if (!in_array($request->ip(), $allowedIps) && !in_array($request->header('origin'), $allowedOrigin)) {
        //     return response()->json([
        //         'message' => 'Unauthorized host url.',
        //         'success' => false,
        //         'status' => 403
        //     ], 403);
        // }
        // }

        return $next($request);
    }
}
