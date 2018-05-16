<?php

namespace Controller;

use Cool\BaseController;
use Model\UserManager;
use Model\TwttManager;

class TwttController extends BaseController
{

    public function newTwttAction()
    {
        $content = json_decode($_POST['content']);
        if (0 == strlen($content) || 140 < strlen($content)){
            error_log($_SESSION['username'] . '(' . $_SESSION['id'] . ') try to create a twtt :' . $_POST['content'] . '\n', 3, "./logs/security.log");
            return json_encode(['err' => 'error']);
        } else {
            $twttManager = new TwttManager();
            $twttManager->newTwtt($content);
            $this->logs("logs/access.log", $_SESSION['username']." just sent a twtt\n");
            return true;
        }
    }

    public function getTlProfileAction()
    {
        $twttManager = new TwttManager();
        $tl = $twttManager->getTwttForProfile($_POST['profile_id']);
        $tweet = array_reverse($tl);
        $tweet = json_encode($tweet, JSON_PRETTY_PRINT, 9999);
        return $tweet;
    }

    public function getMainTlAction()
    {
        $twttManager = new TwttManager();
        $tl = $twttManager->getTwttForHome();
        $tweet = array_reverse($tl);
        $tweet = json_encode($tweet, JSON_PRETTY_PRINT, 9999);
        return $tweet;
    }
}
