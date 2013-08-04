<?php
/*
 * v1.0.0
 * Friday Sep-02-2011
 * @component JooComments
 * @copyright Copyright (C) Abhiram Mishra www.bullraider.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport ( 'joomla.application.component.model' );

class JooCommentsModelComments extends JModel {

	var $_table=null;

	function __construct() {
		parent::__construct ();
	
	}
public function getTable($type = 'Comments', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	function store($post=0, $insert=0) {
		JModel::addTablePath(JPATH_SITE.DS.'components'.DS.'com_joocomments' . DS . 'tables');
		$row = $this->getTable( 'Comments', 'Table' );
		if(!$post){
			$post = $this->getState ( 'request' );
		}
		if (! $row->bind ( $post )) {
			JError::raiseWarning ( 1, $row->getError ( true ) );
			return false;
		}
		$row->save($post);
	}
	function retriveComments($article_id){
		// I should use query construction object instead of the way below
		$query='SELECT  c.name , c.comment FROM #__joocomments as c where published="1" and '.'article_id='.(int) $article_id;
		$db =& JFactory::getDBO();
		$db->setQuery($query);

		return $db->loadAssocList();
	}
	
	function retriveArticleDetails($article_id){
		$query='SELECT  c.title  FROM #__content as c where id='.(int) $article_id;
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadAssocList();
	}
	function vote($voteDetails){
		$db =& JFactory::getDBO();
		// Is the vote posted on valid comment?
		$query = $db->getQuery(true)
		->select('id')
		->from('#__joocomments')
		->where('id = '. (int) $voteDetails['comment_id'])
		->where('published = 1');
		$db->setQuery($query);
		$article_id = $db->loadResult();

		if (!$article_id) {
			$this->setError(JText::_('COM_JOOCOMMENTS_ERROR_COMMENTS_DONT_EXISTS').','.JText::_('COM_JOOCOMMENTS_ERROR').',3');
			return false;
		}

		$component = JComponentHelper::getComponent( 'com_joocomments' );
		$isVoteEnabled=$component->params->get( 'vote_enabled', '1' );
		if($isVoteEnabled=='0'){
			$this->setError(JText::_('COM_JOOCOMMENTS_ERROR_VOTING_DISABLED').','.JText::_('COM_JOOCOMMENTS_ERROR').',3');
			return false;
		}
		// vote value con't be anything otherthan -1 or 1
		if ($voteDetails['voteType'] !== '-1' && $voteDetails['voteType'] !== '1') {
			$this->setError(JText::_('COM_JOOCOMMENTS_ERROR_INVALID_VOTE').','.JText::_('COM_JOOCOMMENTS_ERROR').',3');
			return false;
		}
		$user = JFactory::getUser();
		if (!$user->guest){
			// check if registered user already voted?
			$user_id = $user->get('id');
			$query = $db->getQuery(true)
			->select('vote')
			->from('#__joocomments_ratings')
			->where('user_id = '. (int) $user_id)
			->where('comment_id = '. (int) $voteDetails['comment_id'])
			->where ('vote = '.(int)$voteDetails['voteType']);
			$db->setQuery($query);
			$voted = $db->loadResult();
			// already voted , intimate the same
			if ($voted ){
				$this->setError(JText::_('COM_JOOCOMMENTS_ERROR_ALREADY_VOTED').','.JText::_('COM_JOOCOMMENTS_WARNING').',2');
				return false;
			}
			else {
				$data = (object) array(
					'user_id'	=> (int) $user_id,
					'vote'		=> (int) $voteDetails['voteType'],
					'comment_id'=> (int) $voteDetails['comment_id']
				);
				$stored = $db->insertObject('#__joocomments_ratings', $data);
			}
		}else{

			$yesterday = $db->quote(JFactory::getDate('yesterday')->toMysql());
			// Guest already voted?
			if (time() % 2) {
				$query = $db->getQuery(true)
				->delete('#__joocomments_ratings')
				->where('user_id = 0')
				->where('created < '. $yesterday);
				$db->setQuery($query)->query();
			}
			$query = $db->getQuery(true)
			->select('count(*)')
			->from('#__joocomments_ratings')
			->where('user_id = 0')
			->where('ip = '. $db->quote($_SERVER['REMOTE_ADDR']))
			->where('created > '. $yesterday)
			->where('comment_id = '.(int) $voteDetails['comment_id'])
			->where ('vote = '.(int)$voteDetails['voteType']);
			$db->setQuery($query);
			$voted = $db->loadResult();

			if ($voted){
			$this->setError(JText::_('COM_JOOCOMMENTS_ERROR_ALREADY_VOTED').','.JText::_('COM_JOOCOMMENTS_WARNING').',2');
				return false;
			}

			$data = (object) array(
				'user_id'	=> 0,
				'vote'		=> (int) $voteDetails['voteType'],
				'comment_id'=> (int) $voteDetails['comment_id'],
				'ip'		=> $_SERVER['REMOTE_ADDR'],
				'created'		=> JFactory::getDate()->toMysql()
			);
			$stored = $db->insertObject('#__joocomments_ratings', $data);
		}

		if (!$stored){
			if (JDEBUG) {
				$this->setError(JText::sprintf('COM_COMMENTS_ERROR_COULD_NOT_STORE_VOTE_DEBUG', $db->getErrorMsg()));
			} else {
				$this->setError(JText::_('COM_JOOCOMMENTS_ERROR_COULD_NOT_STORE_VOTE').','.JText::_('COM_JOOCOMMENTS_ERROR').',3');
			}
		}

		// Update the cache
		$query = $db->getQuery(true)
		->update('#__joocomments')
		->set('voting = voting + '.(int)$voteDetails['voteType'])
		->where('id = '.(int) $voteDetails['comment_id']);
		$updated = $db->setQuery($query)->query();

		if (!$updated){
			if (JDEBUG) {
				$this->setError(JText::sprintf('COM_JOOCOMMENTS_ERROR_COULD_NOT_STORE_VOTE_CACHE', $db->getErrorMsg()));
			} else {
				$this->setError(JText::_('COM_JOOCOMMENTS_ERROR_COULD_NOT_STORE_VOTE').','.JText::_('COM_JOOCOMMENTS_ERROR').',3');
			}
			return false;
		}else{
			$query = $db->getQuery(true)
			->select('voting')
			->from('#__joocomments')
			->where('id = '.(int) $voteDetails['comment_id']);
			$db->setQuery($query);
			$voted = $db->loadResult();
			$this->set('cuVoteValue',$voted);
			return true;
		}

		return true;
	}
}