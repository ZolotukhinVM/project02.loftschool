<?php

namespace App\Controllers;

use \App\Models\ImportFaker;

class Import extends \Core\Controller
{
    public function indexAction()
    {
        echo ImportFaker::startImport();
    }
}
