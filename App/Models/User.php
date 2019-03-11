<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $table = "users_tbl";
    public $timestamps = false;

    public static function getUsers($sort = "ASC")
    {
        return User::with('files')->orderBy('age', $sort)->get(['id', 'login', 'name', 'age']);
    }

    public static function getUserByLogin($login)
    {
        return User::where('login', $login)->first();
    }

    public function files()
    {
        return $this->hasMany('App\Models\File', 'id_user', 'id');
    }
}
