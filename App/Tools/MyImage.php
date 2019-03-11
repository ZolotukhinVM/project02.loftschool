<?php

namespace App\Tools;

use Intervention\Image\ImageManagerStatic as Image;

class MyImage
{
    public static function getImage($original, $result, $size = 200, $rotate = false)
    {
        $img = Image::make($original)
            ->resize($size, null, function ($image) {
                $image->aspectRatio();
            });
        if ($rotate == true) {
            $img->rotate(180);
        }
        $img->save($result);
        return true;
    }
}
