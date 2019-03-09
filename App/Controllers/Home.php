<?php

namespace App\Controllers;

use Core\Controller;

class Home extends Controller
{
    public function indexAction()
    {
        $this->render('Home/index.html');
    }
}
