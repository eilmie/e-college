<x-student-layout>
    <x-slot name="header">Room application, {{ $semester }}</x-slot>

    @php
        $opt = fn ($list) => array_combine($list, $list);
        $range = fn ($from, $to) => array_combine(range($from, $to), range($from, $to));
        $steps = ['Student', 'Family', 'Dependants & vehicle', 'Emergency contact', 'Medical & submit'];

        // Which step holds each field, so a server error sends the student back to the right step
        $stepFields = [
            1 => ['student_phone', 'blood_group', 'student_type', 'practical_next_semester', 'practical_from', 'practical_to'],
            2 => ['father_name', 'father_occupation', 'father_income', 'father_employer_name', 'father_employer_phone', 'mother_name', 'mother_occupation', 'mother_income', 'mother_employer_name', 'mother_employer_phone', 'home_address_1', 'home_address_2', 'postcode', 'city', 'state', 'guardian_relationship', 'guardian_phone', 'is_married', 'spouse_name', 'spouse_occupation', 'spouse_income', 'spouse_phone'],
            3 => ['birth_order', 'dependants_private_uni', 'dependants_public_uni', 'dependants_secondary', 'dependants_primary', 'dependants_preschool', 'dependants_not_in_school', 'has_vehicle', 'vehicle_type', 'vehicle_model', 'vehicle_plate'],
            4 => ['emergency_name', 'emergency_phone', 'emergency_relationship'],
            5 => ['medical_proof', 'medical_condition', 'declaration'],
        ];
        $startStep = 1;
        foreach ($stepFields as $n => $fields) {
            if ($errors->hasAny($fields)) { $startStep = $n; break; }
        }
    @endphp

    <form method="POST" action="{{ route('application.store') }}" enctype="multipart/form-data" class="space-y-6"
          x-data="{
              step: {{ $startStep }},
              last: 5,
              practical: '{{ old('practical_next_semester', '0') }}',
              married: '{{ old('is_married', '0') }}',
              vehicle: '{{ old('has_vehicle', '0') }}',
              next() {
                  const panel = this.$refs['step' + this.step];
                  let ok = true;
                  panel.querySelectorAll('[data-required]').forEach(el => {
                      if (el.offsetParent === null) return;
                      if (!el.value.trim()) { el.classList.add('border-red-500'); ok = false; }
                      else { el.classList.remove('border-red-500'); }
                  });
                  if (ok) { this.step++; window.scrollTo({ top: 0, behavior: 'smooth' }); }
                  else { panel.querySelector('.border-red-500')?.focus(); }
              },
              back() { this.step--; window.scrollTo({ top: 0, behavior: 'smooth' }); }
          }"
          @keydown.enter="if ($event.target.tagName === 'INPUT' && step < last) { $event.preventDefault(); next(); }">
        @csrf

        <ol class="flex items-center gap-2 text-sm">
            @foreach ($steps as $i => $label)
                @php $n = $i + 1; @endphp
                <li class="flex items-center gap-2 {{ $n < count($steps) ? 'flex-1' : '' }}">
                    <span class="w-7 h-7 flex-none rounded-full flex items-center justify-center text-xs font-semibold"
                          :class="step > {{ $n }} ? 'bg-emerald-100 text-emerald-700' : (step === {{ $n }} ? 'bg-brand text-white' : 'bg-slate-200 text-slate-600')">{{ $n }}</span>
                    <span class="hidden md:block whitespace-nowrap" :class="step === {{ $n }} ? 'font-medium text-slate-900' : 'text-slate-500'">{{ $label }}</span>
                    @if ($n < count($steps))
                        <span class="flex-1 h-px bg-slate-200"></span>
                    @endif
                </li>
            @endforeach
        </ol>

        {{-- Step 1: A. Student details --}}
        <section x-show="step === 1" x-ref="step1" x-cloak class="card card-body space-y-6">
            <div>
                <h2 class="font-semibold">A. Student details</h2>
                <p class="text-sm text-slate-500">Butiran pelajar</p>
            </div>

            <div>
                <dl class="grid grid-cols-[9rem_1fr] gap-y-2 text-sm bg-slate-50 rounded-md p-4">
                    <dt class="text-slate-500">Student ID</dt><dd>{{ $user->student_id }}</dd>
                    <dt class="text-slate-500">IC number</dt><dd>{{ $portal?->ic_number ?? '-' }}</dd>
                    <dt class="text-slate-500">Name</dt><dd>{{ $user->name }}</dd>
                    <dt class="text-slate-500">Programme</dt><dd>{{ $portal?->program_code ?? ucfirst($user->program_level) }}</dd>
                    <dt class="text-slate-500">Session</dt><dd>{{ $semester }}, semester {{ $user->current_semester }}</dd>
                    <dt class="text-slate-500">Gender</dt><dd>{{ ucfirst($user->gender) }}</dd>
                    <dt class="text-slate-500">Study status</dt><dd>{{ $portal?->study_status ?? '-' }}</dd>
                </dl>
                <p class="text-xs text-slate-500 mt-2">These details come from the student portal and cannot be edited here.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form-field name="student_phone" label="Phone number" :value="$portal?->phone" required hint="For example 0123456789" />
                <x-form-select name="blood_group" label="Blood group" :options="$opt(config('college.blood_groups'))" />
                <x-form-select name="student_type" label="Student type" :options="config('college.student_types')" />
            </div>

            <div class="space-y-4">
                <x-yes-no name="practical_next_semester" label="Practical training next semester?" model="practical" />
                <div x-show="practical === '1'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-form-field name="practical_from" label="From" type="date" required />
                    <x-form-field name="practical_to" label="Until" type="date" required />
                </div>
            </div>
        </section>

        {{-- Step 2: B. Family and C. Marital status --}}
        <section x-show="step === 2" x-ref="step2" x-cloak class="card card-body space-y-6">
            <div>
                <h2 class="font-semibold">B. Family information</h2>
                <p class="text-sm text-slate-500">Maklumat keluarga</p>
            </div>

            <h3 class="text-sm font-semibold text-slate-700">Father / guardian</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form-field name="father_name" label="Name" required :span="2" />
                <x-form-field name="father_occupation" label="Occupation" />
                <x-form-field name="father_income" label="Monthly income (RM)" type="number" min="0" hint="Whole number, no cents" />
                <x-form-field name="father_employer_name" label="Employer name" />
                <x-form-field name="father_employer_phone" label="Employer phone number" />
            </div>

            <h3 class="text-sm font-semibold text-slate-700 pt-4 border-t border-slate-100">Mother</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form-field name="mother_name" label="Name" :span="2" />
                <x-form-field name="mother_occupation" label="Occupation" />
                <x-form-field name="mother_income" label="Monthly income (RM)" type="number" min="0" hint="Whole number, no cents" />
                <x-form-field name="mother_employer_name" label="Employer name" />
                <x-form-field name="mother_employer_phone" label="Employer phone number" />
            </div>

            <h3 class="text-sm font-semibold text-slate-700 pt-4 border-t border-slate-100">Family home</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form-field name="home_address_1" label="Address line 1" required :span="2" />
                <x-form-field name="home_address_2" label="Address line 2" :span="2" />
                <x-form-field name="postcode" label="Postcode" required maxlength="5" />
                <x-form-field name="city" label="City" required />
                <x-form-select name="state" label="State" :options="$opt(config('college.states'))" required />
                <x-form-select name="guardian_relationship" label="Relationship with guardian" :options="$opt(config('college.guardian_relationships'))" required />
                <x-form-field name="guardian_phone" label="Father / mother / guardian phone number" required :span="2" />
            </div>

            <div class="pt-4 border-t border-slate-100 space-y-4">
                <div>
                    <h2 class="font-semibold">C. Marital status</h2>
                    <p class="text-sm text-slate-500">Status perkahwinan, only if you are married</p>
                </div>
                <x-yes-no name="is_married" label="Are you married?" model="married" />
                <div x-show="married === '1'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-form-field name="spouse_name" label="Spouse name" required :span="2" />
                    <x-form-field name="spouse_occupation" label="Spouse occupation" />
                    <x-form-field name="spouse_income" label="Spouse income (RM)" type="number" min="0" />
                    <x-form-field name="spouse_phone" label="Spouse phone number" />
                </div>
            </div>
        </section>

        {{-- Step 3: D. Family standing and E. Vehicle --}}
        <section x-show="step === 3" x-ref="step3" x-cloak class="card card-body space-y-6">
            <div>
                <h2 class="font-semibold">D. Family standing and dependants</h2>
                <p class="text-sm text-slate-500">Kedudukan dan tanggungan dalam keluarga</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form-select name="birth_order" label="Which child are you?" :options="$range(1, 15)" />
                <x-form-select name="dependants_private_uni" label="Dependants in private higher education (IPTS)" :options="$range(0, 10)" :value="0" />
                <x-form-select name="dependants_public_uni" label="Dependants in public higher education (IPTA)" :options="$range(0, 10)" :value="0" />
                <x-form-select name="dependants_secondary" label="Dependants in secondary school" :options="$range(0, 10)" :value="0" />
                <x-form-select name="dependants_primary" label="Dependants in primary school" :options="$range(0, 10)" :value="0" />
                <x-form-select name="dependants_preschool" label="Dependants in pre-school" :options="$range(0, 10)" :value="0" />
                <x-form-select name="dependants_not_in_school" label="Dependants not yet in school" :options="$range(0, 10)" :value="0" />
            </div>

            <div class="pt-4 border-t border-slate-100 space-y-4">
                <div>
                    <h2 class="font-semibold">E. Vehicle information</h2>
                    <p class="text-sm text-slate-500">Maklumat kenderaan, only if you have one</p>
                </div>
                <x-yes-no name="has_vehicle" label="Do you have a vehicle?" model="vehicle" />
                <div x-show="vehicle === '1'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-form-select name="vehicle_type" label="Vehicle type" :options="$opt(config('college.vehicle_types'))" required />
                    <x-form-field name="vehicle_model" label="Make and model" />
                    <x-form-field name="vehicle_plate" label="Plate number" required />
                </div>
            </div>
        </section>

        {{-- Step 4: F. Emergency contact --}}
        <section x-show="step === 4" x-ref="step4" x-cloak class="card card-body space-y-6">
            <div>
                <h2 class="font-semibold">F. Emergency contact</h2>
                <p class="text-sm text-slate-500">Maklumat waris untuk dihubungi ketika kecemasan</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form-field name="emergency_name" label="Contact name" required :span="2" />
                <x-form-field name="emergency_phone" label="Phone number" required />
                <x-form-select name="emergency_relationship" label="Relationship" :options="$opt(config('college.emergency_relationships'))" required />
            </div>
        </section>

        {{-- Step 5: Medical record and submit --}}
        <section x-show="step === 5" x-ref="step5" x-cloak class="card card-body space-y-6">
            <div>
                <h2 class="font-semibold">Medical record</h2>
                <p class="text-sm text-slate-500">Upload only if you have a physical health condition the college should consider.</p>
            </div>

            <div class="rounded-md bg-slate-50 p-4 text-sm text-slate-600 space-y-1">
                <p>Accepted: a letter or report from a doctor or hospital. PDF, JPG or PNG, up to 5 MB.</p>
                <p>Only college staff who review applications can open this file. A verified condition gives priority in room allocation.</p>
            </div>

            <div>
                <label for="medical_proof" class="label">Medical record</label>
                <input id="medical_proof" name="medical_proof" type="file" accept=".pdf,.jpg,.jpeg,.png"
                       class="block w-full text-sm file:mr-4 file:rounded-md file:border-0 file:bg-brand-soft file:px-4 file:py-2 file:text-sm file:font-medium file:text-brand hover:file:bg-slate-200">
                @error('medical_proof')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <x-form-field name="medical_condition" label="What condition does the document show?" hint="Needed if you upload a document. For example: asthma or a mobility impairment." />

            <div class="pt-4 border-t border-slate-100">
                <label class="flex items-start gap-3 text-sm">
                    <input type="checkbox" name="declaration" value="1" @checked(old('declaration')) class="mt-0.5 rounded border-slate-300 text-brand focus:ring-brand">
                    <span>I confirm that the information I entered is true. <span class="text-slate-500">(Sila pastikan maklumat yang diisi adalah betul.)</span></span>
                </label>
                @error('declaration')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </section>

        <div class="flex items-center justify-between">
            <div>
                <a x-show="step === 1" href="{{ route('dashboard') }}" class="text-sm text-slate-600 underline">Cancel</a>
                <button type="button" x-show="step > 1" x-cloak @click="back()" class="btn-outline">Back</button>
            </div>
            <div>
                <button type="button" x-show="step < last" @click="next()" class="btn-primary">Next</button>
                <button type="submit" x-show="step === last" x-cloak class="btn-accent">Submit application</button>
            </div>
        </div>
    </form>
</x-student-layout>