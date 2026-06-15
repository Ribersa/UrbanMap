<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveReport extends Model
{
    protected $fillable = [
        'mystery_id',
        'status_note',
        'image_path',
    ];

    public function mystery()
    {
        return $this->belongsTo(Mystery::class);
    }
}
