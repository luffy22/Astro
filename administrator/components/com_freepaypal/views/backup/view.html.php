<?php
/**
 * @version		$Id: view.php 10381 2008-06-01 03:35:53Z pasamio $
 * @package		Joomla
 * @subpackage	Config
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * @package		Joomla
 * @subpackage	Config
 */
class FreePayPalViewBackup extends JView {

    /**
     * Display the view
     */
    function display($tpl=null) {
        JToolBarHelper::preferences('com_freepaypal');
        JToolBarHelper::title(JText::_('FreePayPal - Backup/Restore'), 'generic.png');
        ?>
        <form enctype="multipart/form-data" action="index.php" method="post" name="adminForm" autocomplete="off">
            <fieldset>
                <legend>
        <?php echo JText::_('Backup'); ?>
                </legend>
                <table class="adminform">
                    <tr>
                        <th colspan="2"><?php echo JText::_('Download Database Table Backup File'); ?></th>
                    </tr>
                    <tr>
                        <td width="120"></td>
                        <td><input type="button" class="button" value="<?php echo JText::_('Download Database Table Backup File'); ?>" onclick="submitbutton('doDataTablesBackup')" />
                        </td>
                    </tr>
                </table>
                <table class="adminform">
                    <tr>
                        <th colspan="2"><?php echo JText::_('Download Component Configuration Backup File'); ?></th>
                    </tr>
                    <tr>
                        <td width="120"></td>
                        <td><input type="button" class="button" value="<?php echo JText::_('Download Configuration Settings File'); ?>" onclick="submitbutton('doConfigBackup')" />
                        </td>
                    </tr>
                </table>
            </fieldset>

            <fieldset>
                <legend>
        <?php echo JText::_('Restore'); ?>
                </legend>
                <table class="adminform">
                    <tr>
                        <th colspan="2"><?php echo JText::_('Upload Database Table Restore File'); ?></th>
                    </tr>
                    <tr>
                        <th colspan="2"><?php echo JText::_('IMPORTANT: You must upload your FreePayPal database backup file here to restore entries from a backup of your FreePayPal database information. If you are upgrading FreePayPal, it is important to upload this file (ending in .sql) prior to putting your site back up to prevent collision of transaction id numbers, i.e., two transactions cannot have the same id. ADMIN ACCESS REQUIRED: The contents of the backup file are executed as a sequence of SQL queries.'); ?></th>
                    </tr>
                    <tr>
                        <td width="120"><label for="install_tables"><?php echo JText::_('Restore File'); ?>:</label></td>
                        <td><input class="input_box" id="install_tables" name="install_tables" type="file" size="57" />
                            <input class="button" type="button" value="<?php echo JText::_('Upload Database Table Restore File'); ?> &amp; <?php echo JText::_('Install'); ?>" onclick="submitbutton('doDataTablesRestore')" />
                        </td>
                    </tr>
                </table>
                <table class="adminform">
                    <tr>
                        <th colspan="2"><?php echo JText::_('Upload Component Configuration Restore File'); ?></th>
                    </tr>
                    <tr>
                        <th colspan="2"><?php echo JText::_('IMPORTANT: You must upload your FreePayPal configuration backup file here to restore your configuration from a previous FreePayPal configuration backup. If you are upgrading FreePayPal, it is important to upload this file (ending in .txt) prior to putting your site back up to get the same site appearance for this component. ADMIN ACCESS REQUIRED: The contents of the backup file are executed as a sequence php statements.'); ?></th>
                    </tr>
                    <tr>
                        <td width="120"><label for="install_config"><?php echo JText::_('Restore File'); ?>:</label></td>
                        <td><input class="input_box" id="install_config" name="install_config" type="file" size="57" />
                            <input class="button" type="button" value="<?php echo JText::_('Upload Configuration Restore File'); ?> &amp; <?php echo JText::_('Install'); ?>" onclick="submitbutton('doConfigRestore')" />
                        </td>
                    </tr>
                </table>
            </fieldset>

            <input type="hidden" name="type" value="" />
            <input type="hidden" name="controller" value="backup" />
            <input type="hidden" name="option" value="com_freepaypal" />
            <input type="hidden" name="task" value="" />
        <?php echo JHTML::_('form.token'); ?> 
        </form>
            <?php
        }

    }
    ?>