<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriberActiveLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id'
    ];

    public $timestamps = false;

    protected static function booted()
    {
        parent::boot();

        static::creating(function ($log) {
            $log->active_at = Carbon::now();
        });
    }
}
