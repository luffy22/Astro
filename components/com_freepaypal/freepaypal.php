<?php

/**
 * @package     Joomla.Tutorials
 * @subpackage  Components
 * components/com_hello/hello.php
 * @link        http://docs.joomla.org/Category:Development
 * @license     GNU/GPL
 */
// no direct access

defined('_JEXEC') or die('Restricted access');

// Require the base controller

require_once( JPATH_COMPONENT . DS . 'controller.php' );

$controller = JRequest::getWord('controller', '');
// Require specific controller if requested
if ($controller) {
    $path = JPATH_COMPONENT . DS . 'controllers' . DS . $controller . '.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}
$action = null;
if (isset($_POST['txn_id']))
    $action = "ipn";
if ($action != null) {
    switch ($action) {
        case "ipn":
            $db = JFactory::getDBO();
            $params1 = JComponentHelper::getParams('com_freepaypal')->toArray();
            require_once("ipn_res.php");
            PayPalIPN_Handler::handle_ipn($db, $_POST, $params1);
            break;
        default:
            echo "Action " . $action . " is not supported.<BR>";
    }
} else {
    $view = JRequest::getVar('view');
    if (empty($view)) {
        echo JText::_('There is no default view for this component.');
        return;
    }
// Create the controller
    $classname = 'FreePayPalController' . ucfirst($controller);
    $controller = new $classname( );

// Perform the Request task
    $controller->execute(JRequest::getVar('task'));

// Redirect if set by the controller
    $controller->redirect();
}
?> 
