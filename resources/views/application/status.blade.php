<x-student-layout>
    <x-slot name="header">Your application, {{ $application->semester }}</x-slot>

    @php
        $medicalLabels = [
            'none' => 'No medical proof submitted',
            'pending' => 'Waiting for review',
            'verified' => 'Verified',
            'rejected' => 'Rejected',
        ];
        $badge = [
            'pending' => 'bg-amber-50 text-amber-700',
            'approved' => 'bg-emerald-50 text-emerald-700',
            'rejected' => 'bg-red-50 text-red-700',
        ];
    @endphp

    <div class="bg-white border border-slate-200 rounded-lg p-6">
        <dl class="grid grid-cols-[10rem_1fr] gap-y-3 text-sm">
            <dt class="text-slate-500">Status</dt>
            <dd><span class="inline-block px-2 py-0.5 rounded-full text-xs {{ $badge[$application->status] }}">{{ ucfirst($application->status) }}</span></dd>
            <dt class="text-slate-500">Submitted</dt>
            <dd>{{ $application->created_at->format('d M Y, H:i') }}</dd>
            <dt class="text-slate-500">Merit score</dt>
            <dd>{{ $application->merit_score }}</dd>
            <dt class="text-slate-500">Medical proof</dt>
            <dd>{{ $medicalLabels[$application->medical_status] }}</dd>
        </dl>
        <a href="{{ route('dashboard') }}" class="inline-block mt-5 text-sm text-brand underline">Back to home</a>
    </div>
</x-student-layout>