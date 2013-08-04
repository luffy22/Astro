<?php
/*
 * v1.0.0
 * Friday Sep-02-2011
 * @component JooComments
 * @copyright Copyright (C) Abhiram Mishra www.bullraider.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined( '_JEXEC' ) or die( 'Restricted access' );?>
<style type="text/css">

</style>
<?php 
$i=0;
$component = JComponentHelper::getComponent( 'com_joocomments' );
$isVoteEnabled=$component->params->get( 'vote_enabled', '1' );
$user =JFactory::getUser();
if($isVoteEnabled=='2'){
	if($user->guest){$isVoteEnabled='0';}else{$isVoteEnabled='1';}
}
?>
<p class="comment-header1"><?php echo count($this->comments).' '.JText::sprintf('COM_JOOCOOMENTS_COMMENTS_LIST_COMMENT_NUMBER');?> </p><hr/>
<div id="joo-comments-entries">
<?php foreach($this->comments as  $item):  ?>
<div class="baksha" id="<?php echo 'jooCommentsId'.$item['id'];?>" >
<div class="comment normal-comment">
<div class="cmeta">
<p class="author"><strong class="author"><?php echo $item['name']; ?></strong>
<em class="action-text"><?php  echo JText::sprintf('COM_JOOCOOMENTS_COMMENTS_LIST_USER_SAID');?> </em></p>
<?php if($isVoteEnabled=='1') {?>
<div style="   
    float: right;
    margin-top: 6px;
    width: 63px;">
    
    	<?php $col=new JInputCookie ;
    	if($col->get('voteType')!='1'){?>
    		<a class="like" onclick="vote(<?php echo $item['id']; ?>, 1);return false;" href="#" title="Thumps up"></a>
    	<?php }
    	?>
    	
		
		<a class="dislike" onclick="vote(<?php echo $item['id']; ?>, -1);return false;" href="#" title="Thumps down"></a>
		<span id='jooCommLike<?php echo $item['id'];?>' style="display:block;padding-top:3px;text-align:center;<?php if( $item['voting']>=0)echo 'color:#78A8E2;';else echo 'color:#EE845F;';?>"><?php 
		
		echo $item['voting'];
		
		?> </span>
</div>
<?php }?>
<?php 
if (intval($item['publish_date'] ) != 0){?>
	<p class="info">
      <em class="date">
      	<?php  echo substr($item['publish_date'], 0, 10); ?>
      </em>
    </p>
<?php }?>
</div>
<div class="body" id="commentid<?php echo $i; $i++;?>">
<?php if($this->isGravatar==1){?>
<?php echo '<img src=\''.get_gravatar($item['email'],42,$this->gravatarIcon,'g',false).'\' class=\'avatar\' />'?>
<div id="text">
   <?php echo Markdown($item['comment']); ?>
</div>
<?php }else{
echo Markdown($item['comment']); 
}?>



</div>
</div>
</div>
<div style="padding-top:12px;" ></div>

<?php endforeach; ?>
</DIV>
<script type="text/javascript" language="javascript">
var length=<?php echo count($this->comments)?>;
</script>
<?php die();?>