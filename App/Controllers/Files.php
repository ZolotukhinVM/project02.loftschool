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
            if (!empty($_FILES['userfile']['tmp_name'])) {
                $fileContent = file_get_contents($_FILES['userfile']['tmp_name']);
                file_put_contents('./uploads/images/' . $_FILES['userfile']['name'], $fileContent);
                File::insertFile($_SESSION["id_user"], $_FILES['userfile']['name']);
                $fileMessage = "File is loaded";
            }
        }
        View::renderTemplate("Files/load.html", ["data" => $fileMessage]);
        View::renderTemplate("Files/listing.html", ["files" => File::getFiles($_SESSION["id_user"])]);
    }
}
