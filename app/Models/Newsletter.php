<?php

namespace App\Models;

class Newsletter extends BaseModel
{
    protected $fillable=['email','token','active','pos'];

    public function setActiveAttribute($value)
    {
        $this->attributes['active'] = w2_set_checkbox($value);
    }
}
