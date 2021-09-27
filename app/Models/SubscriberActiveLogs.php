<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriberActiveLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id'
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($activeLog) {
            $activeLog->user_id = auth('api')->id();
        });
    }
}
