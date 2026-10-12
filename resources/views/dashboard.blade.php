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
        $done = 'bg-emerald-100 text-emerald-700 ring-2 ring-emerald-200';
        $todo = 'bg-gray-100 text-gray-500 ring-2 ring-gray-200';
    @endphp

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Clean Welcome Banner with Black Text -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8 p-6 flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold text-black">Welcome back, {{ $user->name }}!</h3>
                    <p class="mt-1 text-black font-medium">Semester {{ $semester }}. Follow these steps in order to get a room.</p>
                </div>
                <div class="hidden md:block">
                    <!-- Subtle Building Icon -->
                    <svg class="w-16 h-16 text-indigo-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Step 1: Your Details -->
                <section class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center gap-4">
                        <div class="flex-none w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold {{ $profileReady ? $done : $todo }}">1</div>
                        <h2 class="text-lg font-semibold text-gray-800">Your details</h2>
                    </div>
                    <div class="p-6">
                        @if ($profileReady)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50 p-5 rounded-lg border border-gray-100 mb-4">
                                <div><span class="block text-xs font-medium text-gray-500 uppercase">Student ID</span><span class="text-sm font-medium text-gray-900">{{ $user->student_id }}</span></div>
                                <div><span class="block text-xs font-medium text-gray-500 uppercase">Gender</span><span class="text-sm font-medium text-gray-900">{{ ucfirst($user->gender) }}</span></div>
                                <div><span class="block text-xs font-medium text-gray-500 uppercase">Programme</span><span class="text-sm font-medium text-gray-900">{{ ucfirst($user->program_level) }}, semester {{ $user->current_semester }}</span></div>
                                <div><span class="block text-xs font-medium text-gray-500 uppercase">Merit score</span><span class="text-sm font-medium text-gray-900">{{ $user->merit_score }}</span></div>
                            </div>
                            <form method="POST" action="{{ route('portal.fetch') }}">
                                @csrf
                                <button class="text-sm text-indigo-600 hover:text-indigo-900 font-medium transition duration-150 ease-in-out underline">Refresh from student portal</button>
                            </form>
                        @else
                            <p class="text-sm text-gray-600 mb-4">We load your programme, semester and merit score from the student portal.</p>
                            <form method="POST" action="{{ route('portal.fetch') }}">
                                @csrf
                                <button class="px-5 py-2.5 bg-indigo-600 text-white hover:bg-indigo-700 rounded-md text-sm font-medium transition-colors">Load my details</button>
                            </form>
                        @endif
                    </div>
                </section>

                <!-- Step 2: Room Application -->
                <section class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center gap-4">
                        <div class="flex-none w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold {{ ($isFresh || $application) ? $done : $todo }}">2</div>
                        <h2 class="text-lg font-semibold text-gray-800">Room application</h2>
                    </div>
                    <div class="p-6">
                        @if ($isFresh)
                            <div class="bg-blue-50 text-blue-800 p-4 rounded-lg border border-blue-100 flex items-start">
                                <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-sm">Semester 1 diploma students are placed automatically. You do not need to apply.</p>
                            </div>
                        @elseif ($application)
                            <div class="bg-gray-50 p-5 rounded-lg border border-gray-100 space-y-2 mb-4">
                                <p class="text-sm text-gray-700"><span class="font-medium text-gray-900">Submitted on:</span> {{ $application->created_at->format('d M Y, H:i') }}</p>
                                <p class="text-sm text-gray-700"><span class="font-medium text-gray-900">Medical status:</span> {{ $medicalLabels[$application->medical_status] }}</p>
                            </div>
                            <a href="{{ route('application.create') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium transition duration-150 ease-in-out underline">View application</a>
                        @elseif ($profileReady)
                            <p class="text-sm text-gray-600 mb-4">Submit your application. Upload medical proof if you have a health condition the college should consider.</p>
                            <a href="{{ route('application.create') }}" class="inline-block px-5 py-2.5 bg-indigo-600 text-black hover:bg-indigo-700 rounded-md text-sm font-medium transition-colors">Apply for a room</a>
                        @else
                            <p class="text-sm text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Load your details in step 1 first.
                            </p>
                        @endif
                    </div>
                </section>

                <!-- Step 3: Your Room -->
                <section class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center gap-4">
                        <div class="flex-none w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold {{ $allocation ? $done : $todo }}">3</div>
                        <h2 class="text-lg font-semibold text-gray-800">Your room</h2>
                    </div>
                    <div class="p-6">
                        @if ($allocation)
                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-6 flex flex-col md:flex-row md:items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-1">Allocated Room</p>
                                    <p class="text-4xl font-extrabold text-emerald-600">{{ $allocation->room->room_number }}</p>
                                    <p class="text-sm font-medium text-emerald-700 mt-1">{{ $allocation->room->block }}, Level {{ $allocation->room->floor }}</p>
                                </div>
                                <div class="mt-4 md:mt-0 md:text-right border-t md:border-t-0 border-emerald-200 pt-4 md:pt-0">
                                    <p class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-1">Roommates</p>
                                    <p class="text-sm font-medium text-emerald-700">
                                        @forelse ($roommates as $mate)
                                            {{ $mate->user->name }}@if (! $loop->last), @endif
                                        @empty
                                            none yet
                                        @endforelse
                                    </p>
                                </div>
                            </div>
                        @elseif ($application && $application->status === 'rejected')
                            <div class="bg-red-50 text-red-800 p-4 rounded-lg border border-red-100 flex items-start">
                                <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <p class="text-sm">No room was available for you in this round. Please contact the college office.</p>
                            </div>
                        @elseif ($application || $isFresh)
                            <div class="bg-yellow-50 text-yellow-800 p-4 rounded-lg border border-yellow-100 flex items-start">
                                <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-sm">Results are not out yet. Check back after the college runs the allocation.</p>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                Your room will appear here once you are placed.
                            </p>
                        @endif
                    </div>
                </section>
            </div>
            
        </div>
    </div>
</x-student-layout>