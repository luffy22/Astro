<?php

defined('_JEXEC') or die();

class HTML_FreePayPal
{
	
  public static function editTransaction($option, &$row)
  {
    HTML_FreePayPal::setPayPalTransactionToolbar($row->id);
		
    JHTML::_('behavior.calendar');
		
    ?>
      <form action="index.php" method="post" name="adminForm">
		
	 <div class="col100">
	 <fieldset class="adminform">
	 <table class="admintable">
	 <tbody>
	 <tr>
	 <?php
	 $k=0;
	 foreach ($row as $fieldname => $fieldvalue) {
      if ($fieldname[0] == '_') {
      }
      else if ($fieldname == "published") {
	echo "<tr>\n";
	echo "<td class=\"key\">\n";
	echo "<label for=\"published\">Published</label>\n";
	echo "</td>\n";
	echo "<td>\n";
	echo JHTML::_('select.booleanlist',  'published', '', $row->published );
	echo "</td>\n";
	echo "</tr>\n";
      }
      else {
      echo "<tr><td class=\"key\"><label for=\"".$fieldname."\">".$fieldname."</label></td>";
      echo "<td><input class=\"inputbox\" type=\"text\" name=\"".$fieldname."\" idx=\"".$fieldname."\" size=\"40\" value=\"".$fieldvalue."\" /></td>\n";
      echo "</tr>\n";
      }
      $k = 1 - $k;
    }
?>
	 </tr>
	     </tbody>
	     </table>
	     </fieldset>
	     </div>
		
	     <input type="hidden" name="option" value="<?php echo $option; ?>" />
	     <input type="hidden" name="controller" value="transactions" />
	     <input type="hidden" name="task" value="" />
	     <input type="hidden" name="id" value="<?php echo $row->id ?>" />
	     </form>
	     <?php
	     }
	
  public static function setPayPalTransactionToolbar($id)
  {
    if ($id) {
      $newEdit = 'Edit';
    } else {
      $newEdit = 'New';
    }
		
    JToolBarHelper::title($newEdit . ' Transaction', 'generic.png');
    JToolBarHelper::save();
    JToolBarHelper::cancel();
  }

  public static function editItem($option, &$row)
  {
    HTML_FreePayPal::setPayPalTransactionToolbar($row->id);
		
    JHTML::_('behavior.calendar');
		
    ?>
      <form action="index.php" method="post" name="adminForm">
		
	 <div class="col100">
	 <fieldset class="adminform">
	 <table class="admintable">
	 <tbody>
	 <tr>
	 <?
	 $k=0;
	 foreach ($row as $fieldname => $fieldvalue) {
      if ($fieldname[0] == '_') {
      }
      else if ($fieldname == "published") {
	echo "<tr>\n";
	echo "<td class=\"key\">\n";
	echo "<label for=\"published\">Published</label>\n";
	echo "</td>\n";
	echo "<td>\n";
	echo JHTML::_('select.booleanlist',  'published', '', $row->published );
	echo "</td>\n";
	echo "</tr>\n";
      }
      else {
      echo "<tr><td class=\"key\"><label for=\"".$fieldname."\">".$fieldname."</label></td>";
      echo "<td><input class=\"inputbox\" type=\"text\" name=\"".$fieldname."\" idx=\"".$fieldname."\" size=\"40\" value=\"".$fieldvalue."\" /></td>\n";
      echo "</tr>\n";
      }
      $k = 1 - $k;
    }
?>
	 </tr>
	     </tbody>
	     </table>
	     </fieldset>
	     </div>
		
	     <input type="hidden" name="option" value="<?php echo $option; ?>" />
	     <input type="hidden" name="controller" value="items" />
	     <input type="hidden" name="task" value="" />
	     <input type="hidden" name="id" value="<?php echo $row->id ?>" />
	     </form>
	     <?php
	     }
	
  function setPayPalItemToolbar($id)
  {
    if ($id) {
      $newEdit = 'Edit';
    } else {
      $newEdit = 'New';
    }
		
    JToolBarHelper::title($newEdit . ' Item', 'generic.png');
    JToolBarHelper::save();
    JToolBarHelper::cancel();
  }
}

?>
