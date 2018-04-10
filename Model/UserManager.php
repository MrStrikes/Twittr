<?php

namespace Model;

use Cool\BaseController;
use Cool\DBManager;

class UserManager
{
    public function registerUser($firstname, $lastname, $username, $at_username, $password, $repeatPassword, $email)
    {
        $regexPassword = "\"^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$\"";
        $regexEmail =  " /^[^\W][a-zA-Z0-9]+(.[a-zA-Z0-9]+)@[a-zA-Z0-9]+(.[a-zA-Z0-9]+)*.[a-zA-Z]{2,4}$/ ";
        $errors = [];

        if (!preg_match($regexEmail,$email)){
            $errors[] = 'Invalid Email';
        }
        if((strlen($username) < 4) || (strlen($username) > 20)){
            $errors[] = 'Pseudo too short or too long';
        }
        if(!preg_match($regexPassword,$password)){
            $errors[] = 'Password must have at least 6 characters with 1 letter uppercase and 1 number';
        }
        if($password !== $repeatPassword){
            $errors[] = 'Password must be identical to the verification';
        }
        if($errors === []) {
            $dbm = DBManager::getInstance();
            $pdo = $dbm->getPdo();
            $hashedPwd = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("INSERT INTO `Users` (`id`, `firstname`, `lastname`, `username`, `at_username`, `password`, `email`) VALUES (NULL, :firstname, :lastname, :username, :at_username, :password, :email)");
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':at_username', $at_username);
            $stmt->bindParam(':password', $hashedPwd);
            $stmt->bindParam(':email', $email);
            
            $stmt->execute();
        }
        return $errors;
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
            $_SESSION['username'] = $user;
            return $_SESSION;
        }
    }
}