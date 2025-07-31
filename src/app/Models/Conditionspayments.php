<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conditionspayments extends Model
{
    protected $table = 'conditions_payments';
    protected $guarded = ['id'];
    protected $fillable = [
        'sale_id',
        'value',
        'due_date',
    ];
}
