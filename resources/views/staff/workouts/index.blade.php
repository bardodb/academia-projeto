@extends('staff.layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Workout Management</h2>
                    <a href="{{ route('staff.workouts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Create New Workout
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Student</th>
                                <th class="px-4 py-2">Workout Name</th>
                                <th class="px-4 py-2">Start Date</th>
                                <th class="px-4 py-2">End Date</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($workouts as $workout)
                                <tr>
                                    <td class="border px-4 py-2">{{ $workout->student->name }}</td>
                                    <td class="border px-4 py-2">{{ $workout->name }}</td>
                                    <td class="border px-4 py-2">{{ $workout->start_date->format('Y-m-d') }}</td>
                                    <td class="border px-4 py-2">{{ $workout->end_date ? $workout->end_date->format('Y-m-d') : 'Ongoing' }}</td>
                                    <td class="border px-4 py-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $workout->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $workout->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="border px-4 py-2">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('staff.workouts.edit', $workout) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                            <a href="{{ route('staff.workouts.show', $workout) }}" class="text-green-600 hover:text-green-900">View</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="border px-4 py-2 text-center">No workouts found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($workouts->hasPages())
                    <div class="mt-4">
                        {{ $workouts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 