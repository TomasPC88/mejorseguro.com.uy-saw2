<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 11/20/18
 * Time: 2:09 PM
 */

namespace App\Models\Traits;


trait ClassData
{
    public static function getDeclaredTraits(){
        return get_declared_traits();
    }

    public static function getDeclaredInterfaces(){
        return get_declared_interfaces();
    }
}
