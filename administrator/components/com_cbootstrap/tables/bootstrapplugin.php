<?php
 /**
 * @package		Twitter Bootstrap Integration
 * @subpackage	com_cbootstrap
 * @copyright	Copyright (C) 2012 Conflate. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.conflate.nl
 */
 
// No direct access
defined('_JEXEC') or die;

class CBootstrapTableBootstrapPlugin extends JTable{


	public function __construct(&$db){
		parent::__construct('#__cbootstrap_plugins', 'id', $db);
	}

	public function bind($array, $ignore = ''){
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			//remove duplicate aliasses
			$aliasses = explode("\r\n", trim($registry->get('aliasses', '')));
			$unique = array_unique($aliasses);
			$registry->set('aliasses', implode("\r\n", $unique));
			$array['params'] = (string)$registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string)$registry;
		}
		return parent::bind($array, $ignore);
	}

	public function store($updateNulls = false){
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();
		if ($this->id) {
			// Existing item
			$this->modified		= $date->toMySQL();
			$this->modified_by	= $user->get('id');
		} else {
			if (!intval($this->created)) {
				$this->created = $date->toMySQL();
			}
			if (empty($this->created_by)) {
				$this->created_by = $user->get('id');
			}
		}
		if(($store = parent::store($updateNulls)) !== false){
			return true;
		}else{
			return false;
		}
	}
	
	public function check(){

		// check for existing name
		$query = 'SELECT id FROM #__cbootstrap_plugins WHERE `title` = '.$this->_db->Quote($this->title);
		$this->_db->setQuery($query);

		$xid = intval($this->_db->loadResult());
		if($xid && intval($this->id) === 0){
			return $xid;
		}
		if ($xid && $xid != intval($this->id)) {
			return false;
		}

		return true;
	}
	
	public function publish($pks = null, $state = 1, $userId = 0){
		// Initialise variables.
		$k = $this->_tbl_key;

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k.'='.implode(' OR '.$k.'=', $pks);

		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
			$checkin = '';
		}
		else {
			$checkin = ' AND (checked_out = 0 OR checked_out = '.(int) $userId.')';
		}

		// Update the publishing state for rows with the given primary keys.
		$this->_db->setQuery(
			'UPDATE `'.$this->_tbl.'`' .
			' SET `state` = '.(int) $state .
			' WHERE ('.$where.')' .
			$checkin
		);
		$this->_db->query();

		// Check for a database error.
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
		{
			// Checkin the rows.
			foreach($pks as $pk){
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks)) {
			$this->state = $state;
		}

		$this->setError('');
		return true;
	}

}
