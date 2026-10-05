<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Room</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">

                <form method="POST" action="{{ route('admin.rooms.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="block" value="Block" />
                        <x-text-input id="block" name="block" class="block w-full" value="{{ old('block') }}" />
                        <x-input-error :messages="$errors->get('block')" />
                    </div>

                    <div>
                        <x-input-label for="room_number" value="Room Number" />
                        <x-text-input id="room_number" name="room_number" class="block w-full" value="{{ old('room_number') }}" />
                        <x-input-error :messages="$errors->get('room_number')" />
                    </div>

                    <div>
                        <x-input-label for="floor" value="Floor" />
                        <x-text-input id="floor" name="floor" type="number" class="block w-full" value="{{ old('floor') }}" />
                        <x-input-error :messages="$errors->get('floor')" />
                    </div>

                    <div>
                        <x-input-label for="capacity" value="Capacity" />
                        <x-text-input id="capacity" name="capacity" type="number" class="block w-full" value="{{ old('capacity') }}" />
                        <x-input-error :messages="$errors->get('capacity')" />
                    </div>

                    <div>
                        <x-input-label for="gender" value="Gender" />
                        <select id="gender" name="gender" class="block w-full border-gray-300 rounded-md">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        <x-input-error :messages="$errors->get('gender')" />
                    </div>

                    <x-primary-button>Save</x-primary-button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>