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

    public function registerAction()
    {
        if (!empty($_SESSION['id'])) {
            return $this->redirectToRoute('home');
        }
        if (!empty($_POST['firstname']) || !empty($_POST['lastname'])
            || !empty($_POST['username']) || !empty($_POST['at_username'])
            || !empty($_POST['password']) || !empty($_POST['password_repeat'])
            || !empty($_POST['email'])) {

            $UserManager = new UserManager();
            $errors = $UserManager->registerUser(htmlentities($_POST['firstname']), htmlentities($_POST['lastname']), htmlentities($_POST['username']), $_POST['password'], $_POST['password_repeat'], htmlentities($_POST['email']));
            if ($errors === true) {
                $data = [
                    'status' => 'ok',
                    'message' => 'The user has been registred'
                ];
                return json_encode($data);
            } else {
                $data = ['errors' => $errors];
                return json_encode($data);
            }
        }

        return $this->render('login.html.twig');
    }

    public function loginAction()
    {
        if (!empty($_SESSION['id'])) {
            return $this->redirectToRoute('home');
        }

        if (isset($_POST['username']) && isset($_POST['password']) || $_SERVER['REQUEST_METHOD'] === 'POST') {
            $manager = new UserManager();
            $username = htmlentities($_POST['username']);
            $password = $_POST['password'];
            $getUserData = $manager->loginUser($username, $password);
            if ($getUserData !== true) {
                $arr = [
                    'status' => 'failed',
                    'state' => 'There was a problem loggin in the user'
                ];
                return json_encode($arr);
            } else {
                $arr = [
                    'status' => 'ok',
                    'state' => 'The user has successfully been logged in'
                ];
                return json_encode($arr);
            }
        }
        return $this->render('login.html.twig');
    }
}