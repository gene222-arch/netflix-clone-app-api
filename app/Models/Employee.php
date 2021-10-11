<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Model
{
    use HasFactory, HasRoles;

    protected $guard_name = 'api';
    
    protected $fillable = [
        'created_by_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'pin_code'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($employee) {
            $employee->created_by_id = auth('api')->id();
        });
    }
}
