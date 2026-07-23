<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'customer_id',
        'order_date',
        'order_status',
        'total_products',
        'sub_total',
        'vat',
        'total',
        'invoice_no',
        'payment_type',
        'pay',
        'due',
        "user_id",
        "uuid",
        "notes"
    ];

    protected $casts = [
        'order_date'    => 'date',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'order_status'  => OrderStatus::class
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function scopeSearch($query, $value): void
    {
        $query->where(function($q) use ($value) {
            $q->where('invoice_no', 'like', "%{$value}%")
              ->orWhere('order_status', 'like', "%{$value}%")
              ->orWhere('payment_type', 'like', "%{$value}%")
              ->orWhereHas('customer', function ($customerQuery) use ($value) {
                  $customerQuery->where('name', 'like', "%{$value}%");
              });
        });
    }

     /**
     * Get the user that owns the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCustomerNameAttribute()
    {
        if ($this->customer && $this->customer->name === 'Walk-in Customer' && !empty($this->notes) && str_starts_with($this->notes, 'Walk-in: ')) {
            $specificName = str_replace('Walk-in: ', '', $this->notes);
            return $specificName . ' (Walk-in)';
        }

        return $this->customer ? $this->customer->name : 'Unknown';
    }
}
