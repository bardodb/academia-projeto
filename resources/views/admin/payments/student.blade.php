@extends('admin.layout')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold">Payment History</h1>
                <p class="text-gray-600">Student: {{ $student->name }}</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('admin.students.payments.create', $student) }}" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Register Payment
                </a>
                <a href="{{ route('admin.students.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Students
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-gray-50">
                <div class="text-center p-4 bg-white rounded-lg shadow">
                    <div class="text-sm font-medium text-gray-500">Total Payments</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ $payments->count() }}
                    </div>
                </div>
                <div class="text-center p-4 bg-white rounded-lg shadow">
                    <div class="text-sm font-medium text-gray-500">Paid</div>
                    <div class="mt-1 text-3xl font-semibold text-green-600">
                        {{ $payments->where('status', 'paid')->count() }}
                    </div>
                </div>
                <div class="text-center p-4 bg-white rounded-lg shadow">
                    <div class="text-sm font-medium text-gray-500">Pending</div>
                    <div class="mt-1 text-3xl font-semibold text-yellow-600">
                        {{ $payments->where('status', 'pending')->count() }}
                    </div>
                </div>
                <div class="text-center p-4 bg-white rounded-lg shadow">
                    <div class="text-sm font-medium text-gray-500">Overdue</div>
                    <div class="mt-1 text-3xl font-semibold text-red-600">
                        {{ $payments->where('status', 'overdue')->count() }}
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reference
                            </th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Due Date
                            </th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($payments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $payment->reference_month }}/{{ $payment->reference_year }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        R$ {{ number_format($payment->amount, 2, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $payment->due_date->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $payment->status === 'overdue' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $payment->status === 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($payment->status === 'pending' || $payment->status === 'overdue')
                                        <form action="{{ route('admin.payments.mark-as-paid', $payment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                                Mark as Paid
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.payments.cancel', $payment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to cancel this payment?')">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
