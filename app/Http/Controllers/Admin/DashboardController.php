<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\CheckIn;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students' => Student::count(),
            'active_students' => Student::where('active', true)->count(),
            'total_plans' => Plan::count(),
            'check_ins_today' => CheckIn::whereDate('check_in_time', Carbon::today())->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
