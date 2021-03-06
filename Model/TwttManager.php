<?php
/**
 * TwttManager
 *
 * All logic associated with twtts
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

use Cool\DBManager;
use DateTime;

/**
 * TwttManager Class Doc Comment
 * 
 * @category  Class
 * @package   TwttManager
 * @author    Yanis Bendahmane <twttr@yanisbendahmane.fr>
 * @author    Maxime Maréchal <maxime.marechal@supinternet.fr>
 * @copyright 2018 BENDAHMANE & MARÉCHAL. All rights reserved.
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link      https://localhost/
 * 
 * @since 1.0.0
 */
class TwttManager
{
    /**
     * Create a twtt
     *
     * @param mixed $content Content sent by the user
     *
     * @return None
     */
    public function newTwtt($content)
    {
        $user = intval($_SESSION['id']);
        $creation = date('Y-m-d H:i:s');
        $content = htmlentities($content);
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $result = $pdo->prepare(
            'INSERT INTO `twtts` 
            (`twtt_id`, `user_id`, `creation`, `content`) 
            VALUES (NULL, :user_id, :creation, :content)'
        );
        $result->bindParam(':user_id', $user);
        $result->bindParam(':creation', $creation);
        $result->bindParam(':content', $content);
        $result->execute();
    }

    /**
     * Create a reTwtt
     *
     * @param mixed $twttId ID of the Twtt
     * @param mixed $id     ID of the user
     *
     * @return None
     */
    public function newReTwtt($twttId, $id)
    {
        $creation = date('Y-m-d H:i:s');
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $result = $pdo->prepare(
            'INSERT INTO `re_twtts` 
            (`re_twtt_id`, `twtt_id`, `user_id`, `creation`) 
            VALUES (:id, :twtt_id, :user_id, :creation)'
        );
        $result->bindParam(':id', $id);
        $result->bindParam(':twtt_id', $twttId);
        $result->bindParam(':user_id', $_SESSION['id']);
        $result->bindParam(':creation', $creation);
        $result->execute();
    }

