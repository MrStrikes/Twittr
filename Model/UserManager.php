<?php

namespace Model;

use Cool\BaseController;
use Cool\DBManager;

class UserManager
{
    public function registerUser($firstname, $lastname, $username, $password, $repeatPassword, $email)
    {
        $errors = [];
        if (strlen($firstname) < 4){
            $errors[] = 'Firstname too short';
        }
        if (strlen($lastname) < 4){
            $errors[] = 'Lastname too short';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[] = 'Invalid Email';
        }
        if((strlen($username) < 4) || (strlen($username) > 30)){
            $errors[] = 'Pseudo too short or too long';
        }
        if(strlen($password) < 4 || strlen($password) > 30){
            $errors[] = 'Password too short or too long';
        }
        if($password !== $repeatPassword){
            $errors[] = 'Password must be identical to the verification';
        }
        if(empty($errors)) {
            $dbm = DBManager::getInstance();
            $pdo = $dbm->getPdo();
            $hashedPwd = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("INSERT INTO `Users` (`id`, `firstname`, `lastname`, `username`, `at_username`, `password`, `email`) VALUES (NULL, :firstname, :lastname, :username, :at_username, :password, :email)");
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':at_username', $username);
            $stmt->bindParam(':password', $hashedPwd);
            $stmt->bindParam(':email', $email);
            
            $stmt->execute();
            $errors = true;
        }
        return $errors;
    }

    public function logoutUser()
    {
        session_destroy();
    }

    public function loginUser($user, $password)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();

        $stmt = $pdo->prepare("SELECT * FROM Users 
        WHERE username = :username");
        $stmt->bindParam(':username', $user);

        $stmt->execute();
        $result = $stmt->fetch();
        if(!password_verify($password, $result['password'])){
            $errors = 'Invalid username or password';
            return $errors;
        } else {
            $_SESSION['username'] = $result['username'];
            $_SESSION['at_username'] = $result['at_username'];
            $_SESSION['id'] = $result['id'];
            return true;
        }
    }

    public function getUserById($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();

        $stmt = $pdo->prepare("SElECT * FROM Users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $id);

        $stmt->execute();
        $result = $stmt->fetch(2);
        return $result;
    }

    public function followUser($follower, $followed)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $isFollowing = $this->isFollowing($follower, $followed);
        if($follower == $followed){
            $arr = [
                "status" => "Nope",
                "message" => "You can't follow yourself dude"
            ];
            return $arr;
        } elseif($_SESSION['id'] != $follower) {
            $arr = [
                "status" => "Nope",
                "message" => "You can't follow as someone else dude"
            ];
            return $arr;
        } elseif($isFollowing == true) {
            $unfollow = $this->unfollowUser($follower, $followed);
            return $unfollow;
        } else {
            $stmt = $pdo->prepare("INSERT INTO `follow` (`id`, `follower_id`, `followed_id`) VALUES (NULL, :follower, :followed)");
            $stmt->bindParam(':follower', $follower);
            $stmt->bindParam(':followed', $followed);

            $stmt->execute();
            $arr = [
                "status" => "ok",
                "message" => "Follow ok !"
            ];
            return $arr;
        }
    }

    public function unfollowUser($follower, $followed)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare("DELETE FROM `follow` WHERE `follower_id` = :follower AND `followed_id` = :followed");
        $stmt->bindParam(':follower', $follower);
        $stmt->bindParam(':followed', $followed);

        $stmt->execute();
        $arr = [
            "status" => "ok",
            "message" => "Unfollow ok !"
        ];
        return $arr;
    }

    public function isFollowing($follower, $followed)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM `follow` WHERE follower_id = :follower AND followed_id = :followed");
        $stmt->bindParam(':follower', $follower);
        $stmt->bindParam(':followed', $followed);

        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_BOUND);
        return $data;
    }

    public function manageRatings($twtt_id, $rating, $userPoster, $user)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $madeAction = $this->hasAlreadyMadeThisAction($twtt_id, $rating, $userPoster, $user);

        if(false == $madeAction){
            $stmt = $pdo->prepare("INSERT INTO `ratings` (`id`, `twtt_id`, `rating`, `profile_id`, `user_id`) VALUES (NULL, :twtt_id, :rating, :profile_id, :user_id)");
            $stmt->bindParam(':twtt_id', $twtt_id);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':profile_id', $userPoster);
            $stmt->bindParam(':user_id', $user);

            $result = $stmt->execute();

            if ("rt" == $rating){
                $twttManager = new TwttManager();
                $twttManager->newReTwtt($twtt_id);
            }
            $arr = [
                "status" => "ok",
                "message" => "User action has sucessfully been recorded"
            ];
            return $arr;
        } else {
            $removeRating = $this->removeRating($twtt_id, $rating, $userPoster, $user);
            return $removeRating;
        }
    }

    public function removeRating($twtt_id, $rating, $userPoster, $user)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare("DELETE FROM `ratings` WHERE twtt_id = :twtt_id AND rating = :rating AND profile_id = :profile_id AND user_id = :user_id");
        $stmt->bindParam(':twtt_id', $twtt_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':profile_id', $userPoster);
        $stmt->bindParam(':user_id', $user);

        $stmt->execute();
        $arr = [
            "status" => "ok",
            "message" => "Rating removed !"
        ];
        return $arr;
    }

    public function hasAlreadyMadeThisAction($twtt_id, $rating, $userPoster, $user)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM `ratings` WHERE twtt_id = :twtt_id AND rating = :rating AND profile_id = :profile_id AND user_id = :user_id");
        $stmt->bindParam(':twtt_id', $twtt_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':profile_id', $userPoster);
        $stmt->bindParam(':user_id', $user);
        
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_BOUND);
        return $data;
    }
}