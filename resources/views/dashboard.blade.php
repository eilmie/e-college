<x-student-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Welcome, {{ $user->name }}
        </h2>
    </x-slot>

    @php
        $medicalLabels = [
            'none' => 'No medical proof submitted',
            'pending' => 'Medical proof waiting for review',
            'verified' => 'Medical proof verified',
            'rejected' => 'Medical proof rejected',
        ];
        
        $done = 'bg-blue-600 text-white';
        $todo = 'bg-gray-200 text-gray-600';
    @endphp

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Welcome Card -->
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900">Welcome back, {{ $user->name }}!</h3>
                <p class="text-sm text-gray-600 mt-1">Semester {{ $semester }}. Follow these steps in order to get a room.</p>
            </div>

            <!-- Step 1: Your Details -->
            <section class="bg-white p-6 shadow sm:rounded-lg">
                <div class="flex items-start gap-4">
                    <div class="flex-none w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold {{ $profileReady ? $done : $todo }}">1</div>
                    <div class="flex-1">
                        <h2 class="text-lg font-medium text-gray-900">Your details</h2>

                        @if ($profileReady)
                            <dl class="mt-3 grid grid-cols-[10rem_1fr] gap-y-2 text-sm">
                                <dt class="text-gray-500">Student ID</dt><dd class="text-gray-900">{{ $user->student_id }}</dd>
                                <dt class="text-gray-500">Gender</dt><dd class="text-gray-900">{{ ucfirst($user->gender) }}</dd>
                                <dt class="text-gray-500">Programme</dt><dd class="text-gray-900">{{ ucfirst($user->program_level) }}, semester {{ $user->current_semester }}</dd>
                                <dt class="text-gray-500">Merit score</dt><dd class="text-gray-900">{{ $user->merit_score }}</dd>
                            </dl>
                            <form method="POST" action="{{ route('portal.fetch') }}" class="mt-4">
                                @csrf
                                <button class="text-sm text-blue-600 underline">Refresh from student portal</button>
                            </form>
                        @else
                            <p class="text-sm text-gray-600 mt-1">We load your programme, semester and merit score from the student portal.</p>
                            <form method="POST" action="{{ route('portal.fetch') }}" class="mt-3">
                                @csrf
                                <button class="bg-blue-600 text-white px-4 py-2 rounded text-sm">Load my details</button>
                            </form>
                        @endif
                    </div>
                </div>
            </section>

            <!-- Step 2: Room Application -->
            <section class="bg-white p-6 shadow sm:rounded-lg">
                <div class="flex items-start gap-4">
                    <div class="flex-none w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold {{ ($isFresh || $application) ? $done : $todo }}">2</div>
                    <div class="flex-1">
                        <h2 class="text-lg font-medium text-gray-900">Room application</h2>

                        @if ($isFresh)
                            <p class="text-sm text-gray-600 mt-1">Semester 1 diploma students are placed automatically. You do not need to apply.</p>
                        @elseif ($application)
                            <p class="text-sm text-gray-600 mt-1">Submitted on {{ $application->created_at->format('d M Y, H:i') }}.</p>
                            <p class="text-sm text-gray-600">{{ $medicalLabels[$application->medical_status] }}.</p>
                            <a href="{{ route('application.create') }}" class="inline-block mt-3 text-sm text-blue-600 underline">View application</a>
                        @elseif ($profileReady)
                            <p class="text-sm text-gray-600 mt-1">Submit your application. Upload medical proof if you have a health condition the college should consider.</p>
                            <a href="{{ route('application.create') }}" class="inline-block mt-3 bg-blue-600 text-white px-4 py-2 rounded text-sm">Apply for a room</a>
                        @else
                            <p class="text-sm text-gray-500 mt-1">Load your details in step 1 first.</p>
                        @endif
                    </div>
                </div>
            </section>

            <!-- Step 3: Your Room -->
            <section class="bg-white p-6 shadow sm:rounded-lg">
                <div class="flex items-start gap-4">
                    <div class="flex-none w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold {{ $allocation ? $done : $todo }}">3</div>
                    <div class="flex-1">
                        <h2 class="text-lg font-medium text-gray-900">Your room</h2>

                        @if ($allocation)
                            <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $allocation->room->room_number }}</p>
                            <p class="text-sm text-gray-600">{{ $allocation->room->block }}, level {{ $allocation->room->floor }}</p>
                            <p class="text-sm text-gray-600 mt-3">
                                Roommate:
                                @forelse ($roommates as $mate)
                                    {{ $mate->user->name }}@if (! $loop->last), @endif
                                @empty
                                    none yet
                                @endforelse
                            </p>
                        @elseif ($application && $application->status === 'rejected')
                            <p class="text-sm text-gray-600 mt-1">No room was available for you in this round. Please contact the college office.</p>
                        @elseif ($application || $isFresh)
                            <p class="text-sm text-gray-600 mt-1">Results are not out yet. Check back after the college runs the allocation.</p>
                        @else
                            <p class="text-sm text-gray-500 mt-1">Your room will appear here once you are placed.</p>
                        @endif
                    </div>
                </div>
            </section>

        </div>
    </div>
</x-student-layout>