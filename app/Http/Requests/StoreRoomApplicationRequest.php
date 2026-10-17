<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoomApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $phone = ['regex:/^[0-9+\-\s]{9,15}$/'];
        $income = ['nullable', 'integer', 'min:0', 'max:9999999'];
        $count = ['nullable', 'integer', 'min:0', 'max:15'];

        return [
            // A. Student
            'student_phone' => ['required', ...$phone],
            'blood_group' => ['nullable', Rule::in(config('college.blood_groups'))],
            'student_type' => ['nullable', Rule::in(array_keys(config('college.student_types')))],
            'practical_next_semester' => ['nullable', 'boolean'],
            'practical_from' => ['nullable', 'required_if:practical_next_semester,1', 'date'],
            'practical_to' => ['nullable', 'required_if:practical_next_semester,1', 'date', 'after_or_equal:practical_from'],

            // B. Family
            'father_name' => ['required', 'string', 'max:150'],
            'father_occupation' => ['nullable', 'string', 'max:100'],
            'father_income' => $income,
            'father_employer_name' => ['nullable', 'string', 'max:150'],
            'father_employer_phone' => ['nullable', ...$phone],
            'mother_name' => ['nullable', 'string', 'max:150'],
            'mother_occupation' => ['nullable', 'string', 'max:100'],
            'mother_income' => $income,
            'mother_employer_name' => ['nullable', 'string', 'max:150'],
            'mother_employer_phone' => ['nullable', ...$phone],
            'home_address_1' => ['required', 'string', 'max:150'],
            'home_address_2' => ['nullable', 'string', 'max:150'],
            'postcode' => ['required', 'digits:5'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', Rule::in(config('college.states'))],
            'guardian_relationship' => ['required', Rule::in(config('college.guardian_relationships'))],
            'guardian_phone' => ['required', ...$phone],

            // C. Marital status
            'is_married' => ['nullable', 'boolean'],
            'spouse_name' => ['nullable', 'required_if:is_married,1', 'string', 'max:150'],
            'spouse_occupation' => ['nullable', 'string', 'max:100'],
            'spouse_income' => $income,
            'spouse_phone' => ['nullable', ...$phone],

            // D. Family standing
            'birth_order' => ['nullable', 'integer', 'min:1', 'max:15'],
            'dependants_private_uni' => $count,
            'dependants_public_uni' => $count,
            'dependants_secondary' => $count,
            'dependants_primary' => $count,
            'dependants_preschool' => $count,
            'dependants_not_in_school' => $count,

            // E. Vehicle
            'has_vehicle' => ['nullable', 'boolean'],
            'vehicle_type' => ['nullable', 'required_if:has_vehicle,1', Rule::in(config('college.vehicle_types'))],
            'vehicle_model' => ['nullable', 'string', 'max:100'],
            'vehicle_plate' => ['nullable', 'required_if:has_vehicle,1', 'string', 'max:20'],

            // F. Emergency contact
            'emergency_name' => ['required', 'string', 'max:150'],
            'emergency_phone' => ['required', ...$phone],
            'emergency_relationship' => ['required', Rule::in(config('college.emergency_relationships'))],

            // Medical record
            'medical_proof' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'medical_condition' => ['nullable', 'required_with:medical_proof', 'string', 'max:255'],

            'declaration' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'postcode.digits' => 'The postcode must be 5 digits.',
            'declaration.accepted' => 'Please confirm the declaration before submitting.',
            'medical_condition.required_with' => 'Please describe the condition shown in your medical record.',
        ];
    }
}