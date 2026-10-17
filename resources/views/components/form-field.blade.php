@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'hint' => null, 'span' => 1])

<div class="{{ $span === 2 ? 'sm:col-span-2' : '' }}">
    <label for="{{ $name }}" class="label">{{ $label }}@if ($required)<span class="text-red-600"> *</span>@endif</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}"
           @if ($required) data-required @endif
           {{ $attributes->merge(['class' => 'input']) }}>
    @if ($hint)<p class="text-xs text-slate-500 mt-1">{{ $hint }}</p>@endif
    @error($name)<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
</div>