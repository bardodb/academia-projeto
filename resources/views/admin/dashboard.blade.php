@extends('admin.layout')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <h1 class="text-2xl font-semibold mb-6">Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Students Card -->
            <div class="bg-blue-100 p-6 rounded-lg">
                <h2 class="text-lg font-semibold text-blue-800">Total Students</h2>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['total_students'] }}</p>
            </div>

            <!-- Active Students Card -->
            <div class="bg-green-100 p-6 rounded-lg">
                <h2 class="text-lg font-semibold text-green-800">Active Students</h2>
                <p class="text-3xl font-bold text-green-600">{{ $stats['active_students'] }}</p>
            </div>

            <!-- Total Plans Card -->
            <div class="bg-purple-100 p-6 rounded-lg">
                <h2 class="text-lg font-semibold text-purple-800">Total Plans</h2>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['total_plans'] }}</p>
            </div>

            <!-- Check-ins Today Card -->
            <div class="bg-orange-100 p-6 rounded-lg">
                <h2 class="text-lg font-semibold text-orange-800">Check-ins Today</h2>
                <p class="text-3xl font-bold text-orange-600">{{ $stats['check_ins_today'] }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
