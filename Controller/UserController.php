<?php

namespace Controller;

use Cool\BaseController;
use Model\TwttManager;
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

        if (isset($_POST['pseudo']) && isset($_POST['lg-password']) || $_SERVER['REQUEST_METHOD'] === 'POST') {
            $manager = new UserManager();
            $getUserData = $manager->loginUser(htmlentities($_POST['pseudo']), $_POST['lg-password']);
            if ($getUserData !== true) {
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

    public function profileAction()
    {
        $userManager = new UserManager();
        if (empty($userManager->getUserById($_GET['profile_id'])) OR empty($_SESSION['id'])){
            return $this->redirectToRoute('home');
        }

        $twttManager = new TwttManager();
        $userInfo = $userManager->getUserById($_GET['profile_id']);
        $tl = $twttManager->getTwttForProfile($_GET['profile_id']);
        for ($i = 0; $i < sizeof($tl); $i++){
            $tl[$i]['author'] = $userManager->getUserById($tl[$i]['author_id']);
        }
        $arr = [
            "userInfo"   => $userInfo,
            "tl"         => $tl,
            "session"    =>$_SESSION
        ];
        return $this->render('profile.html.twig', $arr);
    }
}