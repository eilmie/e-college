@props(['label', 'value' => null])

<div class="grid grid-cols-[11rem_1fr] gap-4 py-2 text-sm">
    <dt class="text-slate-500">{{ $label }}</dt>
    <dd class="text-slate-900">{{ filled($value) ? $value : '-' }}</dd>
</div>