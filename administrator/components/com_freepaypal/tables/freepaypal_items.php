<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

class TableFreePayPal_Items extends JTable
{
  var $id = null; //int(11) NOT NULL auto_increment,
  var $item_number = null; //varchar(11) NOT NULL default '0',
  var $mc_gross = null; //decimal(9,2) NOT NULL default '0.00',
  var $mc_currency = null; //enum('USD','CAD','EUR','GBP','JPY','CAD') NOT NULL default 'USD',
  var $date_created = null; //datetime NOT NULL,
  var $published = null; //tinyint(1) NOT NULL,
	
  function __construct(&$db)
  {
    parent::__construct( '#__freepaypal_items', 'id', $db );
		
    jimport('joomla.utilities.date');
    //$now = new JDate();
    //$this->set( 'date', $now->toMySQL() );
  }
}
?>