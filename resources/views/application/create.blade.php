<x-student-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Application Portal') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-extrabold text-gray-900">University Accommodation Application</h1>
                <p class="mt-2 text-sm text-gray-600">Please fill out the form below accurately. All fields marked with an asterisk (*) are required.</p>
            </div>

            <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden border border-gray-200">
                <!-- IMPORTANT: enctype="multipart/form-data" is required for file uploads -->
                <form method="POST" action="{{ route('application.store') }}" enctype="multipart/form-data" class="p-8">
                    @csrf

                    <!-- Section 1: Personal Information -->
                    <div class="mb-10">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 border-b border-gray-200 pb-2 mb-6">1. Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <x-input-label for="full_name" :value="__('Full Name *')" />
                                <x-text-input id="full_name" class="block mt-1 w-full bg-gray-50" type="text" name="full_name" :value="Auth::user()->name" readonly required autofocus />
                                <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="student_id" :value="__('Student ID *')" />
                                <x-text-input id="student_id" class="block mt-1 w-full bg-gray-50" type="text" name="student_id" :value="Auth::user()->student_id ?? ''" readonly required />
                                <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('University Email *')" />
                                <x-text-input id="email" class="block mt-1 w-full bg-gray-50" type="email" name="email" :value="Auth::user()->email" readonly required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="phone" :value="__('Contact Number *')" />
                                <x-text-input id="phone" class="block mt-1 w-full focus:ring-indigo-500 focus:border-indigo-500" type="text" name="phone" :value="old('phone')" required placeholder="+60 12-345 6789" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Academic Details -->
                    <div class="mb-10">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 border-b border-gray-200 pb-2 mb-6">2. Academic Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <x-input-label for="faculty" :value="__('Faculty *')" />
                                <select id="faculty" name="faculty" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="" disabled selected>Select your faculty</option>
                                    <option value="fsKM">Faculty of Computer and Mathematical Sciences</option>
                                    <option value="fpa">Faculty of Plantation and Agrotechnology</option>
                                    <option value="fbm">Faculty of Business Management</option>
                                </select>
                                <x-input-error :messages="$errors->get('faculty')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="semester" :value="__('Current Semester *')" />
                                <select id="semester" name="semester" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="" disabled selected>Select semester</option>
                                    @for ($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}">Semester {{ $i }}</option>
                                    @endfor
                                </select>
                                <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Supporting Documents & Specifics -->
                    <div class="mb-10">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 border-b border-gray-200 pb-2 mb-6">3. Application Specifics</h3>
                        <div class="grid grid-cols-1 gap-6">
                            
                            <!-- File Upload Field -->
                            <div>
                                <x-input-label for="medical_proof" :value="__('Upload Supporting Document (Optional)')" />
                                <input class="block w-full mt-1 text-sm text-gray-900 border border-gray-300 rounded-md shadow-sm cursor-pointer bg-white focus:outline-none focus:border-indigo-500 focus:ring-indigo-500 file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" id="medical_proof" name="medical_proof" type="file">
                                <p class="mt-1 text-xs text-gray-500">Accepted formats: PDF, JPG, PNG (Max size: 2MB).</p>
                                <x-input-error :messages="$errors->get('medical_proof')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="special_requirements" :value="__('Special Requirements or Medical Conditions (Optional)')" />
                                <textarea id="special_requirements" name="special_requirements" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Please specify any medical conditions or special needs we should be aware of..."></textarea>
                                <x-input-error :messages="$errors->get('special_requirements')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4 font-medium transition duration-150 ease-in-out">
                            Cancel
                        </a>
                        <x-primary-button class="px-6 py-3 bg-indigo-700 hover:bg-indigo-800">
                            {{ __('Submit Application') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-student-layout>