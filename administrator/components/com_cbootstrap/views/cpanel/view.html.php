<?php
 /**
 * @package		Twitter Bootstrap Integration
 * @subpackage	com_cbootstrap
 * @copyright	Copyright (C) 2012 Conflate. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.conflate.nl
 */

// no direct access
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

jimport( 'joomla.html.pane' );

class CBootstrapViewCPanel extends JView
{
	function display($tpl = null){
		$com_name = 'cbootstrap';
		$option = 'com_'.$com_name;
		$uc_option = strtoupper($option);
		
		JToolBarHelper::title(JText::_($uc_option) . ' - ' . JText::_($uc_option . '_CPANEL'), $com_name.'.png');
		
		JToolBarHelper::publish('cpanel.publish', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('cpanel.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::divider();
		JToolBarHelper::custom('cpanel.installPlugin', 'upload.png', 'upload.png', JText::_('COM_CBOOTSTRAP_PLUGIN_INSTALL'), true, false);
		JToolBarHelper::custom('cpanel.deletePlugin', 'delete.png', 'delete.png', JText::_('COM_CBOOTSTRAP_PLUGIN_DELETE'), true, false);
		JToolBarHelper::divider();
		JToolBarHelper::custom('cpanel.refresh', 'refresh.png', 'refresh.png', JText::_('COM_CBOOTSTRAP_PLUGINS_REFRESH'), false, false);
		
		$user = JFactory::getUser();
		if ($user->authorise('core.admin', $option)) {
		    JToolBarHelper::preferences($option);
		}
		
		$info =& $this->get('Info');
		$plugins =& $this->get('Items');
		$this->state = $this->get('State');
		
		if(empty($plugins)){
			$plugins =& $this->get('BootstrapPlugins');
		}
		
		$this->assignRef('info', $info);
		$this->assignRef('plugins', $plugins);
		
		$this->assign('com_name', $com_name);
		$this->assign('option', $option);
		$this->assign('uc_option', $uc_option);
				
		parent::display($tpl);
	}
}

