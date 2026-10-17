@props(['name', 'label', 'model'])

<div>
    <p class="label">{{ $label }}</p>
    <div class="flex gap-6 text-sm">
        <label class="inline-flex items-center gap-2">
            <input type="radio" name="{{ $name }}" value="1" x-model="{{ $model }}" class="text-brand focus:ring-brand"> Yes
        </label>
        <label class="inline-flex items-center gap-2">
            <input type="radio" name="{{ $name }}" value="0" x-model="{{ $model }}" class="text-brand focus:ring-brand"> No
        </label>
    </div>
</div>