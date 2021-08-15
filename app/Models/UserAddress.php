<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'country',
        'country_code',
        'region_code',
        'region_name',
        'city_name',
        'zip_code',
        'area_code'
    ];
}
