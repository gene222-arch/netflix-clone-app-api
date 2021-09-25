<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'model_type',
        'view_data_path',
        'description',
        'executed_at'
    ];

    public $timestamps = false;

    protected static function booted()
    {
        parent::boot();

        static::creating(function ($activityLog) {
            $activityLog->user_id = auth('api')->id();
        });
    }

    public function setUserIdAttribute($value)
    {
        $this->attributes['user_id'] = auth('api')->user()->id;
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
