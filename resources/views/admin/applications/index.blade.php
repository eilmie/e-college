<x-admin-layout>
    <x-slot name="header">Applications</x-slot>
    <x-slot name="subtitle">Pending applicants for {{ config('app.application_semester') }}, in the order the allocation will follow</x-slot>

    <div class="grid grid-cols-3 gap-4">
        <div class="card card-body">
            <p class="text-sm text-slate-500">Pending applicants</p>
            <p class="text-2xl font-semibold mt-1">{{ $applications->count() }}</p>
        </div>
        <div class="card card-body">
            <p class="text-sm text-slate-500">Verified health condition</p>
            <p class="text-2xl font-semibold mt-1">{{ $applications->where('medical_status', 'verified')->count() }}</p>
        </div>
        <div class="card card-body">
            <p class="text-sm text-slate-500">With disciplinary record</p>
            <p class="text-2xl font-semibold mt-1">{{ $applications->where('has_disciplinary_record', true)->count() }}</p>
        </div>
    </div>

    @php $pendingMedical = $applications->where('medical_status', 'pending')->count(); @endphp

        @if ($pendingMedical > 0)
            <div class="alert bg-amber-50 text-amber-700">
                {{ $pendingMedical }} medical record(s) are waiting for review. Review them first, because only verified records count for priority.
            </div>
        @endif

        <div class="flex justify-end">
            <form method="POST" action="{{ route('admin.applications.run-allocation') }}"
                @if ($pendingMedical > 0) onsubmit="return confirm('{{ $pendingMedical }} medical record(s) are still unreviewed. Run the allocation anyway?')" @endif>
                @csrf
                <button class="btn-primary">Run allocation for {{ $applications->count() }} applicants</button>
            </form>
        </div>

    <div class="card overflow-hidden">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="w-16">Rank</th>
                    <th class="w-64">Student</th>
                    <th class="w-20">Merit</th>
                    <th class="w-56">Medical</th>
                    <th class="w-44">Record</th>
                    <th>Applied</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($applications as $i => $app)
                <tr>
                    <td class="font-semibold text-slate-900">{{ $i + 1 }}</td>
                    <td>
                        <a href="{{ route('admin.applications.show', $app) }}" class="font-medium text-brand hover:underline">{{ $app->user->name }}</a>
                        <p class="text-xs text-slate-500">{{ $app->user->student_id }}</p>
                    </td>
                    <td>{{ $app->merit_score }}</td>
                    <td>
                        @php
                            $medicalBadge = [
                                'none' => 'badge-neutral',
                                'pending' => 'badge-warning',
                                'verified' => 'badge-success',
                                'rejected' => 'badge-danger',
                            ][$app->medical_status];
                        @endphp
                        <a href="{{ route('admin.applications.show', $app) }}#medical"
                        class="{{ $medicalBadge }} hover:ring-2 hover:ring-slate-300" title="Open student details">
                            {{ ucfirst($app->medical_status) }}
                        </a>
                        @if ($app->medical_status === 'pending')
                            <span class="text-xs text-slate-500 ml-1">needs review</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.applications.show', $app) }}#record"
                        class="{{ $app->has_disciplinary_record ? 'badge-danger' : 'badge-neutral' }} hover:ring-2 hover:ring-slate-300" title="Open student details">
                            {{ $app->has_disciplinary_record ? 'Has record' : 'Clean' }}
                        </a>
                    </td>
                    
                    <td class="text-slate-500">{{ $app->created_at->format('d M Y, H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if ($applications->isEmpty())
            <p class="text-sm text-slate-500 p-6">No pending applications for this semester.</p>
        @endif
    </div>
</x-admin-layout>