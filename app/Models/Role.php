<?php

namespace App\Models;

use App\Models\Traits\ClassData;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use ClassData;

    protected $fillable = ['name','guard_name'];

}
