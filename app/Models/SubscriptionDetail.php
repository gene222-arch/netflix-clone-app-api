<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'payment_method',
        'paid_amount'
    ];

    public $timestamps = false;
}
