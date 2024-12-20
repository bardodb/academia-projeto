@extends('admin.layout')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Add New Instructor</h1>
            <a href="{{ route('admin.instructors.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a>
        </div>

        <form action="{{ route('admin.instructors.store') }}" method="POST" class="max-w-2xl">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-form-input 
                        name="name" 
                        label="Name" 
                        :value="old('name')" 
                        required 
                    />

                    <x-form-input 
                        type="email" 
                        name="email" 
                        label="Email" 
                        :value="old('email')" 
                        required 
                    />

                    <x-form-input 
                        type="tel" 
                        name="phone" 
                        label="Phone" 
                        :value="old('phone')" 
                        required 
                    />

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Specialties
                        </label>
                        <div class="mt-2 space-y-2">
                            @foreach(['Weightlifting', 'Cardio', 'Yoga', 'CrossFit', 'Personal Training', 'Group Classes'] as $specialty)
                                <label class="inline-flex items-center mr-4">
                                    <input type="checkbox" name="specialties[]" value="{{ $specialty }}" 
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                        {{ in_array($specialty, old('specialties', [])) ? 'checked' : '' }}>
                                    <span class="ml-2">{{ $specialty }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('specialties')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Schedule
                        </label>
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                            <div class="mb-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="schedule[{{ $day }}]" value="1" 
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                        {{ old("schedule.$day") ? 'checked' : '' }}>
                                    <span class="ml-2">{{ $day }}</span>
                                </label>
                            </div>
                        @endforeach
                        @error('schedule')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-form-input 
                        type="password" 
                        name="password" 
                        label="Password" 
                        required 
                    />

                    <x-form-input 
                        type="password" 
                        name="password_confirmation" 
                        label="Confirm Password" 
                        required 
                    />
                </div>
            </div>

            <div class="flex items-center justify-end mt-6 space-x-4">
                <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Instructor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
