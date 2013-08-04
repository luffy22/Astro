  <!DOCTYPE html>
  <html>
    <head>
      <title>Test WMD Page</title>
      
      <?php 
      JHTML::stylesheet(JURI::root().'components/com_joocomments/assets/css/wmd.css');
      JHtml::script(JURI::root().'components/com_joocomments/assets/js/showdown.js');

      JHtml::_('behavior.modal');
      if(JRequest::getInt('noForm',0)!=1){
      
      $item=$this->items['0'];
      
     
      
      ?>
    </head>
	
    <body>

<form action="index.php?option=com_joocomments" id="mailtoForm" method="post">
      <div id="wmd-button-bar" class="wmd-panel"></div>
      <br/>
      <textarea id="wmd-input" name="userComment" class="wmd-panel"><?php echo $item->comment;?></textarea>
      <div id="wmd-preview" class="wmd-panel"></div>
      <br/>
      <script
	src='<?php echo JURI::root().'components/com_joocomments/assets/js/wmd.js';?>'
	type='text/javascript'></script>
      <script>
     	// wmd_options.ajaxForm=true;   
    	// Attacklab.wmd();
		// Attacklab.wmdBase();
		//Attacklab.Util.startEditor(); 
		  		wmd_options.ajaxForm=true;   
            	Attacklab.wmd();
            	Attacklab.wmd_defaults={buttons:"bold italic link blockquote code image ol ul heading hr"};
            	Attacklab.wmdBase();
            	Attacklab.loadEnv();
            	Attacklab.Util.startEditor();
	</script>
	<?php 
		$user=&JFactory::getUser();
		//echo $user->authorise('core.admin','root.1');

		?>
	<input type="submit" value="Sumbit changes" />
		<?php echo JHtml::_('form.token'); ?>
		<input type="hidden" name="task" value="comments.updateComment" />
	<input type="hidden" name="article_id" value="<?php echo JRequest::getInt('article_id');?>" />
	<input type="hidden" name="id" value="<?php echo JRequest::getInt('id');?>" />
	<input type="hidden" name="article_title" value="Administrator" />
</form>
    </body>
<?php }else{
       echo '<script> //window.close();
						if (window.opener && !window.opener.closed) {
							window.opener.location.reload();
						}
      </script>' ;
      }?>