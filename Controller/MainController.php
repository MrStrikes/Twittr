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
            if (!empty($_POST['firstname']) && !empty($_POST['lastname'])
                && !empty($_POST['username']) && !empty($_POST['at_username'])
                && !empty($_POST['password']) && !empty($_POST['password_repeat'])
                && !empty($_POST['email'])) {
                $firstname = htmlentities($_POST['firstname']);
                $lastname = htmlentities($_POST['lastname']);
                $username = htmlentities($_POST['username']);
                $at_username = htmlentities($_POST['at_username']);
                $email = htmlentities($_POST['email']);
                $password = $_POST['password'];
                $passwordRepeat = $_POST['password_repeat'];

                $UserManager = new UserManager();
                $errors = $UserManager->registerUser($firstname, $lastname, $username, $at_username, $password, $passwordRepeat, $email);
                if ($errors === []) {
                    $data = ['user' => $_SESSION];
                    $this->redirectToRoute('home');
                    return $this->render('register.html.twig', $data);
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
