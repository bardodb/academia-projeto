<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Contact Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
                            <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <x-input-label for="phone" :value="__('Phone Number')" />
                                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" 
                                        :value="old('phone', $student->phone)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="pt-6">
                            <h3 class="text-lg font-medium text-gray-900">Emergency Contact</h3>
                            <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <x-input-label for="emergency_contact" :value="__('Contact Name')" />
                                    <x-text-input id="emergency_contact" name="emergency_contact" type="text" 
                                        class="mt-1 block w-full" :value="old('emergency_contact', $student->emergency_contact)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('emergency_contact')" />
                                </div>

                                <div>
                                    <x-input-label for="emergency_phone" :value="__('Contact Phone')" />
                                    <x-text-input id="emergency_phone" name="emergency_phone" type="text" 
                                        class="mt-1 block w-full" :value="old('emergency_phone', $student->emergency_phone)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('emergency_phone')" />
                                </div>
                            </div>
                        </div>

                        <!-- Health Information -->
                        <div class="pt-6">
                            <h3 class="text-lg font-medium text-gray-900">Health Information</h3>
                            <div class="mt-4">
                                <x-input-label for="health_conditions" :value="__('Health Conditions or Notes')" />
                                <textarea id="health_conditions" name="health_conditions" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    rows="4">{{ old('health_conditions', $student->health_conditions) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('health_conditions')" />
                            </div>
                        </div>

                        <!-- Membership Information (Read-only) -->
                        <div class="pt-6">
                            <h3 class="text-lg font-medium text-gray-900">Membership Information</h3>
                            <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Current Plan</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $student->plan->name ?? 'No Plan' }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Instructor</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $student->instructor->name ?? 'Not Assigned' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 flex justify-end">
                            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 