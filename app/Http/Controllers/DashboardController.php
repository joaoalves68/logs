<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LogScan;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard');
    }
}
