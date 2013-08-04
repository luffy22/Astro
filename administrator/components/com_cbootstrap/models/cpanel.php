<?php
 /**
 * @package		Twitter Bootstrap Integration
 * @subpackage	com_cbootstrap
 * @copyright	Copyright (C) 2012 Conflate. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.conflate.nl
 */

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.modellist');

class CBootstrapModelCPanel extends JModellist {

	public function __construct($config = array()){
		
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'state', 'a.state',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'ordering', 'a.ordering',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down',
			);
		}

		parent::__construct($config);
	}


	protected function populateState($ordering = null, $direction = null){
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$access = $this->getUserStateFromRequest($this->context	.'.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $access);

		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);


		// Load the parameters.
		$params = JComponentHelper::getParams('com_cbootstrap');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.title', 'asc');
	}

	protected function getListQuery(){
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();
		
		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.title, a.installed_version,  a.latest_version,
				a.state, a.publish_up, a.publish_down, a.ordering'
			)
		);
		$query->from('`#__cbootstrap_plugins` AS a');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		
		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = '.(int) $published);
		} elseif ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}
		
		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$query->where('a.access = ' . (int) $access);
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('(a.title LIKE '.$search.')');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');

		$query->order($db->getEscaped($orderCol.' '.$orderDirn));

		return $query;
	}
	
	protected function getStoreId($id = ''){
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}
	
	public function getInfo(){
		$info = array();
		
		$classpath = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_cbootstrap'.DS.'classes'.DS.'cbootstrap.php';
		if(file_exists($classpath)){
			$this->_comExists = true;
			JLoader::register('CBootstrap', $classpath);
		}
		
		//Component version
		$PackageManifest = JFactory::getXML(JPATH_MANIFESTS . DS . 'packages' . DS . 'pkg_cbootstrap.xml');
		$info['package_version'] = $PackageManifest->version;
		$info['author'] = $PackageManifest->author;
		$info['email'] = $PackageManifest->authorEmail;
		$info['website'] = $PackageManifest->authorUrl;
		$info['extension_url'] = $PackageManifest->packagerurl;
		
		//Bootstrap version
		$bsver = CBootstrap::getBootstrapVersion();
		$info['bootstrap_version'] = ($bsver !== false ? $bsver : JText::_('COM_CBOOTSTRAP_UNAVAILABLE'));
		
		//JQuery version
		$jqver = CBootstrap::getJQueryVersion();
		$info['jquery_version'] = ($jqver !== false ? $jqver : JText::_('COM_CBOOTSTRAP_UNAVAILABLE'));
		return $info;
	}
	
	public function getBootstrapPlugins(){
		$plugins = $this->downloadPluginInfo();

		if(is_array($plugins) && !empty($plugins)){
			$table =& JTable::getInstance('BootstrapPlugin', 'CBootstrapTable');
			foreach($plugins as $pluginname){
				$table->id = 0;
				$table->title = $pluginname;
				if(($id = $table->check()) !== false){
					if($id !== true){
						$table->load($id);
					}
					$name = (substr($pluginname, -1, 1) == 's' ? substr($pluginname, 0, -1) : $pluginname);
					$plugin = $this->downloadBootstrapPlugin($name);
					if(isset($plugin['version'])){
						$table->latest_version = $plugin['version'];
					}
					$table->store();
				}
			}
		}else{
			//Did not receive latest plugin info
			$app =& JFactory::getApplication();
			$app->enqueueMessage('Failed to retreive plugin info', 'error');
		}
		
		return $this->getItems();
	}
	
	public function installBootstrapPlugin($id){
		$table =& JTable::getInstance('BootstrapPlugin', 'CBootstrapTable');
		$table->load($id);
		if($table->id){
			//remove the last s for the plugin name
			$name = (substr($table->title, -1, 1) == 's' ? substr($table->title, 0, -1) : $table->title);
			$plugin = $this->downloadBootstrapPlugin($name);
			$pluginName = 'bootstrap-' . $name . '.js';
			
			jimport('joomla.filesystem.file');
			$filename = JPATH_ROOT . DS . 'media' . DS . 'bootstrap' . DS . 'js' . DS . $pluginName;
			if(JFile::write($filename, $plugin['content'])){
				//The file is installed
				//check if a version was found
				if(!$table->installed_version) $table->state = 1;
				if(isset($plugin['version'])){
					$table->installed_version = $plugin['version'];
				}
				$table->store();
				return true;
			}else{
				$this->setError(JText::_('COM_CBOOTSTRAP_INSTALL_FAILED_NO_WRITE'));
			}
		}else{
			$this->setError(JText::_('COM_CBOOTSTRAP_INSTALL_FAILED'));
		}
		return false;
	}
	
	public function deleteBootstrapPlugin($id){
		$table =& JTable::getInstance('BootstrapPlugin', 'CBootstrapTable');
		$table->load($id);
		if($table->id){
			//remove the last s for the plugin name
			$name = (substr($table->title, -1, 1) == 's' ? substr($table->title, 0, -1) : $table->title);
			$pluginName = 'bootstrap-' . $name . '.js';
			jimport('joomla.filesystem.file');
			$filename = JPATH_ROOT . DS . 'media' . DS . 'bootstrap' . DS . 'js' . DS . $pluginName;
			if(JFile::delete($filename)){
				$table->state = 0;
				$table->installed_version = '';
				$table->store();
				return true;
			}
		}
		return false;
	}
	
	public function downloadBootstrapPlugin($name){
		$pluginName = 'bootstrap-' . $name . '.js';
		$location = 'http://twitter.github.com/bootstrap/assets/js/' . $pluginName;
		$results = array();
		if(function_exists('curl_init')){
			$c = curl_init($location);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

			$plugin = curl_exec($c);

			if(curl_error($c)){
				return false;
			}
			// Get the status code
			$status = curl_getinfo($c, CURLINFO_HTTP_CODE);

			curl_close($c);
		}else{
			$plugin = file_get_contents($location);
		}
		
		if($plugin){
			$results['content'] = $plugin;
			//get the version
			$regex = '/\* ' . addcslashes($pluginName, '.-') . ' v(.*?)\n/i';
			$matches = array();
			preg_match_all($regex, $plugin, $matches);
			if(isset($matches[1][0])){
				$version = trim($matches[1][0]);
				if($version != ''){
					$results['version'] = $version;
				}
			}
		}
		
		return $results;
	}
	
	public function downloadPluginInfo(){
		$site = 'http://twitter.github.com/bootstrap/javascript.html';
		if(function_exists('curl_init')){
			$c = curl_init($site);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

			$html = curl_exec($c);
			
			if(curl_error($c)){
				return false;
			}
			// Get the status code
			$status = curl_getinfo($c, CURLINFO_HTTP_CODE);

			curl_close($c);
		}else{
			$html = file_get_contents($site);
		}
		
		$sidenav = array();
		
		$regex = '/\<ul.*?bs-docs-sidenav.*?>(.*?)<\/ul\>/is';
		
		preg_match_all($regex, $html, $sidenav);
			
		if(empty($sidenav)){
			return false;
		}
		$sidenav = $sidenav[1][0];
		
		$regex = '/\#(.*?)\"/i';
		
		$matches = array();
		preg_match_all($regex, $sidenav, $matches);
		
		if(empty($matches)){
			return false;
		}
		
		$plugins = array_slice($matches[1], 1);
		
		return $plugins;
	}
}
