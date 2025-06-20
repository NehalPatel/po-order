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
        'street_address',
        'city',
        'state',
        'zipcode',
        'phone',
        'website',
        'email',
    ];

    // Optionally, define the relationship to Team
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
} 