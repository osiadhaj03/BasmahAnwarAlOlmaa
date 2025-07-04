<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/admin/login');
        }

        $user = Auth::user();
        
        // السماح للمدير والمعلم بالوصول، لكن مع قيود في Controllers
        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'غير مسموح لك بالوصول لهذه الصفحة');
        }

        return $next($request);
    }
}
