<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 11/20/18
 * Time: 2:09 PM
 */

namespace App\Models\Traits;


trait Translable
{
    public function translations()
    {
        return $this->morphMany('App\Models\Translation', 'translable');
    }

    public function translation(){
        return $this->MorphOne('App\Models\Translation', 'translable')->where('locale',app()->getLocale());
    }

    public function defaultTranslation(){
        return $this->MorphOne('App\Models\Translation', 'translable')->where('locale',config('app.locale'));
    }
}