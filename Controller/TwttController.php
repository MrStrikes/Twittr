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
}
