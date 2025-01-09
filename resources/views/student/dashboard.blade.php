<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Quick Actions -->
            <div class="mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <form action="{{ route('student.check-in') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg shadow-lg flex items-center justify-center space-x-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Check-in Today</span>
                        </button>
                    </form>
                    <a href="{{ route('student.workouts') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg shadow-lg flex items-center justify-center space-x-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span>View My Workouts</span>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Personal Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">My Information</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Current Plan</p>
                                <p class="text-lg text-gray-900">{{ $student->plan->name ?? 'No Plan' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Instructor</p>
                                <p class="text-lg text-gray-900">{{ $student->instructor->name ?? 'Not Assigned' }}</p>
                            </div>
                            @if($nextPayment)
                                <div class="border-t pt-4">
                                    <p class="text-sm font-medium text-gray-500">Next Payment Due</p>
                                    <p class="text-lg {{ Carbon\Carbon::parse($nextPayment->due_date)->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                                        {{ Carbon\Carbon::parse($nextPayment->due_date)->format('M d, Y') }}
                                        ({{ Carbon\Carbon::parse($nextPayment->due_date)->diffForHumans() }})
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Workouts -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Workouts</h3>
                            <a href="{{ route('student.workouts') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                        </div>
                        @if($student->workouts->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($student->workouts as $workout)
                                    <div class="border-b pb-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $workout->name }}</h4>
                                                <p class="text-sm text-gray-500">
                                                    Assigned {{ $workout->pivot->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            <span class="px-2 py-1 text-xs rounded-full {{ $workout->pivot->completed_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $workout->pivot->completed_at ? 'Completed' : 'Pending' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No workouts assigned yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Check-ins -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Check-ins</h3>
                        <a href="{{ route('student.check-in-history') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                    </div>
                    @if($recentCheckIns->isNotEmpty())
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            @foreach($recentCheckIns as $checkIn)
                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <div class="text-lg font-semibold text-gray-900">
                                        {{ $checkIn->created_at->format('M d') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $checkIn->created_at->format('g:i A') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No recent check-ins.</p>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('student.progress') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                    <h3 class="font-semibold text-gray-900">View Progress</h3>
                    <p class="text-sm text-gray-500">Track your fitness journey</p>
                </a>
                <a href="{{ route('student.payments') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                    <h3 class="font-semibold text-gray-900">Payment History</h3>
                    <p class="text-sm text-gray-500">View your payment records</p>
                </a>
                <a href="{{ route('profile.edit') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                    <h3 class="font-semibold text-gray-900">Account Settings</h3>
                    <p class="text-sm text-gray-500">Update your profile</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout> 