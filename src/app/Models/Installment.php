<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'installment_number',
        'amount',
        'due_date',
        'paid',
        'paid_date'
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
        'paid' => 'boolean'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}

