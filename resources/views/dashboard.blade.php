<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">

                @if (session('status'))
                    <div class="mb-4 text-green-600">{{ session('status') }}</div>
                @endif

                {{ __("You're logged in!") }}

                <form method="POST" action="{{ route('portal.fetch') }}" class="mt-4">
                    @csrf
                    <button class="text-sm underline text-blue-600">Fetch my details from student portal</button>
                </form>

            </div>
        </div>
    </div>
</div>
</x-app-layout>
