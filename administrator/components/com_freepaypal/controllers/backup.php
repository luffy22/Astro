<?php

defined('_JEXEC') or die();

jimport('joomla.application.component.controller');
jimport('joomla.application.component.view');

require_once( JPATH_COMPONENT . DS . 'views' . DS . 'backup' . DS . 'view.html.php' );

class FreePayPalControllerBackup extends JController {

    function __construct() {
        parent::__construct();
    }

    public function display($cachable = false, $urlparams = false) {
        // load the component's language file
        $component = JRequest::getCmd('component');
        $lang = JFactory::getLanguage();
        $lang->load($component);
        $model = $this->getModel('Backup');
        $view = new FreePayPalViewBackup( );
        //$view->assignRef('component', $table);
        //$view->setModel( $model, true );
        $view->display();
    }

    function doConfigBackup() {
        $component = JComponentHelper::getComponent('com_freepaypal');
        $params = JComponentHelper::getParams('com_freepaypal')->toArray();
        $result = "";
        foreach ($params as $fieldname => $fieldvalue) {
            $nfieldvalue = str_replace('$', '\$', $fieldvalue);
            $result .= "\$params['" . $fieldname . "'] = \"" . $nfieldvalue . "\";\n";
        }
        $file_name = "freepaypal_config_backup-" . date("m-d-y_h:i:s") . ".txt";
        Header("Content-type: application/octet-stream");
        Header("Content-Disposition: attachment; filename=$file_name");
        echo $result;
        exit;
    }

    function doDataTablesBackup() {
        $model = $this->getModel('Backup');
        $table1 = $model->datadump("freepaypal_transactions");
        $table2 = $model->datadump("freepaypal_items");

        $result = $table1 . $table2;
        $file_name = "freepaypal_tables_backup-" . date("m-d-y_h:i:s") . ".sql";
        Header("Content-type: application/octet-stream");
        Header("Content-Disposition: attachment; filename=$file_name");
        echo $result;
        exit;
    }

    function doConfigRestore() {
        $model = $this->getModel('Backup');
        $uploadedFilePath = $model->_getPackageFromUpload('install_config');

        if ($uploadedFilePath == null) {
            $msg = "Upload test failed, database tables NOT restored.";
            $this->setRedirect('index.php?option=com_freepaypal&controller=backup', $msg);
            return;
        }
        // Read uploaded file
        jimport('joomla.filesystem.file');
        $uploaded_data = JFile::read($uploadedFilePath);
        $component_name = 'com_freepaypal';
        $result = "";

        eval($uploaded_data);
        $paramsArr['params'] = $params;

        $component = JComponentHelper::getComponent($component_name);
        $id = $component->id;
        $option = $component_name;

        // Attempt to save the configuration.
        $data = array(
            'params' => $paramsArr,
            'id' => $id,
            'option' => $option
        );
        $model2 = $this->getModel('FreePayPal');
        $return = $model2->save($data);

        // Check the return value.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_config.config.global.data', $data);

            // Save failed, go back to the screen and display a notice.
            $message = JText::sprintf('JERROR_SAVE_FAILED', $model->getError());
            $this->setRedirect('index.php?option=com_freepaypal', $message, 'error');
            return false;
        }

        // Set the redirect based on the task.
        switch ($this->getTask()) {
            case 'apply':
                $message = JText::_('COM_CONFIG_SAVE_SUCCESS');
                $this->setRedirect('index.php?option=com_freepaypal', $message);
                break;

            case 'save':
            default:
                $message = JText::_('COM_CONFIG_SAVE_SUCCESS');
                $this->setRedirect('index.php?option=com_freepaypal', $message);
                break;
        }

        return true;
    }

    function doDataTablesRestore() {
        $model = $this->getModel('Backup');
        $uploadedFilePath = $model->_getPackageFromUpload('install_tables');
        if ($uploadedFilePath == null) {
            $msg = "Upload failed, FreePayPal database tables NOT restored.";
            $this->setRedirect('index.php?option=com_freepaypal&controller=backup', $msg);
            return;
        }
        // Read uploaded file
        jimport('joomla.filesystem.file');
        $uploaded_data = JFile::read($uploadedFilePath);
        $db = & JFactory::getDBO();
        $queryArray = $db->splitSql($uploaded_data);
        foreach ($queryArray as $query) {
            $db->setQuery($query);
            $qresult = $db->query();
            if ($qresult == false) {
                JError::raiseWarning('SOME_ERROR_CODE', JText::_("Database restore query failed: " . $query));
            }
        }
        //$db->setQuery($uploaded_data);
        //$admin_component_view_basepath = JPATH_BASE.DS."components".DS."com_freepaypal".DS."views".DS."backup".DS;
        //$dataFilePath = $admin_component_view_basepath."uploaded_data.sql";
        //$model->dumpToFile($uploaded,$dataFilePath);
        //$qresult = $db->query();
        //if ($qresult == false) {
        //JError::raiseWarning('SOME_ERROR_CODE', JText::_("Database restore query failed: ".$uploaded_data));
        //}
        //$component = JRequest::getCmd( 'component' );
        $msg = "FreePayPal Database Tables restored";
        $this->setRedirect('index.php?option=com_freepaypal&controller=backup', $msg);
    }

}

?>
