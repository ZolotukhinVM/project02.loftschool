<?php

namespace App\Models;

use PDO;

class File extends \Core\Model
{
    public static function insertFile($userId, $filename)
    {
        $db = static::getDB();
        $db->exec("INSERT INTO `files_tbl` (id_user, `file`) VALUES ('$userId', '$filename')");
    }

    public static function getFiles($userId)
    {
        $db = static::getDB();
        $selectFiles = $db->query("SELECT * FROM `files_tbl` WHERE `id_user` = '" . $userId . "' ORDER BY `id` DESC LIMIT 100");
        if ($selectFiles->rowCount() != 0) {
            $res = $selectFiles->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }
    }
}
