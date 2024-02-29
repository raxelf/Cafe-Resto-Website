<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyXenditWebhookSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $expectedToken = config('services.xendit.secret_webhook');
        $xenditToken = $request->header('x-callback-token');

        if ($xenditToken !== $expectedToken) {
            return response()->json([
                'error' => 'Unauthorized: Invalid Xendit webhook token',
            ], 401);
        }

        return $next($request);
    }
}
