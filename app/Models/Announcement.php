<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'caption_path',
        'event_date',
        'headline',
        'description',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];
}
