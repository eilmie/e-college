<x-student-layout>
    <x-slot name="header">Room application, {{ $semester }}</x-slot>

    <div class="bg-white border border-slate-200 rounded-lg p-6 space-y-6">
        <div>
            <h2 class="font-semibold">Check your details</h2>
            <dl class="mt-3 grid grid-cols-[10rem_1fr] gap-y-2 text-sm">
                <dt class="text-slate-500">Name</dt><dd>{{ $user->name }}</dd>
                <dt class="text-slate-500">Student ID</dt><dd>{{ $user->student_id }}</dd>
                <dt class="text-slate-500">Programme</dt><dd>{{ ucfirst($user->program_level) }}, semester {{ $user->current_semester }}</dd>
                <dt class="text-slate-500">Merit score</dt><dd>{{ $user->merit_score }}</dd>
            </dl>
            <p class="text-xs text-slate-500 mt-2">Your merit score is locked in when you submit.</p>
        </div>

        <form method="POST" action="{{ route('application.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="medical_proof" class="font-semibold text-sm">Medical proof (optional)</label>
                <p class="text-sm text-slate-600 mb-2">Upload a letter from a doctor or hospital if you have a physical health condition. Leave this empty if you have nothing to declare. PDF, JPG or PNG, up to 5 MB.</p>
                <input id="medical_proof" name="medical_proof" type="file" class="block w-full text-sm">
            </div>

            <div class="flex items-center gap-4">
                <button class="bg-accent text-white px-4 py-2 rounded-md text-sm">Submit application</button>
                <a href="{{ route('dashboard') }}" class="text-sm text-slate-600 underline">Cancel</a>
            </div>
        </form>
    </div>
</x-student-layout>