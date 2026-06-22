<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RitualRequirement extends Model
{
    protected $fillable = [
        'mystery_id',
        'instruction',
        'ritual_type',
        'risk_level',
    ];

    /**
     * The mystery this requirement belongs to.
     */
    public function mystery()
    {
        return $this->belongsTo(Mystery::class);
    }

    /**
     * The items/equipment needed for this ritual requirement.
     */
    public function ritualItems()
    {
        return $this->hasMany(\App\Entities\RitualItem::class);
    }

    /**
     * The mystical experiences related to this ritual requirement.
     */
    public function ritualExperiences()
    {
        return $this->hasMany(\App\Entities\RitualExperience::class)->latest();
    }

    /**
     * The acknowledgements from users who committed to this ritual/taboo.
     */
    public function ritualAcknowledgements()
    {
        return $this->hasMany(\App\Entities\RitualAcknowledgement::class);
    }
}
