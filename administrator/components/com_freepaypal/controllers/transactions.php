<?php

defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

class FreePayPalControllerTransactions extends JController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
        $this->registerTask('unpublish', 'publish');
    }

    function save() {
        $option = JRequest::getCmd('option');
        $this->setRedirect('index.php?option=' . $option . '&view=transactions');

        $post = JRequest::get('post');
        JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_freepaypal' . DS . 'tables');
        $row = & JTable::getInstance('FreePayPal_Transactions', 'Table');

        if (!$row->bind($post)) {
            return JError::raiseWarning(500, $row->getError());
        }

        if (!$row->store()) {
            return JError::raiseWarning(500, $row->getError());
        }

        $this->setMessage('PayPal Transaction Saved');
    }

    function edit() {
        $cid = JRequest::getVar('cid', array(), 'request', 'array');
        JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_freepaypal' . DS . 'tables');
        $row = JTable::getInstance('freepaypal_transactions', 'Table');
        //print_r($row);			    
        if (isset($cid[0])) {
            $row->load($cid[0]);
        }

        require_once(JPATH_COMPONENT . DS . 'admin.freepaypal.html.php');
        $option = JRequest::getCmd('option');
        $controller = JRequest::getCmd('controller');
        HTML_FreePayPal::editTransaction($option, $row);
    }

    function remove() {
        $option = JRequest::getCmd('option');
        $this->setRedirect('index.php?option=' . $option . '&view=transactions');
        $db = & JFactory::getDBO();
        $cid = JRequest::getVar('cid', array(), 'request', 'array');
        $count = count($cid);
        if ($count) {
            $db->setQuery('DELETE FROM #__freepaypal_transactions WHERE id IN (' . implode(',', $cid) . ')');
            if (!$db->query()) {
                JError::raiseWarning(500, $db->getError());
            }

            if ($count > 1) {
                $s = 's';
            } else {
                $s = '';
            }

            $this->setMessage('Transaction' . $s . ' removed');
        }
    }

    function publish() {
        $option = JRequest::getCmd('option');
        $this->setRedirect('index.php?option=' . $option . '&view=transactions');

        $cid = JRequest::getVar('cid', array(), 'request', 'array');

        if ($this->getTask() == 'publish') {
            $publish = 1;
        } else {
            $publish = 0;
        }

        JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_freepaypal' . DS . 'tables');
        $table = & JTable::getInstance('FreePayPal_Transactions', 'Table');

        $table->publish($cid, $publish);

        if (count($cid) > 1) {
            $s = 's';
        } else {
            $s = '';
        }

        $action = ucfirst($this->getTask()) . 'ed';

        $this->setMessage('Transaction' . $s . ' ' . $action);
    }

}

?>
