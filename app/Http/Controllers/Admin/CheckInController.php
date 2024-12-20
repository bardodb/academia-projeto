<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CheckIn::with(['student.user'])
            ->orderBy('check_in_time', 'desc');

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('check_in_time', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('check_in_time', '<=', $request->end_date);
        }

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $checkIns = $query->paginate(15);
        $students = Student::with('user')->get();

        return view('admin.check-ins.index', compact('checkIns', 'students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::with('user')->get();
        return view('admin.check-ins.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'check_in_time' => 'nullable|date',
        ]);

        $checkIn = new CheckIn();
        $checkIn->student_id = $validated['student_id'];
        $checkIn->check_in_time = $validated['check_in_time'] ?? now();
        $checkIn->save();

        return redirect()->route('admin.check-ins.index')
            ->with('success', 'Check-in registered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(CheckIn $checkIn)
    {
        $checkIn->delete();
        return redirect()->route('admin.check-ins.index')
            ->with('success', 'Check-in deleted successfully.');
    }
}
