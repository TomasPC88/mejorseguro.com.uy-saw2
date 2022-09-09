<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Translation extends Model
{
    protected $table = 'translations';

    public $timestamps = false;

    protected $fillable = [
        'friendly_url', 'name','description','long_description','active', 'pos', 'locale','cargo'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['friendly_url'] = Str::slug($value, "-");
    }

    public function translable(){
        return $this->morphTo();
    }

}
