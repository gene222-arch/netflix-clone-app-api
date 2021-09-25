<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

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


    public function setModelTypeAttribute($value)
    {
        $this->attributes['model_type'] = ActivityLog::class;
    }

    public function setUserIdAttribute($value)
    {
        $this->attributes['user_id'] = Auth::user()->id;
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
