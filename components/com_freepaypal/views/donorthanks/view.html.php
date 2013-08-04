<?php
  /**
   * Listdonations View for FreePayPal Component
   * 
   * @package    Joomla.Tutorials
   * @subpackage Components
   * @link       http://docs.joomla.org/Category:Development
   * @license    GNU/GPL
   */
 
  // no direct access 
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
 
/**
 * HTML View class for the FreePayPal Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
 
class FreePayPalViewDonorthanks extends JView
{
  function display($tpl = null)
  {
    $params1 = JComponentHelper::getParams( 'com_freepaypal' )->toArray();
    echo $params1['donor_thanks_msg'];
  }
}
