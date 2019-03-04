<?php

namespace App\Models;

use PDO;

class User extends \Core\Model
{

    public static function getAuth($arrPost)
    {
        $sql = "SELECT `id`, `password` FROM `users_tbl` 
                            WHERE `login` = '" . $arrPost['login'] . "'";
        $stmt = self::getDB()->query($sql);
        if ($stmt->rowCount() == 1) {
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($arrPost["pass"], $res["password"])) {
                $_SESSION["id_user"] = $res['id'];
            }
        }
        return $_SESSION["id_user"] ?? NULL;
    }

    public static function regUser()
    {
        $login = trim(strtolower($_POST["login"]));
        $password = password_hash($_POST["pass"], PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users_tbl`(login, password, name, age, comment, photo) 
                VALUES(?, ?, ?, ?, ?, ?)";
        $insertUser = self::getDB()->prepare($sql);
        $insertUser->bindParam(1, $login);
        $insertUser->bindParam(2, $password);
        $insertUser->bindParam(3, $_POST["name"]);
        $insertUser->bindParam(4, $_POST["age"]);
        $insertUser->bindParam(5, $_POST["comment"]);
        $insertUser->bindParam(6, $_FILES["userfile"]["name"]);
        $insertUser->execute();
        if (!empty($_FILES['userfile']['tmp_name'])) {
            $fileContent = file_get_contents($_FILES['userfile']['tmp_name']);
            file_put_contents('./uploads/' . $_FILES['userfile']['name'], $fileContent);
        }
        return $_SESSION["id_user"] = self::getDB()->lastInsertId();
    }

    public static function getUserInfo($userId)
    {
        return self::getDB()->query("SELECT * FROM `users_tbl` WHERE `id` = '" . $userId . "'")->fetch(PDO::FETCH_ASSOC);
    }

    public static function getUsers($sort = "ASC")
    {
        $selectUsers = self::getDB()->query("SELECT `name`, `age` FROM `users_tbl` ORDER BY `age` $sort LIMIT 100");
        if ($selectUsers->rowCount() != 0) {
            $res = $selectUsers->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }
    }
}
