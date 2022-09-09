<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends BaseModel
{
    protected $table = 'configuraciones';

    protected $fillable = [
        'app_name','email_from_address','email_to_contact',
        'phone','mobile','recaptcha_public','recaptcha_secret','h_script','f_script'
    ];

    public $timestamps = false;

    public function setHScriptAttribute($value){
        $this->attributes['h_script'] = str_replace(["\t","\n"],'',$value);
    }
    
    public function setFScriptAttribute($value){
        $this->attributes['f_script'] = str_replace(["\t","\n"],'',$value);
    }
}
