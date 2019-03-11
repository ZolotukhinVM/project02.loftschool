<?php

namespace App\Controllers;

use Core\Controller;
use App\Tools\MyTools;
use App\Models\User;
use App\Models\User;
use GUMP;

class Sign extends Controller
{

    public function inAction()
    {
        if (isset($_SESSION["id_user"])) {
            $this->redirect("/users/profile");
        }
        if (empty($_POST)) {
            return $this->render('sign/index.html');
        }
        $user = User::getUserByLogin($_POST["login"]);
        if ($user !== null && password_verify($_POST["pass"], $user->password)) {
            $_SESSION["id_user"] = $user->id;
            $this->redirect("/users/profile");
        }
        return $this->render('sign/index.html', ["message" => "Login or password is not valid"]);
    }

    public function upAction()
    {
        // фильтрация, валидация, логика, представление
        if (empty($_POST)) {
            return $this->render("/Users/reg.html");
        }
        if (empty($_POST["login"])) {
            return $this->render("/Users/reg.html", ["data" => $_POST, "message" => "Login is empty"]);
        }
        $user = User::getUserByLogin($_POST["login"]);
        if ($user !== null) {
            return $this->render("/Users/reg.html", ["data" => $_POST, "message" => "User is exists"]);
        }
        $result = GUMP::is_valid(array_merge($_POST, $_FILES), [
            "login" => "required|alpha_numeric|min_len, 3",
            "pass" => "required|min_len, 3",
            "age" => "required|numeric|max_len, 120",
            "userfile" => "required_file|extension,png;jpg"
        ]);
        if ($result !== true) {
            var_dump($result);
            return $this->render("/Users/reg.html", ["data" => $_POST, "message" => "Not valid", "errors" => $result]);
        }
        $user = new User();
        $user->login = trim(strtolower($_POST["login"]));
        $user->password = password_hash($_POST["pass"], PASSWORD_DEFAULT);
        $user->name = htmlentities(strip_tags($_POST["name"]), ENT_QUOTES);
        $user->age = htmlentities(strip_tags($_POST["age"]), ENT_QUOTES);
        $user->comment = htmlentities(strip_tags($_POST["comment"]), ENT_QUOTES);
        if (!$user->save()) {
            return $this->render("Users/reg.html", ["data" => $_POST, "message" => "DB Error"]);
        }
        $file = empty($_FILES["userfile"]) ? null : $_FILES["userfile"];
        $newFileName = MyTools::getNameUploadFile($file["name"]);
        move_uploaded_file($file["tmp_name"], UPLOAD_PROFILES . $newFileName);
        $user->photo = $newFileName;
        $user->save();
        $_SESSION["id_user"] = $user->id;
        $this->redirect("/users/profile");
        return true;
    }
}