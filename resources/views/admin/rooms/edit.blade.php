<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Room</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">

                <form method="POST" action="{{ route('admin.rooms.update', $room) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="block" value="Block" />
                        <x-text-input id="block" name="block" class="block w-full" value="{{ old('block', $room->block) }}" />
                        <x-input-error :messages="$errors->get('block')" />
                    </div>

                    <div>
                        <x-input-label for="room_number" value="Room Number" />
                        <x-text-input id="room_number" name="room_number" class="block w-full" value="{{ old('room_number', $room->room_number) }}" />
                        <x-input-error :messages="$errors->get('room_number')" />
                    </div>

                    <div>
                        <x-input-label for="floor" value="Floor" />
                        <x-text-input id="floor" name="floor" type="number" class="block w-full" value="{{ old('floor', $room->floor) }}" />
                        <x-input-error :messages="$errors->get('floor')" />
                    </div>

                    <div>
                        <x-input-label for="capacity" value="Capacity" />
                        <x-text-input id="capacity" name="capacity" type="number" class="block w-full" value="{{ old('capacity', $room->capacity) }}" />
                        <x-input-error :messages="$errors->get('capacity')" />
                    </div>

                    <div>
                        <x-input-label for="gender" value="Gender" />
                        <select id="gender" name="gender" class="block w-full border-gray-300 rounded-md">
                            <option value="male" @selected(old('gender', $room->gender) === 'male')>Male</option>
                            <option value="female" @selected(old('gender', $room->gender) === 'female')>Female</option>
                        </select>
                        <x-input-error :messages="$errors->get('gender')" />
                    </div>

                    <div>
                        <x-input-label for="status" value="Status" />
                        <select id="status" name="status" class="block w-full border-gray-300 rounded-md">
                            <option value="available" @selected(old('status', $room->status) === 'available')>Available</option>
                            <option value="maintenance" @selected(old('status', $room->status) === 'maintenance')>Maintenance</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" />
                    </div>

                    <x-primary-button>Update</x-primary-button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>