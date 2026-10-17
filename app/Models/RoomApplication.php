<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomApplication extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'semester', 'medical_status', 'medical_proof_path', 'merit_score', 'has_disciplinary_record', 'status'];

public function user()
{
    return $this->belongsTo(User::class);
}

public function allocation()
{
    return $this->hasOne(Allocation::class);
}

public function detail()
{
    return $this->hasOne(ApplicationDetail::class);
}
}
