<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 11/20/18
 * Time: 2:09 PM
 */

namespace App\Models\Traits;


trait Mediable
{
    public function getFirstImageAttribute()
    {
        $first_image = collect([]);
        $sizes = collect(array_keys(config('wo2.images.sizes')));
        $media = $this->medias->first();

        if (!$media)
            $sizes->each(function ($size) use (&$first_image) {
                $first_image->put($size,asset(sprintf('assets/page/img/default/%s_default.png',$size)));
            });
        else
            $sizes->each(function ($size) use (&$first_image,$media) {
                $first_image->put($size, $media->{$size});
            });

        return $first_image;

    }

    public function medias()
    {
        return $this->morphMany('App\Models\Media', 'mediable')->orderBy('pos', 'asc');
    }

}
