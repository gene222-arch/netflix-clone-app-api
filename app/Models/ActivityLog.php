<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


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


    public function setModelTypeAttribute($value)
    {
        $this->attributes['model_type'] = ActivityLog::class;
    }

    public function setUserIdAttribute($value)
    {
        $this->attributes['user_id'] = auth('api')->user()->id;
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
