<?php

namespace App\Tools;

class MyTools
{
    public static function getNameUploadFile($name)
    {
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        return uniqid() . "-" . time() . "." . $ext;
    }
}