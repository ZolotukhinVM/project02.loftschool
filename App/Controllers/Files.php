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
                $newFileName = uniqid() . "-" . time() . "." . $ext;
                move_uploaded_file($file["tmp_name"], './uploads/images/' . $newFileName);
//                File::insertFile($_SESSION["id_user"], $newName);
                $file = new \FileTable();
                $file->id_user = $_SESSION["id_user"];
                $file->file = $newFileName;
                if ($file->save()) {
                    $fileMessage = "File is loaded";
                } else {
                    $fileMessage = "Error: file is not load";
                }
            }
        }
        View::renderTemplate("Files/load.html", ["message" => $fileMessage]);
        $files = \FileTable::where('id_user', $_SESSION['id_user'])->orderBy('id', 'desc');
        $arrFiles = $files->get(['file', 'date']);
        View::renderTemplate("Files/listing.html", ["files" => $arrFiles, "count" => $files->count()]);
    }
}
