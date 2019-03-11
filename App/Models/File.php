<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public $table = "files_tbl";
    public $timestamps = false;

    public static function getUserFiles($userId)
    {
        return File::where('id_user', $userId)->orderBy('id', 'desc')->get(['file', 'date']);
    }
//    public function users()
//    {
//        return $this->belongsTo('UserTable', 'id_user', 'id');
//    }
}
