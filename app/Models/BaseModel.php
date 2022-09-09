<?php

namespace App\Models;

use App\Models\Traits\ClassData;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class BaseModel extends Model
{
    use HasRoles, ClassData;

    //Base methods here
}
