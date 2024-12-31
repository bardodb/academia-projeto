@extends('layouts.app')

@section('content')
@if(auth()->user()->role === 'student')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header with Welcome and Quick Actions -->
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-semibold text-gray-900">Student Dashboard</h1>
            <div class="flex space-x-3">
                <a href="{{ route('student.workouts') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    My Workouts
                </a>
                <a href="{{ route('student.progress') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    My Progress
                </a>
            </div>
        </div>

        <!-- User Info and Plan Status -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- User Profile Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="h-16 w-16 bg-gray-200 rounded-full flex items-center justify-center">
                            <svg class="h-10 w-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-semibold text-gray-900">{{ $student->user->name }}</h2>
                            <p class="text-sm text-gray-600">Member since {{ $student->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('student.profile') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Edit Profile →</a>
                    </div>
                </div>
            </div>

            <!-- Membership Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Membership Status</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Current Plan:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $student->plan->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Valid Until:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $student->plan_end_date->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Monthly Fee:</span>
                            <span class="text-sm font-medium text-gray-900">R$ {{ number_format($student->monthly_fee, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Payment Day:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $student->payment_day }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Check-in Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Today's Status</h3>
                    @php
                        $todayCheckIn = $student->checkIns()
                            ->whereDate('created_at', today())
                            ->first();
                    @endphp
                    
                    @if(!$todayCheckIn)
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">You haven't checked in today</p>
                            <form action="{{ route('student.check-in') }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Check In Now
                                </button>
                            </form>
                        </div>
                    @elseif(!$todayCheckIn->check_out_time)
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">Checked in at {{ $todayCheckIn->created_at->format('H:i') }}</p>
                            <form action="{{ route('student.check-out') }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Check Out
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="mt-2 text-sm text-green-600">Session Completed</p>
                            <p class="text-xs text-gray-500">
                                {{ $todayCheckIn->created_at->format('H:i') }} - 
                                {{ $todayCheckIn->check_out_time->format('H:i') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <a href="{{ route('student.workouts') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Workouts</h3>
                            <p class="text-sm text-gray-500">View your training plan</p>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('student.progress') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Progress</h3>
                            <p class="text-sm text-gray-500">Track your results</p>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('student.payments') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Payments</h3>
                            <p class="text-sm text-gray-500">View payment history</p>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('student.check-in-history') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Check-ins</h3>
                            <p class="text-sm text-gray-500">View attendance history</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Workouts -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Workouts</h3>
                        <a href="{{ route('student.workouts') }}" class="text-sm text-indigo-600 hover:text-indigo-900">View All →</a>
                    </div>
                    @if($student->workouts->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($student->workouts->take(3) as $workout)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $workout->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $workout->pivot->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $workout->pivot->completed_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $workout->pivot->completed_at ? 'Completed' : 'Pending' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No workouts assigned yet</p>
                    @endif
                </div>
            </div>

            <!-- Recent Check-ins -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Check-ins</h3>
                        <a href="{{ route('student.check-in-history') }}" class="text-sm text-indigo-600 hover:text-indigo-900">View All →</a>
                    </div>
                    @if($recentCheckIns->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($recentCheckIns->take(3) as $checkIn)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $checkIn->created_at->format('M d, Y') }}</h4>
                                        <p class="text-sm text-gray-500">
                                            {{ $checkIn->created_at->format('H:i') }}
                                            @if($checkIn->check_out_time)
                                                - {{ $checkIn->check_out_time->format('H:i') }}
                                            @endif
                                        </p>
                                        @if($checkIn->steps || $checkIn->distance || $checkIn->calories)
                                            <div class="mt-1 flex space-x-4 text-xs text-gray-500">
                                                @if($checkIn->steps)
                                                    <span>{{ number_format($checkIn->steps) }} steps</span>
                                                @endif
                                                @if($checkIn->distance)
                                                    <span>{{ number_format($checkIn->distance/1000, 2) }}km</span>
                                                @endif
                                                @if($checkIn->calories)
                                                    <span>{{ round($checkIn->calories) }} cal</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $checkIn->check_out_time ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $checkIn->check_out_time ? 'Completed' : 'In Progress' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No recent check-ins</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Google Fit Integration -->
        @if(!$student->google_connected)
            <div class="mt-6 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold text-white">Connect Google Fit</h3>
                                <p class="text-blue-100">
                                    Track your fitness progress automatically by connecting your Google Fit account
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('google.redirect') }}" 
                           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-600 focus:ring-white">
                            Connect Now
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@else
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Access Denied</h3>
                <p class="mt-1 text-sm text-gray-500">You don't have permission to access this area.</p>
                <div class="mt-6">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Return to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection 