<?php
/*
 * v1.0.0
 * Friday Sep-02-2011
 * @component JooComments
 * @copyright Copyright (C) Abhiram Mishra www.bullraider.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
class com_joocommentsInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) 
	{
		// $parent is the class calling this method
		echo '<p>' .'It is installing, make sure you install the plg_joocomments_v1.0.4 and plg_system_joocomments_v1.0.3 version'. '</p>';
	}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
		// $parent is the class calling this method
		echo '<p>' .'It is uninstalling'. '</p>';
	}
 
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{
		// $parent is the class calling this method
		echo '<p><strong>' .'Make sure you update the JooComments content plugin ( v1.0.4) and Install JooComments system plugin( v1.0.3)' . '</strong></p>';
	}
 
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
	
	$jversion = new JVersion();
	if( version_compare( $jversion->getShortVersion(), '1.6', 'lt' ) ) {
		Jerror::raiseWarning(null, 'Cannot install com_joocomment in a Joomla release prior to 1.6');
		return false;
	}
		$tableName='#__joocomments';
		// if update then only do the following check
		if($type=='update'){
			$db = JFactory::getDBO();
			$results=$db->getTableColumns($tableName);
			if(!array_key_exists('publish_date', $results)){
			// worried, cause the installing guy perhaps installed 1.0.0 and then 1.0.1 which is why 
			// published_date is not there. Let create that here.
			$query='ALTER TABLE #__joocomments ADD COLUMN publish_date datetime NOT NULL DEFAULT '.'\'0000-00-00 00:00:00\'';
			$db->setQuery($query);
			$db->query();
			echo 'log: publish_date added.';
			}
			if(array_key_exists('article_title',$results)){
			// so article_title column was installed may be because the installer guy installed 1.0.0 and then 1.0.1
			// whatever lets remove this column , we don't need this column.
			$query='ALTER TABLE #__joocomments DROP COLUMN article_title';
			$db->setQuery($query);
			$db->query();
			echo 'log: article_title, unnecesary column removed.';
			}
		}
		echo '<p>' . JText::_('It is prelight'. $type) . '</p>';
	}
 
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent)
	{
	$configValue103='{"frontend-comment_order":"0","frontend-comment_feature_link":"1","frontend-comment_category_link":"1","frontend-comment_count_in_link":"1","gravatar_enabled":"1","gravatar_icon":"mm","captcha_enabled":"0","captcha_foreground_color":"FF2864","captcha_background_color":"FFFFFF","captcha_noise_type":"0","email_administrator_notification":"0","email_administrator_value":"0","autoapprove_administrator":"1"}';
	$configValue104='{"vote_enabled":"1","wmd_bold_button":"1","wmd_italic_button":"1","wmd_link_button":"1","wmd_quote_button":"1","wmd_code_button":"1","wmd_image_button":"1","wmd_olist_button":"1","wmd_ulist_button":"1","wmd_heading_button":"1","wmd_hr_button":"1","version":"1.0.4"}';

	// $parent is the class calling this method
	// $type is the type of change (install, update or discover_install)
	if ($type == 'install' ) {
		$db = &JFactory::getDBO();
		$query  = $db->getQuery(true);
		$query->update('#__extensions');
		$defaults = '{"frontend-comment_order":"0","frontend-comment_feature_link":"1","frontend-comment_category_link":"1","frontend-comment_count_in_link":"1","gravatar_enabled":"1","gravatar_icon":"mm","captcha_enabled":"0","captcha_foreground_color":"FF2864","captcha_background_color":"FFFFFF","captcha_noise_type":"0","email_administrator_notification":"0","email_administrator_value":"0","autoapprove_administrator":"1"}'; // JSON format for the parameters
		$query->set("params = '" . $defaults . "'");
		$query->where("name = 'joocomments'");
		$db->setQuery($query);
		$db->query();
		echo '<p>' . JText::_('Configuration saved successfully :' . $type ) . '</p>';
	}
	if($type=='update'){
		// assume that all it is an update, so the #__joocomments must be present
		/*
		 * updating ?
		 * from 1.0.3 to 1.0.4 ( need to maintain the old data ) category 1
		 *
		 * from 1.0.4 to 1.0.4 ( no need to anything, since old data remains) category 2
		 *
		 * category 3 as follows
		 * from 1.0.0 to 1.0.4 ( there are no old data, so perhaps need to maintain the 1.0.3 data)
		 * from 1.0.1 to 1.0.4 ( same as above)
		 * from 1.0.2 to 1.0.4 (same as above)
		 *
		 *
		 */
		jimport('joomla.registry.registry');
		$db = &JFactory::getDBO();
		$query  = $db->getQuery(true);
		$query->select('params');
		$query->from('#__extensions');
		$query->where("name = 'joocomments'");
		$db->setQuery($query);
		$data = $db->loadObject();
		$registry = new JRegistry;
		$registry->loadString($data->params);

		// if category 1  i.e check if one of the v103 has a value otherwise category 3
		// if category 3
		// if category 2 i.e check if version information is avilable
			
		//can be category 3 or category 1
		// category 3
		if($registry->get('email_administrator_notification','not')=='not'){
			// may be updraging from 1.0.0,1.0.1,1.0.2 to 1.0.4
			// so need to do it for 1.0.3 and 1.0.4
			// create another regirstry with update details
			$registry1=new JRegistry();
			$registry1->loadString($configValue103);
			$registry2=new JRegistry();
			$registry2->loadString($configValue104);
			$registry->merge($registry1);
			$registry->merge($registry2);
			$db = &JFactory::getDBO();
			$query  = $db->getQuery(true);
			$query->update('#__extensions');

			$query->set("params = '" . $registry->toString('JSON') . "'");

			$query->where("name = 'joocomments'");
			$db->setQuery($query);
			$db->query();
			echo 'upgrading from any of the 1.0.0,1.0.1,1.0.2 to 1.0.4';

		}else{
			// may be upgrading from 1.0.3 or 1.0.4
			if($registry->get('version','not')=='not'){
				// must be upgrading from 1.0.3
				// maintain the old data
				$registry1=new JRegistry();
				$registry1->loadString($configValue104);
				$registry->merge($registry1);

				$db = &JFactory::getDBO();
				$query  = $db->getQuery(true);
				$query->update('#__extensions');

				$query->set("params = '" . $registry->toString('JSON') . "'");

				$query->where("name = 'joocomments'");
				$db->setQuery($query);
				$db->query();
				echo 'Upgrading from 1.0.3 to 1.0.4';
			}else{
				echo 'Looks like it is an correctional upgrade from 1.0.4 to 1.0.4';
			}

		}

	}
	}
}
