<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileTable extends Model
{
    public $table = "files_tbl";
    public $timestamps = false;

//    public function users()
//    {
//        return $this->belongsTo('UserTable', 'id_user', 'id');
//    }
}
