<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_('behavior.framework',true);
?>
<script type="text/javascript">
function sumbitIndividualStatus(id,status){
	var element=document.getElementById('jform_comment_Enable_Disable').value;
	 var myHTMLRequest = new Request.HTML({url: 'index.php',onComplete: function(response){
			$('co').empty();
	        $('right').empty().adopt(response);
	        alert("<?php echo JText::_("COM_JOOCOMMENTS_ALERT_CACHE_CLEAR"); ?>");
   }}).get('option=com_joocomments&task=settings.update&articleId='+id+'&parentCategory='+element+'&view=articlesettings&layout=category&status='+status);
}
function sumbitBulkStatus(status){	
	var element=document.getElementById('jform_comment_Enable_Disable').value;
	 var a=document.getElementsByName("joocendisa[]");
	    var j=0;
	        for (i=0, l=a.length; i<l; i++)
	        {
	            if (a[i].checked==true)
	            {
	                 j++;
	            }
	        }
	       if (j==0)
	       {
	          alert("<?php echo JText::_('COM_JOOCOMMENTS_COMMENTS_NOT_SELECTED');?>");
	          return ;
	       }
	var formRequest = new Form.Request(document.articleStatus,'right',{extraData:{'status':status,'view':'articlesettings','layout':'category','parentCategory':element}}).send();
	 alert("<?php echo JText::_("COM_JOOCOMMENTS_ALERT_CACHE_CLEAR"); ?>");
}

</script>
<style type="text/css">
div.joo_tablewrapper {
	background-color: #F4F4F4;
	border: 1px solid #CCCCCC;
	border-radius: 10px 10px 10px 10px;
	margin: 12px;
	clear:both;
}

div.joo_toolbar-list {
	padding: 0;
	text-align: right;
	float:right;
}

div.joo_toolbar-list ul {
	margin: 0;
	padding: 0;
	float:right;
}

div.joo_toolbar-list li {
	color: #666666;
	float: left;
	list-style: none outside none;
	padding: 1px 1px 3px 4px;
	text-align: center;
}

div.joo_toolbar-list a {
	border: 1px solid #F4F4F4;
	cursor: pointer;
	display: block;
	float: left;
	white-space: nowrap;
	padding: 1px 5px;
}
div.joo_toolbar-list span{
height: 32px;
width: 32px;
float:none;
display:block;
margin: 0 auto;
}

div.joo_toolbar-list a  span.enable {
background-image:
		url("<?php  echo JURI::root();?>/administrator/components/com_joocomments/assets/icon-32-unblock.png");
}
div.joo_toolbar-list a span.disable {
background-image:
		url("<?php echo JURI::root();?>/administrator/components/com_joocomments/assets/icon-32-unpublish.png");
}
input.joo_checkall{
margin:12px !important;

}
</style>

<div class="joo_tablewrapper">
<div class="joo_toolbar-list">

<ul>
	<li>
		<a id="publish" href="javascript:sumbitBulkStatus('1');">
			<span class="enable"></span><?php echo JText::_("COM_JOOCOMMENTS_VIEW_CONFIGURATION_FRONTEND_OPTIONS_ENABLE_DISABLE_COMMENTS_ENABLE");?>
		</a>
	</li>
	<li>
		<a id="unpublish" href="javascript:sumbitBulkStatus('0');">
			<span class="disable"></span><?php echo JText::_("COM_JOOCOMMENTS_VIEW_CONFIGURATION_FRONTEND_OPTIONS_ENABLE_DISABLE_COMMENTS_DISABLE");?>
		</a>
	</li>
</ul>
</div>

<div class="clr"></div>
<div style="overflow:auto;">
<form name="articleStatus" id="articleStatus" action="index.php?option=com_joocomments&view=articlesettings&layout=category">
<table class="adminlist">
	<thead>
		<th><input class="joo_checkall" type="checkbox" name="checkall-toggle" value=""
			title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
			onclick="Joomla.checkAll(this)" />Id</th>
		<th><strong>Article Title</strong></th>
		<th width="5%"><b>Comments status</b></th>
	</thead>

	<tbody>
		<?php
		if (count($this->items)>0){
			foreach($this->items as  $item):
	  $registry = new JRegistry;
	  $registry->loadString($item['attribs']);
	  $attribs = $registry->toArray();
	  echo '<tr>';
	  echo '<td class="center">';
	  echo JHtml::_('grid.id', $item['id'], $item['id'],false,'joocendisa');
	  echo $item['id'];	
	  echo '</td>';
	  echo '<td>'.$item['title'].'<br/>'.'</td>';
	  $path='ss';
	  $commentEnabled='1';
	  if(isset($attribs['comments_enabled'])){
	  	$commentEnabled=$attribs['comments_enabled'];
	  }
	  if($commentEnabled=='1'){
	  	$path='admin/tick.png';
	  }else{
	  	$path='admin/publish_x.png';
	  }
	  $swapStatus= $commentEnabled=='1' ? '0':'1';
	  $attrib='style="cursor:pointer" onclick=sumbitIndividualStatus(\''.$item['id'].'\',\''.$swapStatus.'\');';
	  echo '<td class="center">'.JHtml::image($path,'',$attrib,true,false).'</td>';
	  echo '</tr>';
	  endforeach;
		}else{
			echo '<tr><td colspan="3" align="center">'.JText::_('COM_JOOCOMMENTS_VIEW_CONFIGURATION_FRONTEND_OPTIONS_ENABLE_DISABLE_COMMENTS_NO_ARTICLES').'</td></tr>';
		}
		echo '</tbody>';?>
		</table>
		<input type="hidden" id="boxchecked" name="boxchecked" value="0" />
		<input type="hidden" value="settings.updateBulk" name="task" />
		</form>
		</div>
		</div>;