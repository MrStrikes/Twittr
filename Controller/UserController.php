<?php

namespace Controller;

use Cool\BaseController;
use Model\UserManager;

class UserController extends BaseController
{

    public function logoutAction()
    {
        $userManager = new UserManager();
        $userManager->logoutUser();
        return $this->redirectToRoute('login');
    }

    public function loginAction()
    {
        if (!empty($_SESSION['id'])){
            return $this->redirectToRoute('home');
        }
        //REGISTER
        if (!empty($_POST['firstname']) || !empty($_POST['lastname'])
            || !empty($_POST['username']) || !empty($_POST['at_username'])
            || !empty($_POST['password']) || !empty($_POST['password_repeat'])
            || !empty($_POST['email'])) {

            $UserManager = new UserManager();
            $errors = $UserManager->registerUser(htmlentities($_POST['firstname']), htmlentities($_POST['lastname']), htmlentities($_POST['username']), $_POST['password'], $_POST['password_repeat'], htmlentities($_POST['email']));
            if ($errors === true) {
                return $this->redirectToRoute('home');
            } else {
                $data = ['errors' => $errors];
                return $this->render('login.html.twig', $data);
            }
        }
        //LOGIN
        else if (isset($_POST['pseudo']) && isset($_POST['lg-password']) || $_SERVER['REQUEST_METHOD'] === 'POST'){
            $manager = new UserManager();
            $getUserData = $manager->loginUser(htmlentities($_POST['pseudo']), $_POST['lg-password']);
            if ($getUserData !== true){
                $arr = [
                    'error' => $getUserData
                ];
                return $this->render('login.html.twig', $arr);
            } else {
                return $this->redirectToRoute('home');
            }
        }

        return $this->render('login.html.twig');
    }
}