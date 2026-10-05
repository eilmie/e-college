<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPortalData extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'full_name', 'faculty', 'gender', 'program_level', 'current_semester', 'cgpa'];
}
