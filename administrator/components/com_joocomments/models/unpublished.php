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
/**
 * HelloWorldList Model
 */
class JooCommentsModelUnpublished extends JModelList
{
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields
		$query->select('com.id,com.article_id,com.name,com.comment,com.published,con.title,com.email,con.alias,con.catid');
		// From the hello table
		$query->from('#__joocomments as com');
		$query->from('#__content as con');
		$query->where('com.article_id=con.id');
		$query->where('com.published=0');
		$query->order('publish_date desc,id desc');
		return $query;
	}
}
