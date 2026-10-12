<x-student-layout>
    <x-slot name="header">Welcome, {{ $user->name }}</x-slot>

    @php
        $medicalLabels = [
            'none' => 'No medical proof submitted',
            'pending' => 'Medical proof waiting for review',
            'verified' => 'Medical proof verified',
            'rejected' => 'Medical proof rejected',
        ];
        $done = 'bg-emerald-100 text-emerald-700';
        $todo = 'bg-slate-200 text-slate-600';
    @endphp

    <p class="text-sm text-slate-600">Semester {{ $semester }}. Follow these steps in order to get a room.</p>

    <section class="bg-white border border-slate-200 rounded-lg p-6">
        <div class="flex items-start gap-4">
            <div class="flex-none w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold {{ $profileReady ? $done : $todo }}">1</div>
            <div class="flex-1">
                <h2 class="font-semibold">Your details</h2>

                @if ($profileReady)
                    <dl class="mt-3 grid grid-cols-[10rem_1fr] gap-y-2 text-sm">
                        <dt class="text-slate-500">Student ID</dt><dd>{{ $user->student_id }}</dd>
                        <dt class="text-slate-500">Gender</dt><dd>{{ ucfirst($user->gender) }}</dd>
                        <dt class="text-slate-500">Programme</dt><dd>{{ ucfirst($user->program_level) }}, semester {{ $user->current_semester }}</dd>
                        <dt class="text-slate-500">Merit score</dt><dd>{{ $user->merit_score }}</dd>
                    </dl>
                    <form method="POST" action="{{ route('portal.fetch') }}" class="mt-3">
                        @csrf
                        <button class="text-sm text-brand underline">Refresh from student portal</button>
                    </form>
                @else
                    <p class="text-sm text-slate-600 mt-1">We load your programme, semester and merit score from the student portal.</p>
                    <form method="POST" action="{{ route('portal.fetch') }}" class="mt-3">
                        @csrf
                        <button class="bg-accent text-white px-4 py-2 rounded-md text-sm">Load my details</button>
                    </form>
                @endif
            </div>
        </div>
    </section>

    <section class="bg-white border border-slate-200 rounded-lg p-6">
        <div class="flex items-start gap-4">
            <div class="flex-none w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold {{ ($isFresh || $application) ? $done : $todo }}">2</div>
            <div class="flex-1">
                <h2 class="font-semibold">Room application</h2>

                @if ($isFresh)
                    <p class="text-sm text-slate-600 mt-1">Semester 1 diploma students are placed automatically. You do not need to apply.</p>
                @elseif ($application)
                    <p class="text-sm text-slate-600 mt-1">Submitted on {{ $application->created_at->format('d M Y, H:i') }}.</p>
                    <p class="text-sm text-slate-600">{{ $medicalLabels[$application->medical_status] }}.</p>
                    <a href="{{ route('application.create') }}" class="inline-block mt-3 text-sm text-brand underline">View application</a>
                @elseif ($profileReady)
                    <p class="text-sm text-slate-600 mt-1">Submit your application. Upload medical proof if you have a health condition the college should consider.</p>
                    <a href="{{ route('application.create') }}" class="inline-block mt-3 bg-accent text-white px-4 py-2 rounded-md text-sm">Apply for a room</a>
                @else
                    <p class="text-sm text-slate-500 mt-1">Load your details in step 1 first.</p>
                @endif
            </div>
        </div>
    </section>

    <section class="bg-white border border-slate-200 rounded-lg p-6">
        <div class="flex items-start gap-4">
            <div class="flex-none w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold {{ $allocation ? $done : $todo }}">3</div>
            <div class="flex-1">
                <h2 class="font-semibold">Your room</h2>

                @if ($allocation)
                    <p class="mt-2 text-2xl font-semibold text-brand">{{ $allocation->room->room_number }}</p>
                    <p class="text-sm text-slate-600">{{ $allocation->room->block }}, level {{ $allocation->room->floor }}</p>
                    <p class="text-sm text-slate-600 mt-3">
                        Roommate:
                        @forelse ($roommates as $mate)
                            {{ $mate->user->name }}@if (! $loop->last), @endif
                        @empty
                            none yet
                        @endforelse
                    </p>
                @elseif ($application && $application->status === 'rejected')
                    <p class="text-sm text-slate-600 mt-1">No room was available for you in this round. Please contact the college office.</p>
                @elseif ($application || $isFresh)
                    <p class="text-sm text-slate-600 mt-1">Results are not out yet. Check back after the college runs the allocation.</p>
                @else
                    <p class="text-sm text-slate-500 mt-1">Your room will appear here once you are placed.</p>
                @endif
            </div>
        </div>
    </section>
</x-student-layout>