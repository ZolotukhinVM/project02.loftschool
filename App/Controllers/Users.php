<?php

namespace App\Controllers;

use \App\Models\User;
use \Core\View;


class Users extends \Core\Controller
{
    public function authAction()
    {
        $tmpl = 'Users/index.html';
        if ($_POST) {
            $user = new User();
            if ($user->getAuth($_POST)) {
                header("Location: /users/profile");
                exit;
            }
        } else {
            $tmpl = 'Users/index.html';
        }
        View::renderTemplate($tmpl);
    }

    public function showAction()
    {
        $sort = "desc";
        $sortUsers = "ASC";
        if (isset($_GET["desc"])) {
            $sortUsers = "DESC";
            $sort = "asc";
        }
        $data = User::getUsers($sortUsers);
        View::renderTemplate("Users/show.html", ["users" => $data, "sort" => $sort]);
    }

    public function profileAction()
    {
        View::renderTemplate("Users/profile.html", ["data" => User::getUserInfo($_SESSION["id_user"])]);
    }

    public function regAction()
    {
        $message = "Registration data. Fields can not be empty";
        if (isset($_POST["reg"])) {
            if (User::checkUserLogin($_POST["login"]) == 0) {
                User::regUser();
                header("Location: /users/profile");
                exit;
            } else {
                $message = "Login is exists. Change other login";
            }
        }
        View::renderTemplate("Users/reg.html", ["data"=>$_POST, "message" => $message]);
    }

    public function updateAction()
    {
        $message = "";
        if (isset($_POST["reg"])) {
            $message = (User::updateUser($_SESSION["id_user"])) ? "Update" : "Error";
        }
        View::renderTemplate("Users/update.html", ["data" => User::getUserInfo($_SESSION["id_user"]), "message" => $message]);
    }

    public function logout()
    {
        $_SESSION["id_user"] = null;
        header("Location: /");
    }
}
