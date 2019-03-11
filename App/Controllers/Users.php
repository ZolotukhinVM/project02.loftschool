<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Tools\MyTools;

class Users extends Controller
{

    public function __construct(array $route_params)
    {
        parent::__construct($route_params);
        if (!isset($_SESSION["id_user"])) {
            $this->redirect("/");
        }
    }

    public function profileAction()
    {
        $this->render("Users/profile.html", ["data" => User::find($_SESSION["id_user"]), "dirProfiles" => ltrim(UPLOAD_PROFILES, ".")]);
    }

    public function showAction()
    {
        $sort = isset($_GET["desc"]) ? "asc" : "desc";
        $this->render("Users/show.html", ["users" => User::getUsers($sort), "sort" => $sort]);
    }

    public function updateAction()
    {
        if (empty($_POST)) {
            return $this->render("Users/update.html", ["data" => User::find($_SESSION['id_user'])]);
        }
        $user = User::find($_SESSION["id_user"]);
        if (!empty($_POST["pass"])) {
            $user->password = password_hash($_POST["pass"], PASSWORD_DEFAULT);
        }
        $user->name = htmlentities(strip_tags($_POST["name"]), ENT_QUOTES);
        $user->age = htmlentities(strip_tags($_POST["age"]), ENT_QUOTES);
        $user->comment = htmlentities(strip_tags($_POST["comment"]), ENT_QUOTES);
        if (!$user->save()) {
            return $this->render("Users/update.html", ["data" => User::find($_SESSION['id_user']), "message" => "DB Error"]);
        }
        $file = $_FILES["userfile"];
        if (!empty($file["name"])) {
            $newFileName = MyTools::getNameUploadFile($file["name"]);
            move_uploaded_file($file["tmp_name"], UPLOAD_PROFILES . $newFileName);
            $user->photo = $newFileName;
            $user->save();
        }
        return $this->render("Users/update.html", ["data" => User::find($_SESSION['id_user']), "message" => "User is updated"]);
    }

    public function logout()
    {
        $_SESSION["id_user"] = null;
        $_SESSION["role_user"] = null;
        $this->redirect("/");
    }
}
