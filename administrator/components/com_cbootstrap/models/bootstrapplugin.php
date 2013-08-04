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

jimport('joomla.application.component.modeladmin');


class CBootstrapModelBootstrapPlugin extends JModelAdmin{

	protected $text_prefix = 'COM_BOOTSTRAP';

	public function getTable($type = 'BootstrapPlugin', $prefix = 'CBootstrapTable', $config = array()){
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true){
	
	}
	
}	
