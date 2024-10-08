<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkedPayments extends Model
{
    use HasFactory;

    protected $table = 'linked_payments';

    protected $fillable = [
        'payment_id',
        'type_payment',
        'parcel',
        'pay_date',
        'pay_value',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
