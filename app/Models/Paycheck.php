<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paycheck extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'amount', 'expense', 'balance'];

    public function items()
    {
        return $this->hasMany(PaycheckItem::class);
    }
}