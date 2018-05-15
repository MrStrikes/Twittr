<?php
/**
 * MainController
 *
 * All calls for logic associated with the main actions
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

/**
 * MainController Class Doc Comment
 * 
 * @category  Class
 * @package   MainController
 * @author    Yanis Bendahmane <twttr@yanisbendahmane.fr>
 * @author    Maxime Maréchal <maxime.marechal@supinternet.fr>
 * @copyright 2018 BENDAHMANE & MARÉCHAL. All rights reserved.
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link      https://localhost/
 * 
 * @since 1.0.0
 */
class MainController extends BaseController
{
    /**
     * Call for home rendering
     *
     * @return render
     * => Returns the view rendered by the function
     * => Returns an array containing datas
     */
    public function homeAction()
    {
        $getUsernames = new UserManager();
        if (empty($_SESSION)) {
            return $this->redirectToRoute('login');
        }
        $allUsernames = $getUsernames->getAllUsernames();
        $arr = [
            'session' => $_SESSION,
            'allUsers' => $allUsernames
        ];
        return $this->render('home.html.twig', $arr);
    }

}
