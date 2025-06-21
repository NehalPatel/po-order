<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'po_number',
        'po_date',
        'vendor_id',
        'sub_total',
        'tax',
        'shipping',
        'other',
        'grand_total',
        'notes',
        'terms_and_conditions',
        'status',
        'payment_status',
        'expected_delivery_date',
    ];

    protected $casts = [
        'po_date' => 'date',
        'expected_delivery_date' => 'date',
    ];

    /**
     * Get the team that owns the purchase order.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the vendor for the purchase order.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the items for the purchase order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Get the ship to address for the purchase order.
     */
    public function shipToAddress(): BelongsTo
    {
        return $this->belongsTo(ShipToAddress::class);
    }
}