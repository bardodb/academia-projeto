<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('student')
            ->latest()
            ->paginate(10);
            
        return view('admin.payments.index', compact('payments'));
    }

    public function studentPayments(Student $student)
    {
        $payments = Payment::where('student_id', $student->id)
            ->latest()
            ->paginate(10);
            
        return view('admin.payments.student', compact('student', 'payments'));
    }

    public function create(Student $student)
    {
        return view('admin.payments.create', compact('student'));
    }

    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'reference_month' => 'required|integer|min:1|max:12',
            'reference_year' => 'required|integer|min:2023',
        ]);

        $payment = new Payment($validated);
        $payment->student_id = $student->id;
        $payment->save();

        return redirect()
            ->route('admin.students.payments', $student)
            ->with('success', 'Payment registered successfully!');
    }

    public function markAsPaid(Payment $payment)
    {
        $payment->update([
            'status' => 'paid',
            'payment_date' => Carbon::now()
        ]);

        return redirect()->back()->with('success', 'Payment marked as paid!');
    }

    public function cancel(Payment $payment)
    {
        $payment->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Payment cancelled!');
    }

    public function updateStatus()
    {
        // Update overdue payments
        Payment::where('status', 'pending')
            ->where('due_date', '<', Carbon::now())
            ->update(['status' => 'overdue']);

        return redirect()->back()->with('success', 'Payment statuses updated!');
    }
}
