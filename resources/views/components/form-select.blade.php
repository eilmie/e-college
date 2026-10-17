@props(['name', 'label', 'options', 'value' => null, 'required' => false, 'span' => 1])

<div class="{{ $span === 2 ? 'sm:col-span-2' : '' }}">
    <label for="{{ $name }}" class="label">{{ $label }}@if ($required)<span class="text-red-600"> *</span>@endif</label>
    <select id="{{ $name }}" name="{{ $name }}" @if ($required) data-required @endif class="input">
        <option value="">Select</option>
        @foreach ($options as $key => $text)
            <option value="{{ $key }}" @selected((string) old($name, $value) === (string) $key)>{{ $text }}</option>
        @endforeach
    </select>
    @error($name)<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
</div>