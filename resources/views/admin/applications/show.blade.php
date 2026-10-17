<x-admin-layout>
    <x-slot name="header">{{ $application->user->name }}</x-slot>
    <x-slot name="subtitle">{{ $application->user->student_id }} · Application for {{ $application->semester }}</x-slot>

    @php
        $d = $application->detail;
        $rm = fn ($v) => $v === null ? null : 'RM '.number_format($v);
        $yn = fn ($v) => $v ? 'Yes' : 'No';
        $ext = strtolower(pathinfo($application->medical_proof_path ?? '', PATHINFO_EXTENSION));
        $medicalBadge = [
            'none' => 'badge-neutral',
            'pending' => 'badge-warning',
            'verified' => 'badge-success',
            'rejected' => 'badge-danger',
        ][$application->medical_status];
        $statusBadge = [
            'pending' => 'badge-warning',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
        ][$application->status];
    @endphp

    <a href="{{ route('admin.applications.index') }}" class="text-sm text-brand underline">← Back to ranking</a>

    <div class="grid lg:grid-cols-3 gap-6 items-start">

        {{-- Left: the submitted form --}}
        <div class="lg:col-span-2 space-y-6">
            @if (! $d)
                <div class="card card-body text-sm text-slate-600">
                    This application was submitted before the full form existed, so there are no extra details to show.
                </div>
            @else
                <section class="card card-body">
                    <h2 class="font-semibold mb-2">A. Student details</h2>
                    <dl class="divide-y divide-slate-100">
                        <x-detail-row label="Student ID" :value="$application->user->student_id" />
                        <x-detail-row label="IC number" :value="$portal?->ic_number" />
                        <x-detail-row label="Programme" :value="$portal?->program_code" />
                        <x-detail-row label="Gender" :value="ucfirst($application->user->gender)" />
                        <x-detail-row label="Phone number" :value="$d->student_phone" />
                        <x-detail-row label="Blood group" :value="$d->blood_group" />
                        <x-detail-row label="Student type" :value="ucfirst($d->student_type ?? '')" />
                        <x-detail-row label="Practical training next semester" :value="$yn($d->practical_next_semester)" />
                        @if ($d->practical_next_semester)
                            <x-detail-row label="Practical period" :value="$d->practical_from?->format('d M Y').' to '.$d->practical_to?->format('d M Y')" />
                        @endif
                    </dl>
                </section>

                <section class="card card-body">
                    <h2 class="font-semibold mb-2">B. Family information</h2>
                    <dl class="divide-y divide-slate-100">
                        <x-detail-row label="Father / guardian" :value="$d->father_name" />
                        <x-detail-row label="Occupation" :value="$d->father_occupation" />
                        <x-detail-row label="Monthly income" :value="$rm($d->father_income)" />
                        <x-detail-row label="Employer" :value="$d->father_employer_name" />
                        <x-detail-row label="Employer phone" :value="$d->father_employer_phone" />
                        <x-detail-row label="Mother" :value="$d->mother_name" />
                        <x-detail-row label="Occupation" :value="$d->mother_occupation" />
                        <x-detail-row label="Monthly income" :value="$rm($d->mother_income)" />
                        <x-detail-row label="Employer" :value="$d->mother_employer_name" />
                        <x-detail-row label="Employer phone" :value="$d->mother_employer_phone" />
                        <x-detail-row label="Home address" :value="trim($d->home_address_1.', '.$d->home_address_2, ', ')" />
                        <x-detail-row label="Postcode and city" :value="$d->postcode.' '.$d->city" />
                        <x-detail-row label="State" :value="$d->state" />
                        <x-detail-row label="Relationship with guardian" :value="$d->guardian_relationship" />
                        <x-detail-row label="Guardian phone" :value="$d->guardian_phone" />
                    </dl>
                </section>

                @if ($d->is_married)
                    <section class="card card-body">
                        <h2 class="font-semibold mb-2">C. Marital status</h2>
                        <dl class="divide-y divide-slate-100">
                            <x-detail-row label="Spouse" :value="$d->spouse_name" />
                            <x-detail-row label="Occupation" :value="$d->spouse_occupation" />
                            <x-detail-row label="Income" :value="$rm($d->spouse_income)" />
                            <x-detail-row label="Phone" :value="$d->spouse_phone" />
                        </dl>
                    </section>
                @endif

                <section class="card card-body">
                    <h2 class="font-semibold mb-2">D. Family standing and dependants</h2>
                    <dl class="divide-y divide-slate-100">
                        <x-detail-row label="Birth order" :value="$d->birth_order" />
                        <x-detail-row label="IPTS" :value="$d->dependants_private_uni" />
                        <x-detail-row label="IPTA" :value="$d->dependants_public_uni" />
                        <x-detail-row label="Secondary school" :value="$d->dependants_secondary" />
                        <x-detail-row label="Primary school" :value="$d->dependants_primary" />
                        <x-detail-row label="Pre-school" :value="$d->dependants_preschool" />
                        <x-detail-row label="Not yet in school" :value="$d->dependants_not_in_school" />
                    </dl>
                </section>

                @if ($d->has_vehicle)
                    <section class="card card-body">
                        <h2 class="font-semibold mb-2">E. Vehicle</h2>
                        <dl class="divide-y divide-slate-100">
                            <x-detail-row label="Type" :value="$d->vehicle_type" />
                            <x-detail-row label="Make and model" :value="$d->vehicle_model" />
                            <x-detail-row label="Plate number" :value="$d->vehicle_plate" />
                        </dl>
                    </section>
                @endif

                <section class="card card-body">
                    <h2 class="font-semibold mb-2">F. Emergency contact</h2>
                    <dl class="divide-y divide-slate-100">
                        <x-detail-row label="Name" :value="$d->emergency_name" />
                        <x-detail-row label="Phone" :value="$d->emergency_phone" />
                        <x-detail-row label="Relationship" :value="$d->emergency_relationship" />
                    </dl>
                    <p class="text-xs text-slate-500 mt-3">Declared true on {{ $d->declared_at?->format('d M Y, H:i') }}</p>
                </section>
            @endif
        </div>

        {{-- Right: ranking inputs and medical record --}}
        <div class="space-y-6 lg:sticky lg:top-6">
            <section id="record" class="card card-body">
                <h2 class="font-semibold mb-2">Ranking inputs</h2>
                <dl class="divide-y divide-slate-100">
                    <div class="flex justify-between py-2 text-sm"><dt class="text-slate-500">Merit score</dt><dd>{{ $application->merit_score }}</dd></div>
                    <div class="flex justify-between py-2 text-sm"><dt class="text-slate-500">Medical</dt><dd><span class="{{ $medicalBadge }}">{{ ucfirst($application->medical_status) }}</span></dd></div>
                    <div class="flex justify-between items-center py-2 text-sm">
                        <dt class="text-slate-500">Disciplinary record</dt>
                        <dd>
                            <form method="POST" action="{{ route('admin.applications.disciplinary', $application) }}" class="flex items-center gap-2">
                                @csrf
                                <input type="hidden" name="has_record" value="{{ $application->has_disciplinary_record ? 0 : 1 }}">
                                <span class="{{ $application->has_disciplinary_record ? 'badge-danger' : 'badge-neutral' }}">{{ $application->has_disciplinary_record ? 'Has record' : 'Clean' }}</span>
                                <button class="text-xs text-brand underline">{{ $application->has_disciplinary_record ? 'Clear' : 'Flag' }}</button>
                            </form>
                        </dd>
                    </div>
                    <div class="flex justify-between py-2 text-sm"><dt class="text-slate-500">Applied</dt><dd>{{ $application->created_at->format('d M Y, H:i') }}</dd></div>
                    <div class="flex justify-between py-2 text-sm"><dt class="text-slate-500">Status</dt><dd><span class="{{ $statusBadge }}">{{ ucfirst($application->status) }}</span></dd></div>
                </dl>
            </section>

            <section id="medical" class="card card-body space-y-4">
                <h2 class="font-semibold">Medical record</h2>

                @if ($application->medical_proof_path)
                    <p class="text-sm text-slate-600">
                        <span class="text-slate-500">Condition declared:</span>
                        {{ $d?->medical_condition ?: '-' }}
                    </p>

                    @if ($ext === 'pdf')
                        <iframe src="{{ route('admin.applications.proof', $application) }}" class="w-full h-96 rounded-md border border-slate-200"></iframe>
                    @else
                        <img src="{{ route('admin.applications.proof', $application) }}" alt="Medical record" class="w-full rounded-md border border-slate-200">
                    @endif

                    <a href="{{ route('admin.applications.proof', $application) }}" target="_blank" class="text-sm text-brand underline">Open in a new tab</a>

                    <form method="POST" action="{{ route('admin.applications.medical', $application) }}" class="flex gap-2">
                        @csrf
                        <button name="decision" value="verified" class="btn-accent" @disabled($application->medical_status === 'verified')>Verify</button>
                        <button name="decision" value="rejected" class="btn-outline" @disabled($application->medical_status === 'rejected')>Reject</button>
                    </form>
                @else
                    <p class="text-sm text-slate-500">The student did not upload a medical record.</p>
                @endif
            </section>
        </div>
    </div>
</x-admin-layout>