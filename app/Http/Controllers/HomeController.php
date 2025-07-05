<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // إذا كان المستخدم مسجل الدخول
        if (auth()->check()) {
            $user = auth()->user();
            
            // توجيه بناء على دور المستخدم
            switch ($user->role) {
                case 'admin':
                case 'teacher':
                    return redirect()->route('admin.dashboard');
                case 'student':
                    return redirect()->route('student.dashboard');
                default:
                    return redirect()->route('admin.login');
            }
        }
        
        // إذا لم يكن مسجل الدخول، وجهه إلى صفحة تسجيل الدخول
        return redirect()->route('admin.login');
    }
}
