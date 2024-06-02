<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model  // Change this to singular
{
    use HasFactory;

    protected $fillable = [
        'debt_id',
        'payment_amount',
        'paid_at',
        'type',
        'balance'
    ];

    protected $dates = [
        'paid_at',
    ];

    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }
}
