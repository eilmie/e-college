<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Your Application — {{ $application->semester }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg space-y-2">
                <p>Application status: <strong>{{ ucfirst($application->status) }}</strong></p>
                <p>Medical document: <strong>{{ ucfirst($application->medical_status) }}</strong></p>
            </div>
        </div>
    </div>
</x-app-layout>