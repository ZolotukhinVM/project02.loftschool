<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\FileTable;
use App\Models\File;
use App\Tools\MyTools;

class Files extends Controller
{

    public function loadAction()
    {
        if (isset($_FILES["userfile"])) {
            $file = new FileTable();
            // todo: шедевр пока остался
            $file->id_user = $_SESSION["id_user"];
            if (!$file->save()) {
                $this->render("Files/load.html", ["message" => "DB Error"]);
            }
            $newFileName = MyTools::getNameUploadFile($_FILES["userfile"]["name"]);
            move_uploaded_file($_FILES["userfile"]["tmp_name"], './uploads/images/' . $newFileName);
            $file->file = $newFileName;
            $file->save();
        }
        $arrFiles = File::getUserFiles($_SESSION["id_user"]);
        $this->render("Files/load.html", ["files" => $arrFiles]);
    }
}
