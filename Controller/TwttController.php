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
            $twttManager->newTwitt($content);
            return true;
        }
    }

    public function getTlProfileAction()
    {
        $userManager = new UserManager();
        $twttManager = new TwttManager();
        $tl = $twttManager->getTwttForProfile($_POST['profile_id']);
        for ($i = 0; $i < sizeof($tl); $i++){
            $tl[$i]['author'] = $userManager->getUserById($tl[$i]['author_id']);
        }
       // var_dump("<pre>");
        //var_dump($tl);
        $tl = json_encode($tl, JSON_PRETTY_PRINT, 9999);
        //var_dump("<hr>");
        //var_dump("<hr>");
        //var_dump(json_last_error());
        return $tl;
    }
}
