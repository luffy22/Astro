<?php
 /**
 * @package		Twitter Bootstrap Integration
 * @subpackage	com_cbootstrap
 * @copyright	Copyright (C) 2012 Conflate. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.conflate.nl
 */
 
defined('_JEXEC') or die( 'Restricted access' );


class CBootstrap {

	private $_errors;
	private static $_actions;

	public function __construct() {

	}
	
	public static function load(){
		$comParams =& JComponentHelper::getParams('com_cbootstrap');
		
		$db =& JFactory::getDbo();
		$doc =& JFactory::getDocument();
		$uri =& JFactory::getURI();

		$config =& JFactory::getConfig();
		
		$debug = $config->get('debug');
		$loadResponsive = $comParams->get('load_respons');
		$enableJQuery = $comParams->get('en_jquery');
		$disableMootools = $comParams->get('dis_mootools');
		
		if($enableJQuery){
			if(($jq_version = self::getJQueryVersion()) !== false){
				switch($enableJQuery){
					case 1:{
						$doc->addScript($uri->root(true) . '/media/bootstrap/js/jquery-' . $jq_version . (!$debug?'.min':'') . '.js');
					}break;
					case 2:{
						$doc->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js');
					}break;
				}
			}
		}
		
		if($enableJQuery && $disableMootools){
			foreach($doc->_scripts as $src => $data){
				if(stristr($src, 'mootools')){
					unset($doc->_scripts[$src]);
				}
			}
		}elseif($enableJQuery && !$disableMootools){
			$doc->addScriptDeclaration('jQuery.noConflict();');
		}
		
		$doc->addScript($uri->root(true) . '/media/bootstrap/js/bootstrap.' . (!$debug?'min.':'') . 'js');
		$doc->addStyleSheet($uri->root(true) . '/media/bootstrap/css/bootstrap.' . (!$debug?'min.':'') . 'css');
		if($loadResponsive){
			$doc->addStyleSheet($uri->root(true) . '/media/bootstrap/css/bootstrap-responsive.' . (!$debug?'min.':'') . 'css');
		}
		
		$query = "select `title` from `#__cbootstrap_plugins`"
				. " where `state` = 1"
				. "";
		$db->setQuery($query);
		$plugins = $db->loadObjectList();
		if(is_array($plugins) && !empty($plugins)){
			foreach($plugins as $plugin){
				$name = (substr($plugin->title, -1, 1) == 's' ? substr($plugin->title, 0, -1) : $plugin->title);
				$doc->addScript($uri->root(true) . '/media/bootstrap/js/bootstrap-' . $name . '.js');
			}
		}
	}
	
	public static function getJQueryVersion(){
		jimport('joomla.filsystem.file');
		jimport('joomla.filsystem.folder');
		$path = JPATH_ROOT . DS . 'media' . DS . 'bootstrap' . DS . 'js';
		$files = JFolder::files($path);

		foreach($files as $file){
			if(stristr($file, 'jquery') && !stristr($file, 'min')){
				$matches = array();
				$regex = '/jquery\-(.*?)\.js/i';
				preg_match($regex, $file, $matches);
				if(count($matches)){
					return $matches[1];
				}
			}
		}
		return false;
	}
	
	public static function getBootstrapVersion(){
		jimport('joomla.filsystem.file');
		$path = JPATH_ROOT . DS . 'media' . DS . 'bootstrap' . DS . 'js' . DS . 'bootstrap.js';
		$file = JFile::read($path);
		$regex = '/\* bootstrap\-transition\.js v(.*?)\n/i';
		$matches = array();
		preg_match($regex, $file, $matches);
		if(count($matches)){
			return $matches[1];
		}
		return false;
	}
		
}
