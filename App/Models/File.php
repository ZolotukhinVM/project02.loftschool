<?php

namespace App\Models;

use Core\Model;

class File extends Model
{
    public static function getUserFiles($userId)
    {
        return FileTable::where('id_user', $userId)->orderBy('id', 'desc')->get(['file', 'date']);
    }

    /*
    public static function insertFile($userId, $filename)
    {
        self::getDB()->exec("INSERT INTO `files_tbl` (id_user, `file`) VALUES ('$userId', '$filename')");
    }

    public static function getFiles($userId)
    {
        $selectFiles = self::getDB()->query("SELECT * FROM `files_tbl` WHERE `id_user` = '" . $userId . "' ORDER BY `id` DESC LIMIT 100");
        if ($selectFiles->rowCount() != 0) {
            $res = $selectFiles->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }
    }

    public static function getCountFiles($userId)
    {
        return self::getDB()->query("SELECT COUNT(*) FROM files_tbl WHERE id_user = '$userId'")->fetchColumn();
    }
*/
}
