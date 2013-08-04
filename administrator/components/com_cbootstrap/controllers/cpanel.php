<?php
 /**
 * @package		Twitter Bootstrap Integration
 * @subpackage	com_cbootstrap
 * @copyright	Copyright (C) 2012 Conflate. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.conflate.nl
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class CBootstrapControllerCPanel extends JControllerAdmin{	

	public function getModel($name = 'BootstrapPlugin', $prefix = 'CBootstrapModel', $config = array('ignore_request' => true)){
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	public function refresh(){
		$app =& JFactory::getApplication();
		$model =& $this->getModel('CPanel');
		$model->getBootstrapPlugins();
		$app->redirect('index.php?option=com_cbootstrap&view=cpanel', JText::_('COM_CBOOTSTRAP_PLUGINS_REFRESHED'));
	}
	
	public function installPlugin(){
		$cid = JRequest::getVar('cid');
		
		$model =& $this->getModel('CPanel');
		$app =& JFactory::getApplication();
		$err = false;
		foreach($cid as $id){
			if(!$model->installBootstrapPlugin($id)){
				$errors = $model->getErrors();
				foreach($errors as $error){
					$app->enqueueMessage($error, 'error');
				}
				$err = true;
			}
		}
		if(!$err){
			$app->enqueueMessage(JText::_('COM_CBOOTSTRAP_PLUGIN_INSTALLED'));
		}
		$app->redirect('index.php?option=com_cbootstrap&view=cpanel');
	}
	
	public function deletePlugin(){
		$cid = JRequest::getVar('cid');
		$model =& $this->getModel('CPanel');
		$app =& JFactory::getApplication();
		$err = false;
		foreach($cid as $id){
			if(!$model->deleteBootstrapPlugin($id)){
				$errors = $model->getErrors();
				foreach($errors as $error){
					$app->enqueueMessage($error, 'error');
				}
				$err = true;
			}
		}
		if(!$err){
			$app->enqueueMessage(JText::_('COM_CBOOTSTRAP_PLUGIN_DELETED'));
		}
		$app->redirect('index.php?option=com_cbootstrap&view=cpanel');
	}
}
