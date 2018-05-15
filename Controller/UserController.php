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
        if (!empty($_POST['firstname']) && !empty($_POST['lastname'])
            && !empty($_POST['username']) && !empty($_POST['password']) 
            && !empty($_POST['password_repeat']) && !empty($_POST['email'])) {

                $UserManager = new UserManager();
                $login = $UserManager->registerUser(htmlentities($_POST['firstname']), htmlentities($_POST['lastname']), htmlentities($_POST['username']), $_POST['password'], $_POST['password_repeat'], htmlentities($_POST['email']));
                if ($login === true) {
                    $data = [
                        'status' => 'ok',
                        'message' => 'The user has been registred'
                    ];
                return json_encode($data);
            } else {
                $data = ['errors' => $login];
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
            $userManager = new UserManager();
            $username = htmlentities($_POST['username']);
            $password = $_POST['password'];
            $getUserData = $userManager->loginUser($username, $password);
            if ($getUserData !== true) {
                $arr = [
                    'status' => 'failed',
                    'message' => 'There was a problem loggin in the user'
                ];
                return json_encode($arr);
            } else {
                $arr = [
                    'status' => 'ok',
                    'message' => 'The user has successfully been logged in'
                ];
                return json_encode($arr);
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
        $userInfo = $userManager->getUserById($_GET['profile_id']);
        $isFollowing = $userManager->isFollowing($_SESSION['id'], $_GET['profile_id']);
        $allUsernames = $userManager->getAllUsernames();
        $arr = [
            "isFollowing" => $isFollowing,
            "userInfo"   => $userInfo,
            "session"    => $_SESSION,
            "allUsers" => $allUsernames
        ];
        return $this->render('profile.html.twig', $arr);
    }

    public function followAction()
    {
        $userManager = new UserManager();
        $follow = $userManager->followUser($_POST['follower_id'], $_POST['followed_id']);
        return json_encode($follow);
    }

    public function unfollowAction()
    {
        $userManager = new UserManager();
        $unfollow = $userManager->unfollowUser($_POST['follower_id'], $_POST['followed_id']);
        return json_encode($unfollow);
    }

    public function manageRatingsAction()
    {
        $userManager = new UserManager();
        $manageRating = $userManager->manageRatings($_POST['twtt_id'], $_POST['rating'], $_SESSION['id'], $_POST['re_twtt_id']);
        return json_encode($manageRating);
    }

    public function searchUserAction()
    {
        $userManager = new UserManager();
        $searchUser = $userManager->searchUser($_POST['username']);
        return json_encode($searchUser);
    }
}