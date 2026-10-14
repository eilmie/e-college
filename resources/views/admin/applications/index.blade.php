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

    <div class="flex justify-end">
        <form method="POST" action="{{ route('admin.applications.run-allocation') }}">
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
                        <p class="font-medium text-slate-900">{{ $app->user->name }}</p>
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
                        <span class="{{ $medicalBadge }}">{{ ucfirst($app->medical_status) }}</span>

                        @if ($app->medical_proof_path)
                            <a href="{{ route('admin.applications.proof', $app) }}" target="_blank" class="ml-2 text-brand underline text-xs">View proof</a>
                        @endif

                        @if ($app->medical_proof_path && $app->medical_status === 'pending')
                            <form method="POST" action="{{ route('admin.applications.medical', $app) }}" class="mt-1 flex gap-3 text-xs">
                                @csrf
                                <button name="decision" value="verified" class="text-emerald-700 underline">Verify</button>
                                <button name="decision" value="rejected" class="text-red-600 underline">Reject</button>
                            </form>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.applications.disciplinary', $app) }}" class="flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="has_record" value="{{ $app->has_disciplinary_record ? 0 : 1 }}">
                            <span class="{{ $app->has_disciplinary_record ? 'badge-danger' : 'badge-neutral' }}">
                                {{ $app->has_disciplinary_record ? 'Has record' : 'Clean' }}
                            </span>
                            <button class="text-xs text-brand underline">{{ $app->has_disciplinary_record ? 'Clear' : 'Flag' }}</button>
                        </form>
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