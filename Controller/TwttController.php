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
        var_dump($_POST);
        $tl = $twttManager->getTwttForProfile($_POST['profile_id']);
        for ($i = 0; $i < sizeof($tl); $i++){
            $tl[$i]['author'] = $userManager->getUserById($tl[$i]['author_id']);
        }
        return json_encode($tl);
    }
}
