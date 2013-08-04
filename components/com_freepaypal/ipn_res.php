<?php

defined('_JEXEC') or die('Restricted Access.');
require("ipn_cls.php");

// made by robin kohli (robin@19.5degs.com) for 19.5 Degrees (http://www.19.5degs.com)
// ----- edit these settings

class PayPalIPN_Handler {
  function handle_ipn($db, $paypal_info, $params) {
    $filter_by_email = isset($params['filterby_seller_email']) ? $params['filterby_seller_email'] : 1;
    $paypal_email = isset($params['seller_email']) ? $params['seller_email'] : "your.paypal@email.address";
    $error_email = isset($params['log_email']) ? $params['log_email'] : "your@email.address";
    $from_name = isset($params['log_from_name']) ? $params['log_from_name'] : "from_name";
    $from_email = isset($params['log_from_email']) ? $params['log_from_email'] : "from_email";
    $company_name = isset($params['company_name']) ? $params['company_name'] : "company_name";

    // email header
    $em_headers  = "From: ".$from_name." <".$from_email.">\n";		
    $em_headers .= "Reply-To: ".$from_email."\n";
    $em_headers .= "Return-Path: ".$from_email."\n";
    $em_headers .= "Organization: ".$company_name."\n";
    $em_headers .= "X-Priority: 3\n";

    // -----------------
    $paypal_ipn = new paypal_ipn($paypal_info);

    foreach ($paypal_ipn->paypal_post_vars as $key=>$value) {
      if (getType($key)=="string") {
	eval("\$$key=\$value;");
      }
    }

    $paypal_ipn->send_response();
    $paypal_ipn->error_email = $error_email;

    if (!$paypal_ipn->is_verified()) {
      $paypal_ipn->error_out(JText::_("Bad order (PayPal says it's invalid)") . $paypal_ipn->paypal_response , $em_headers);
      die();
    }


    switch( $paypal_ipn->get_payment_status() )
      {
      case 'Pending':
	$pending_reason=$paypal_ipn->paypal_post_vars['pending_reason'];
	if ($pending_reason!="intl") {
	  $paypal_ipn->error_out(JText::_("Pending Payment")." - $pending_reason", $em_headers);
	  break;
	}

      case 'Completed':
	$qry= "SELECT i.mc_gross, i.mc_currency FROM #__freepaypal_items as i WHERE i.item_number='$item_number'";
	//mysql_connect("$host","$ln","$pw") or die("Unable to connect to database");
	//mysql_select_db("$db") or die("Unable to select database");
	//$res=mysql_query ($qry);
	$db->setQuery($qry);
	$res = $db->loadObjectList();
	$config=mysql_fetch_array($res);
	if ($paypal_ipn->paypal_post_vars['txn_type']=="reversal") {
	  $reason_code=$paypal_ipn->paypal_post_vars['reason_code'];
	  $paypal_ipn->error_out(JText::_("PayPal reversed an earlier transaction."), $em_headers);
	  // you should mark the payment as disputed now
	} else {
	  //if ((strtolower(trim($paypal_ipn->paypal_post_vars['business'])) == $paypal_email) && (trim($mc_currency)==$config['mc_currency']) && (trim($mc_gross)-$tax == $quantity*$config['mc_gross'])) {
	  //if (strtolower(trim($paypal_ipn->paypal_post_vars['business'])) == $paypal_email) {
	  if (($filter_by_email == 0 && 
	       strtolower(trim($paypal_ipn->paypal_post_vars['business'])) == $paypal_email) ||
	      ($filter_by_email == 1)) {
	    $timestr=strtotime($payment_date);
            $payment_date_mysql=strftime("%Y-%m-%d %H:%M:%S",$timestr);
	    $quantity = !empty($quantity) ? $quantity : 1;
	    $mc_gross = !empty($mc_gross) ? $mc_gross : 0;
	    $mc_fee = !empty($mc_fee) ? $mc_fee : 0;
	    $payment_fee = !empty($payment_fee) ? $payment_fee : 0;
	    $tax = !empty($tax) ? $tax : 0;
	    $anonymous_name = !empty($anonymous_name) ? $anonymous_name : 0;
	    $anonymous_amount = !empty($anonymous_amount) ? $anonymous_amount : 0;
	    $qry="INSERT INTO #__freepaypal_transactions VALUES (0 , '$payer_id', '$payment_date', '$payment_date_mysql', '$txn_id', '$first_name', '$last_name', '$payer_email', '$payer_status', '$payment_type', '$memo', '$item_name', '$item_number', '$option_name1', '$option_selection1', '$option_name2', '$option_selection2', '$option_name3', '$option_selection3', '$option_name4', '$option_selection4', '$option_name5', '$option_selection5', $quantity, $mc_gross, '$mc_currency', $mc_fee, $payment_fee, $tax, '$address_name', '".nl2br($address_street)."', '$address_city', '$address_state', '$address_zip', '$address_country', '$address_status', '$payer_business_name', '$payment_status', '$pending_reason', '$reason_code', '$txn_type', '$anonymous_name', '$anonymous_amount', 1)";
	    $db->setQuery($qry);
	    if ($db->query()) {
	      $paypal_ipn->error_out(JText::_("This was a successful transaction"), $em_headers);			
	      // you should add your code for sending out the download link to your customer at $payer_email here.
	    } else {
	      $paypal_ipn->error_out(JText::_("This was a duplicate transaction"), $em_headers);
	      //$paypal_ipn->error_out("The SQL query was \n".$qry."\n", $em_headers);
	    } 
	  } else {
	    $paypal_ipn->error_out(JText::_("Someone attempted a sale using a manipulated URL"), $em_headers);
	  }
	}
	break;
		
      case 'Failed':
	// this will only happen in case of echeck.
	$paypal_ipn->error_out(JText::_("Failed Payment"), $em_headers);
	break;

      case 'Denied':
	// denied payment by us
	$paypal_ipn->error_out(JText::_("Denied Payment"), $em_headers);
	break;

      case 'Refunded':
	// payment refunded by us
	$paypal_ipn->error_out(JText::_("Refunded Payment"), $em_headers);
	break;

      case 'Canceled':
	// reversal cancelled
	// mark the payment as dispute cancelled		
	$paypal_ipn->error_out(JText::_("Cancelled reversal"), $em_headers);
	break;

      default:
	// order is not good
	$paypal_ipn->error_out(JText::_("Unknown Payment Status")." - " . $paypal_ipn->get_payment_status(), $em_headers);
	break;
      } 
  }
}
?>
