<?php

namespace App\Controllers;

use \App\Models\User;
use \App\Models\UserTable;
use \Core\View;
use GUMP;

// todo: ruden UserTable и FileTable нужно в отдельные файлы и просто Users и Files
// todo: $flight = App\Flight::find(1);
// todo link /users/23

class Users extends \Core\Controller
{
    public function authAction()
    {
        $message = "";
        $tmpl = 'Users/index.html';
        if ($_POST) {
            // todo: fetch one row
            $user = UserTable::where('login', $_POST['login'])->get(['id', 'password']);
            if ($user->count() == 1) {
                $user = $user->first();
                if (password_verify($_POST["pass"], $user->password)) {
                    $_SESSION["id_user"] = $user->id;
                } else {
                    $message = "login or password is not valid";
                }
            }
            if (isset($_SESSION["id_user"])) {
                header("Location: /users/profile");
                exit;
            }
        } else {
            $tmpl = 'Users/index.html';
        }
        // todo: why view->renderTemplate? Why not static method
        View::renderTemplate($tmpl, ["message" => $message]);
    }

    public function showAction()
    {
        $sort = "desc";
        $sortUsers = "ASC";
        if (isset($_GET["desc"])) {
            $sortUsers = "DESC";
            $sort = "asc";
        }
        $users = UserTable::with('files')->orderBy('age', $sort)->get(['id', 'login', 'name', 'age']);
        View::renderTemplate("Users/show.html", ["users" => $users, "sort" => $sort]);
    }

    public function profileAction()
    {
        //todo: how to set field in method find
        $data = UserTable::find($_SESSION["id_user"]);
        View::renderTemplate("Users/profile.html", ["data" => $data]);
    }

    public function regAction()
    {
        $message = "Registration data. Fields can not be empty";
        //todo: ruden использую $_FILES["userfile"]; но проверяю $_POST["reg"]
        if (isset($_POST["reg"])) {
            $result = GUMP::is_valid(array_merge($_POST, $_FILES), [
                "login" => "required|alpha_numeric|min_len, 3",
                "pass" => "required|min_len, 3",
                "age" => "required|numeric|max_len, 120",
                "userfile" => "required_file|extension,png;jpg"
            ]);
            if (!is_array($result)) {
                $arrUser = UserTable::where('login', $_POST['login'])->get(['id']);
                if ($arrUser->count() == 0) {
                    $file = empty($_FILES["userfile"]) ? null : $_FILES["userfile"];
                    $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
                    $newFileName = uniqid() . "-" . time() . "." . $ext;
                    $user = new UserTable();
                    $user->login = trim(strtolower($_POST["login"]));
                    $user->password = password_hash($_POST["pass"], PASSWORD_DEFAULT);
                    $user->name = htmlentities(strip_tags($_POST["name"]), ENT_QUOTES);
                    $user->age = htmlentities(strip_tags($_POST["age"]), ENT_QUOTES);
                    $user->comment = htmlentities(strip_tags($_POST["comment"]), ENT_QUOTES);
                    $user->photo = $newFileName;
                    $user->save();
                    //todo: ruden $user->save() ты сперва сохрани в базу, а потом сохраняй файл
                    move_uploaded_file($file["tmp_name"], './uploads/' . $newFileName);
                    $_SESSION["id_user"] = $user->id;
                    header("Location: /users/profile");
                    exit;
                } else {
                    $message = "Login is exists. Change other login";
                }
            } else {
                $message = implode("<br>", $result);
            }
        }
        View::renderTemplate("Users/reg.html", ["data" => $_POST, "message" => $message]);
    }

    public function updateAction()
    {
        $message = "";
        if (isset($_POST["reg"])) {
            $file = $_FILES["userfile"];
            $user = UserTable::find($_SESSION["id_user"]);
            if (!empty($_POST["pass"])) {
                $user->password = password_hash($_POST["pass"], PASSWORD_DEFAULT);
            }
            if (!empty($file["name"])) {
                $newFileName = uniqid() . "-" . time() . "." . strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
                move_uploaded_file($file["tmp_name"], "./uploads/" . $newFileName);
                $user->photo = $newFileName;
            }
            $user->name = htmlentities(strip_tags($_POST["name"]), ENT_QUOTES);
            $user->age = htmlentities(strip_tags($_POST["age"]), ENT_QUOTES);
            $user->comment = htmlentities(strip_tags($_POST["comment"]), ENT_QUOTES);
            $message = ($user->save()) ? "Update" : "Error";
        }
        View::renderTemplate("Users/update.html", ["data" => UserTable::find($_SESSION['id_user']), "message" => $message]);
    }

    public
    function logout()
    {
        $_SESSION["id_user"] = null;
        $_SESSION["role_user"] = null;
        header("Location: /");
    }
}
