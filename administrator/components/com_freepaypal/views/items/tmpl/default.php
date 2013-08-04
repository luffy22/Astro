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
    <th>item_number</th> 
    <th>mc_gross</th> 
    <th>mc_currency</th>
    <th>date_created</th>
    <th>published</th>
    </tr>
    </thead>
    <?php
    $k = 0;
for ($i=0, $n=count( $this->items ); $i < $n; $i++)
  {		$row = &$this->items[$i];
    $published = JHTML::_('grid.published', $row, $i );
    $checked 	= JHTML::_('grid.id',   $i, $row->id );
    $link 	= JRoute::_( 'index.php?option=com_freepaypal&controller=items&task=edit&cid[]='. $row->id );

    //$link = 'index.php?option=' . $option . '&task=edit&cid[]='. $row->id;
    $titlefield = "item_number";
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
  <input type="hidden" name="controller" value="items" />
  </form>
