<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Instructor Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-gray-900">{{ $totalStudents }}</div>
                    <div class="text-sm font-medium text-gray-500">Total Students</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-gray-900">{{ $totalWorkouts }}</div>
                    <div class="text-sm font-medium text-gray-500">Total Workouts</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-gray-900">{{ $todayCheckIns }}</div>
                    <div class="text-sm font-medium text-gray-500">Today's Check-ins</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Check-ins -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Check-ins</h3>
                        @if($recentCheckIns->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentCheckIns as $checkIn)
                                    <div class="flex items-center justify-between border-b pb-2">
                                        <div>
                                            <a href="{{ route('instructor.students.show', $checkIn->student) }}" class="text-blue-600 hover:text-blue-800">
                                                {{ $checkIn->student->name }}
                                            </a>
                                            <div class="text-sm text-gray-500">
                                                {{ $checkIn->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No recent check-ins</p>
                        @endif
                    </div>
                </div>

                <!-- Active Students -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Active Students</h3>
                        @if($activeStudents->count() > 0)
                            <div class="space-y-4">
                                @foreach($activeStudents as $student)
                                    <div class="border-b pb-2">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <a href="{{ route('instructor.students.show', $student) }}" class="text-blue-600 hover:text-blue-800">
                                                    {{ $student->name }}
                                                </a>
                                                <div class="text-sm text-gray-500">
                                                    Plan: {{ $student->plan->name ?? 'No Plan' }}
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <a href="{{ route('instructor.students.workouts', $student) }}" 
                                                   class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    View Workouts
                                                </a>
                                            </div>
                                        </div>
                                        @if($student->workouts->isNotEmpty())
                                            <div class="mt-2 text-sm text-gray-500">
                                                Latest workout: {{ $student->workouts->first()->name }}
                                                ({{ $student->workouts->first()->pivot->created_at->diffForHumans() }})
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No active students</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('instructor.workouts.create') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Create Workout
                        </a>
                        <a href="{{ route('instructor.students.index') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            View Students
                        </a>
                        <a href="{{ route('instructor.workouts.index') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                            View Workouts
                        </a>
                        <a href="{{ route('instructor.check-ins.index') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
                            View Check-ins
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 