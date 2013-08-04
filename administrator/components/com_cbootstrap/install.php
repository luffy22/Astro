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

class com_cbootstrapInstallerScript{

	public function install($installer){

		echo '<h3>'. JText::_('COM_CBOOTSTRAP_INSTALLED') .'</h3>';
	}
	
	public function update($installer){
		//remove old JQuery files
		jimport('joomla.filsystem.file');
		jimport('joomla.filsystem.folder');
		$path = JPATH_ROOT . DS . 'media' . DS . 'bootstrap' . DS . 'js';
		$files = JFolder::files($path);
		$versions = array();
		foreach($files as $file){
			if(stristr($file, 'jquery')){
				$matches = array();
				$regex = '/jquery\-(.*?)\.(min\.)?js/i';
				preg_match($regex, $file, $matches);

				if(count($matches)){
					 $versions[$matches[1]][] = $file;
				}
			}
		}
		ksort($versions);
		
		//remove latest version from the list
		array_pop($versions);
		
		//remove all other jquery versions
		if(count($versions)){
			foreach($versions as $version => $files){
				foreach($files as $file){
					JFile::delete($path . DS . $file);
				}
			}
		}
		
		echo '<h3>'. JText::_('COM_CBOOTSTRAP_UPDATED') .'</h3>';
	}
    
    public function uninstall($installer){
		
	
	    echo '<h3>'. JText::_('COM_CBOOTSTRAP_UNINSTALLED') .'</h3>';
	    
		return true;
	}
	
}
?>
