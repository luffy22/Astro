<?php

/**
 * Thermometer View for FreePayPal Component
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
class FreePayPalViewThermometer extends JView {

    function display($tpl = null) {
        //$model =& $this->getModel();
        //$greeting = $model->getGreeting();
        //$this->assignRef( 'greeting',        $greeting );
        //parent::display($tpl);
        $params1 = JComponentHelper::getParams('com_freepaypal')->toArray();
        $config = new JConfig();
        $component_view_basepath = JPATH_BASE . DS . "components" . DS . "com_freepaypal" . DS . "views" . DS . "thermometer" . DS;
        //$component_view_basepath = JPATH_COMPONENT.DS."views".DS."thermometer".DS;
        $db = JFactory::getDBO();
        FreePayPalViewThermometer::HTML_thermometerDonations($db, $params1, $component_view_basepath);
    }

    function getRecords($db, $params) {
        $debug = $params['debug'];
        $time_interval_repeat = $params['thermometer_timeinterval_repeat'];
        $interval_type = $params['thermometer_timeinterval_type'];
        $start_date = $params['thermometer_timeinterval_start_date'];
        $end_date = $params['thermometer_timeinterval_end_date'];
        if ($time_interval_repeat == 1) {
            switch ($interval_type) {
                case 1: // weekly
                    // start of the week
                    $first_day_of_week = date('d') - date('w');
                    $last_day_of_week = date('d') - date('w') + 7;
                    $sql = "SELECT * FROM #__freepaypal_transactions WHERE published = 1 AND YEAR(payment_date_mysql) = '" . date('Y') . "' AND MONTH(payment_date_mysql) = '" . date('m') . "' AND DAY(payment_date_mysql) BETWEEN " . $first_day_of_week . " AND " . $last_day_of_week;
                    if ($debug == 1) {
                        echo "Start of Week: " . date('Y-m-d', mktime(1, 0, 0, date('m'), date('d') - date('w'), date('Y'))) . ' 00:00:00';
                        echo "End of Week: " . date('Y-m-d', mktime(1, 0, 0, date('m'), date('d') - date('w') + 7, date('Y'))) . ' 00:00:00';
                        echo "SQL Query: " . $sql;
                    }
                    break;
                case 2: // monthly
                    $sql = "SELECT * FROM #__freepaypal_transactions WHERE published = 1 AND YEAR(payment_date_mysql) = '" . date('Y') . "' AND MONTH(payment_date_mysql) = '" . date('m') . "'";
                    if ($debug == 1) {
                        echo "Start of Month: " . date('Y-m-d', mktime(1, 0, 0, date('m'), 1, date('Y'))) . ' 00:00:00';
                        echo "End of Month: " . date('Y-m-d', mktime(1, 0, 0, date('m') + 1, 1, date('Y'))) . ' 00:00:00';
                        echo "SQL Query: " . $sql;
                    }
                    break;
                case 3: // annual
                    $sql = "SELECT * FROM #__freepaypal_transactions WHERE published = 1 AND YEAR(payment_date_mysql) = '" . date('Y') . "'";
                    if ($debug == 1) {
                        echo "Start of Year: " . date('Y-m-d', mktime(1, 0, 0, 1, 1, date('Y'))) . ' 00:00:00';
                        echo "End of Year: " . date('Y-m-d', mktime(1, 0, 0, 1, 1, date('Y') + 1)) . ' 00:00:00';
                        echo "SQL Query: " . $sql;
                    }
                    break;
                case 4: // all
                    $sql = "SELECT * FROM #__freepaypal_transactions WHERE published = 1";
                    if ($debug == 1) {
                        echo "SQL Query: " . $sql;
                    }
                    break;
            }
        } else {
            $sql = "SELECT * FROM #__freepaypal_transactions WHERE published = 1 AND payment_date_mysql BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
            if ($debug == 1) {
                echo "SQL Query: " . $sql;
            }
        }
        $db->setQuery($sql);
        $rows = $db->loadObjectList();
        return $rows;
    }

    function HTML_thermometerDonations($db, $params, $view_basepath) {
        $rows = FreePayPalViewThermometer::getRecords($db, $params);
        if ($params['debug'] == 1) {
            echo '<ul>';
            echo '<li>thermometer_graphic  = ' . $params['thermometer_graphic'] . '</li>';
            echo '<li>thermometer_timeinterval_repeat  = ' . $params['thermometer_timeinterval_repeat'] . '</li>';
            echo '<li>thermometer_timeinterval_type  = ' . $params['thermometer_timeinterval_type'] . '</li>';
            echo '<li>thermometer_timeinterval_start_date  = ' . $params['thermometer_timeinterval_start_date'] . '</li>';
            echo '<li>thermometer_timeinterval_end_date  = ' . $params['thermometer_timeinterval_end_date'] . '</li>';
            echo '<li>thermometer_numdivisions  = ' . $params['thermometer_numdivisions'] . '</li>';
            echo '<li>therm_unit = ' . $params['therm_unit'] . '</li>';
            echo '<li>therm_show_timeinterval = ' . $params['therm_show_timeinterval'] . '</li>';
            echo '<li>therm_show_remainder = ' . $params['therm_show_remainder'] . '</li>';
            echo '<li>therm_amount_view = ' . $params['therm_amount_view'] . '</li>';
            echo '<li>therm_max = ' . $params['therm_max'] . '</li>';
            echo '<li>thermometer_goalvalue  = ' . $params['thermometer_goalvalue'] . '</li>';
            echo '<li>therm_min = ' . $params['therm_min'] . '</li>';
            echo '<li>therm_width = ' . $params['therm_width'] . '</li>';
            echo '<li>therm_height = ' . $params['therm_height'] . '</li>';
            echo '<li>therm_goalmsg_text = ' . $params['therm_goalmsg_text'] . '</li>';
            echo '<li>therm_goalmsg_color = ' . $params['therm_goalmsg_color'] . '</li>';
            echo '<li>therm_goalmsg_fontsize = ' . $params['therm_goalmsg_fontsize'] . '</li>';
            echo '<li>therm_textcolor = ' . $params['therm_textcolor'] . '</li>';
            echo '<li>therm_bgcolor = ' . $params['therm_bgcolor'] . '</li>';
            echo '<li>therm_boundcolor = ' . $params['therm_boundcolor'] . '</li>';
            echo '<li>therm_scale = ' . $params['therm_scale'] . '</li>';
            echo '<li>thermometer_numdivisions  = ' . $params['thermometer_numdivisions'] . '</li>';
            echo '<li>numSmallDivsPerBigDiv = ' . $params['numSmallDivsPerBigDiv'] . '</li>';
            echo '<li>therm_bulbpct = ' . $params['therm_bulbpct'] . '</li>';
            echo '</ul>';
        }
        $total = 0;
        $fees = 0;
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $total += $row->mc_gross;
                $fees += $row->mc_fee;
            }
        }
        if ($params['thermometer_graphic'] == 1) {
            if (substr($params['therm_bgcolor'], 0, 1) == '#')
                $params['therm_textcolor'] = substr($params['therm_textcolor'], 1);
            if (substr($params['therm_bgcolor'], 0, 1) == '#')
                $params['therm_bgcolor'] = substr($params['therm_bgcolor'], 1);
            if (substr($params['therm_boundcolor'], 0, 1) == '#')
                $params['therm_boundcolor'] = substr($params['therm_boundcolor'], 1);
            if (substr($params['therm_goalmsg_color'], 0, 1) == '#')
                $params['therm_goalmsg_color'] = substr($params['therm_goalmsg_color'], 1);
            $xmlFilePath = $view_basepath . "images/thermometer.xml";
            $xmlFileContents = generateFlashXML($params, $total, $fees);

            if (file_exists($xmlFilePath) && !is_writable($xmlFilePath)) {
                // Destination is not writeable
                JError::raiseWarning(500, 'XML File isn\'t writeable: "' . $xmlFilePath . '"');
                JRequest::setVar('view', 'freepaypal');
                parent::display();
                return false;
            }

            jimport("joomla.filesystem.file");

            if (!JFile::write($xmlFilePath, $xmlFileContents)) {
                JError::raiseWarning(500, 'Error while writing File. Maybe it isn\'t writeable: "' . $xmlFilePath . '"');
                JRequest::setVar('view', 'freepaypal');
                parent::display();
                return false;
            }
            if (true) {
                $window_height = $params['therm_height'] + 80 + 60;
                $randint = rand();
                $siteURL = JURI::base();
                echo "<object 
	width=\"200\"
	height=\"" . $window_height . "\"
	id=\"gauge_" . $randint . "\">
<param name=\"movie\" value=\"" . $siteURL . "/components/com_freepaypal/views/thermometer/images/gauge.swf?xml_source=" . $siteURL . "/components/com_freepaypal/views/thermometer/images/thermometer.xml%3Frandint%3D" . $randint . "\" />
<param name=\"movie\" value=\"components/com_freepaypal/views/thermometer/images/gauge.swf?xml_source=components/com_freepaypal/views/thermometer/images/thermometer.xml%3Frandint%3D" . $randint . "\" />
<param name=\"quality\" value=\"high\" />
<param name=\"bgcolor\" value=\"#" . $params['therm_bgcolor'] . "\" />
<param name=\"allowScriptAccess\" value=\"sameDomain\" />
<embed src=\"" . $siteURL . "/components/com_freepaypal/views/thermometer/images/gauge.swf?xml_source=" . $siteURL . "/components/com_freepaypal/views/thermometer/images/thermometer.xml%3Frandint%3D" . $randint . "\"
	quality=\"high\"
	bgcolor=\"#" . $params['therm_bgcolor'] . "\"
	width=\"200\"
	height=\"" . $window_height . "\"
	name=\"gauge_" . $randint . "\"
	allowScriptAccess=\"sameDomain\"
	swLiveConnect=\"true\"
	type=\"application/x-shockwave-flash\"
	pluginspage=\"http://www.macromedia.com/go/getflashplayer\">
</embed>
</object>";
            }
        }
        //echo "total = ".$total;

        if ($params['thermometer_graphic'] == 3) {
            $jpgFilePath = $view_basepath . "images/autotherm.jpg";
            FreePayPalViewThermometer::showThermometer($params, $params['thermometer_goalvalue'], '$', $total, $fees, $view_basepath);
            echo "<img src=\"components/com_freepaypal/views/thermometer/images/autotherm.jpg\"></img>";
        }

        if ($params['thermometer_graphic'] == 4) {
            $htmlFileContents = generateHTML($params, $total, $fees);
            echo $htmlFileContents;
        }

        if ($params['thermometer_graphic'] == 2) {
            $svgFilePath = $view_basepath . "images/autotherm.svg";
            require_once($view_basepath . "thermometer.svg.php");
            $therm_unit = $params['therm_unit'];
            $therm_max = $params['therm_max'];
            $therm_goal = $params['thermometer_goalvalue'];
            $therm_min = $params['therm_min'];
            $therm_width = $params['therm_width'];
            $therm_height = $params['therm_height'];
            $therm_value = $total;
            $therm_textcolor = $params['therm_textcolor'];
            $therm_boundcolor = $params['therm_boundcolor'];
            $therm_scale = $params['therm_scale'];
            $numBigDivs = $params['thermometer_numdivisions'];
            $numSmallDivsPerBigDiv = $params['numSmallDivsPerBigDiv'];
            $therm_bulbpct = $params['therm_bulbpct'];
            $therm_fees = $fees;
            $therm_show_timeinterval = $params['therm_show_timeinterval'];
            $therm_show_remainder = $params['therm_show_remainder'];
            $therm_amount_view = $params['therm_amount_view'];
            $svgFileContents = generateSVGThermometer($therm_unit, $therm_max, $therm_goal, $therm_min, $therm_width, $therm_height, $therm_value, $therm_textcolor, $therm_boundcolor, $therm_scale, $numBigDivs, $numSmallDivsPerBigDiv, $therm_bulbpct, $therm_fees, $therm_show_timeinterval, $therm_show_remainder, $therm_amount_view);
            if (file_exists($svgFilePath) && !is_writable($svgFilePath)) {
                // Destination is not writeable
                JError::raiseWarning(500, 'SVG File isn\'t writeable: "' . $svgFilePath . '"');
                JRequest::setVar('view', 'freepaypal');
                parent::display();
                return false;
            }

            jimport("joomla.filesystem.file");

            if (!JFile::write($svgFilePath, $svgFileContents)) {
                JError::raiseWarning(500, 'Error while writing File. Maybe it isn\'t writeable: "' . $svgFilePath . '"');
                JRequest::setVar('view', 'freepaypal');
                parent::display();
                return false;
            }
            echo "<embed src=\"components/com_freepaypal/views/thermometer/images/autotherm.svg\" type=\"image/svg+xml\" pluginspage=\"http://www.adobe.com/svg/viewer/install/\" />";
            //echo "<embed src=\"components/com_freepaypal/views/thermometer/images/autotherm.svg\" type=\"image/svg+xml\" width=\"100%\" height=\"100%\" pluginspage=\"http://www.adobe.com/svg/viewer/install/\" />";
        }
    }

    /*
      Fundraising Thermometer Generator v1.1
      Sairam Suresh sai1138@yahoo.com / www.entropyfarm.org

      NOTE - you must include the full path to the truetype font on your system below
      if you want text labels to appear on your graph. No TrueType fonts are
      included in this package, you can probably find some on your system or
      else download one off the net.


      Inputs: 'unit'    - the ascii value of the currency unit. By default 36 ($)
      other interesting ones are:
      163:  British Pound
      165:  Japanese Yen
      8355: French Franc
      8364: Euro

      'max'     - the goal
      'current' - the current amount raised

      Versions:
      1.2 - added a 'burst' image on request, cleaned up the images a little bit.
      1.1 - Internationalized :) added 'unit' at a user's request so other currencies could be used.
      1.0 - intial version
     */

    function showThermometer($params, $max=0, $unit=36, $current=0, $fees=0, $view_basepath) {
        error_reporting(7); // Only report errors
        Header("Content-Type: image/jpeg");
        $font = $view_basepath . "images/FreeSans.ttf";

        //$t_unit = ($unit == 'none') ? '' : code2utf($unit);
        $t_unit = ($unit == 'none') ? '' : $unit;
        $t_max = $max;
        $t_current = $current;
        $finalimagewidth = max(strlen($t_max), strlen($t_current)) * 25;
        $finalimage = imagecreateTrueColor(60 + $finalimagewidth, 405);

        $white = imagecolorallocate($finalimage, 255, 255, 255);
        $black = imagecolorallocate($finalimage, 0, 0, 0);
        $red = imagecolorallocate($finalimage, 255, 0, 0);

        imagefill($finalimage, 0, 0, $white);
        ImageAlphaBlending($finalimage, true);

        $thermImage = imagecreatefromjpeg($view_basepath . "images/therm.jpg");
        $tix = ImageSX($thermImage);
        $tiy = ImageSY($thermImage);
        ImageCopy($finalimage, $thermImage, 0, 0, 0, 0, $tix, $tiy);

        // thermbar pic courtesy http://www.rosiehardman.com/
        $thermbarImage = imagecreatefromjpeg($view_basepath . "images/thermbar.jpg");
        $barW = ImageSX($thermbarImage);
        $barH = ImageSY($thermbarImage);

        $xpos = 5;
        $ypos = 327;
        $ydelta = 15;
        $fsize = 12;

        // Set number of $ybars to use, calculated as a factor of current / max.
        if ($t_current > $t_max) {
            $ybars = 25;
        } elseif ($t_current > 0) {
            $ybars = $t_max ? round(20 * ($t_current / $t_max)) : 0;
        }

        // Draw each ybar (filled red bar) in successive shifts of $ydelta.
        while ($ybars--) {
            ImageCopy($finalimage, $thermbarImage, $xpos, $ypos, 0, 0, $barW, $barH);
            $ypos = $ypos - $ydelta;
        }

        if ($t_current == $t_max) {
            ImageCopy($finalimage, $thermbarImage, $xpos, $ypos, 0, 0, $barW, $barH);
            $ypos -= $ydelta;
        }

        // If there's a truetype font available, use it
        if ($font && (file_exists($font))) {
            imagettftext($finalimage, $fsize, 0, 60, 355, $black, $font, $t_unit . "0");                 // Write the Zero
            imagettftext($finalimage, $fsize, 0, 60, 10 + (2 * $fsize), $black, $font, $t_unit . "$t_max");   // Write the max
            if ($t_current > $t_max) {
                imagettftext($finalimage, $fsize + 1, 0, 60, $fsize, $black, $font, $t_unit . "$t_current!!"); // Current > Max
            } elseif ($t_current != 0) {
                if ($t_current == $t_max) {
                    imagettftext($finalimage, $fsize, 0, 60, 10 + (2 * $fsize), $red, $font, $t_unit . "$t_max!");  // Current = Max
                } else {
                    if (round($t_current / $t_max) == 1) {
                        $ypos += 2 * $fsize;
                    }
                    imagettftext($finalimage, $fsize, 0, 60, ($t_current > 0) ? ($ypos + $fsize) : ($ypos + (4 * $fsize)), ($t_current > 0) ? $black : $red, $font, $t_unit . "$t_current");  // Current < Max
                }
            }
        }

        if ($t_current > $t_max) {
            $burstImg = imagecreatefromjpeg($view_basepath . "images/burst.jpg");
            $burstW = ImageSX($burstImg);
            $burstH = ImageSY($burstImg);
            ImageCopy($finalimage, $burstImg, 0, 0, 0, 0, $burstW, $burstH);
        }

        Imagejpeg($finalimage, $view_basepath . "images/autotherm.jpg");
        Imagedestroy($finalimage);
        Imagedestroy($thermImage);
        Imagedestroy($thermbarImage);
    }

    function code2utf($num) {
        //Returns the utf string corresponding to the unicode value
        //courtesy - romans@void.lv
        if ($num < 128)
            return chr($num);
        if ($num < 2048)
            return chr(($num >> 6) + 192) . chr(($num & 63) + 128);
        if ($num < 65536)
            return chr(($num >> 12) + 224) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
        if ($num < 2097152)
            return chr(($num >> 18) + 240) . chr((($num >> 12) & 63) + 128) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
        return '';
    }

}

