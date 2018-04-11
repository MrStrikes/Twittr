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
}