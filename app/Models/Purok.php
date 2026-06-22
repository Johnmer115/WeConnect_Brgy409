<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purok extends Model
{
    protected $fillable = ['name', 'color_code'];

    public function residents()
    {
        return $this->hasMany(Resident::class);
    }
}