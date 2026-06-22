<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class RitualItem extends Model
{
    protected $fillable = [
        'ritual_requirement_id',
        'item_name',
        'quantity',
        'notes',
    ];

    /**
     * The ritual requirement this item belongs to.
     */
    public function ritualRequirement()
    {
        return $this->belongsTo(RitualRequirement::class);
    }
}
