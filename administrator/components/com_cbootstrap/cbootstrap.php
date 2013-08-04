<?php
 /**
 * @package		Twitter Bootstrap Integration
 * @subpackage	com_cbootstrap
 * @copyright	Copyright (C) 2012 Conflate. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.conflate.nl
 */
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_cbootstrap')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

$view = JRequest::getVar('view', 'cpanel');
JRequest::setVar('view', $view);

$doc =& JFactory::getDocument();
$doc->addStyleSheet(JURI::root(true) . '/administrator/components/com_cbootstrap/assets/css/com_cbootstrap.css');

//Add sub menu
JSubMenuHelper::addEntry(JText::_('COM_CBOOTSTRAP_CPANEL'), 'index.php?option=com_cbootstrap', $view == 'cpanel');

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JController::getInstance('CBootstrap');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
