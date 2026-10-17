<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPortalData extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'ic_number', 'full_name', 'faculty', 'program_code', 'phone', 'study_status', 'gender', 'program_level', 'current_semester', 'cgpa'];
}
