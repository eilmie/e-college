<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $room->block }} — Room {{ $room->room_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <p class="mb-4 text-sm text-gray-600">
                    Gender: {{ ucfirst($room->gender) }} · Capacity: {{ $occupants->count() }}/{{ $room->capacity }} · Status: {{ ucfirst($room->status) }}
                </p>

                <h3 class="font-semibold mb-2">Current Occupants</h3>
                @forelse ($occupants as $alloc)
                    <div class="flex justify-between items-center border-b py-2">
                        <span>{{ $alloc->user->name }} ({{ $alloc->user->student_id }})</span>
                        <form method="POST" action="{{ route('admin.allocations.remove', $alloc) }}" onsubmit="return confirm('Remove this student from the room?')">
                            @csrf
                            <button class="text-red-600 underline text-sm">Remove</button>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-500">No students assigned yet.</p>
                @endforelse
            </div>

            @if ($occupants->count() < $room->capacity)
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="font-semibold mb-2">Assign a Student Directly</h3>
                <form method="POST" action="{{ route('admin.rooms.assign', $room) }}" class="flex gap-2">
                    @csrf
                    <select name="room_application_id" class="flex-1 border-gray-300 rounded-md">
                        <option value="">Select a pending applicant ({{ ucfirst($room->gender) }})</option>
                        @foreach ($availableApplications as $app)
                            <option value="{{ $app->id }}">
                                {{ $app->user->name }} ({{ $app->user->student_id }}) — Merit: {{ $app->merit_score }}, Medical: {{ ucfirst($app->medical_status) }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn-primary">Assign</button>
                </form>
            </div>
            @endif

            <a href="{{ route('admin.rooms.index') }}" class="text-blue-600 underline text-sm">← Back to all rooms</a>
        </div>
    </div>
</x-app-layout>