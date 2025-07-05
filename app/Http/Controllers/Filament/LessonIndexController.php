<?php

namespace App\Http\Controllers\Filament;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LessonIndexController extends Controller
{
    public function index()
    {
        // Redirect to the main lessons page
        return redirect('/admin/lessons');
    }
}
