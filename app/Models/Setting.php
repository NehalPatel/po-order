<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'company_name',
        'address',
        'phone',
        'logo',
        'website',
        'email',
    ];

    // Optionally, define the relationship to Team
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }
}