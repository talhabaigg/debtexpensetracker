<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaycheckItem extends Model
{
    use HasFactory;

    protected $fillable = ['paycheck_id', 'item_name', 'item_amount', 'item_type'];

    public function paycheck()
    {
        return $this->belongsTo(Paycheck::class);
    }
}