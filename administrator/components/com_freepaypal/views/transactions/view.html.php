<?php
  /**
   * Transactions View for FreePayPal Component
   * 
   * @package    Joomla.Tutorials
   * @subpackage Components
   * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
   * @license		GNU/GPL
   */

  // Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * Transactions View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class FreePayPalViewTransactions extends JView
{
  /**
   * Tranactions view display method
   * @return void
   **/
  function display($tpl = null)
  {
    JToolBarHelper::title(   JText::_( 'Transactions Manager' ), 'generic.png' );
    JToolBarHelper::publishList();
    JToolBarHelper::unpublishList();
    JToolBarHelper::deleteList();
    JToolBarHelper::editListX();
    JToolBarHelper::addNewX();
    //JToolBarHelper::editList();
    //JToolBarHelper::addNew();
    //JToolBarHelper::preferences( 'com_freepaypal' );

    // Get data from the model
    $items		=  $this->get( 'Data');
    $this->assignRef('items',		$items);

    parent::display($tpl);
  }
}
