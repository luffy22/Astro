<?php
/*
 * v1.0.0
 * Friday Sep-02-2011
 * @component JooComments
 * @copyright Copyright (C) Abhiram Mishra www.bullraider.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
require_once JPATH_SITE.'/components/com_content/helpers/route.php';
$var=array('closeBtn'=>0);
JHtml::_('behavior.modal','a.modal',$var);
?>

<?php 
if(count($this->items)===0){?>
	<tr class="1" >
		<td colspan="5" align="center">
			<?php echo JText::sprintf('COM_JOOCOMMENTS_VIEW_UNPUBLISHED_NO_COMMENTS_FOUND'); ?>
		</td>
		
	</tr>
<?php }
 foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td style="width:5%">
			<?php echo $item->id; ?>
		</td>
		<td style="width:2%;">
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td style="width:20%">
			<div style="float: left;width: 80%;">
			<strong><?php echo JText::sprintf('COM_JOOCOMMENTS_TABLE_BODY_COMMENTATOR_NAME').': ';?></strong><?php echo $item->name; ?><br/><br/>
			<strong><?php echo JText::sprintf('COM_JOOCOMMENTS_TABLE_BODY_COMMENTATOR_EMAIL').': ';?> </strong><?php echo $item->email; ?>

			</div>
			<div style="float: right;width:20%;text-decoration: underline;"> <a class="modal"  rel="{handler: 'iframe', size: {x: 620, y: 400}}" href="index.php?option=com_joocomments&view=mail&layout=modal&tmpl=component&name=<?php echo $item->name?>&toMail=<?php echo $item->email; ?>&title=<?php echo "RE: ".$item->title;?>&header=Send a quick mail to commentator" >E-Mail</a> 
			 
		</td>
		<td style="width:68%">
			<strong><?php echo JText::sprintf('COM_JOOCOMMENTS_TABLE_BODY_COMMENTATOR_ARTICLE_TITLE').': ';?> </strong>
				<a style="text-decoration: underline;" target="_blank" href="../<?php echo ContentHelperRoute::getArticleRoute($item->alias ? ($item->article_id . ':' . $item->alias) : $item->article_id, $item->catid); ?>">
						<?php echo $this->escape($item->title); ?>
					</a><br/><br/>
			<strong><?php echo JText::sprintf('COM_JOOCOMMENTS_TABLE_BODY_COMMENTATOR_COMMENT').': ';?> </strong><?php echo $item->comment; ?><a class="moda" style="color: green;float: right;text-decoration: underline;" href="#" onclick="window.open('index.php?option=com_joocomments&view=reply&layout=modal&tmpl=component&id=<?php echo $item->id ?>&article_id=<?php echo $item->article_id?>','mywin',
'left=300,top=200,width=600,height=800,location=no,status=no,toolbar=no,resizable=0');" >Edit Comment</a> </div>
			
		</td>
		<td style="width:5%">
		<?php echo JHtml::_('jgrid.published', $item->published, $i, 'comments.'); ?>
		</td>
	</tr>
<?php endforeach; ?>
