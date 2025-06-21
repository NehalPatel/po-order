<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipToAddress extends Model
{
    use HasFactory;

    protected $table = 'ship_to_addresses';

    protected $fillable = [
        'team_id',
        'name',
        'company_name',
        'address',
        'city',
        'state',
        'zipcode',
        'phone',
    ];

    /**
     * Get the team that owns the address.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}