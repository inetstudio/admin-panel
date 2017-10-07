<?php

namespace InetStudio\AdminPanel\Controllers\Images;

use Intervention\Image\ImageManagerStatic as Image;

class ImagesController
{
    public function getImage($id)
    {
        $mediaClass = config('medialibrary.media_model');
        $media = $mediaClass::findOrFail($id);

        $img = Image::cache(function ($image) use ($media) {
            return $image->make($media->getPath());
        }, 86400, true);

        return $img->response();
    }
}
