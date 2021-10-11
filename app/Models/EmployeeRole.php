<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class EmployeeRole extends Role
{
    use HasFactory;

    public function getMorphClass()
    {
        return 'employees';
    }

    public function employees()
    {
        return $this->morphedByMany(
            Employee::class,
            'model',
            config('permission.table_names.model_has_roles'),
            'role_id',
            config('permission.column_names.model_morph_key')
        );
    }

    public function getTable()
    {
        return 'employees';
    }
}
