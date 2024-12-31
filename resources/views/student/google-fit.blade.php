<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Google Fit Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Connection Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Connection Status</h3>
                            @if($student->google_connected)
                                <p class="text-sm text-green-600">Connected to Google Fit</p>
                                @if($student->last_sync_at)
                                    <p class="text-xs text-gray-500">Last synced: {{ $student->last_sync_at->diffForHumans() }}</p>
                                @endif
                            @else
                                <p class="text-sm text-red-600">Not connected to Google Fit</p>
                            @endif
                        </div>
                        <div>
                            @if($student->google_connected)
                                <form action="{{ route('google.disconnect') }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                        Disconnect
                                    </button>
                                </form>
                                <form action="{{ route('google.sync') }}" method="POST" class="inline-block ml-2">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                        Sync Now
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('google.redirect') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    Connect Google Fit
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($student->google_connected && $student->google_fit_data)
                <!-- Activity Data -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Activity Summary</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Steps -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500">Steps Today</h4>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">
                                    {{ number_format($student->google_fit_data['steps'] ?? 0) }}
                                </p>
                            </div>

                            <!-- Distance -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500">Distance Today</h4>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">
                                    {{ number_format(($student->google_fit_data['distance'] ?? 0) / 1000, 2) }} km
                                </p>
                            </div>

                            <!-- Calories -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500">Calories Burned Today</h4>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">
                                    {{ number_format($student->google_fit_data['calories'] ?? 0) }} cal
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Heart Rate Data -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Heart Rate</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Average Heart Rate -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500">Average Heart Rate</h4>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">
                                    {{ $student->google_fit_data['heart_rate']['average'] ?? '--' }} bpm
                                </p>
                            </div>

                            <!-- Min Heart Rate -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500">Minimum Heart Rate</h4>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">
                                    {{ $student->google_fit_data['heart_rate']['min'] ?? '--' }} bpm
                                </p>
                            </div>

                            <!-- Max Heart Rate -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500">Maximum Heart Rate</h4>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">
                                    {{ $student->google_fit_data['heart_rate']['max'] ?? '--' }} bpm
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Body Metrics -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Body Metrics</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Weight -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500">Weight</h4>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">
                                    {{ $student->google_fit_data['weight'] ?? '--' }} kg
                                </p>
                            </div>

                            <!-- Height -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500">Height</h4>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">
                                    {{ $student->google_fit_data['height'] ?? '--' }} cm
                                </p>
                            </div>

                            <!-- BMI -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500">BMI</h4>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">
                                    {{ number_format($student->google_fit_data['bmi'] ?? 0, 1) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 