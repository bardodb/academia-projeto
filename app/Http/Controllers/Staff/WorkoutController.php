<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Workout;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workouts = Workout::with('exercises')->paginate(10);
        return view('staff.workouts.index', compact('workouts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staff.workouts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'exercises' => 'required|array',
            'exercises.*.name' => 'required|string',
            'exercises.*.sets' => 'required|integer',
            'exercises.*.reps' => 'required|integer',
        ]);

        $workout = Workout::create($validated);
        return redirect()->route('staff.workouts.show', $workout)
            ->with('success', 'Workout created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Workout $workout)
    {
        $workout->load('exercises');
        return view('staff.workouts.show', compact('workout'));
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

    public function studentWorkouts(Student $student)
    {
        $student->load(['workouts' => function ($query) {
            $query->orderBy('pivot_created_at', 'desc');
        }]);
        $availableWorkouts = Workout::all();
        
        return view('staff.students.workouts', compact('student', 'availableWorkouts'));
    }

    public function assignWorkout(Request $request, Student $student)
    {
        $validated = $request->validate([
            'workout_id' => 'required|exists:workouts,id',
            'notes' => 'nullable|string',
        ]);

        $student->workouts()->attach($validated['workout_id'], [
            'notes' => $validated['notes'],
            'assigned_at' => now(),
        ]);

        return redirect()->route('staff.students.workouts', $student)
            ->with('success', 'Workout assigned successfully');
    }
}
