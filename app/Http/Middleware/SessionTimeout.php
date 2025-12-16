<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for API routes, login, logout, and static assets
        if ($request->is('api/*') ||
            $request->is('login') ||
            $request->is('logout') ||
            $request->is('extend-session') ||
            $request->is('check-session') ||
            $request->routeIs('login') ||
            $request->routeIs('logout') ||
            $request->routeIs('extend_session') ||
            $request->routeIs('check_session') ||
            str_starts_with($request->path(), 'adminlte/') ||
            str_starts_with($request->path(), 'css/') ||
            str_starts_with($request->path(), 'js/') ||
            str_starts_with($request->path(), 'images/')) {
            return $next($request);
        }

        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Check if user is banned
            if ($user->status === 'banned') {
                Auth::logout();
                return redirect('/login')->with('failed', 'Akun Anda telah diblokir.');
            }

            // Update last activity timestamp
            $user->updated_at = now();
            $user->save();

            // Store last activity in session
            Session::put('last_activity', now());

            // Check session timeout (2 hours = 7200 seconds)
            $lastActivity = Session::get('last_activity');
            if ($lastActivity) {
                $inactiveTime = now()->diffInSeconds($lastActivity);
                $timeout = config('session.lifetime', 3) * 60; // Convert minutes to seconds

                if ($inactiveTime > $timeout) {
                    Auth::logout();
                    Session::flush();

                    // If it's an AJAX request, return JSON
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'message' => 'Session expired due to inactivity',
                            'expired' => true
                        ], 401);
                    }

                    return redirect('/login')->with('failed', 'Sesi Anda telah berakhir karena tidak ada aktivitas.');
                }
            }
        }

        return $next($request);
    }
}