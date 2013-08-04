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
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

class JooCommentsModelReply extends JModelList
{
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		$id=JRequest::getInt('id');
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields
		$query->select('com.comment');
		// From the hello table
		$query->from('#__joocomments as com');
		$query->where('com.id='.$id);
		$query->where('(com.published=0 or com.published=1)');

		return $query;
	}
}