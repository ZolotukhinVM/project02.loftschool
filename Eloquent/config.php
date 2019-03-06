<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'project02_db',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);
//$users = Capsule::table('users')
//    ->select(Capsule::raw('count(*) as user_count, status'))
//    ->where('status', '<>', 1)
//    ->groupBy('status')
//    ->get();
// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();
// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();
//$users = $capsule->table('users')
//    ->where('id', '>', 35)
//    ->select(['id', 'name'])
//    ->get();
//echo
// "<pre>";
//users ==> User
class UserTable extends Illuminate\Database\Eloquent\Model
{
//    protected $fillable = ['name', 'password', 'info'];//разрешено редактировать только это, остальное запрещено
//    protected $guarded = ['id']; //запрещено редактировать только это, все остальное разрешено
    //created_at - дата создания
    //update_at - дата последнего редактирования
//    public $timestamps = false;
    public $table = "users_tbl";
    public $timestamps = false;

//    protected $primaryKey = 'id';
    public function files()
    {
        return $this->hasMany('FileTable', 'id_user', 'id');
    }
}

class FileTable extends Illuminate\Database\Eloquent\Model
{
    public $table = "files_tbl";
    public $timestamps = false;

    public function userData()
    {
//        return $this->belongsTo('UserTable', 'id_user', 'id');
    }
}
//class Post extends Illuminate\Database\Eloquent\Model {
//    public function userdata()
//    {
//        return $this->belongsTo('User', 'user_id', 'id');
//    }
//}