<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTable extends Model
{
    public $table = "users_tbl";
    public $timestamps = false;

//    protected $primaryKey = 'id';
    public function files()
    {
        return $this->hasMany('App\Models\FileTable', 'id_user', 'id');
    }
}