function generateHTML($params, $total, $fees) {
    $therm_unit = $params['therm_unit'];
    $therm_max = $params['thermometer_goalvalue'];
    $therm_min = $params['therm_min'];
    $therm_value = $total;
    $therm_gross = $therm_value;
    $therm_fees = $fees;
    $therm_timeinterval = $params['thermometer_timeinterval_type'];
    $therm_show_timeinterval = $params['therm_show_timeinterval'];
    $therm_show_remainder = $params['therm_show_remainder'];
    $therm_amount_view = $params['therm_amount_view'];
    $therm_textcolor = $params['therm_textcolor'];
    $therm_boundcolor = $params['therm_boundcolor'];
    $therm_bulbpct = $params['therm_bulbpct'];

    $xmlFile = '';
    if ($therm_show_timeinterval == 2) {
        if ($therm_timeinterval == 1) { // weekly
            $timestr = JText::_("Donations this week.");
        } else if ($therm_timeinterval == 2) { // monthly
            $timestr = JText::_("Donations for") . " " . date('F') . ".";
            //$timestr = "Donations for " . date('M') . ".";
        } else if ($therm_timeinterval == 3) { // yearly
            $timestr = JText::_("Donations for") . " " . date('Y') . ".";
            //$timestr = "Donations for " . date('y') . ".";
        } else if ($therm_timeinterval == 4) { // all time
            $timestr = JText::_("Overall donations.");
        }
        $xmlFile .= $timestr . "<br />";
    }
    $xmlFile .= JText::_("Goal Amount:") . " " . $therm_unit . sprintf("%0.02f", $therm_max) . "<br />";

    if ($therm_amount_view == 2) {
        $therm_value = $therm_value - $fees;
    }
    $goal_reached = 0;
    if ($therm_ypos < $therm_top) {
        $goal_reached = 1;
        // Draw actual value --
        $xmlFile .= JText::_("Donations Received:") . " " . $therm_unit . sprintf("%0.02f", $therm_value) . "<br />";
        $xmlFile .= JText::_("Thank You! We reached our goal!") . "<br />";
    } else {
        $xmlFile .= JText::_("Donations Received:") . " " . $therm_unit . sprintf("%0.02f", $therm_value) . "<br />";
    }
    if ($therm_amount_view == 3 && $fees > 0) {
        $therm_value = $therm_value - $fees;
        $xmlFile .= JText::_("Donations Received after fees:") . " " . $therm_unit . sprintf("%0.02f", $therm_value) . "<br />";
    }

    if ($therm_show_remainder == 2 && $therm_max > $therm_value) {
        $remvalue = $therm_max - $therm_value;
        $xmlFile .= $therm_unit . sprintf("%0.02f", $remvalue) . " " . JText::_("remaining to reach our goal.") . "<br />";
    }

    if ($therm_bulbpct == 1) {
        $therm_pct = round(100 * ($therm_value - $therm_min) / ($therm_max - $therm_min));
        if ($therm_pct < 0)
            $therm_pct = '0';
        $xmlFile .= JText::_("Percent complete:") . " " . $therm_pct . "%" . "<br />";
    }

    return $xmlFile;
}

