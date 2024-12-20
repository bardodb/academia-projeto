@props(['disabled' => false, 'value' => '', 'label' => '', 'type' => 'text'])

<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="{{ $attributes->get('name') }}">
        {{ $label }}
    </label>
    
    @if ($type === 'textarea')
        <textarea 
            {{ $disabled ? 'disabled' : '' }} 
            {!! $attributes->merge(['class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline' . ($errors->has($attributes->get('name')) ? ' border-red-500' : '')]) !!}
        >{{ $value }}</textarea>
    @else
        <input 
            type="{{ $type }}" 
            value="{{ $value }}"
            {{ $disabled ? 'disabled' : '' }} 
            {!! $attributes->merge(['class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline' . ($errors->has($attributes->get('name')) ? ' border-red-500' : '')]) !!}
        >
    @endif

    @error($attributes->get('name'))
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>
