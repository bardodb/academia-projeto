<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InstructorController extends Controller
{
    public function index()
    {
        $instructors = Instructor::with('user')->paginate(10);
        return view('admin.instructors.index', compact('instructors'));
    }

    public function create()
    {
        return view('admin.instructors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'specialties' => ['required', 'array', 'min:1'],
            'schedule' => ['required', 'array', 'min:1'],
        ]);

        // Convert schedule from checkbox format to array
        $schedule = [];
        foreach ($validated['schedule'] as $day => $value) {
            if ($value === "1") {
                $schedule[] = $day;
            }
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'staff'
            ]);

            $instructor = Instructor::create([
                'user_id' => $user->id,
                'phone' => $validated['phone'],
                'specialties' => $validated['specialties'],
                'schedule' => $schedule,
                'active' => true,
            ]);

            DB::commit();
            return redirect()->route('admin.instructors.index')
                ->with('success', 'Instructor created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to create instructor: ' . $e->getMessage()]);
        }
    }

    public function edit(Instructor $instructor)
    {
        return view('admin.instructors.edit', compact('instructor'));
    }

    public function update(Request $request, Instructor $instructor)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($instructor->user_id)],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'specialties' => ['required', 'array', 'min:1'],
            'schedule' => ['required', 'array', 'min:1'],
        ]);

        // Convert schedule from checkbox format to array
        $schedule = [];
        foreach ($validated['schedule'] as $day => $value) {
            if ($value === "1") {
                $schedule[] = $day;
            }
        }

        DB::beginTransaction();
        try {
            $user = $instructor->user;
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if (!empty($validated['password'])) {
                $user->update([
                    'password' => Hash::make($validated['password'])
                ]);
            }

            $instructor->update([
                'phone' => $validated['phone'],
                'specialties' => $validated['specialties'],
                'schedule' => $schedule,
            ]);

            DB::commit();
            return redirect()->route('admin.instructors.index')
                ->with('success', 'Instructor updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to update instructor: ' . $e->getMessage()]);
        }
    }

    public function destroy(Instructor $instructor)
    {
        try {
            $instructor->user->delete();
            $instructor->delete();
            return redirect()->route('admin.instructors.index')
                ->with('success', 'Instructor deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete instructor.']);
        }
    }

    public function toggleActive(Instructor $instructor)
    {
        $instructor->update(['active' => !$instructor->active]);
        return back()->with('success', 'Instructor status updated successfully.');
    }
}
