<?php

namespace Model;

use Cool\DBManager;

class TwttManager
{
    public function newTwitt($content){
        $type = 'twtt';
        $author_id = intval($_SESSION['id']);
        $creation = date('Y-m-d H:i:s');
        $content = htmlentities($content);
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $result = $pdo->prepare('INSERT INTO `twtts` (`twtt_id`, `type`, `author_id`, `rt/fav_author_id`, `creation`, `content`) VALUES (NULL, :type_twtt, :author_id, :fav_author_id, :creation, :content)');
        $result->bindParam(':type_twtt', $type);
        $result->bindParam(':author_id', $author_id);
        $result->bindParam(':fav_author_id', $author_id);
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
        die(var_dump($result));
        return array_reverse($result);
    }
}