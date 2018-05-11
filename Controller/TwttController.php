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
        if (0 == strlen($content)){
            return json_encode("error");
        } else {
            $twttManager = new TwttManager();
            $twttManager->newTwtt($content);
            return true;
        }
    }

    public function getTlProfileAction()
    {
        $twttManager = new TwttManager();
        $tl = $twttManager->getTwttForProfile($_POST['profile_id']);
        $tl = array_reverse($tl);
        $tweet = json_encode($tl, JSON_PRETTY_PRINT, 9999);
        return $tweet;
    }

    public function getMainTlAction()
    {
        $twttManager = new TwttManager();
        $userManager = new UserManager();

        $tl = $twttManager->getTwttForHome();
        $tl = array_reverse($tl);
        for ($i = 0; $i < sizeof($tl); $i++){
            $tl[$i]['author'] = $userManager->getUserById($tl[$i]['author_id']);
            if ("retwtt" == $tl[$i]['type']){
                $tl[$i]['author_rt'] = $userManager->getUserById($tl[$i]['rt/fav_author_id']);
                unset($tl[$i]['author_rt']['email']);
                unset($tl[$i]['author_rt']['password']);
                unset($tl[$i]['author_rt']['firstname']);
                unset($tl[$i]['author_rt']['lastname']);
            }
            unset($tl[$i]['author']['email']);
            unset($tl[$i]['author']['password']);
            unset($tl[$i]['author']['firstname']);
            unset($tl[$i]['author']['lastname']);
        }
        $tweet = json_encode($tl, JSON_PRETTY_PRINT, 9999);
        return $tweet;
    }
}
