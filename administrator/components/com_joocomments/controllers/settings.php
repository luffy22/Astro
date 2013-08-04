<?php
/**
 * @version		$Id: //depot/dev/Joomla/Joo_Comments/ver_1_0_0/com_joocomments/admin/controllers/settings.php#7 $
 * @package		Joomla.Administrator
 * @subpackage	com_config
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
/**
 * Note: this view is intended only to be opened in a popup
 * @package		Joomla.Administrator
 * @subpackage	com_config
 */
class JooCommentsControllerSettings extends JController
{
	/**
	 * Class Constructor
	 *
	 * @param	array	$config		An optional associative array of configuration settings.
	 * @return	void
	 * @since	1.5
	 */
	function __construct($config = array())
	{
		parent::__construct($config);

		// Map the apply task to the save method.
		$this->registerTask('apply', 'save');
	}
	function updateBulk(){
		$noOfItems=JRequest::getInt('boxchecked');
		$status=JRequest::getInt('status');
		$cid = JRequest::getVar('joocendisa', array(), '', 'array');
		for($i=0;$i<count($cid);$i++){
			$this->update('true',$cid[$i],$status);
		}
		$this->fetch();
	}

	function update($bulkCall='false',$aid='',$st=''){
		$model	= $this->getModel('ArticleSettings');
		$messages='';
		$messageType='';
		if($bulkCall=='false'){
			$articleId=JRequest::getInt('articleId');
			$status=JRequest::getInt('status');
		}else{
			$articleId=$aid;
			$status=$st;
		}
		// First get the current attributes
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields
		$query->select('con.attribs,con.checked_out,con.title');
		// From the hello table
		$query->from('#__content as con');
		$query->where('id='.$articleId);
		$db->setQuery($query);
		$data = $db->loadObject();
		jimport('joomla.registry.registry');
		// Just ensure that the item is not checked out.
		if($data->checked_out){
			$messageType='error';
			$messages='The article: '.$data->title .' is checked out.Please check in, to perform this operation';
		}else{

			// register with registry
			$registry = new JRegistry;
			$registry->loadString($data->attribs);
			// create another regirstry with update details
			$registry1=new JRegistry();
			$registry1->loadString('{"comments_enabled":"'.$status.'"}');
			//print_r($registry1->toString());
			//merge the registry
			$registry->merge($registry1);
			//print_r($registry->toString('JSON'));


			$db = &JFactory::getDBO();
			$query  = $db->getQuery(true);
			$query->update('#__content');
			 
			$query->set("attribs  = '" . $registry->toString('JSON') . "'");
			$query->where('id = '.$articleId);
			$db->setQuery($query);
			$db->query();
			$messages='Successfully modified the comment state of article <b>'.$data->title.'</b>';
			$messageType='';
			//die();
		}
		
		$message ='<dl id="system-message"><dd class="'.$messageType. ' message"><ul><li>'. $messages.'</ul></li></dd></dl>';
		echo $message;
		if($bulkCall=='false'){
			$this->fetch();
		}
	}
	
	function fetch(){
	parent::display();
	 die();
	}

	/**
	 * Save the configuration
	 */
	function save()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set FTP credentials, if given.
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		// Initialise variables.
		$app	= JFactory::getApplication();
		$model	= $this->getModel('Settings');
		$form	= $model->getForm();
		$data	= JRequest::getVar('jform', array(), 'post', 'array');
		$id		= JRequest::getInt('id');
		$option	= JRequest::getCmd('component');

		// Validate the posted data.
		$return = $model->validate($form, $data);

		// Check for validation errors.
		if ($return === false) {
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_config.config.global.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option=com_config&view=component&component='.$option.'&tmpl=component', false));
			return false;
		}

		// Attempt to save the configuration.
		$data	= array(
					'params'	=> $return,
					'id'		=> $id,
					'option'	=> $option
		);

		$isCacheSensitive=$this->isCacheSensitive($data);
		$return = $model->save($data);


		// Check the return value.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_config.config.global.data', $data);

			// Save failed, go back to the screen and display a notice.
			$message = JText::sprintf('COM_JOOCOMMENTS_ERROR_SAVE_FAILED', $model->getError());
			$this->setRedirect('index.php?option=com_joocomments&view=settings&refresh=1', $message, 'error');
			return false;
		}

		// Set the redirect based on the task.
		switch ($this->getTask())
		{
			case 'apply':
				$message = JText::_('COM_JOOCOMMENTS_CONFIG_SAVE_SUCCESS');
				if($isCacheSensitive)
					$message.='<script>alert("'.JText::_("COM_JOOCOMMENTS_ALERT_CACHE_CLEAR").'");</script>';
				$this->setRedirect('index.php?option=com_joocomments&view=settings&refresh=1', $message);
				break;

			case 'save':
			default:
				$message = JText::_('COM_JOOCOMMENTS_CONFIG_SAVE_SUCCESS');
				if($isCacheSensitive)
					$message.='<script>alert("'.JText::_("COM_JOOCOMMENTS_ALERT_CACHE_CLEAR").'");</script>';
				$this->setRedirect('index.php?option=com_joocomments', $message);
				break;
		}

		return true;
	}
	function isCacheSensitive($currentData){
		// following array contains the list of cache sensitive configuration. which 
		//needs to be intimated to the administrator.
		$optionList= array('wmd_bold_button','wmd_italic_button','wmd_link_button',
							'wmd_quote_button','wmd_code_button','wmd_image_button',
							'wmd_olist_button','wmd_ulist_button','wmd_heading_button',
							'wmd_hr_button','frontend-comment_feature_link',
							'frontend-comment_category_link','frontend-comment_count_in_link','captcha_enabled');
		
		$db = &JFactory::getDBO();
			$query  = $db->getQuery(true);
			$query->select('params');
			$query->from('#__extensions');
			$query->where("name = 'joocomments'");
			$db->setQuery($query);
			$storedData= $db->loadObject();
			print_r($storedData);
			$registry = new JRegistry;
			$registry->loadString($storedData->params);
			
		for($i=0;$i<count($optionList);$i++){
			if($currentData['params'][$optionList[$i]]!==$registry->get($optionList[$i])){
				return true;
			}
		}
		return false;
	}
}
