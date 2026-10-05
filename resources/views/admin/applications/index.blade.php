<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Student Ranking — {{ config('app.application_semester') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">

                @if (session('status'))
                    <div class="mb-4 text-green-600">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.applications.run-allocation') }}" class="mb-4">
                    @csrf
                    <button class="bg-blue-600 text-white px-4 py-2 rounded text-sm">
                        Run Allocation for {{ count($applications) }} Pending Applicants
                    </button>
                </form>

                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Rank</th>
                            <th>Student</th>
                            <th>Merit</th>
                            <th>Medical</th>
                            <th>Proof</th>
                            <th>Record</th>
                            <th>Applied</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $i => $app)
                        <tr class="border-b {{ $app->medical_status === 'verified' ? 'bg-green-50' : '' }}">
                            <td class="py-2 font-semibold">{{ $i + 1 }}</td>
                            <td>{{ $app->user->name }} ({{ $app->user->student_id }})</td>
                            <td>{{ $app->merit_score }}</td>
                            <td>{{ ucfirst($app->medical_status) }}</td>
                            <td>
                                @if ($app->medical_proof_path)
                                    <a href="{{ route('admin.applications.proof', $app) }}" target="_blank" class="text-blue-600 underline">View</a>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.applications.disciplinary', $app) }}">
                                    @csrf
                                    <input type="hidden" name="has_record" value="{{ $app->has_disciplinary_record ? 0 : 1 }}">
                                    <button class="text-sm underline">{{ $app->has_disciplinary_record ? 'Has record — clear' : 'Clean — flag' }}</button>
                                </form>
                            </td>
                            <td>{{ $app->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if (count($applications) === 0)
                    <p class="text-gray-500 py-4">No pending applications for this semester.</p>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>