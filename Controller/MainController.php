<?php

namespace Controller;

use Cool\BaseController;
use Model\UserManager;

class MainController extends BaseController
{
    public function homeAction()
    {
        if (empty($_SESSION)){
            return $this->redirectToRoute('login');
        }
        $arr = [
            'session' => $_SESSION
        ];
        var_dump($_SESSION);
        return $this->render('home.html.twig', $arr);
    }

}
