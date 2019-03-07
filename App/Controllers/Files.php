<?php

namespace App\Controllers;

//use \App\Models\File;
//use \App\Models\User;
use \App\Models\FileTable;
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
                //File::insertFile($_SESSION["id_user"], $newName);
                $file = new FileTable();
                //todo: ruden Это вообще шедевр
                $file->id_user = $_SESSION["id_user"];
                $file->file = $newFileName;
                if ($file->save()) {
                    $fileMessage = "File is loaded";
                } else {
                    $fileMessage = "Error: file is not load";
                }
            }
        }
        $arrFiles = FileTable::where('id_user', $_SESSION['id_user'])->orderBy('id', 'desc')->get(['file', 'date']);
        View::renderTemplate("Files/load.html", ["files" => $arrFiles, "message" => $fileMessage]);
    }
}
