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
class FreePayPalViewFreepaypal extends JView {

    /**
     * Display the view
     */
    public function display($tpl = null) {
        JToolBarHelper::save('Save', 'Save');
        JToolBarHelper::cancel();
        // for existing items the button is renamed `close`
        JToolBarHelper::divider();
        JToolBarHelper::preferences('com_freepaypal');
        $document = JFactory::getDocument();

        $form = $this->get('Form');
        $component = $this->get('Component');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        // Bind the form to the data.
        if ($form && $component->params) {
            $form->bind($component->params);
        }

        $this->assignRef('form', $form);
        $this->assignRef('component', $component);
        JToolBarHelper::title(JText::_('FreePayPal - Config'), 'generic.png');

        //$this->document->setTitle(JText::_('JGLOBAL_EDIT_PREFERENCES'));
// Load the tooltip behavior.
        JHtml::_('behavior.tooltip');
        JHtml::_('behavior.formvalidation');
        ?>
        <form action="index.php" method="post" name="adminForm" autocomplete="off">
            <fieldset>
                <div style="float: right">
                    <button type="button" onclick="submitbutton('save');">
        <?php echo JText::_('Save'); ?></button>
                </div>
                <div class="configuration" >
                </div>
                <legend>
        <?php echo JText::_('Configuration'); ?>
                </legend>

        <?php
        echo JHtml::_('tabs.start', 'config-tabs-' . $this->component->option . '_configuration', array('useCookie' => 1));
        $fieldSets = $this->form->getFieldsets();
        foreach ($fieldSets as $name => $fieldSet) :
            $label = empty($fieldSet->label) ? 'COM_CONFIG_' . $name . '_FIELDSET_LABEL' : $fieldSet->label;
            echo JHtml::_('tabs.panel', JText::_($label), 'publishing-details');
            if (isset($fieldSet->description) && !empty($fieldSet->description)) :
                echo '<p class="tab-description">' . JText::_($fieldSet->description) . '</p>';
            endif;
            ?>
                    <ul class="config-option-list">
                    <?php
                    foreach ($this->form->getFieldset($name) as $field):
                        ?>
                            <li>
                            <?php if (!$field->hidden) : ?>
                                    <?php echo $field->label; ?>
                                <?php endif; ?>
                                <?php echo $field->input; ?>
                            </li>
                                <?php
                            endforeach;
                            ?>
                    </ul>


                    <div class="clr"></div>
            <?php
        endforeach;
        echo JHtml::_('tabs.end');
        ?>
            </fieldset>
            <input type="hidden" name="id" value="<?php echo $this->component->id; ?>" />
            <input type="hidden" name="component" value="<?php echo $this->component->option; ?>" />
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="controller" value="component" />
            <input type="hidden" name="option" value="com_freepaypal" />
            <!-- <input type="hidden" name="tmpl" value="component" /> -->
            <input type="hidden" name="task" value="save" />
        <?php echo JHTML::_('form.token'); ?>
        </form>
            <?php
        }

    }
    ?>
