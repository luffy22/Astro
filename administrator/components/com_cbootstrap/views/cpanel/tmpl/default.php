<?php
 /**
 * @package		Twitter Bootstrap Integration
 * @subpackage	com_cbootstrap
 * @copyright	Copyright (C) 2012 Conflate. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.conflate.nl
 */
 
// No direct access
defined('_JEXEC') or die('Restricted access');

//JHtml::_('behavior.tooltip');
//JHtml::_('behavior.multiselect');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_cbootstrap&view=cpanel'); ?>" method="post" name="adminForm" id="adminForm">
<div class="width-60 fltlft">
	<div id="" style="width: 90%;">

	    

		<fieldset >
			<legend>Twitter Bootstrap Plugins</legend>
			<table class="adminlist">
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th><?php print JText::_('COM_CBOOTSTRAP_PLUGIN_NAME'); ?></th>
				<th class="center" width="10%"><?php print JText::_('COM_CBOOTSTRAP_PLUGIN_ENABLED'); ?></th>
				<th class="center" width="15%"><?php print JText::_('COM_CBOOTSTRAP_PLUGIN_INSTALLED_VERSION'); ?></th>
				<th class="center" width="15%"><?php print JText::_('COM_CBOOTSTRAP_PLUGIN_LATEST'); ?></th>
				<th width="20%"></th>
				<tbody>
			<?php foreach ($this->plugins as $i => $item) : ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td><?php print ucfirst($item->title); ?></td>
					<td class="center">
						<?php if($item->installed_version != '') : 
							echo JHtml::_('jgrid.published', $item->state, $i, 'cpanel.', true, 'cb', $item->publish_up, $item->publish_down);
						endif; ?>
					</td>
					<td class="center">
						<?php if($item->installed_version != '') : ?>
							<strong><?php print $item->installed_version; ?></strong>
						<?php endif; ?>
					</td>
					<td class="center">
						<?php if($item->latest_version != '') : ?>
							<strong><?php print $item->latest_version; ?></strong>
						<?php endif; ?>
					</td>
					<td>
						<?php if($item->installed_version != '') : ?>
							<a href="javascript:void(0);" onclick="return listItemTask('cb<?php print $i; ?>','CPanel.installPlugin')">
								<?php print ($item->installed_version == $item->latest_version ? JText::_('COM_CBOOTSTRAP_PLUGIN_REINSTALL') : JText::_('COM_CBOOTSTRAP_PLUGIN_UPDATE')); ?>
							</a>
							<a href="javascript:void(0);" onclick="return listItemTask('cb<?php print $i; ?>','CPanel.deletePlugin')">
								<?php print JText::_('COM_CBOOTSTRAP_PLUGIN_DELETE'); ?>
							</a>
						<?php else : ?>
							<a href="javascript:void(0);" onclick="return listItemTask('cb<?php print $i; ?>','CPanel.installPlugin')">
								<?php print JText::_('COM_CBOOTSTRAP_PLUGIN_INSTALL'); ?>
							</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
		</fieldset>
		

	</div>
</div>
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
<?php echo JHTML::_('form.token'); ?>
</form>
<div class="width-40 fltrt">

	<?php
	echo JHtml::_('sliders.start', $this->com_name.'-info-pane', array('useCookie' => 1, 'allowAllClose' => false));
	echo JHtml::_('sliders.panel', JText::_($this->uc_option), 'info-panel');
	?>
	
	<table class="adminlist">
	   <tr>
			<td colspan="2">
          		<img src="components/<?php print $this->option; ?>/assets/images/logo.png" align="middle" alt="<?php print JText::_($this->uc_option);?> logo" style="margin: 8px;" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
          		<?php print JText::_($this->uc_option.'_DESC'); ?>
			</td>
		</tr>
		<tr class="row1">
			<td width="25%"><?php print JText::_('COM_CONFLATE_PACKAGE_VERSION'); ?>:</td>
			<td><?php print $this->info['package_version']; ?></td>
		</tr>
		<tr class="row1">
			<td width="25%"><?php print JText::_('COM_CBOOTSTRAP_BOOTSTRAP_VERSION'); ?>:</td>
			<td><?php print $this->info['bootstrap_version']; ?></td>
		</tr>
		<tr class="row1">
			<td width="25%"><?php print JText::_('COM_CBOOTSTRAP_JQUERY_VERSION'); ?>:</td>
			<td><?php print $this->info['jquery_version']; ?></td>
		</tr>
		<tr>
			<td colspan="2">
          		<img src="components/<?php print $this->option; ?>/assets/images/logo_conflate.png" align="middle" alt="Conflate logo" />
			</td>
		</tr>
		<tr class="row">
			<td width="25%"><?php print JText::_('COM_CONFLATE_AUTHOR'); ?>:</td>
			<td><?php print $this->info['author']; ?></td>
		</tr>
		<tr class="row1">
			<td width="25%"><?php print JText::_('COM_CONFLATE_EMAIL'); ?>:</td>
			<td><?php print $this->info['email']; ?></td>
		</tr>
		<tr class="row">
			<td width="25%"><?php print JText::_('COM_CONFLATE_WEBSITE'); ?>:</td>
			<td><a href="<?php print $this->info['website']; ?>" title="Conflate website" target="_blank"><?php print $this->info['website']; ?></a></td>
		</tr>
		<tr class="row1">
			<td width="25%"><?php print JText::_('COM_CONFLATE_EXTENSION_PAGE'); ?>:</td>
			<td><a href="<?php print $this->info['extension_url']; ?>" target="_blank"><?php print $this->info['extension_url']; ?></a></td>
		</tr>
		<tr class="row">
			<td width="25%"><?php print JText::_('COM_CONFLATE_SUPPORT_US'); ?>:</td>
			<td>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
					<input type="hidden" name="cmd" value="_donations">
					<input type="hidden" name="business" value="sebastiaan@paauwtjes.nl">
					<input type="hidden" name="lc" value="US">
					<input type="hidden" name="item_name" value="Donation for the Twitter Bootstrap Integration extension by Conflate">
					<input type="hidden" name="no_note" value="0">
					<input type="hidden" name="currency_code" value="EUR">
					<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
					<input type="image" src="components/<?php print $this->option; ?>/assets/images/btn_paypal.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
			</td>
		</tr>
	</table>

	<?php
	echo JHtml::_('sliders.end');
	?>
    	
</div>

