<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class RitualExperience extends Model
{
    protected $fillable = [
        'ritual_requirement_id',
        'user_id',
        'story',
        'witness_count',
    ];

    /**
     * The ritual requirement this experience belongs to.
     */
    public function ritualRequirement()
    {
        return $this->belongsTo(RitualRequirement::class);
    }

    /**
     * The user who submitted this experience.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
