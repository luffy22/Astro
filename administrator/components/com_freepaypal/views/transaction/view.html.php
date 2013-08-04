<?php
  /**
   * Transaction View for FreePayPal Component
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
 * Transaction View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class FreePayPalViewTransaction extends JView
{
  /**
   * display method of Transaction view
   * @return void
   **/
  function display($tpl = null)
  {
    //get the transaction
    $transaction	= $this->get('Data');
    $isNew		= ($hello->id < 1);

    $text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
    JToolBarHelper::title(   JText::_( 'Item' ).': <small><small>[ ' . $text.' ]</small></small>' );
    JToolBarHelper::save();
    if ($isNew)  {
      JToolBarHelper::cancel();
    } else {
      // for existing items the button is renamed `close`
      JToolBarHelper::cancel( 'cancel', 'Close' );
    }
    //JToolBarHelper::preferences( 'com_COMPONENT_NAME' );


    $this->assignRef('transaction', $transaction);

    parent::display($tpl);
  }
}
