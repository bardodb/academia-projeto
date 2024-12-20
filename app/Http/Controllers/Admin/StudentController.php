<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['plan', 'user'])->latest()->paginate(10);
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $plans = Plan::where('active', true)->get();
        return view('admin.students.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'emergency_contact' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',
            'health_conditions' => 'nullable|string',
            'plan_id' => 'required|exists:plans,id',
            'payment_day' => 'required|integer|min:1|max:28',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addMonths($plan->duration_months);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'student'
            ]);

            // Create student
            $student = Student::create([
                'user_id' => $user->id,
                'phone' => $validated['phone'],
                'birth_date' => $validated['birth_date'],
                'emergency_contact' => $validated['emergency_contact'],
                'emergency_phone' => $validated['emergency_phone'],
                'health_conditions' => $validated['health_conditions'],
                'plan_id' => $validated['plan_id'],
                'plan_start_date' => $startDate,
                'plan_end_date' => $endDate,
                'monthly_fee' => $plan->price,
                'payment_day' => $validated['payment_day'],
                'active' => true
            ]);

            DB::commit();
            return redirect()
                ->route('admin.students.index')
                ->with('success', 'Student registered successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to register student: ' . $e->getMessage()]);
        }
    }

    public function show(Student $student)
    {
        $student->load('user', 'plan');
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $plans = Plan::where('active', true)->get();
        return view('admin.students.edit', compact('student', 'plans'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($student->user_id)],
            'phone' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'emergency_contact' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',
            'health_conditions' => 'nullable|string',
            'plan_id' => 'required|exists:plans,id',
            'payment_day' => 'required|integer|min:1|max:28',
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        DB::beginTransaction();
        try {
            // Update user
            $user = $student->user;
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email']
            ]);

            if (!empty($validated['password'])) {
                $user->update([
                    'password' => Hash::make($validated['password'])
                ]);
            }

            // Handle plan change
            if ($student->plan_id != $request->plan_id) {
                $plan = Plan::findOrFail($request->plan_id);
                $startDate = Carbon::now();
                $endDate = $startDate->copy()->addMonths($plan->duration_months);
                
                $validated['plan_start_date'] = $startDate;
                $validated['plan_end_date'] = $endDate;
                $validated['monthly_fee'] = $plan->price;
            }

            // Update student
            $student->update([
                'phone' => $validated['phone'],
                'birth_date' => $validated['birth_date'],
                'emergency_contact' => $validated['emergency_contact'],
                'emergency_phone' => $validated['emergency_phone'],
                'health_conditions' => $validated['health_conditions'],
                'plan_id' => $validated['plan_id'],
                'payment_day' => $validated['payment_day'],
                'active' => $request->has('active'),
                'plan_start_date' => $validated['plan_start_date'] ?? $student->plan_start_date,
                'plan_end_date' => $validated['plan_end_date'] ?? $student->plan_end_date,
                'monthly_fee' => $validated['monthly_fee'] ?? $student->monthly_fee,
            ]);

            DB::commit();
            return redirect()
                ->route('admin.students.index')
                ->with('success', 'Student updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update student: ' . $e->getMessage()]);
        }
    }

    public function destroy(Student $student)
    {
        try {
            $student->user->delete();
            $student->delete();
            return redirect()
                ->route('admin.students.index')
                ->with('success', 'Student deleted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete student.']);
        }
    }
}
