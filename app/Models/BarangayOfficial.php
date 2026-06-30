<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangayOfficial extends Model
{
    protected $fillable = ['name', 'position', 'category', 'sort_order'];

    // TODO: Add a management UI for CRUD operations on this model in a future sprint.
    // For now, officials are seeded manually or via Tinker.
}
