@extends('admin.layout')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Add New Student</h1>
            <a href="{{ route('admin.students.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">Please check the form for errors.</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.students.store') }}" method="POST" class="max-w-2xl">
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

                    <x-form-input 
                        type="tel" 
                        name="phone" 
                        label="Phone" 
                        :value="old('phone')" 
                        required 
                    />

                    <x-form-input 
                        type="date" 
                        name="birth_date" 
                        label="Birth Date" 
                        :value="old('birth_date')" 
                        required 
                    />
                </div>

                <div>
                    <x-form-input 
                        name="emergency_contact" 
                        label="Emergency Contact Name" 
                        :value="old('emergency_contact')" 
                        required 
                    />

                    <x-form-input 
                        type="tel" 
                        name="emergency_phone" 
                        label="Emergency Contact Phone" 
                        :value="old('emergency_phone')" 
                        required 
                    />

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="plan_id">
                            Plan
                        </label>
                        <select name="plan_id" id="plan_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Select a plan</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} - R$ {{ number_format($plan->price, 2, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('plan_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-form-input 
                        type="number" 
                        name="payment_day" 
                        label="Payment Day (1-28)" 
                        :value="old('payment_day')" 
                        min="1" 
                        max="28" 
                        required 
                    />
                </div>
            </div>

            <div class="col-span-2">
                <x-form-input 
                    type="textarea" 
                    name="health_conditions" 
                    label="Health Conditions (Optional)" 
                    :value="old('health_conditions')" 
                />
            </div>

            <div class="flex items-center justify-end mt-6 space-x-4">
                <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Register Student
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
