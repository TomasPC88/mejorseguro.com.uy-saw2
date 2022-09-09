<?php

namespace App\Facades;

use App\Models\Media as MediaModel;

use Hashids\Hashids;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class Media
{
    /**
     * Media constructor.
     */
    public function __construct()
    {
        $this->hasher = new Hashids();
    }

    public function new($request)
    {
        $metas = ( !is_null($request->metas) ) ? $request->metas : [];

        $m                  = new MediaModel();
        $m->metas           = ( !is_null($request->metas) ) ? json_encode($metas) : '';
        $m->group           = $request->group;
        $m->type            = $request->simple_type;
        $m->extension       = ( $request->hasFile('file') && $request->file('file')->isValid() ) ? $request->file('file')->extension() : $request->video['platform'];
        $m->url             = ( $request->simple_type == 'video' ) ? $request->video['url'] : '';
        $m->video_id        = ( $request->simple_type == 'video' ) ? $request->video['id'] : '';
        $m->video_thumbnail = ( $request->simple_type == 'video' ) ? $request->video['thumbnail'] : '';
        $m->pos             = $request->pos;

        return $m;
    }

    public function updateMetas($row_id, $metas, $pos)
    {
        $t          = MediaModel::find($row_id);
        $t->metas   = ( !is_null($metas) ) ? json_encode($metas) : '';
        $t->pos     = $pos;
        $t->save();

        return true;
    }

    public function updateUrl($id, $extension)
    {
        $hash_id = $this->hasher->encode($id);
        MediaModel::findOrFail($id)->update(['url' => $hash_id.'.'.$extension]);

    }

    public function delete($row_id)
    {
        $t  = MediaModel::find($row_id);

        // delete files
        $t->delete();
    }

    public function store($type, $file, $hash_id, $user_sizes, $ignore)
    {
        if($type == 'document') return $this->storeDocument($file, $hash_id);

        $image_extension = $file->extension();
        $images = [];
        $sizes = [];

        foreach(config('wo2.images.sizes') as $size_key => $size_val)
        {
            if(!in_array($size_key, $ignore)) {
                if(!array_key_exists($size_key, $user_sizes)){
                    $sizes[$size_key] = $size_val;
                } else {
                    $sizes[$size_key] = $user_sizes[$size_key];
                }
            }
        }

        foreach($sizes as $size_name => $size_dimentions)
        {
            //if($size_name == 'icon') {
//
//                $image_stream = Image::make($file)
//                    ->fit($size_dimentions[0], $size_dimentions[1], function($constraint) {
//                        $constraint->upsize();
//                    })->stream('jpg', 60);
//
//                $file_name = config('wo2.images.images_folder').'/'.$size_name.'_'.$hash_id.'.jpg';
//
//            } else {
//
            $image_stream = Image::make($file)
                ->resize($size_dimentions[0], $size_dimentions[1], function($constraint) {
                    $constraint->aspectRatio();
                })->stream(null, config('wo2.images.quality'));

            $file_name = config('wo2.images.images_folder').'/'.$size_name.'_'.$hash_id.'.'.$image_extension;
//            }

            $store = Storage::disk('public')->put($file_name, $image_stream);

            if($store) {
                array_push($images, asset('storage/'.$file_name));
            } else {
                array_push($images, $store);
            }
        }

        return $images;
    }

    public function storeDocument($file, $hash_id)
    {
        $file_extension = $file->extension();

        return asset(
            'storage/'.Storage::disk('public')->putFileAs(config('wo2.images.documents_folder'), $file, 'document_'.$hash_id.'.'.$file_extension)
        );
    }

}
