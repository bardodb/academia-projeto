<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\CheckIn;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $activeStudents = Student::where('active', true)->count();
        $todayCheckIns = CheckIn::whereDate('created_at', Carbon::today())->count();

        return view('instructor.dashboard', compact('totalStudents', 'activeStudents', 'todayCheckIns'));
    }
}
