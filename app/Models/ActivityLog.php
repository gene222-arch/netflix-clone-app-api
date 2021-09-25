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

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