    /**
     * Delete a reTwtt
     *
     * @param mixed $reTwttId ID of the RT ID
     *
     * @return None
     */
    public function deleteReTwtt($reTwttId)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $result = $pdo->prepare(
            'DELETE FROM `re_twtts` 
            WHERE re_twtt_id = :re_twtt_id'
        );
        $result->bindParam(':re_twtt_id', $reTwttId);
        $result->execute();
    }

    /**
     * Calculate the number of ratings
     *
     * @param mixed $twtt_id ID of the twtt
     * @param mixed $type    Checks if the type is a fav or a RT
     *
     * @return int $result Total count of the query result
     */
    public function getNumberOfRating($twtt_id, $type)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            'SELECT * FROM `ratings` 
            WHERE twtt_id = :twtt_id 
                AND rating = :ratingType'
        );
        $stmt->bindParam(':twtt_id', $twtt_id);
        $stmt->bindParam(':ratingType', $type);
        $stmt->execute();
        $result = $stmt->fetchAll(2);
        return sizeof($result);
    }

    /**
     * Get timeline from a profile of a user
     *
     * @param mixed $id ID of the user
     *
     * @return Array $result Returns all the timeline of a user into an array
     */
    public function getTwttForProfile($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            'SELECT * 
            FROM `twtts` 
            WHERE user_id = :id'
        );
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(2);

        $stmt = $pdo->prepare('SELECT * FROM `re_twtts` WHERE user_id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = array_merge($result, $stmt->fetchAll(2));

        $userManager = new UserManager();

        for ($a = 0; $a < sizeof($result); $a++) {
            $result[$a]['user_id'] = $userManager->getUserById(
                $result[$a]['user_id']
            );
            $result[$a]['rt'] = $this->getNumberOfRating(
                $result[$a]['twtt_id'],
                'rt'
            );
            $result[$a]['fav'] = $this->getNumberOfRating(
                $result[$a]['twtt_id'],
                'star'
            );
            unset($result[$a]['user_id']['firstname']);
            unset($result[$a]['user_id']['lastname']);
            unset($result[$a]['user_id']['password']);
            unset($result[$a]['user_id']['email']);
            $result[$a]['type'] = 'twtt';
            if (isset($result[$a]['re_twtt_id'])) {
                $result[$a]['twtt'] = $this->getTwttById($result[$a]['twtt_id']);
                $result[$a]['twtt']['user_id'] = $userManager->getUserById(
                    $result[$a]['twtt']['user_id']
                );
                unset($result[$a]['twtt']['user_id']['firstname']);
                unset($result[$a]['twtt']['user_id']['lastname']);
                unset($result[$a]['twtt']['user_id']['password']);
                unset($result[$a]['twtt']['user_id']['email']);
                $result[$a]['type'] = 're_twtt';
            }
            $result[$a]['isRt'] = $b = $userManager->hasAlreadyMadeThisAction(
                $result[$a]['twtt_id'],
                'rt', 
                $_SESSION['id']
            );
            $result[$a]['isFav'] = $b = $userManager->hasAlreadyMadeThisAction(
                $result[$a]['twtt_id'],
                'star',
                $_SESSION['id']
            );
        }
        usort(
            $result, 
            function ($a, $b) {
                $ad = new DateTime($a['creation']);
                $bd = new DateTime($b['creation']);
                if ($ad == $bd) {
                    return 0;
                }
                return $ad < $bd ? -1 : 1;
            }
        );
        return array_reverse($result);
    }

    /**
     * Get timeline from home of a user
     *
     * @return Array $result Returns all the timeline of a user into an array
     */
    public function getTwttForHome()
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();

        $stmt = $pdo->prepare(
            'SELECT followed_id 
            FROM `follow` 
            WHERE `follower_id` = ?'
        );
        $stmt->execute([$_SESSION['id']]);
        $follows = $stmt->fetchAll(2);
        foreach ($follows as $i) {
            $a[] = $i['followed_id'];
        }
        $a[] = $_SESSION['id'];
        $follows = join("','", $a);
        $follows = "'" . $follows . "'";


        $stmt = $pdo->prepare(
            'SELECT * 
            FROM `twtts`
            WHERE user_id
                IN (' . $follows . ')'
        );
        $stmt->execute();
        $result = $stmt->fetchAll(2);

        $stmt = $pdo->prepare(
            'SELECT * 
            FROM `re_twtts` 
            WHERE user_id
                IN (' . $follows . ')'
        );
        $stmt->execute();
        $result = array_merge($result, $stmt->fetchAll(2));

        $userManager = new UserManager();

        for ($a = 0; $a < sizeof($result); $a++) {
            $result[$a]['user_id'] = $userManager->getUserById(
                $result[$a]['user_id']
            );
            $result[$a]['rt'] = $this->getNumberOfRating(
                $result[$a]['twtt_id'],
                'rt'
            );
            $result[$a]['fav'] = $this->getNumberOfRating(
                $result[$a]['twtt_id'],
                'star'
            );
            unset($result[$a]['user_id']['firstname']);
            unset($result[$a]['user_id']['lastname']);
            unset($result[$a]['user_id']['password']);
            unset($result[$a]['user_id']['email']);
            $result[$a]['type'] = 'twtt';
            if (isset($result[$a]['re_twtt_id'])) {
                $result[$a]['twtt'] = $this->getTwttById($result[$a]['twtt_id']);
                $result[$a]['twtt']['user_id'] = $userManager->getUserById(
                    $result[$a]['twtt']['user_id']
                );
                unset($result[$a]['twtt']['user_id']['firstname']);
                unset($result[$a]['twtt']['user_id']['lastname']);
                unset($result[$a]['twtt']['user_id']['password']);
                unset($result[$a]['twtt']['user_id']['email']);
                $result[$a]['type'] = 're_twtt';
            }
            $result[$a]['isRt'] = $b = $userManager->hasAlreadyMadeThisAction(
                $result[$a]['twtt_id'], 
                'rt', 
                $_SESSION['id']
            );
            $result[$a]['isFav'] = $b = $userManager->hasAlreadyMadeThisAction(
                $result[$a]['twtt_id'], 
                'star', 
                $_SESSION['id']
            );
        }

        usort(
            $result, 
            function ($a, $b) {
                $ad = new DateTime($a['creation']);
                $bd = new DateTime($b['creation']);

                if ($ad == $bd) {
                    return 0;
                }
                return $ad < $bd ? -1 : 1;
            }
        );
        return array_reverse($result);
    }

    /**
     * Get twtt by their ID
     *
     * @param mixed $id ID of the twtt
     * 
     * @return SQL Returns SQL result of the twtt id
     */
    public function getTwttById($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            'SELECT * 
            FROM `twtts` 
            WHERE twtt_id = :id'
        );
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(2);
    }

}
