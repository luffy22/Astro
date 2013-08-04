<?php

/**
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link       http://docs.joomla.org/Category:Development
 * @license    GNU/GPL
 */
// no direct access

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * FreePayPal front-end Component Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class FreePayPalController extends JController {

    /**
     * Method to display the view
     *
     * @access    public
     */
    public function display($cachable = false, $urlparams = false) {
        //$params = &JComponentHelper::getParams( 'com_freepaypal' )->toArray();
        //print_r($params);
        parent::display();
    }

}
?>

