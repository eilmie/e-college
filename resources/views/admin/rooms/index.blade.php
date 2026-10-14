<x-admin-layout>
    <x-slot name="header">Rooms</x-slot>

    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <div class="flex gap-2">
                <a href="{{ route('admin.rooms.index') }}" class="px-3 py-1.5 rounded-md text-sm {{ !$selectedBlock ? 'bg-brand text-white' : 'bg-white border border-slate-200 text-slate-600' }}">All</a>
                <a href="{{ route('admin.rooms.index', ['block' => 'Tun Sri Lanang A']) }}" class="px-3 py-1.5 rounded-md text-sm {{ $selectedBlock === 'Tun Sri Lanang A' ? 'bg-brand text-white' : 'bg-white border border-slate-200 text-slate-600' }}">Tun Sri Lanang A</a>
                <a href="{{ route('admin.rooms.index', ['block' => 'Tun Sri Gemala A']) }}" class="px-3 py-1.5 rounded-md text-sm {{ $selectedBlock === 'Tun Sri Gemala A' ? 'bg-brand text-white' : 'bg-white border border-slate-200 text-slate-600' }}">Tun Sri Gemala A</a>
            </div>
            <a href="{{ route('admin.rooms.create') }}" class="btn-accent">Add Room</a>
        </div>

        <form method="GET" action="{{ route('admin.rooms.index') }}" class="flex gap-2">
            @if ($selectedBlock)
                <input type="hidden" name="block" value="{{ $selectedBlock }}">
            @endif
            <input type="text" name="search" value="{{ $search }}" placeholder="Search room number, e.g. 1101A" class="flex-1 border-slate-300 rounded-md text-sm">
            <button class="btn-outline">Search</button>
        </form>

        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-40 text-left px-4 py-3">Block</th>
                        <th class="w-24 text-left px-4 py-3">Room</th>
                        <th class="w-16 text-left px-4 py-3">Level</th>
                        <th class="w-20 text-left px-4 py-3">Gender</th>
                        <th class="w-24 text-left px-4 py-3">Occupants</th>
                        <th class="w-24 text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rooms as $room)
                    <tr>
                        <td class="px-4 py-3 text-slate-700">{{ $room->block }}</td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $room->room_number }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $room->floor }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ ucfirst($room->gender) }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $room->occupants }}/{{ $room->capacity }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs {{ $room->status === 'available' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ ucfirst($room->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-3 text-sm">
                                <a href="{{ route('admin.rooms.show', $room) }}" class="text-brand underline">View</a>
                                <a href="{{ route('admin.rooms.edit', $room) }}" class="text-brand underline">Edit</a>
                                <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}" onsubmit="return confirm('Delete this room?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($rooms->isEmpty())
                <p class="text-slate-500 text-sm p-6">No rooms match this filter.</p>
            @endif
        </div>
    </div>
</x-admin-layout>