function generateFlashXML($params, $total, $fees) {
    $therm_unit = $params['therm_unit'];
    $therm_max = $params['therm_max'];
    $therm_goal = $params['thermometer_goalvalue'];
    $therm_min = $params['therm_min'];
    $therm_width = $params['therm_width'];
    $therm_height = $params['therm_height'];
    $therm_value = $total;
    $therm_gross = $therm_value;
    $therm_fees = $fees;
    $therm_timeinterval = $params['thermometer_timeinterval_type'];
    $therm_show_timeinterval = $params['therm_show_timeinterval'];
    $therm_show_remainder = $params['therm_show_remainder'];
    $therm_amount_view = $params['therm_amount_view'];
    $therm_textcolor = $params['therm_textcolor'];
    $therm_bgcolor = $params['therm_bgcolor'];
    $therm_boundcolor = $params['therm_boundcolor'];
    $therm_scale = $params['therm_scale'];
    $numBigDivs = $params['thermometer_numdivisions'];
    $numSmallDivsPerBigDiv = $params['numSmallDivsPerBigDiv'];
    $therm_bulbpct = $params['therm_bulbpct'];

    $therm_bulboffset = 30;
    $numSmallDivs = $numBigDivs * $numSmallDivsPerBigDiv;
    $left_offset = 84;
    $right_offset = $left_offset + $therm_width;
    $therm_top = 20;
    $therm_peak = $therm_top;
    $therm_bottom = $therm_top + $therm_height;
    $therm_middle = 0.5 * ($therm_top + $therm_bottom);
    $bigDivLen = $therm_width / 4;
    $smallDivLen = $therm_width / 8;
    $bulb_ctr_x = $left_offset + 0.5 * $therm_width;
    $bulb_ctr_y = $therm_bottom + $therm_bulboffset + 20;

    $xmlFile = '';
    $xmlFile.="<gauge>";

    $xmlFile.= flashDrawCircle($bulb_ctr_x, $bulb_ctr_y, 48, $therm_boundcolor);
    $xmlFile .= flashDrawRect($left_offset, $therm_peak, $therm_width, $therm_height + $therm_bulboffset + 20, '880000', 8, $therm_boundcolor);
    $xmlFile.= flashDrawCircle($bulb_ctr_x, $bulb_ctr_y, 40, 'ff0000');
    $xmlFile.= flashDrawCircle($bulb_ctr_x, $bulb_ctr_y, 30, 'ffff00', 50);
    $xmlFile.= flashDrawCircle($bulb_ctr_x - 3, $bulb_ctr_y + 2, 30, 'ff0000');
    $xmlFile .= flashDrawRect($left_offset, $therm_bottom, $therm_width, $therm_bulboffset + 20, 'ff0000');
    $x_start = $right_offset + 3;
    $x_end = $x_start + $bigDivLen;
    $y_offset = ($therm_bottom - $therm_top) / $numBigDivs;
    $therm_offset = ($therm_max - $therm_min) / $numBigDivs;
    for ($i = 0; $i <= $numBigDivs; $i++) {
        $value = $therm_max - $i * $therm_offset;
        $therm_label = $therm_unit . round($value);
        $y_start = $therm_top + $i * $y_offset;
        $y_end = $y_start;
        $xmlFile .= flashDrawLine($x_start, $y_start, $x_end, $y_end, 2, $therm_boundcolor);
        $xmlFile .= flashDrawText($x_end + 2, $y_end - 8, 100, 'left', 10, $therm_textcolor, $therm_label);
    }
    if ($therm_show_timeinterval == 2) {
        if ($therm_timeinterval == 1) { // weekly
            $timestr = JText::_("Donations this week.");
        } else if ($therm_timeinterval == 2) { // monthly
            $timestr = JText::_("Donations for") . " " . date('F') . ".";
        } else if ($therm_timeinterval == 3) { // yearly
            $timestr = JText::_("Donations for") . " " . date('Y') . ".";
        } else if ($therm_timeinterval == 4) { // all time
            $timestr = JText::_("Overall donations.");
        }
        $xmlFile .= flashDrawText(10, $bulb_ctr_y + 50, '200', 'center', 12, $therm_textcolor, $timestr);
    }

    $y_offset = ($therm_bottom - $therm_top) / $numSmallDivs;
    $x_end = $x_start + $smallDivLen;
    for ($i = 1; $i < $numSmallDivs; $i++) {
        $y_start = $therm_top + $i * $y_offset;
        $y_end = $y_start;
        $xmlFile .= flashDrawLine($x_start, $y_start, $x_end, $y_end, 1, $therm_boundcolor);
    }

    if ($therm_bulbpct == 1) {
        $therm_pct = round(100 * ($therm_value - $therm_min) / ($therm_goal - $therm_min));
        if ($therm_pct < 0)
            $therm_pct = '0';
        if ($therm_pct < 10)
            $x_offset = -12;
        else if ($therm_pct < 100)
            $x_offset = -20;
        else
            $x_offset= - 27;
        $xmlFile .= flashDrawText($bulb_ctr_x - 15, $bulb_ctr_y - 10, '', 'left', 16, '000000', $therm_pct . "%");
    }

    $therm_temp = $therm_height * ($therm_value - $therm_min) / ($therm_max - $therm_min);
    $therm_ypos = $therm_bottom - $therm_temp;
// red thermometer foreground
    if ($therm_amount_view == 2) {
        $therm_value = round($therm_value - $fees);
    }
    if ($therm_show_remainder == 2 && $therm_goal > $therm_value) {
        $remvalue = $therm_goal - $therm_value;
        $remstr = $therm_unit . $remvalue . " " . JText::_("remaining to reach our goal.");
        $xmlFile .= flashDrawText(1, $bulb_ctr_y - 25, $left_offset - 30, 'center', 10, $therm_textcolor, $remstr);
    }
    if ($therm_amount_view == 3 && $fees > 1) {
        $xmlFile.=flashDrawText(1, $therm_ypos + 10, $left_offset - 8, 'right', 10, $therm_textcolor, $therm_unit . round($therm_value - $fees) . " " . JText::_("after fees"));
    }
    $goal_reached = 0;
    if ($therm_ypos < $therm_top) {
        $therm_ypos = $therm_peak;
        $therm_temp = $therm_height + $therm_bulboffset;
    }
    if ($therm_value > $therm_goal) {
        $goal_reached = 1;
        // Draw actual value --
        //$xmlFile .= flashDrawText( $right_offset+10, $therm_peak, '', 'left', 10, 'ffffff', $therm_unit . $therm_value . "!!");
        $xmlFile.=flashDrawText(10, $therm_ypos - 12, $left_offset - 20, 'right', 16, $therm_textcolor, $therm_unit . $therm_value . "!!");
        //$xmlFile .= flashDrawText( $right_offset+10, $therm_peak, '', 'left', 10, 'ffffff', $therm_unit . $therm_value . "!!");
        // TODO: burst thermometer?
        // TODO: put "goal reached!" diagonally across thermometer flashing?
    } else {
        $xmlFile.=flashDrawText(10, $therm_ypos - 12, $left_offset - 20, 'right', 16, $therm_textcolor, $therm_unit . $therm_value);
    }

    $xmlFile.="<!-- thermometer pointer -->
 <scale x='" . $left_offset . "' y='" . $therm_bottom . "' start_scale='0' end_scale='100' direction='vertical' step='1' shake_span='1' shadow_alpha='0'>";
    $xmlFile.=flashDrawRect($left_offset, $therm_ypos, $therm_width, $therm_temp, 'ff0000');
    if ($goal_reached)
// $xmlFile.=flashDrawText($left_offset-30,$therm_height,$therm_height,'left',40,'ffff00',JText::_("We met our goal!"),-90,50);
        $xmlFile.=flashDrawText($left_offset - 30, $therm_height, $therm_height, 'left', $params['therm_goalmsg_fontsize'], $params['therm_goalmsg_color'], $params['therm_goalmsg_text'], -90, 50);
    $xmlFile.="</scale>\n";
    $xmlFile.="</gauge>";

    return $xmlFile;
}

