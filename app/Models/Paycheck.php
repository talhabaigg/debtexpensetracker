<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paycheck extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'paycheck_date',
        'amount',
        'balance',
    ];
}
