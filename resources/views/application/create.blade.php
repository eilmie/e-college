<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Room Application — {{ $semester }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">

                @if (session('status'))
                    <div class="mb-4 text-green-600">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('application.store') }}" enctype="multipart/form-data">
                    @csrf

                    <x-input-label for="medical_proof" value="Medical proof (optional)" />
                    <p class="text-sm text-gray-500 mb-2">
                        Upload a doctor's or hospital's letter if you have a physical health condition that should be prioritized. PDF, JPG or PNG, max 5MB.
                    </p>
                    <input id="medical_proof" name="medical_proof" type="file" class="block w-full text-sm" />
                    <x-input-error :messages="$errors->get('medical_proof')" class="mt-2" />

                    <div class="flex items-center justify-end mt-6">
                        <x-primary-button>Submit Application</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>