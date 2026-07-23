<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'amount',
        'category',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch($query, $value): void
    {
        $query->where(function ($q) use ($value) {
            $q->where('category', 'like', "%{$value}%")
              ->orWhere('description', 'like', "%{$value}%");
        });
    }
}
