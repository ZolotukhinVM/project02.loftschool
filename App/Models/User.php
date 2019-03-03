<?php

namespace App\Models;

use PDO;

class User extends \Core\Model
{

    public static function getAuth($arrPost)
    {
        $db = static::getDB();
        $sql = "SELECT `id`, `password` FROM `users_tbl` 
                            WHERE `login` = '" . $arrPost['login'] . "'";
        $stmt = $db->query($sql);
        if ($stmt->rowCount() == 1) {
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($arrPost["pass"], $res["password"])) {
                $_SESSION["id_user"] = $res['id'];
                return $_SESSION["id_user"] ?? NULL;
            }
        }
    }

    public static function regUser()
    {
        $db = static::getDB();
        $login = trim(strtolower($_POST["login"]));
        $password = password_hash($_POST["pass"], PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users_tbl`(login, password, name, age, comment, photo) 
                    VALUES('$login', '$password', '" . $_POST["name"] . "', '" . $_POST["age"] . "', '" . $_POST["comment"] . "', '" . $_FILES["userfile"]["name"] . "')";
        $insertUser = $db->exec($sql);
        if (!empty($_FILES['userfile']['tmp_name'])) {
            $fileContent = file_get_contents($_FILES['userfile']['tmp_name']);
            file_put_contents('./uploads/' . $_FILES['userfile']['name'], $fileContent);
        }
        return $_SESSION["id_user"] = $db->lastInsertId();
    }

    public static function getUserInfo($userId)
    {
        $db = static::getDb();
        return $db->query("SELECT * FROM `users_tbl` WHERE `id` = '" . $userId . "'")->fetch(PDO::FETCH_ASSOC);
    }

    public static function getUsers($sort = "ASC")
    {
        $db = static::getDB();
        $selectUsers = $db->query("SELECT `name`, `age` FROM `users_tbl` ORDER BY `age` $sort LIMIT 100");
        if ($selectUsers->rowCount() != 0) {
            $res = $selectUsers->fetchAll(PDO::FETCH_ASSOC);
            foreach ($res as &$value) {
                $value["status"] = ($value["age"] > 18) ? "Совершеннолетний" : "Несовершеннолетний";
            }
            return $res;
        }
    }

}
