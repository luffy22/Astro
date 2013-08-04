<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

class TableFreePayPal_Transactions extends JTable
{  
  var $id = null; //int(11) NOT NULL auto_increment,
  var $payer_id = null; //varchar(60) default NULL,
  var $payment_date = null; //varchar(50) default NULL,
  var $payment_date_mysql = null; //datetime default NULL,
  var $transaction_id = null; //varchar(50) default NULL,
  var $first_name = null; //varchar(50) default NULL,
  var $last_name = null; //varchar(50) default NULL,
  var $payer_email = null; //varchar(75) default NULL,
  var $payer_status = null; //varchar(50) default NULL,
  var $payment_type = null; //varchar(50) default NULL,
  var $memo = null; //tinytext,
  var $item_name = null; //varchar(127) default NULL,
  var $item_number = null; //varchar(127) default NULL,
  var $option_name1 = null; //varchar(64) NOT NULL default '',
  var $option_selection1 = null; //varchar(200) NOT NULL default '',
  var $option_name2 = null; //varchar(64) NOT NULL default '',
  var $option_selection2 = null; //varchar(200) NOT NULL default '',
  var $option_name3 = null; //varchar(64) NOT NULL default '',
  var $option_selection3 = null; //varchar(200) NOT NULL default '',
  var $option_name4 = null; //varchar(64) NOT NULL default '',
  var $option_selection4 = null; //varchar(200) NOT NULL default '',
  var $option_name5 = null; //varchar(64) NOT NULL default '',
  var $option_selection5 = null; //varchar(200) NOT NULL default '',
  var $quantity = null; //int(11) NOT NULL default '0',
  var $mc_gross = null; //decimal(9,2) default NULL,
  var $mc_currency = null; //char(3) default NULL,
  var $mc_fee = null; //decimal(9,2) default NULL,
  var $payment_fee = null; //decimal(9,2) default NULL,
  var $tax = null; //decimal(9,2) default NULL,
  var $address_name = null; //varchar(255) NOT NULL default '',
  var $address_street = null; //varchar(255) NOT NULL default '',
  var $address_city = null; //varchar(255) NOT NULL default '',
  var $address_state = null; //varchar(255) NOT NULL default '',
  var $address_zip = null; //varchar(255) NOT NULL default '',
  var $address_country = null; //varchar(255) NOT NULL default '',
  var $address_status = null; //varchar(255) NOT NULL default '',
  var $payer_business_name = null; //varchar(255) NOT NULL default '',
  var $payment_status = null; //varchar(255) NOT NULL default '',
  var $pending_reason = null; //varchar(255) NOT NULL default '',
  var $reason_code = null; //varchar(255) NOT NULL default '',
  var $txn_type = null; //varchar(255) NOT NULL default '',
  var $anonymous_name = null; //tinyint(1) NOT NULL,
  var $anonymous_amount = null; //tinyint(1) NOT NULL,
  var $published = null; //tinyint(1) NOT NULL,

  function __construct(&$db)
  {
    parent::__construct( '#__freepaypal_transactions', 'id', $db );
		
    jimport('joomla.utilities.date');
    //$now = new JDate();
    //$this->set( 'date', $now->toMySQL() );
  }
}
?>
