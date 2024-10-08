<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Debt extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'debt_name',
        'debt_amount',
        'debt_supplier',
        'debt_start_date',
        'expected_end_date',
        'remaining_balance'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class)->orderBy('created_at', 'desc');
    }
}
