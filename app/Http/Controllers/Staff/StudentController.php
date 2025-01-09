<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Workout;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with(['plan', 'workouts'])->paginate(10);
        return view('staff.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load(['plan', 'workouts', 'checkIns']);
        return view('staff.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function progress(Student $student)
    {
        $student->load(['workouts', 'checkIns']);
        $workoutHistory = $student->workouts()
            ->withPivot('completed_at', 'notes')
            ->orderBy('pivot_created_at', 'desc')
            ->get();
            
        $checkInHistory = $student->checkIns()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('staff.students.progress', compact('student', 'workoutHistory', 'checkInHistory'));
    }
}
