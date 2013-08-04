<?php

defined('_JEXEC') or die();

// Make sure the user is authorized to view this page
/* $user = & JFactory::getUser();
  if (!$user->authorize( 'com_freepaypal', 'manage' )) {
  $mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
  } */

// Set the table directory
//JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
// Require the base controller
require_once (JPATH_COMPONENT . DS . 'controller.php');
$task = JRequest::getCmd('task');
// Require specific controller if requested
JSubMenuHelper::addEntry(JText::_('Configuration'), 'index.php?option=com_freepaypal');
JSubMenuHelper::addEntry(JText::_('Transactions'), 'index.php?option=com_freepaypal&view=transactions', in_array($task, array('users', 'sync', 'syncStart')));
JSubMenuHelper::addEntry(JText::_('Items'), 'index.php?option=com_freepaypal&view=items', $task == 'about');
JSubMenuHelper::addEntry(JText::_('Backup/Restore'), 'index.php?option=com_freepaypal&controller=backup');

// Require the base controller
require_once (JPATH_COMPONENT . DS . 'controller.php');

$controller = JRequest::getWord('controller', 'application');
// Require specific controller if requested
if ($controller) {
    $path = JPATH_COMPONENT . DS . 'controllers' . DS . $controller . '.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}
$view = JRequest::getWord('view', '');
if (empty($controller) && empty($view)) {
    $controller = 'freepaypal';
    $path = JPATH_COMPONENT . DS . 'controllers' . DS . $controller . '.php';
    require_once $path;
}
// Create the controller
$classname = 'FreePayPalController' . ucfirst($controller);
$controller = new $classname( );

JResponse::setHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT', true);
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
?>
