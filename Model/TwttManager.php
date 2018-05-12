<?php

namespace Model;

use Cool\DBManager;
use DateTime;

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

    public function newReTwtt($twttId)
    {
        $creation = date('Y-m-d H:i:s');
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $result = $pdo->prepare('INSERT INTO `re_twtts` (`re_twtt_id`, `twtt_id`, `user_id`, `creation`) VALUES (NULL, :twtt_id, :user_id, :creation)');
        $result->bindParam(':twtt_id', $twttId);
        $result->bindParam(':user_id', $_SESSION['id']);
        $result->bindParam(':creation', $creation);
        $result->execute();
    }

    public function deleteReTwtt($reTwttId)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $result = $pdo->prepare('DELETE FROM `re_twtts` WHERE re_twtt_id = :re_twtt_id');
        $result->bindParam(':re_twtt_id', $reTwttId);
        $result->execute();
    }

    public function getTwttForProfile($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare('SELECT * FROM `twtts` WHERE user_id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(2);

        $stmt = $pdo->prepare('SELECT * FROM `re_twtts` WHERE user_id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = array_merge($result, $stmt->fetchAll(2));

        $userManager = new UserManager();

        for ($a = 0; $a < sizeof($result); $a++) {
            $result[$a]['user_id'] = $userManager->getUserById($result[$a]['user_id']);
            unset($result[$a]['user_id']['firstname']);
            unset($result[$a]['user_id']['lastname']);
            unset($result[$a]['user_id']['password']);
            unset($result[$a]['user_id']['email']);
            $result[$a]['type'] = 'twtt';
            if (isset($result[$a]['re_twtt_id'])) {
                $result[$a]['twtt'] = $this->getTwttById($result[$a]['twtt_id']);
                $result[$a]['twtt']['user_id'] = $userManager->getUserById($result[$a]['twtt']['user_id']);
                unset($result[$a]['twtt']['user_id']['firstname']);
                unset($result[$a]['twtt']['user_id']['lastname']);
                unset($result[$a]['twtt']['user_id']['password']);
                unset($result[$a]['twtt']['user_id']['email']);
                $result[$a]['type'] = 're_twtt';
            }
        }

        usort($result, function($a, $b) {
            $ad = new DateTime($a['creation']);
            $bd = new DateTime($b['creation']);

            if ($ad == $bd) {
                return 0;
            }

            return $ad < $bd ? -1 : 1;
        });

        return $result;
    }

    public function getTwttForHome()
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare('SELECT followed_id FROM `follow` WHERE `follower_id` = ?');
        $stmt->execute([$_SESSION['id']]);
        $follows = $stmt->fetchAll(2);
        foreach ($follows as $i) {
            $a[] = $i['followed_id'];
        }
        $follows = join("','", $a);
        $follows = "'" . $follows . "'";

        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare('SELECT * FROM `twtts` WHERE `rt/fav_author_id` IN (' . $follows . ')');
        $stmt->execute();
        $result = $stmt->fetchAll(2);
        return $result;
    }

    public function getTwttById($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare('SELECT * FROM `twtts` WHERE twtt_id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(2);
    }

}