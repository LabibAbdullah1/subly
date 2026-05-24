<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Payment;

class CheckPendingPayment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $pendingPayment = Payment::where('user_id', auth()->id())
                ->where('status', 'pending')
                ->first();

            if ($pendingPayment) {
                $allowedRoutes = [
                    'client.checkout.qris',
                    'client.checkout.cancel',
                    'client.chat.*',
                    'client.notifications.*',
                    'logout'
                ];

                $currentRoute = $request->route()->getName();
                
                $isAllowed = false;
                foreach ($allowedRoutes as $allowed) {
                    if (\Illuminate\Support\Str::is($allowed, $currentRoute)) {
                        $isAllowed = true;
                        break;
                    }
                }

                if (!$isAllowed && ($request->routeIs('client.plans.index') || $request->routeIs('client.checkout.process'))) {
                    return redirect()->route('client.checkout.qris', $pendingPayment)
                        ->with('info', 'Selesaikan pembayaran Anda terlebih dahulu atau batalkan untuk memilih paket lain.');
                }
            }
        }

        return $next($request);
    }
}
