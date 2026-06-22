<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class RitualAcknowledgement extends Model
{
    protected $fillable = [
        'user_id',
        'ritual_requirement_id',
        'acknowledged_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'acknowledged_at' => 'datetime',
        ];
    }

    /**
     * The user who acknowledged the ritual/taboo.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The ritual requirement that was acknowledged.
     */
    public function ritualRequirement()
    {
        return $this->belongsTo(RitualRequirement::class);
    }
}
