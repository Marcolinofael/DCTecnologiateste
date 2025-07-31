<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'costumer_id',
        'total_amount',
        'payment_method',
        'user_id',
        'sale_date',
        'origin_user',
        'last_user'
    ];

    protected $casts = [
        'sale_date' => 'date'
    ];

    public function customer()
    {
        return $this->belongsTo(Costumer::class, 'costumer_id');
    }

    public function products()
    {
        return $this->hasMany(SaleProduct::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function costumer()
    {
        return $this->belongsTo(Costumer::class);
    }
}
