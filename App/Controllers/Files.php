<?php

namespace App\Controllers;

use \App\Models\File;
use \Core\View;

class Files extends \Core\Controller
{
    public function loadAction()
    {
        $fileMessage = "";
        if (isset($_POST)) {
            $file = empty($_FILES["userfile"]) ? null : $_FILES["userfile"];
            if ($file) {
                $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
                $newName = uniqid() . "-" . time() . "." . $ext;
                move_uploaded_file($file["tmp_name"], './uploads/images/' . $newName);
                File::insertFile($_SESSION["id_user"], $newName);
                $fileMessage = "File is loaded";
            }
        }
        View::renderTemplate("Files/load.html", ["message" => $fileMessage]);
        View::renderTemplate("Files/listing.html", ["files" => File::getFiles($_SESSION["id_user"])]);
    }
}
