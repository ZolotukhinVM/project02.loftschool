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

    public static function checkUserLogin($login)
    {
        $result = self::getDB()->prepare("SELECT * FROM users_tbl WHERE login  = ?");
        $result->execute([$login]);
        return $result->rowCount();
    }

    public static function regUser()
    {
        $login = trim(strtolower($_POST["login"]));
        $password = password_hash($_POST["pass"], PASSWORD_DEFAULT);
        $file = empty($_FILES["userfile"]) ? null : $_FILES["userfile"];
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $newName = uniqid() . "-" . time() . "." . $ext;
        $sql = "INSERT INTO `users_tbl`(login, password, name, age, comment, photo) 
                VALUES(?, ?, ?, ?, ?, ?)";
        $insertUser = self::getDB()->prepare($sql);
        $insertUser->bindParam(1, $login);
        $insertUser->bindParam(2, $password);
        $insertUser->bindParam(3, $_POST["name"]);
        $insertUser->bindParam(4, $_POST["age"]);
        $insertUser->bindParam(5, $_POST["comment"]);
        $insertUser->bindParam(6, $newName);
        $insertUser->execute();
        move_uploaded_file($file["tmp_name"], './uploads/' . $newName);
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
        }
        return $res ?? null;
    }

    public static function updateUser($userId)
    {
        $sqlPass = "";
        if (!empty($_POST["pass"])) {
            $password = password_hash($_POST["pass"], PASSWORD_DEFAULT);
            $sqlPass = "`password` = '" . $password . "',";
            $data[] = $password;
        }
        $sql = "UPDATE `users_tbl` 
                SET " . $sqlPass . " `name` = ?, age = ?, comment = ?
                WHERE `id` = '$userId'";
        $updateUser = self::getDB()->prepare($sql);
        $data = [$_POST["name"], $_POST["age"], $_POST["comment"]];
        return $updateUser->execute($data);
    }
}
