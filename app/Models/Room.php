<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = ['block', 'room_number', 'floor', 'capacity', 'gender', 'status'];

public function allocations()
{
    return $this->hasMany(Allocation::class);
}
}
