<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client',
        'subtotal',
        'qtd_parcels',
        'payment_type',
    ];

    public function linkedPayments()
    {
        return $this->hasMany(LinkedPayments::class, 'payment_id');
    }

    public function linkedProducts()
    {
        return $this->hasMany(LinkedProducts::class, 'payment_id');
    }
}
