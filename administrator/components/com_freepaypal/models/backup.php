<?php

/**
 * @version		$Id: component.php 10381 2008-06-01 03:35:53Z pasamio $
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

jimport('joomla.application.component.model');

/**
 * @package		Joomla
 * @subpackage	Config
 */
class FreePayPalModelBackup extends JModel {

    function dumpToFile($fileContents, $filePath) {

        if (file_exists($filePath) && !is_writable($filePath)) {
            // Destination is not writeable
            JError::raiseWarning(500, 'File  isn\'t writeable: "' . $filePath . '"');
            JRequest::setVar('controller', 'backup');
            parent::display();
            return false;
        }

        jimport("joomla.filesystem.file");

        if (!JFile::write($filePath, $fileContents)) {
            JError::raiseWarning(500, 'Error while writing File. Maybe it isn\'t writeable: "' . $filePath . '"');
            JRequest::setVar('controller', 'backup');
            parent::display();
            return false;
        }
    }

    /**
     * @param string The class name for the installer
     */
    function _getPackageFromUpload($HTML_param) {
        // Get the uploaded file information
        $userfile = JRequest::getVar($HTML_param, null, 'files', 'array');
        //print_r($userfile);
        // Make sure that file uploads are enabled in php
        if (!(bool) ini_get('file_uploads')) {
            JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLFILE'));
            return false;
        }

        // Make sure that zlib is loaded so that the package can be unpacked
        if (!extension_loaded('zlib')) {
            JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLZLIB'));
            return false;
        }

        // If there is no uploaded file, we have a problem...
        if (!is_array($userfile)) {
            JError::raiseWarning('SOME_ERROR_CODE', JText::_('No file selected'));
            return false;
        }

        // Check if there was a problem uploading the file.
        if ($userfile['error'] || $userfile['size'] < 1) {
            JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLUPLOADERROR'));
            return false;
        }
        // Build the appropriate paths
        $config = JFactory::getConfig();
        $tmp_dest = $config->getValue('config.tmp_path') . DS . $userfile['name'];
        $tmp_src = $userfile['tmp_name'];

        // Move uploaded file
        jimport('joomla.filesystem.file');
        $uploaded = JFile::upload($tmp_src, $tmp_dest);
        //JError::raiseWarning('SOME_ERROR_CODE', JText::_($uploaded));
        //return $uploaded;		
        if ($uploaded != true) {
            return null;
        } else {
            return $tmp_dest;
        }
    }

    function datadump($table) {
        $db = JFactory::getDBO();
        $config = new JConfig();
        //$tablename = $config->dbprefix . $table;
        $tablename = "#__" . $table;
        $dbname = $config->db;
        $result = "# Dump of $tablename from database $dbname \n";
        $result .= "# Dump DATE : " . date("d-M-Y") . "\n";
        $query = "SELECT * FROM #__" . $table;
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        //$result .= "# table ".$tablename." is from database ".$dbname."\n";
        //$result .= "# ".count($rows['0'])." fields in table ".$tablename."\n";
        $result .= "# " . count($rows) . " row elements in table " . $tablename . "\n\n";
        foreach ($rows as $row) {
            $result .= "INSERT INTO `" . $tablename . "` (";
            $rownum = 0;
            foreach ($row as $fieldname => $fieldvalue) {
                if ($rownum != 0)
                    $result .= ", ";
                $result .= "`" . $fieldname . "`";
                $rownum++;
            }
            $result .= ") VALUES (";
            $rownum = 0;
            foreach ($row as $fieldname => $fieldvalue) {
                $fieldvalue = addslashes($fieldvalue);
                $fieldvalue = preg_replace("/\n/", "/\\n/", $fieldvalue);
                if ($rownum != 0)
                    $result .= ", ";
                if (isset($fieldvalue)) {
                    $result.= '"' . $fieldvalue . '"';
                } else {
                    $result.= '""';
                }
                //$result .= "'". $fieldvalue . "'";
                $rownum++;
            }
            $result .= ");\n";
        }
        return $result . "\n\n\n";
    }

}

?>
