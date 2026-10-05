<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'room_id', 'room_application_id', 'semester', 'allocated_at', 'status'];

public function user()
{
    return $this->belongsTo(User::class);
}

public function room()
{
    return $this->belongsTo(Room::class);
}

public function roomApplication()
{
    return $this->belongsTo(RoomApplication::class);
}
}
