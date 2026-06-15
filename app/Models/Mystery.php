<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mystery extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'category',
        'scary_level',
        'latitude',
        'longitude',
        'is_verified',
        'image_path',
    ];

    public function liveReports()
    {
        return $this->hasMany(LiveReport::class);
    }
}
