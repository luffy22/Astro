<?php
 /**
 * @package		Twitter Bootstrap Integration
 * @subpackage	com_cbootstrap
 * @copyright	Copyright (C) 2012 Conflate. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.conflate.nl
 */
 
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
class plgSystemCBootstrap extends JPlugin{

	private $_comExists;
	private $_enabled;

	public function __construct( &$subject, $params ){
		parent::__construct( $subject, $params );
		
		$app =& JFactory::getApplication();
		
		//add the classes for handling
		$classpath = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cbootstrap'.DS.'classes'.DS.'cbootstrap.php';
		if(file_exists($classpath)){
			$this->_comExists = true;
			JLoader::register('CBootstrap', $classpath);
		}
	}
	
	public function onAfterInitialize(){
		

    }
	
	public function onAfterDispatch(){
		$app =& JFactory::getApplication();
		$comParams =& JComponentHelper::getParams('com_cbootstrap');
		if(($this->_enabled = $comParams->get('en_bootstrap')) > 0 && $this->_comExists){
			switch($this->_enabled){
				case 1: {
					if(!$app->isAdmin()){
						CBootstrap::load();
					}
				}break;
				case 2: {
					if($app->isAdmin()){
						CBootstrap::load();
					}
				}break;
				case 3: {
					CBootstrap::load();
				}break;
			}
		}		
    }
    
	
}
