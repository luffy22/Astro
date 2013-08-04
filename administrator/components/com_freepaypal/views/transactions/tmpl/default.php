<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
    <div id="editcell">
    <table class="adminlist">
    <thead>
<tr>
</th>
<th width="20">
    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
    </th>			
<th>id</th>
<th>payer_id</th> 
<th>payment_date</th> 
<th>payment_date_mysql</th> 
<th>transaction_id</th>
<th>first_name</th>
<th>last_name</th>
<th>payer_email</th> 
<th>payer_status</th>
<th>payment_type</th>
<th>memo</th> 
<th>item_name</th> 
<th>item_number</th> 
<th>option_name1</th> 
<th>option_selection1</th> 
<th>option_name2</th> 
<th>option_selection2</th> 
<th>option_name3</th> 
<th>option_selection3</th> 
<th>option_name4</th> 
<th>option_selection4</th> 
<th>option_name5</th> 
<th>option_selection5</th> 
<th>quantity</th> 
<th>mc_gross</th> 
<th>mc_currency</th> 
<th>mc_fee</th> 
<th>payment_fee</th> 
<th>tax</th> 
<th>address_name</th> 
<th>address_street</th> 
<th>address_city</th> 
<th>address_state</th> 
<th>address_zip</th>
<th>address_country</th>
<th>address_status</th>
<th>payer_business_name</th>
<th>payment_status</th> 
<th>pending_reason</th> 
<th>reason_code</th> 
<th>txn_type</th> 
<th>anonymous_name</th>
<th>anonymous_amount</th>
<th>published</th>
</tr>
</thead>
<?php
$k = 0;
for ($i=0, $n=count( $this->items ); $i < $n; $i++)
  {		$row = &$this->items[$i];
    $published = JHTML::_('grid.published', $row, $i );
    $checked 	= JHTML::_('grid.id',   $i, $row->id );
    $link 	= JRoute::_( 'index.php?option=com_freepaypal&controller=transactions&task=edit&cid[]='. $row->id );

    //$link = 'index.php?option=' . $option . '&task=edit&cid[]='. $row->id;
    $titlefield = "payment_date";
    ?>
      <tr class="<?php echo "row$k"; ?>">
	 <td align="center">
	 <?php echo $checked;?>
	 </td>
	     <?php
	     foreach ($row as $fieldname => $fieldvalue) {
      if ($fieldname == $titlefield) {
	echo "<td><a href=\"".$link."\" title=\"Edit PayPal Donation Entry\">";
	echo $fieldvalue."\n";
	echo "</a></td>\n";
      }
      else if ($fieldname == "published") {
	echo "<td>".$published."</td>\n";
      }
      else {
	echo "<td>".$fieldvalue."</td>\n";
      }
    }
    echo "</tr>";
    $k = 1 - $k;
  }
?>
</table>
</div>

<input type="hidden" name="option" value="com_freepaypal" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="controller" value="transactions" />
  </form>
