<?php
/**
 * TwttController
 *
 * All calls for logic associated with the Twtts actions
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
namespace Controller;

use Cool\BaseController;
use Model\UserManager;
use Model\TwttManager;

/**
 * TwttController Class Doc Comment
 * 
 * @category  Class
 * @package   TwttController
 * @author    Yanis Bendahmane <twttr@yanisbendahmane.fr>
 * @author    Maxime Maréchal <maxime.marechal@supinternet.fr>
 * @copyright 2018 BENDAHMANE & MARÉCHAL. All rights reserved.
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link      https://localhost/
 * 
 * @since 1.0.0
 */
class TwttController extends BaseController
{
    /**
     * Call for adding a new twtt
     *
     * @return boolean
     * => $err | Returns an array containing an error
     * => true | Returns true if the tweet has been succesfully added
     */
    public function newTwttAction()
    {
        $content = json_decode($_POST['content']);
        if (0 == strlen($content) || 140 < strlen($content)) {
            error_log($_SESSION['username'] . '(' . $_SESSION['id'] . ') try to create a twtt :' . $_POST['content'] . '\n', 3, "./logs/security.log");
            return json_encode(['err' => 'error']);
        } else {
            $twttManager = new TwttManager();
            $twttManager->newTwtt($content);
            $this->logs(
                "logs/access.log",
                $_SESSION['username']." just sent a twtt\n"
            );
            return true;
        }
    }

    /**
     * Call for getting all twtts for the profile
     *
     * @return JSON $tweet Returns a JSON Object for AJAX calls
     */    
    public function getTlProfileAction()
    {
        $twttManager = new TwttManager();
        $tl = $twttManager->getTwttForProfile($_POST['profile_id']);
        $tl = array_reverse($tl);
        $tweet = json_encode($tl, JSON_PRETTY_PRINT, 9999);
        return $tweet;
    }

    /**
     * Call for getting all twtts for the timeline
     *
     * @return JSON $tweet Returns a JSON Object for AJAX calls
     */   
    public function getMainTlAction()
    {
        $twttManager = new TwttManager();
        $tl = $twttManager->getTwttForHome();
        $tl = array_reverse($tl);
        $tweet = json_encode($tl, JSON_PRETTY_PRINT, 9999);
        return $tweet;
    }
}