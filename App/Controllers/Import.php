<?php

namespace App\Controllers;

use App\Models\ImportFaker;
use Core\Controller;

class Import extends Controller
{
    public function indexAction()
    {
        echo ImportFaker::startImport();
    }
}
