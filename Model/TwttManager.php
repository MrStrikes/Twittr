<?php

namespace Model;

use Cool\DBManager;

class TwttManager
{
    public function newTwtt($content)
    {
        $user = intval($_SESSION['id']);
        $creation = date('Y-m-d H:i:s');
        $content = htmlentities($content);
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $result = $pdo->prepare('INSERT INTO `twtts` (`twtt_id`, `user_id`, `creation`, `content`) VALUES (NULL, :user_id, :creation, :content)');
        $result->bindParam(':user_id', $user);
        $result->bindParam(':creation', $creation);
        $result->bindParam(':content', $content);
        $result->execute();
    }

    public function getTwttForProfile($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare('SELECT * FROM `twtts` WHERE `rt/fav_author_id` = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetchAll();
        return array_reverse($result);
    }

    public function getTwttForHome()
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare('SELECT followed_id FROM `follow` WHERE `follower_id` = ?');
        $stmt->execute([$_SESSION['id']]);
        $follows = $stmt->fetchAll(2);
        foreach ($follows as $i){
               $a[] = $i['followed_id'];
        }
        $follows = join("','",$a);
        $follows = "'" . $follows . "'";

        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare('SELECT * FROM `twtts` WHERE `rt/fav_author_id` IN (' . $follows . ')');
        $stmt->execute();
        $result = $stmt->fetchAll(2);
        return $result;
    }
}