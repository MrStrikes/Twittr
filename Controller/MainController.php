<?php

namespace Controller;

use Cool\BaseController;
use Model\UserManager;

class MainController extends BaseController
{
    public function homeAction()
    {
        $arr = [
            'datas' => $_SESSION
        ];
        return $this->render('home.html.twig', $arr);
    }

    public function registerAction()
    {
        // if (isset($_SESSION)){
        //     $this->redirectToRoute('home');
        // }
        // else 
        // {
            if (!empty($_POST['firstname']) || !empty($_POST['lastname'])
                || !empty($_POST['username']) || !empty($_POST['at_username'])
                || !empty($_POST['password']) || !empty($_POST['password_repeat'])
                || !empty($_POST['email'])) {

                $UserManager = new UserManager();
                $errors = $UserManager->registerUser(htmlentities($_POST['firstname']), htmlentities($_POST['lastname']),
                    htmlentities($_POST['username']), $_POST['password'], $_POST['password_repeat'], htmlentities($_POST['email']));
                if ($errors === true) {
                    return $this->redirectToRoute('home');
                } else {
                    $data = ['errors' => $errors];
                    return $this->render('register.html.twig', $data);
                }
            }
        //}
        return $this->render('register.html.twig');
    }

    public function loginAction()
    {
        // if(isset($_SESSION['username'])){
        //     return $this->redirectToRoute('home');
        // }
        if(isset($_POST['pseudo']) && isset($_POST['password'])
        && $_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $username = htmlentities($_POST['pseudo']);
            $password = $_POST['password'];
            $manager = new UserManager();
            $getUserData = $manager->loginUser($username, $password);
            if ($getUserData === "Invalid username or password"){
                $arr = [
                    'errors' => $getUserData
                ];
                return $this->render('login.html.twig', $arr);
            } else {
                $arr = [
                    'user' => $_SESSION
                ];
                $this->redirectToRoute('home');
                return $this->render('login.html.twig', $arr);
            }
        }
        return $this->render('login.html.twig');
    }
}
