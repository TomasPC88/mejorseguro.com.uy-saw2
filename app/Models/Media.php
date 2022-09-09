<?php

namespace App\Models;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'medias';

    protected $hasher = null;

    protected $fillable = [
        'name', 'description', 'group', 'url', 'extension', 'type', 'video_id', 'video_thumbnail', 'mediable_id', 'mediable_type'
    ];

    protected $casts = [
        'metas' => 'json'
    ];

    protected $appends = [
        'icon', 'thumb', 'normal', 'large', 'extra'
    ];

    /**
     * Media constructor.
     */
    public function __construct()
    {
        $this->hasher = new Hashids();
        parent::__construct();
    }

    public function mediable()
    {
        return $this->morphTo();
    }

    public function getMetasAttribute($value)
    {
        $clean = [];
        if($value == '""') return json_encode($clean);
        foreach(json_decode(json_decode($value)) as $k => $v)
        {
            if($v == null) $v = '';
            $clean[$k] = $v;
        }

        return json_encode($clean);
    }

    public function getIconAttribute()
    {

        if($this->type == 'video') return $this->video_thumbnail;
        if($this->type == 'image') return asset('storage/'.config('wo2.images.images_folder').'/icon_'.$this->url);
        return '';
    }

    public function getThumbAttribute()
    {
        if($this->type == 'video') return $this->video_thumbnail;
        if($this->type == 'image') return asset('storage/'.config('wo2.images.images_folder').'/thumb_'.$this->url);
        return '';
    }

    public function getNormalAttribute()
    {
        if($this->type == 'video') return $this->video_thumbnail;
        if($this->type == 'image') return asset('storage/'.config('wo2.images.images_folder').'/normal_'.$this->url);
        return '';
    }

    public function getLargeAttribute()
    {
        if($this->type == 'video') return $this->video_thumbnail;
        if($this->type == 'image') return asset('storage/'.config('wo2.images.images_folder').'/large_'.$this->url);
        return '';
    }

    public function getExtraAttribute()
    {
        if($this->type == 'video') return $this->video_thumbnail;
        if($this->type == 'image') return asset('storage/'.config('wo2.images.images_folder').'/extra_'.$this->url);
        return '';
    }

    public static function boot()
    {
        parent::boot();
        self::deleting( function (Media $media){

            if($media->type !== 'video') {
                if($media->type !== 'image') {
                    Storage::disk('public')->delete( config('wo2.images.documents_folder').'/document_'.$media->url);
                } else {
                    foreach( array_keys(config('wo2.images.sizes')) as $size ){
//                        if($size == 'icon') {
//                            Storage::disk('public')->delete( config('wo2.images.images_folder').'/'.$size.'_'.$media->hasher->encode($media->id).'.jpg');
//                        } else {
                            Storage::disk('public')->delete( config('wo2.images.images_folder').'/'.$size.'_'.$media->url);
//                        }
                    }
                }
            }

        });
    }
}
