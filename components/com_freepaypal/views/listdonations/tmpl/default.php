<?php
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS."helpers".DS."freepaypalcore.class.php");
FreePayPalCore::loadSettings();

$params['filterby_seller_email'] = FreePayPalCore::getParameter('filterby_seller_email');
$params['seller_email'] = FreePayPalCore::getParameter('seller_email');
$params['log_email'] = FreePayPalCore::getParameter('log_email');
$params['log_from_name'] = FreePayPalCore::getParameter('log_from_name');
$params['log_from_email'] = FreePayPalCore::getParameter('log_from_email');
$params['company_name'] = FreePayPalCore::getParameter('company_name');
$params['dispBold'] = FreePayPalCore::getParameter('dispBold');
$params['dispItalics'] = FreePayPalCore::getParameter('dispItalics');
$params['dispUnderline'] = FreePayPalCore::getParameter('dispUnderline');
$params['debug'] = FreePayPalCore::getParameter('debug');


function HTML_showDonations($db,$params) {
  $db->setQuery("SELECT * FROM #__freepaypal_transactions WHERE published = 1 ORDER BY payment_date");
  $rows = $db->loadObjectList();

  list($begin, $end) = getMessageFormat();

  echo '<ul>';
  /*
   echo '<li> filterby_seller_email = '.$params[filterby_seller_email].'</li>';
   echo '<li> seller_email = '.$params[seller_email].'</li>';
   echo '<li> log_email = '.$params[log_email].'</li>';
   echo '<li> log_from_name = '.$params[log_from_name].'</li>';
   echo '<li> log_from_email = '.$params[log_from_email].'</li>';
   echo '<li> company_name = '.$params[company_name].'</li>';
   echo '<li> dispBold = '.$params[dispBold].'</li>';
   echo '<li> dispItalics = '.$params[dispItalics].'</li>';
   echo '<li> dispUnderline = '.$params[dispUnderline].'</li>';
   echo '<li> debug = '.$params[debug].'</li>';
  */
  foreach ($rows as $row) {
    echo '<li>' . JHTML::date($row->payment_date) . '<br>Donation Amount: ' . $begin . $row->mc_gross." ".$row->mc_currency."<br> Donor: ".$row->first_name." ".$row->last_name."<br>".$row->option_name1 ." ".$row->option_selection1 ."<br>".$row->option_name2 ." ".$row->option_selection2 . $end . '</li>';
  }
  echo '</ul>';
}

$db =& JFactory::getDBO();
HTML_showDonations($db, $params);

?>