function flashDrawLine($x_start, $y_start, $x_end, $y_end, $thickness='1', $color='') {
    $xml = "<line x1='" . $x_start . "' y1='" . $y_start . "' x2='" . $x_end . "' y2='" . $y_end . "' ";
    if (!empty($color))
        $xml.= "color='" . $color . "' ";
    $xml .= "thickness='" . $thickness . "' ";
    $xml .= " />";
    return $xml;
}

function flashDrawText($x, $y, $width='', $align='', $size='', $color='', $text, $rotate='', $alpha='') {
    $xml = "<text x='" . $x . "' y='" . $y . "' ";
    if (!empty($width))
        $xml.= "width='" . $width . "' ";
    if (!empty($rotate))
        $xml.= "rotation='" . $rotate . "' ";
    if (!empty($align))
        $xml.= "align='" . $align . "' ";
    if (!empty($size))
        $xml.= "size='" . $size . "' ";
    if (!empty($color))
        $xml.= "color='" . $color . "' ";
    if (!empty($alpha))
        $xml.= "alpha='" . $alpha . "' ";
    $xml.=" >" . $text . "</text>";
    return $xml;
}

function flashDrawRect($x, $y, $width, $height, $fillcolor, $linethickness='', $linecolor='') {
    $xml = "<rect x='" . $x . "' y='" . $y . "' width='" . $width . "' height='" . $height . "' fill_color='" . $fillcolor . "' ";
    if (!empty($linethickness))
        $xml.="line_thickness='" . $linethickness . "' ";
    if (!empty($linecolor))
        $xml.="line_color='" . $linecolor . "' ";
    $xml.=" />\n";
    return $xml;
}

function flashDrawCircle($x, $y, $r, $fillcolor, $fillalpha='', $linethickness='') {
    $xml = "<circle x='" . $x . "' y='" . $y . "' radius='" . $r . "' fill_color='" . $fillcolor . "' ";
    if (!empty($fillalpha))
        $xml.="fill_alpha='" . $fillalpha . "' ";
    if (!empty($linethickness))
        $xml.="line_thickness='" . $linethickness . "' ";
    $xml.=" />\n";
    return $xml;
}
?>

