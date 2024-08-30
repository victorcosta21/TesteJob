<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkedProducts extends Model
{
    use HasFactory;

    protected $table = 'linked_products';

    protected $fillable = [
        'payment_id',
        'product_name',
        'quantity',
        'value',
        'sub_value',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
