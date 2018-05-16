<?php
/**
 * UserManager
 *
 * All logic associated for all user actions
 *
 * PHP Version 7.2
 *
 * @category Recipe
 * @package  Recipe
 * @author   Yanis Bendahmane <twttr@yanisbendahmane.fr>
 * @author   Maxime Maréchal <maxime.marechal@supinternet.fr>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://localhost/
 */

namespace Model;

use Cool\BaseController;
use Cool\DBManager;

/**
 * UserManager Class Doc Comment
 * 
 * @category  Class
 * @package   UserManager
 * @author    Yanis Bendahmane <twttr@yanisbendahmane.fr>
 * @author    Maxime Maréchal <maxime.marechal@supinternet.fr>
 * @copyright 2018 BENDAHMANE & MARÉCHAL. All rights reserved.
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link      https://localhost/
 * 
 * @since 1.0.0
 */
class UserManager
{
    /**
     * Register a user
     *
     * @param mixed $firstname      Firstname entered by the user on the form
     * @param mixed $lastname       Lastname entered by the user on the form
     * @param mixed $username       Username entered by the user on the form
     * @param mixed $password       Password entered by the user on the form
     * @param mixed $repeatPassword Password entered by the user on the form
     *                              check if it's the same as $password
     * @param mixed $email          Email entered by the user on the form
     *
     * @return Array 
     * => $errors | If errors are found, then the process fail and send AJAX datas
     * => true | If no errors found, then the user is registred into the database
     */
    public function registerUser(
        $firstname, $lastname, $username, 
        $password, $repeatPassword, $email
    ) {
        $errors = [];

        $usernameExists = $this->usernameExists($username);
        $emailExists = $this->emailExists($email);
        if (strlen($firstname) < 2) {
            $errors = [
                "status" => "failed",
                "message" => 'Firstname too short'
            ];
        }
        if (strlen($lastname) < 2) {
            $errors = [
                "status" => "failed",
                "message" => "Lastname too short"
            ];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors = [
                "status" => "failed",
                "message" => "Invalid Email"
            ];
        }
        if (strlen($username) < 4) {
            $errors = [
                "status" => "failed",
                "message" => "Username too short"
            ];
        }
        if (strlen($password) < 4) {
            $errors = [
                "status" => "failed",
                "message" => "Password too short"
            ];
        }
        if ($password !== $repeatPassword) {
            $errors = [
                "status" => "failed",
                "message" => "Password must be identicals"
            ];
        }
        if ($usernameExists) {
            $errors = [
                "status" => "failed",
                "message" => "Username already exists"
            ];
        }
        if ($emailExists) {
            $errors = [
                "status" => "failed",
                "message" => "Email already used"
            ];

        }
        if (empty($errors)) {
            $dbm = DBManager::getInstance();
            $pdo = $dbm->getPdo();
            $hashedPwd = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare(
                "INSERT INTO `Users` 
                (`id`, `firstname`, `lastname`, `username`, 
                    `at_username`, `password`, `email`)
                VALUES (NULL, :firstname, :lastname, :username, 
                    :at_username, :password, :email)"
            );
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

    /**
     * Logs out a user
     *
     * @return None
     */
    public function logoutUser()
    {
        session_destroy();
    }

    /**
     * Login a user
     *
     * @param mixed $user     Username entered by the user
     * @param mixed $password Password entered the user
     *
     * @return Array Returns an array of datas
     * => Errors | if datas not found or false
     * => SESSION | if true
     */
    public function loginUser($user, $password)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();

        $stmt = $pdo->prepare(
            "SELECT * 
            FROM Users 
            WHERE username = :username"
        );
        $stmt->bindParam(':username', $user);

        $stmt->execute();
        $result = $stmt->fetch();
        if (!password_verify($password, $result['password'])) {
            $errors = 'Invalid username or password';
            return $errors;
        } else {
            $_SESSION['username'] = $result['username'];
            $_SESSION['at_username'] = $result['at_username'];
            $_SESSION['id'] = $result['id'];
            return true;
        }
    }

    /**
     * Check if this username is already existing
     *
     * @param string $username Username entered on the form
     *
     * @return boolean $data Returns true if a username has been found, false if not
     */
    public function usernameExists(string $username)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            "SELECT * 
            FROM `Users` 
            WHERE username = :username"
        );
        $stmt->bindParam(':username', $username);

        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_BOUND);
        return $data;
    }

    /**
     * Checks if this emails is already existing
     *
     * @param string $email Email entered on the form
     *
     * @return boolean $data Returns an array for AJAX requests
     */
    public function emailExists(string $email)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            "SELECT * 
            FROM `Users` 
            WHERE email = :email"
        );
        $stmt->bindParam(':email', $email);

        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_BOUND);
        return $data;
    }

    /**
     * Gets the user datas
     *
     * @param mixed $id ID of the user
     *
     * @return Array $result Returns an array containing user datas
     */
    public function getUserById($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();

        $stmt = $pdo->prepare(
            "SELECT * 
            FROM Users 
            WHERE id = :user_id"
        );
        $stmt->bindParam(':user_id', $id);

        $stmt->execute();
        $result = $stmt->fetch(2);
        return $result;
    }

    /**
     * Perform the action of following or unfollowing a user
     *
     * @param mixed $follower ID of the user following
     * @param mixed $followed ID of the followed user
     *
     * @return Array $arr Returns an array for AJAX requests
     */
    public function followUser($follower, $followed)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $isFollowing = $this->isFollowing($follower, $followed);
        if ($follower == $followed) {
            $arr = [
                "status" => "Nope",
                "message" => "You can't follow yourself dude"
            ];
            error_log($follower . " try to follow himself\n", 3, "./logs/security.log");
            return $arr;
        } elseif ($_SESSION['id'] != $follower) {
            $arr = [
                "status" => "Nope",
                "message" => "You can't follow as someone else dude"
            ];
            return $arr;
        } elseif ($isFollowing == true) {
            $unfollow = $this->unfollowUser($follower, $followed);
            error_log($follower . " just unfollow " . $follower . "\n", 3, "./logs/access.log");
            return $unfollow;
        } else {
            error_log($follower . " just follow " . $follower . "\n", 3, "./logs/access.log");
            $stmt = $pdo->prepare(
                "INSERT INTO `follow` 
                (`id`, `follower_id`, `followed_id`) 
                VALUES (NULL, :follower, :followed)"
            );
            $stmt->bindParam(':follower', $follower);
            $stmt->bindParam(':followed', $followed);

            $stmt->execute();
            $arr = [
                "status" => "followed",
                "message" => "Follow ok !"
            ];
            return $arr;
        }
    }

    /**
     * Unfollow a user
     *
     * @param mixed $follower ID of the user following
     * @param mixed $followed ID of the followed user
     *
     * @return Array $arr Returns an array for AJAX requests
     */
    public function unfollowUser($follower, $followed)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            "DELETE FROM `follow` 
            WHERE `follower_id` = :follower 
                AND `followed_id` = :followed"
        );
        $stmt->bindParam(':follower', $follower);
        $stmt->bindParam(':followed', $followed);

        $stmt->execute();
        $arr = [
            "status" => "unfollowed",
            "message" => "Unfollow ok !"
        ];
        return $arr;
    }

    /**
     * Check if a user is already following someone
     *
     * @param mixed $follower ID of the user following
     * @param mixed $followed ID of the followed user
     *
     * @return boolean $data Returns true if a row is found, false if not
     */
    public function isFollowing($follower, $followed)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            "SELECT * 
            FROM `follow` 
            WHERE follower_id = :follower 
                AND followed_id = :followed"
        );
        $stmt->bindParam(':follower', $follower);
        $stmt->bindParam(':followed', $followed);

        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_BOUND);
        return $data;
    }

    /**
     * Manage ratings (Likes or Retwtts)
     *
     * @param mixed $twtt_id  ID of the twtt
     * @param mixed $rating   Rating sent by the user
     * @param mixed $user     User associated by this action
     * @param mixed $reTwttId The ID of the retwtt
     *
     * @return Array $arr Returns an array with all datas for AJAX request
     */
    public function manageRatings($twtt_id, $rating, $user, $reTwttId)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $madeAction = $this->hasAlreadyMadeThisAction($twtt_id, $rating, $user);

        if (false == $madeAction) {
            error_log($user . " ". $rating . "the twtt" . $twtt_id . "\n", 3, "./logs/access.log");
            $stmt = $pdo->prepare(
                "INSERT INTO `ratings` 
                (`id`, `twtt_id`, `rating`, `user_id`) 
                VALUES (NULL, :twtt_id, :rating, :user_id)"
            );
            $stmt->bindParam(':twtt_id', $twtt_id);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':user_id', $user);

            $stmt->execute();

            $stmt = $pdo->prepare(
                "SELECT *
                FROM ratings 
                ORDER BY `id` DESC 
                LIMIT 1"
            );
            $stmt->execute();
            $result = $stmt->fetch(2);

            if ("rt" == $rating) {

                $stmt = $pdo->prepare(
                    "SELECT *
                    FROM `ratings`
                    ORDER BY id DESC LIMIT 1"
                );
                $stmt->execute();
                $result = $stmt->fetch(2);

                $twttManager = new TwttManager();
                $twttManager->newReTwtt($twtt_id, $result['id']);
            }
            $arr = [
                "status" => "ok",
                "message" => "User action has sucessfully been recorded"
            ];
            return $arr;
        } else {
            $twttManager = new TwttManager();
            $stmt = $pdo->prepare(
                "SELECT *
                FROM `ratings` 
                WHERE twtt_id = :twtt_id 
                    AND rating = :rating 
                    AND user_id = :user_id"
            );
            $stmt->bindParam(':twtt_id', $twtt_id);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':user_id', $user);
            $stmt->execute();
            $result = $stmt->fetch(2);
            $twttManager->deleteReTwtt($result['id']);

            $removeRating = $this->removeRating($twtt_id, $rating, $user);
            return $removeRating;
        }
    }

    /**
     * Remove the rating made by the user
     *
     * @param mixed $twtt_id ID of the twtt
     * @param mixed $rating  Rating sent by the user
     * @param mixed $user    User associated by this action
     *
     * @return Array $arr Returns an array for AJAX request
     */
    public function removeRating($twtt_id, $rating, $user)
    {
        error_log($user . " un". $rating . "the twtt" . $twtt_id . "\n", 3, "./logs/access.log");
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            "DELETE FROM `ratings` 
            WHERE twtt_id = :twtt_id 
                AND rating = :rating 
                AND user_id = :user_id"
        );
        $stmt->bindParam(':twtt_id', $twtt_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':user_id', $user);

        $stmt->execute();
        $arr = [
            "status" => "ok",
            "message" => "Rating removed !"
        ];
        return $arr;
    }

    /**
     * Check if a user already made an action
     *
     * @param mixed $twtt_id ID of the twtt
     * @param mixed $rating  Rating sent by the user
     * @param mixed $user    User associated by this action
     *
     * @return boolean $data Returns true if a row has been found or false if not
     */
    public function hasAlreadyMadeThisAction($twtt_id, $rating, $user)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            "SELECT * 
            FROM `ratings` 
            WHERE twtt_id = :twtt_id 
                AND rating = :rating 
                AND user_id = :user_id"
        );
        $stmt->bindParam(':twtt_id', $twtt_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':user_id', $user);

        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_BOUND);
        return $data;
    }

    /**
     * Search users
     *
     * @param String $username The username entered by the user
     *
     * @return Array $arr An array containing JSON datas for AJAX request
     */
    public function searchUser(string $username)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pattern = '/^\@/';
        $checking = preg_match($pattern, $username);
        if ($checking) {
            $user = ltrim($username, '@');
            $stmt = $pdo->prepare(
                "SELECT *
                FROM `Users` 
                WHERE at_username = :username"
            );
            $stmt->bindParam(':username', $user);
            $stmt->execute();
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($data == false) {
                $arr = [
                    "status" => "Err",
                    "message" => "Username not found"
                ];
                return $arr;
            } else {
                $arr = [
                    "status" => "Yup",
                    "message" => "Username found",
                    "id" => $data['id']
                ];
                return $arr;                
            }
        } else {
            $stmt = $pdo->prepare(
                "SELECT * 
                FROM `Users` 
                WHERE username = :username"
            );
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($data == false) {
                $arr = [
                    "status" => "Err",
                    "message" => "Username not found"
                ];
                return $arr;
            } else {
                $arr = [
                    "status" => "Yup",
                    "message" => "Username found",
                    "id" => $data['id']
                ];
                return $arr;                
            }
        }
    }

    /**
     * Catch all usernames
     *
     * @return $result The result of all usernames
     */
    public function getAllUsernames()
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            "SELECT username 
            FROM users"
        );
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
}