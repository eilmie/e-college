<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationDetail extends Model
{
    // Only validated form data ever reaches this model, and room_application_id is set by us.
    protected $guarded = ['id', 'room_application_id'];

    protected $casts = [
        'practical_next_semester' => 'boolean',
        'is_married' => 'boolean',
        'has_vehicle' => 'boolean',
        'practical_from' => 'date',
        'practical_to' => 'date',
        'declared_at' => 'datetime',
    ];

    public function roomApplication()
    {
        return $this->belongsTo(RoomApplication::class);
    }
}