<?php

/**
 * Topdonors View for FreePayPal Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link       http://docs.joomla.org/Category:Development
 * @license    GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.environment.uri');

/**
 * HTML View class for the FreePayPal Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class FreePayPalViewTopdonors extends JView {

    function display($tpl = null) {
        //$model =& $this->getModel();
        //$greeting = $model->getGreeting();
        //$this->assignRef( 'greeting',        $greeting );
        //parent::display($tpl);
        $params1 = JComponentHelper::getParams('com_freepaypal')->toArray();
        $db = JFactory::getDBO();
        FreePayPalViewTopdonors::HTML_topDonors($db, $params1);
    }

    function getRecords($db, $params) {
        $debug = $params['debug'];
        $viewoption = $params['top_donors_viewoption'];
        $numdonors = $params['top_donors_num_donors'];
        $interval_type = $params['top_donors_timeinterval_type'];
        switch ($interval_type) {
            case 1: // weekly
                // start of the week
                $first_day_of_week = date('d') - date('w');
                $last_day_of_week = date('d') - date('w') + 7;
                $sql = "SELECT *, SUM(mc_gross) AS sum_mc_gross FROM #__freepaypal_transactions WHERE published = 1 AND YEAR(payment_date_mysql) = '" . date('Y') . "' AND MONTH(payment_date_mysql) = '" . date('m') . "' AND DAY(payment_date_mysql) BETWEEN " . $first_day_of_week . " AND " . $last_day_of_week . " GROUP BY payer_id ORDER BY sum_mc_gross";
                if ($debug == 1) {
                    echo "Start of Week: " . date('Y-m-d', mktime(1, 0, 0, date('m'), date('d') - date('w'), date('Y'))) . ' 00:00:00';
                    echo "End of Week: " . date('Y-m-d', mktime(1, 0, 0, date('m'), date('d') - date('w') + 7, date('Y'))) . ' 00:00:00';
                    echo "SQL Query: " . $sql;
                }
                break;
            case 2: // monthly
                $sql = "SELECT *, SUM(mc_gross) AS sum_mc_gross FROM #__freepaypal_transactions WHERE published = 1 AND YEAR(payment_date_mysql) = '" . date('Y') . "' AND MONTH(payment_date_mysql) = '" . date('m') . "' GROUP BY payer_id ORDER BY sum_mc_gross";
                if ($debug == 1) {
                    echo "Start of Month: " . date('Y-m-d', mktime(1, 0, 0, date('m'), 1, date('Y'))) . ' 00:00:00';
                    echo "End of Month: " . date('Y-m-d', mktime(1, 0, 0, date('m') + 1, 1, date('Y'))) . ' 00:00:00';
                    echo "SQL Query: " . $sql;
                }
                break;
            case 3: // annual
                $sql = "SELECT *, SUM(mc_gross) AS sum_mc_gross FROM #__freepaypal_transactions WHERE published = 1 AND YEAR(payment_date_mysql) = '" . date('Y') . "' GROUP BY payer_id ORDER BY sum_mc_gross";
                if ($debug == 1) {
                    echo "Start of Year: " . date('Y-m-d', mktime(1, 0, 0, 1, 1, date('Y'))) . ' 00:00:00';
                    echo "End of Year: " . date('Y-m-d', mktime(1, 0, 0, 1, 1, date('Y') + 1)) . ' 00:00:00';
                    echo "SQL Query: " . $sql;
                }
                break;
            case 4: // all
                $sql = "SELECT *, SUM(mc_gross) AS sum_mc_gross FROM #__freepaypal_transactions WHERE published = 1 GROUP BY payer_id ORDER BY sum_mc_gross";
                if ($debug == 1) {
                    echo "SQL Query: " . $sql;
                }
        }
        $db->setQuery($sql);
        $rows = $db->loadObjectList();
        return $rows;
    }

    function HTML_topDonors($db, $params) {
        $viewoption = $params['top_donors_viewoption'];
        $numdonors = $params['top_donors_num_donors'];
        $timeinterval = $params['top_donors_timeinterval_type'];
        $titles_csv = $params['top_donors_table_titles'];
        $fields_csv = $params['top_donors_table_fields'];
        if ($viewoption == 3) {
            echo JText::_("FreePayPal::List Top Donors view not available. The configuration must be changed to enable this view.");
            return;
        }
        $rows = FreePayPalViewTopdonors::getRecords($db, $params);
        $config = new JConfig();
        echo "<script type=\"text/javascript\" src=\"" . JURI::root() . "components/com_freepaypal/views/listdonations/sorttable.js\"></script>";
        if ($params['debug'] == 1) {
            echo '<ul>';
            echo '<li>top_donors_viewoption = ' . $params['top_donors_viewoption'] . '</li>';
            echo '<li>top_donors_num_donors = ' . $params['top_donors_num_donors'] . '</li>';
            echo '<li>top_donors_timeinterval_type = ' . $params['top_donors_timeinterval_type'] . '</li>';
            echo '</ul>';
        }
        $numrows = count($rows);
        $rows_shown = ($numrows < $numdonors) ? $numrows : $numdonors;
        echo '<ul>';
        echo '<li>' . JText::_('Top') . ' ' . $rows_shown . ' ' . JText::_('donors for') . ' ';
        if ($timeinterval == 1)
            echo JText::_('this week') . '.</li>';
        else if ($timeinterval == 2)
            echo JText::_('this month') . '.</li>';
        else if ($timeinterval == 3)
            echo JText::_('this year') . '.</li>';
        else
            echo JText::_('all time') . '.</li>';
        echo '</ul>';
        $colnames = explode(',', $titles_csv);
        $fieldnames = explode(',', $fields_csv);
        //echo "<div id=\"test\" class=\"module\">";
        //echo "<table class=\"sortable\" border=\"1\">\n";
        if (count($colnames) > 0) {
            echo "<table class=\"moduletable\" >\n";
            echo "<thead><tr>";
            /* echo "<th>Date</th>";
              echo "<th>Amount</th>";
              echo "<th>Currency</th>";
              echo "<th>Name</th>";
              echo "<th>" . $rows[0]->option_name1 . "</th>";
              echo "<th>" . $rows[1]->option_name2 . "</th>"; */
            foreach ($colnames as $colname) {
                echo "<th>" . trim($colname) . "</th>";
            }
            echo "</tr></thead>";
            if (count($rows) > 0 && count($fieldnames) > 0) {
                echo "<tbody>";
                $i = 0;
                $rows = array_reverse($rows);
                foreach ($rows as $row) {
                    echo '<tr>';
                    foreach ($fieldnames as $fieldname) {
                        $fields = explode(' ', $fieldname);
                        $j = 0;
                        foreach ($fields as $field) {
                            $field = trim($field);
                            if (strlen($field) > 0) {
                                if (substr($field, 0, 1) == '$') {
                                    $field = substr($field, 1);
                                    if ($j == 0)
                                        $phpstr = '$row->' . $field;
                                    else
                                        $phpstr .= " .' '. " . '$row->' . $field;
                                }
                                else {
                                    if ($j == 0)
                                        $phpstr = "\"" . $field . "\"";
                                    else
                                        $phpstr .= " .\" \". " . "\"" . $field . "\"";
                                }
                                $j++;
                            }
                        }
                        $phpstr_eval = "echo \"<td>\" . " . $phpstr . " . \"</td>\";";
                        if ($params['debug'] == 1) {
                            $phpstr_evaldbg = "echo \"td\" . " . $phpstr . " .\"/td \" ;";
                            //echo '<li>phpcommand = <code>' . $phpstr_eval . ' ' . print_r($field) . '</code></li>';
                            echo '<li>phpcommand = </li><pre>' . $phpstr_evaldbg . '</pre>';
                        }
                        eval($phpstr_eval);
                    }
                    echo '</tr>';
                    $i++;
                    if ($i >= $numdonors && $numdonors > 0)
                        break;
                }
                echo "</tbody>";
            }
            else {
                echo '<tbody><tr><td></td></tr></tbody>';
            }
            echo '</table>';
        }
    }

}
