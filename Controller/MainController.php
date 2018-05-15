<?php

namespace Controller;

use Cool\BaseController;
use Model\UserManager;

class MainController extends BaseController
{
    public function homeAction()
    {
        $getUsernames = new UserManager();
        if (empty($_SESSION)){
            return $this->redirectToRoute('login');
        }
        $allUsernames = $getUsernames->getAllUsernames();
        $arr = [
            'session' => $_SESSION,
            'allUsers' => $allUsernames
        ];
        return $this->render('home.html.twig', $arr);
    }

}
