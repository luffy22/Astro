<?php
 /**
 * @package		Twitter Bootstrap Integration
 * @subpackage	com_cbootstrap
 * @copyright	Copyright (C) 2012 Conflate. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.conflate.nl
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.installer.installer' );

class plgSystemCBootstrapInstallerScript{

	public function install($installer){

		//Find plugin and set default to enabled on installation
		$db =& JFactory::getDbo();
		
		$query = 'SELECT `extension_id`' .
			' FROM `#__extensions`' .
			' WHERE folder = '.$db->Quote('system') .
			' AND element = '.$db->Quote('cbootstrap');
		$db->setQuery($query);
		try {
			$db->Query();
		}
		catch(JException $e){  return true; }
		$id = (int)$db->loadResult();
		
		if($id && $id >= 10000){
			$row = JTable::getInstance('extension');
			$row->load($id);
			$row->enabled = 1;
			$row->store();
		}
	}
	
}
?>
