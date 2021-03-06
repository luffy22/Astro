<?php
/**
 * @version		$Id: fieldsattachement.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
 
// require helper file
/*$dir = dirname(__FILE__);
$dir = $dir.DS.'..'.DS.'..'.DS.'..'.DS;
JLoader::register('fieldsattachHelper',   $dir.'administrator/components/com_fieldsattach/helpers/fieldsattach.php');*/

// require helper file
$sitepath = JPATH_BASE ;
$sitepath = str_replace ("administrator", "", $sitepath); 
$sitepath = JPATH_SITE ;
JLoader::register('fieldsattachHelper',   $sitepath.DS.'administrator/components/com_fieldsattach/helpers/fieldsattach.php');



class plgSystemfieldsattachment extends JPlugin
{
        private $str ;
        private $path;
        public $array_fields  = array();
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	function plgSystemfieldsattachment(& $subject, $config)
	{
		//DELETE ROWS =======================================================
				$option = JRequest::getCmd('option', '');
	        	$view = JRequest::getCmd('view', 'default');  
	           if ( JFactory::getApplication()->isAdmin()) {
	                if (($option == 'com_content' )&&($view == "articles")) { $this->cleandatabase(); } 
			   }
	           
			   
		parent::__construct($subject, $config);
                
                $this->path= '..'.DS.'images'.DS.'documents';
                if ((JRequest::getVar('option')=='com_categories' && JRequest::getVar('layout')=="edit" && JRequest::getVar('extension')=="com_content"  )){
                     $this->path= '..'.DS.'images'.DS.'documentscategories';
                }
                
                $mainframe = JFactory::getApplication();
		if ($mainframe->isAdmin()) {
 			$document = &JFactory::getDocument();
                	$document->addStyleSheet(   JURI::base().'../plugins/system/fieldsattachment/js/style.css' );
                    $dispatcher =& JDispatcher::getInstance();

                    JPluginHelper::importPlugin('fieldsattachment'); // very important
                    //select

                    $this->array_fields = fieldsattachHelper::get_extensions() ;

                     foreach ($this->array_fields as $obj)
                    {
                        $function  = "plgfieldsattachment_".$obj."::construct();";
                        $base = JPATH_BASE;
                        $base = str_replace("/administrator", "", $base);
                        $base = JPATH_SITE;
                        $file = $base.'/plugins/fieldsattachment/'.$obj.'/'.$obj.'.php';
                        
                        if( JFile::exists($file)){
                            //file exist
                            eval($function);
                        }
                        
                    }
                    
                    //DELETE
                    //JError::raiseWarning( 100,  "DELETE".JRequest::getVar("cid")   ." task:".JRequest::getVar("task")  );
                    if(JRequest::getVar("task") == "articles.trash") { $this->deleteFields();}
                   return;
                } 

 
	}
        
        /**
	* Function for batch FUCTION
	*
	* @access	public
	* @since	1.5
	*/
        
        public function batchcopy($newId, $oldId)
        {
            $db	= & JFactory::getDBO();
            $query = 'SELECT a.* FROM #__content as a  WHERE  a.id ='. $newId ; 
            $db->setQuery($query);  
 
            //echo "WW:".$query;

            $article = $db->loadObject();  
            plgSystemfieldsattachment::copyArticle($article, $oldId); 
        }
       
       

         
        /**
	* Function DELETE Fields
	*
	* @access	public
	* @since	1.5
	*/
        
        public function deleteFields()
        {
            $app = JFactory::getApplication();
            $db	= & JFactory::getDBO();
            $arrays = JRequest::getVar("cid");
            $ids = "";
            foreach ($arrays as $obj)
            { 
                $query = 'DELETE FROM  #__fieldsattach_values WHERE articleid= '.$obj ;
                $db->setQuery($query);
                $db->query();
                $app->enqueueMessage( JTEXT::_("Delete fields of ID ") . $obj )   ;

            } 

        }
        
        /**
	* Function DELETE Fields
	*
	* @access	public
	* @since	1.5
	*/
        public function onContentBeforeDelete($context,  $article, $isNew)
        {

        }
     
        /**
	* Function DELETE Fields
	*
	* @access	public
	* @since	1.5
	*/

        public function onContentBeforeSave($context, $article, $isNew)
	{ 
			 
            //***********************************************************************************************
            //create array of fields  ****************  ****************  ****************
            //***********************************************************************************************
            //CATEGORIES ==============================================================
            $user =& JFactory::getUser();
            $option = JRequest::getVar("option","");
            $layout = JRequest::getVar('layout',""); 
            $view= JRequest::getVar('view',"");
			$fontend = false; 
            if( $option=='com_content' && $user->get('id')>0 &&  $view == 'form' &&  $layout == 'edit'  ) $fontend = true;
		            
            if (($option=='com_content' ))
                 {
		            $app = JFactory::getApplication();
					$db	= & JFactory::getDBO();
		            $nameslst = fieldsattachHelper::getfields($article->id);
		 
		            
		            $fields_tmp0 = fieldsattachHelper::getfieldsForAll($article->id);
		            $nameslst = array_merge($fields_tmp0, $nameslst );
		
		            $fields_tmp2 = fieldsattachHelper::getfieldsForArticlesid($article->id, $nameslst);
		
		            $nameslst = array_merge( $nameslst, $fields_tmp2 );
					
					$session = JFactory::getSession(); 
					
					 
					$error=false;
					
					
		            //Si existen fields relacionados se mira uno a uno si tiene valores
		            if(count($nameslst)>0){
		                foreach($nameslst as $obj)
		                {
		                	$query = 'SELECT   b.required ,b.title FROM #__fieldsattach_values as a INNER JOIN #__fieldsattach as b ON a.fieldsid = b.id WHERE a.articleid='.$article->id .' AND a.fieldsid ='. $obj->id ;
		                    //echo $query;
		                    
		                    $db->setQuery($query);
		                    $valueslst = $db->loadObject();
							
							$valor = JRequest::getVar("field_". $obj->id, '', 'post', null, JREQUEST_ALLOWHTML); 
							
							//Is required
							$query = 'SELECT a.title,  a.required, a.type,b.title as titlegroup FROM #__fieldsattach as a INNER JOIN #__fieldsattach_groups as b ON a.groupid	= b.id  WHERE  a.id ='. $obj->id ; 
		                    $db->setQuery($query); 
		                    $fields_row = $db->loadObject();  
							
							if(($fields_row->required) && (empty($valor) && ($fields_row->type != "imagegallery") ) )
							{
								$error_text = JText::sprintf('JLIB_FORM_VALIDATE_FIELD_REQUIRED', $fields_row->title." (". $fields_row->titlegroup.")");
									   
								if($fontend) {
									JError::raiseWarning( 100, $error_text );
								}else{
									$app->enqueueMessage( $error_text, 'error'   )   ;
									$error=true;
								}
								
							}
							
							//Save values or required fields
							//$session->set('field_'.$obj->id , $valor); 
							//Delete Session if all ok in fieldsHelper line 1010 
		                }
				}
				
				
				if($error){ 
		            return false;
				} 
				
				
                    //IF TITLE THEN ACTIVE CONTENT =========================================================================================
            
                    $db	= & JFactory::getDBO();
	            $user =& JFactory::getUser(); 
				 
				
	
	            //-----------------------
	            $query = 'SELECT  id  FROM #__content WHERE created_by='.$user->get('id').' AND title IS NOT NULL AND state  = -2 AND id='.$article->id;
	            $db->setQuery( $query );
	            $id = $db->loadResult(); 
	            
	            if(!empty($id))
	                {   
	                 $article->state = 1;
	                } 
			}
			 
            
    }
        
        /**
	* Save alls fields of cagtegory 
	*
        *  TODO : CLONE FUNCTIO FOR TRANSLATION
        *  TODO : ALL CATEGORIES
        * 
	* @access	public
	* @since	1.5
	*/
        public function onContentAfterSaveCategories($context,  $article, $isNew)
	{
             //Ver categorias del artículo ==============================================================
            //$idscat = $this->recursivecat($article->id);
            $this->str = fieldsattachHelper::recursivecat($article->id  );
           
            $db	= & JFactory::getDBO(); 
  /*
            $query = 'SELECT a.id, a.type, b.recursive, b.catid FROM #__fieldsattach as a INNER JOIN #__fieldsattach_groups as b ON a.groupid = b.id WHERE b.catid IN ('. $this->str .') AND a.published=1 AND b.published = 1 ORDER BY b.ordering, a.ordering,  a.title';
            $db->setQuery( $query );
            $nameslst = $db->loadObjectList();  
*/

            //***********************************************************************************************
            //Mirar cual de los grupos es RECURSIVO  ****************  ****************  ****************
            //***********************************************************************************************
            /*$cont = 0;
            foreach ($nameslst as $field)
            {
                //JError::raiseWarning( 100, $field->catid ." !=".$article->catid  );
                if( $field->catid != $article->id )
                {
                    //Mirar si recursivamente si
                    if(!$field->recursive)
                        {
                            //echo "ELIMINO DE LA LISTA " ;
                            unset($nameslst[$cont]);
                        }
                }
                $cont++;
            }*/
            //***********************************************************************************************
            //Create array of fields   ****************  ****************  ****************
            //***********************************************************************************************
            //$fields_tmp0 = fieldsattachHelper::getfieldsForAllCategory($article->id);
            //$nameslst = array_merge($fields_tmp0, $nameslst );


             $fields_tmp0 = fieldsattachHelper::getfieldsForAllCategory($article->id);
            $fields = fieldsattachHelper::getfieldsCategory($article->id);
            $nameslst = array_merge($fields_tmp0, $fields);
            
            //Si existen fields relacionados se mira uno a uno si tiene valores
            if(count($nameslst)>0){
                foreach($nameslst as $obj)
                {
                    $query = 'SELECT a.id , b.extras, b.visible FROM #__fieldsattach_categories_values as a INNER JOIN #__fieldsattach as b ON a.fieldsid = b.id WHERE a.catid='.$article->id .' AND a.fieldsid ='. $obj->id ;
                    //JError::raiseWarning( 100,   " --- ". $query   );

                    $db->setQuery($query);
                    $valueslst = $db->loadObject(); 
                    if(count($valueslst)==0)
                        {
                            //INSERT
                            $valor = JRequest::getVar("field_". $obj->id, '', 'post', 'string', JREQUEST_ALLOWHTML);
                            if(is_array($valor))
                            {
                                $valortxt="";
                                for($i = 0; $i < count($valor); $i++ )
                                {

                                      $valortxt .=  $valor[$i].", ";
                                }
                                $valor = $valortxt;
                            }
                            //INSERT
                            //$valor = str_replace('"','&quot;', $valor );
                            $valor = htmlspecialchars($valor);
                            $query = 'INSERT INTO #__fieldsattach_categories_values(catid,fieldsid,value) VALUES ('.$article->id.',\''.  $obj->id .'\',\''.$valor.'\' )     ';
                            $db->setQuery($query);
                            $db->query();

                            //Select last id ----------------------------------
                            $query = 'SELECT  id  FROM #__fieldsattach_categories_values AS a WHERE  a.catid='.$article->id.' AND a.fieldsid='.$obj->id;
                            //echo $query;
                            $db->setQuery( $query );
                            $result = $db->loadObject();
                            $valueslst->id = $result->id; 
							
							//Required 
							

                        }
                        else{
                            //UPDATE
                            $valor = JRequest::getVar("field_". $obj->id, '', 'post', 'string', JREQUEST_ALLOWHTML); 
                            if(is_array($valor))
                            {
                                $valortxt="";
                                for($i = 0; $i < count($valor); $i++ )
                                {
                                      $valortxt .=  $valor[$i].", ";
                                }
                                $valor = $valortxt;
                            }
                            //$valor = str_replace('"','&quot;', $valor );
                            $valor = htmlspecialchars($valor);
                            $query = 'UPDATE  #__fieldsattach_categories_values SET value="'.$valor.'" WHERE id='.$valueslst->id ;
                            $db->setQuery($query);
                            //JError::raiseWarning( 100, $query  );
                            $db->query();
                        }

                        //Acción PLUGIN ========================================================
                        JPluginHelper::importPlugin('fieldsattachment'); // very important
                        $query = 'SELECT *  FROM #__extensions as a WHERE a.element="'.$obj->type.'"  AND a.enabled= 1';
                        // JError::raiseWarning( 100, $obj->type." --- ". $query   );
                        $db->setQuery( $query );
                        $results = $db->loadObject();
                        if(!empty($results)){

                            $function  = "plgfieldsattachment_".$obj->type."::action( ".$article->id.",".$obj->id.",".$valueslst->id.");";
                            //  JError::raiseWarning( 100,   $function   );
                            eval($function);
                        }

                        //JError::raiseWarning( 100,   " IDDDD CATEGORIES: ". $obj->id   );
                        //TODO Insert in category text 
                         $this->insertinDescription($article->id, $obj->id, $valueslst->visible);
                }
            }



	    return true;

            
        }
         /**
	* Insert fields in categori description
	*
	* @access	public
	* @since	1.5
	*/
       public function resetToDescription($id, $fieldsid,$cadena)
        {
            //$patron ="/{\d+}/i";
           
            $patron = "{fieldsattach_".$fieldsid."}";
            $sustitucion="";
            $cadena = str_replace($patron, $sustitucion, $cadena);

            $cadena = str_replace("<p></p>", "", $cadena);


            return $cadena ;
        } 
        /**
		* Insert fields in categori description
		*
		* @access	public
		* @since	1.5
		*/
        public function insertinDescription($id, $fieldsid, $visible)
        {
            $db	= & JFactory::getDBO();
            $query = 'SELECT description  FROM #__categories as a WHERE a.id= '.$id ;

            //$patron ="/{\d+}/i";
            $patron = "{fieldsattach_".$fieldsid."}";
             
            //JError::raiseWarning( 100, "FIEL: ". $fieldsid." : ".$query  );
            //JError::raiseWarning( 100, "patron;  ".$patron  );
            $sustitucion = "";

            $db->setQuery( $query );
            $results = $db->loadObject();
            if(!empty($results)){
                $cadena = $results->description;

                $cadena = $this->resetToDescription($id, $fieldsid,$results->description); 
                 
            }

            if($visible==1) $cadena = $cadena . $patron;

            //JError::raiseWarning( 100, "cadna: ".$cadena );

            $query = 'UPDATE  #__categories SET description="'.$cadena.'" WHERE id='.$id ;
            $db->setQuery($query);
             //JError::raiseWarning( 100, $patron  );
            $db->query();
        }
 

        /**
	* Save alls fields of article
	*
	* @access	public
	* @since	1.5
	*/
	public function onContentAfterSave($context, $article, $isNew)
	{  
            
            $app = JFactory::getApplication();
            $user =& JFactory::getUser();
            $option = JRequest::getVar("option","");
            $layout = JRequest::getVar('layout',"");
            $extension = JRequest::getVar('extension',"");
            $view= JRequest::getVar('view',"");

            $sitepath = JPATH_BASE ;
            $sitepath = str_replace ("administrator", "", $sitepath); 
            $sitepath = JPATH_SITE;
             $fontend = false;
             if( $option=='com_content' && $user->get('id')>0 &&  $view == 'form' &&  $layout == 'edit'  ) $fontend = true;
             if(JRequest::getVar("a_id")>0) $fontend = true;

             //CATEGORIES ==============================================================
              if (($option=='com_categories' && $layout=="edit" && $extension=="com_content"  ))
                 {
                     $backendcategory = true;
                     $backend=true;
                     
                     $this->onContentAfterSaveCategories($context, $article, $isNew);
                     //$this->createDirectory($article->id); 
                 }

             //Crear directorio ==============================================================
            /* if (($option=='com_content' && $view=="article"   )||($fontend))
             {
                 $this->createDirectory($article->id); 
             }*/
 
             //============================================================================
            //COPY AND SAVE LIKE COPY
            if( (JRequest::getVar("id") != $article->id && (!empty( $article->id))   && ($article->id>0) && (JRequest::getVar("id")>0))  ){
                $oldid = JRequest::getVar("id")  ; 
               
                $this->copyArticle($article, $oldid); 
            }
             
            //END COPY AND SAVE============================================================================

            //Ver categorias del artículo ==============================================================
            //$idscat = $this->recursivecat($article->catid);
            /*fieldsattachHelper::recursivecat($article->catid, & $idscat);
            

            $query = 'SELECT a.id, a.type, b.recursive, b.catid FROM #__fieldsattach as a INNER JOIN #__fieldsattach_groups as b ON a.groupid = b.id WHERE b.catid IN ('. $idscat .') AND a.published=1 AND b.published = 1 ORDER BY a.ordering, a.title';
            $db->setQuery( $query );
            $nameslst = $db->loadObjectList();  

            //***********************************************************************************************
            //Mirar cual de los grupos es RECURSIVO  ****************  ****************  ****************
            //***********************************************************************************************
            $cont = 0;
            foreach ($nameslst as $field)
            {
                //JError::raiseWarning( 100, $field->catid ." !=".$article->catid  );
                if( $field->catid != $article->catid )
                {
                    //Mirar si recursivamente si
                    if(!$field->recursive)
                        {
                            //echo "ELIMINO DE LA LISTA " ;
                            unset($nameslst[$cont]);
                        }
                }
                $cont++;
            } 
             */
            
            if (($option=='com_content' && $layout=="edit" ) || $fontend)
                 {
            $db	= & JFactory::getDBO();
            $nameslst = fieldsattachHelper::getfields($article->id);
            
            

           // JError::raiseWarning( 100, "NUMEROOO: ". count($nameslst) ." - ".$article->catid );
            //***********************************************************************************************
            //create array of fields  ****************  ****************  ****************
            //***********************************************************************************************
            $fields_tmp0 = fieldsattachHelper::getfieldsForAll($article->id);
            $nameslst = array_merge($fields_tmp0, $nameslst );

            $fields_tmp2 = fieldsattachHelper::getfieldsForArticlesid($article->id, $nameslst);

            $nameslst = array_merge( $nameslst, $fields_tmp2 );
            
            //Si existen fields relacionados se mira uno a uno si tiene valores
            //JError::raiseWarning( 100, count($nameslst)  );
            if(count($nameslst)>0){
                foreach($nameslst as $obj)
                {
                    $query = 'SELECT a.id, b.required ,b.title, b.extras, b.type FROM #__fieldsattach_values as a INNER JOIN #__fieldsattach as b ON a.fieldsid = b.id WHERE a.articleid='.$article->id .' AND a.fieldsid ='. $obj->id ;
                    //echo $query;
                    
                    $db->setQuery($query);
                    $valueslst = $db->loadObject();
                    if(count($valueslst)==0)
                        {
                            //INSERT 
                            //$valor = JRequest::getVar("field_". $obj->id, '', 'post', null, JREQUEST_ALLOWHTML);
                             $valor = $_POST["field_". $obj->id]; 
                            if(is_array($valor))
                            {
                                $valortxt="";
                                for($i = 0; $i < count($valor); $i++ )
                                {

                                      $valortxt .=  $valor[$i].", ";
                                }
                                $valor = $valortxt;
                            }
                            
                            //GET TYPE
                            $query = 'SELECT type FROM  #__fieldsattach     WHERE  id ='. $obj->id ;
                            $db->setQuery($query);
                            $type = $db->loadResult();
                            
                            //remove vbad characters
                            //$valor = preg_replace('/[^(\x20-\x7F)]*/','', $valor);
                            
                            if($type == "listunits"){
                                  
                            }else{
                                 $valor = htmlspecialchars($valor);
                            }
                            
                            //TODO:: Transform data type for MYSQL SEARCH
                            if($type == "date")
                            {
                                $valor = strtotime( $valor );
                                $valor = date("Y-m-d",$valor);
                            }
                            
                            //*******************************
                            
                            //INSERT 
                            $query = 'INSERT INTO #__fieldsattach_values(articleid,fieldsid,value) VALUES ('.$article->id.',\''.  $obj->id .'\',\''.$valor.'\' )     ';
                            $db->setQuery($query);
                            $db->query();

                            //Select last id ----------------------------------
                            $query = 'SELECT  id  FROM #__fieldsattach_values AS a WHERE  a.articleid='.$article->id.' AND a.fieldsid='.$obj->id;
                            //echo $query;
                            $db->setQuery( $query );
                            $result = $db->loadObject();
                            $valueslst->id = $result->id; 
                            
                        }
                        else{
                            //UPDATE 
                           // $valor = JRequest::getVar("field_". $obj->id, '', 'post', null, JREQUEST_ALLOWHTML); 
                             $valor = $_POST["field_". $obj->id]; 
                             
                            if(is_array($valor))
                            { 
                                $valortxt="";
                                for($i = 0; $i < count($valor); $i++ )
                                { 
                                      $valortxt .=  $valor[$i].", ";
                                }
                                $valor = $valortxt;
                            }
                            
                            //remove vbad characters
                            //$valor = preg_replace('/[^(\x20-\x7F)]*/','', $valor);
                            
                            //$valor = str_replace('"','&quot;', $valor );
                            //$valor = htmlspecialchars($valor);
                            //Remove BAD characters ****
                            $valor = preg_replace('/border="*"*/','', $valor);
                            
                            if($valueslst->type == "listunits"){
                                  
                            }else{
                                 $valor = htmlspecialchars($valor);
                            }
                            
                            //TODO:: Transform data type for MYSQL SEARCH
                            if($valueslst->type  == "date")
                            {
                                 
                                $valor = strtotime( $valor );
                                $valor = date("Y-m-d",$valor);
                            }
                            //********************************
                            
                            $query = 'UPDATE  #__fieldsattach_values SET value="'.$valor.'" WHERE id='.$valueslst->id .' AND articleid='.$article->id ;
                            $db->setQuery($query); 
                            $db->query(); 
							
							 
						    
                        }

                        //Acción PLUGIN ========================================================
                        JPluginHelper::importPlugin('fieldsattachment'); // very important 
                        $query = 'SELECT *  FROM #__extensions as a WHERE a.element="'.$obj->type.'"  AND a.enabled= 1';
                        // JError::raiseWarning( 100, $obj->type." --- ". $query   );
                        $db->setQuery( $query );
                        $results = $db->loadObject();
                        if(!empty($results)){
                            
                            $function  = "plgfieldsattachment_".$obj->type."::action( ".$article->id.",".$obj->id.",".$valueslst->id.");";
                            //  JError::raiseWarning( 100,   $function   );
                            eval($function);
                        }
                }
                } 
            }

	    return true;
        }
 
        /**
	* Injects Insert Tags input box and drop down menu to adminForm
	*
	* @access	public
	* @since	1.5
	*/
	function onBeforeRender()
	{
			  
	}
        
	function cleandatabase()
	{
		$option = JRequest::getCmd('option', '');
                $view = JRequest::getCmd('view', '');

                //DELETE THE ARTICLES WITHOUT TITLE
                if ($option == 'com_content') 
                        //if ($option == 'com_content' ) 
                { 
                        $db = &JFactory::getDBO(  );
                        //$query = 'INSERT INTO #__content(title, catid, created_by, created, state) VALUES ("", '.$filter_category_id.', '.$user->get("id").',"'.$mysqldate.'", -2)     ';
                    //$query = 'DELETE FROM #__content WHERE title="" AND state= -2 ';
                                $query = 'DELETE FROM #__content WHERE title=""';
                                //echo "sss:".$query;
                                //JError::raiseWarning( 100,  JTEXT::_("DELETE:").$query   );
                    $db->setQuery($query);

                    $db->query(); 	
    		
		}  
	}
	/**
	* Injects Insert Tags input box and drop down menu to adminForm
	*
	* @access	public
	* @since	1.5
	*/
	function onAfterRender()
	{ 
             
                                
               //SELECT WHERE I AM ******
               if ( !JFactory::getApplication()->isAdmin()) {
                    $option = JRequest::getCmd('option', '');
                    $view = JRequest::getCmd('view', '');

                    if ($option == 'com_content'  && $view == 'category') {
                         // your processing code here
                         $body = JResponse::getBody();
                         //JResponse::setBody("saa");
                         //TODO WRITE A FIELDS TO CATEGORY EDIT CONTENT ========================
                         //$this->addFieldsInCategory();
                         $body = $this->onAfterRenderCategory($body);
                         return;
                    }
                }
 
                $body = JResponse::getBody();

                //$model = JController::getInstance("com_content");
                //$state = $model->getModel();
                //$dd = $state->getState("filter.category_id");
                // $state->state->get('filter.category_id'); 
                 
                 $writed = false;
                 $id = JRequest::getVar('id');
                 $str = '';
                 $str_options = '';
                 $exist=false;
                 $exist_options=false;
                 $idgroup= 0; 
                 $editor =& JFactory::getEditor();

                 

                 //EDIT ARTICLE =====================================================================
				 if (!$id)
				 {
                        $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
                        @$id = $cid[0];

                        $view = JRequest::getVar('view');
                        if ($view =='article') $path = '';
                        else $path = '..'.DS;
				 }
				 $task = JRequest::getVar('task');
				 $option= JRequest::getVar('option');
                 $id= JRequest::getVar('id', JRequest::getVar('a_id'));

                 $view= JRequest::getVar('view');
                 $layout= JRequest::getVar('layout');
		 
				 //$tagsList = $this->getTags($id, $option);
				 //$masterTagList = $this->getMasterTagsList(); // Added by Duane Jeffers
                 $pos = strrpos(JPATH_BASE, "administrator"); 

                 $user =& JFactory::getUser();
                //***********************************************************************************************
                //Where we are  ****************  ****************  ****************
                //***********************************************************************************************
                 $fontend = false; 
                 if( $option=='com_content' && $user->get('id')>0 &&  $view == 'form' &&  $layout == 'edit'  ) $fontend = true;
                 
                 $backend = false;
                 if( $option=='com_content' && !empty($pos) &&  $layout == 'edit') $backend = true;

                 $backendcategory = false;
                 if ((JRequest::getVar('option')=='com_categories' && JRequest::getVar('view')=="category"  && JRequest::getVar('extension')=="com_content"  )){
                     $backendcategory = true;
                     $backend=true;
                 }
				//EDIT FRONTEND
                 if(($fontend)&&($view == "form")){
                     //echo "el id".$id."<br>";
                     $id = JRequest::getVar( 'a_id');
                 }
                 
                 //***********************************************************************************************
                //If we are in admin content edit or frontend edit   ****************  ****************   
                //***********************************************************************************************
                 if (($backend ) || ( $fontend )  )
		 {
                       $body = str_replace('method="post"',   'method="post" enctype="multipart/form-data" ' , $body);
					   
					   //DELETE DE ERROR WRONG
					   //$body = str_replace('<li>Save failed with the following error: </li>',   '' , $body); 
					   $body .= '<style>.message ul li:last-of-type {display: none;} .message ul li:first-of-type {display: block;}</style>'; 
					   
					   //Plugin control for the no insert new rows ****************
					   //Author: giuppe
					   //**********************************************************
					   $oneclicksave = $this->params->def('oneclicksave', 1);
                       if($oneclicksave == 1){
					   
                         if(empty($id))
                         {
                             
                            //***********************************************************************************************
                            //If NEW Redirect  ****************  ****************
                            //***********************************************************************************************
 
                            if($backend && !$backendcategory ) {
                                $id = $this->getlastId();
                                if(!empty($id)){
                                    $url = JURI::base() ."index.php?option=com_content&task=article.edit&id=".$id;
                                    echo "<script>document.location.href='" .  ($url) . "';</script>\n";
                                    //header('Location: '.$url);
                                    //JApplication::redirect($url); 
                                }
                            }

                            if($fontend ) { 
                                $id = $this->getlastId();
                               // echo "aaaaaaaa : ".$id;
                                //$canEdit	= $this->item->params->get('access-edit');
                                if(!empty($id)){
                                  //index.php?option=com_content&view=form&layout=edit&a_id=3&Itemid=124
                                    //base64_encode($uri)
                                    //$uri = $_SERVER["HTTP_REFERER"];
                                    $user	= JFactory::getUser();
                                    $userId	= $user->get('id');
                                    $uri	= JFactory::getURI();
                                    //index.php?option=com_content&view=form&layout=edit&a_id=20&Itemid=130&return=aHR0cDovLzEyNy4wLjAuMS90ZXN0L3dlYjMvaW5kZXgucGhwP29wdGlvbj1jb21fY29udGVudCZ2aWV3PWNhdGVnb3J5JmxheW91dD1ibG9nJmlkPTImSXRlbWlkPTEzMA==
                                    //index.php?option=com_content&task=article.edit&a_id=20
                                    $uri = 'index.php?option=com_content&task=article.edit&a_id='.$id;
                                    $app	= JFactory::getApplication();
                                    $app->setUserState('com_content.edit.article.id',$id);
                                    $url = JURI::base() ."index.php?option=com_content&view=form&layout=edit&a_id=".$id."&Itemid=".JRequest::getVar("Itemid")."&return=".base64_encode($uri);
                                    //echo "URLLLLLLLLLLLLL ".$url;
                                    $button = JHtml::_('link',JRoute::_($url), "TESTO");


                                    echo "<script>document.location.href='" .  ($url) . "';</script>\n";
                                    //header('Location: '.$url);
                                    //JApplication::redirect($url);
                                } 
                            }

                         }
                         }
                          
                        //***********************************************************************************************
                        //create array of fields  ****************  ****************  ****************
                        //***********************************************************************************************
                        $fields = array();
                        if($backendcategory){
                            if(!empty($id)){
                                $fields_tmp0 = fieldsattachHelper::getfieldsForAllCategory($id);
                                $fields = fieldsattachHelper::getfieldsCategory($id);
                                $fields = array_merge($fields_tmp0, $fields);
                            }
                           
                        }else{
                               
                            $fields_tmp0 = fieldsattachHelper::getfieldsForAll($id);
                            //$fields_tmp1 = $this->getfields($id); 
                            $fields_tmp1 = fieldsattachHelper::getfields($id);
                            $fields_tmp1 = array_merge($fields_tmp0, $fields_tmp1);

                            
                            $fields_tmp2 = fieldsattachHelper::getfieldsForArticlesid($id, $fields_tmp1); 
                            $fields = array_merge($fields_tmp1, $fields_tmp2);
                            //$fields = $fields_tmp0;
                        }

                        //***********************************************************************************************
                        //create HTML  with new extra fields  ****************  ****************  ****************
                        //***********************************************************************************************

                        if(count($fields)>0){
                           $helper = new fieldsattachHelper();
                           $helper->body = $body;
                           $helper->str = $str;
                           $helper->str_options = $str_options;
                           //$helper->getinputfields($id, $fields, $backend, $fontend, $backendcategory, $exist_options, &$body,  &$str, &$str_options);
                           $helper->getinputfields($id, $fields, $backend, $fontend, $backendcategory, $exist_options);
                           $str =   $helper->str;
                           $str_options=   $helper->str_options;
                           $body =  $helper->body;
						   $exist =   $helper->exist;
						   $exist_options =   $helper->exist_options;
                          // fieldsattachHelper::getinputfields($id, $fields, $backend, $fontend, $backendcategory, $exist_options, &$body,  &$str, &$str_options);
                        }

                        //***********************************************************************************************
                        //WRITE HTML in page  ****************  ****************  ****************
                        //***********************************************************************************************
                        $pos = strrpos(JPATH_BASE, "administrator");
                        //echo "-------".$body."--------";
                        //echo "EXIST:: ".$exist_options;

                        if ( !empty($pos)  ){
                             //if(!empty($str)){
                            // descendant selector  
                            include('lib/phpQuery-onefile.php'); 
                            //include('lib/QueryPath-2.1.1/php/QueryPath/QueryPath.php'); 
                            //debajo ------------------
                             if(isset($exist) ) if(!$exist) $str="<p></p>";
                             if(isset($exist_options) ) if(!$exist_options) { $str_options="<p></p>"; };
                            // echo "---------".$str." -------------";
                         //   echo "---------".$str_options." -------------";
							//$str="<p></p>";
                             //=================================================================================================
                             //Special characters replace
                             //http://groups.google.com/group/support-querypath/browse_thread/thread/f1b156f791835b37?pli=1
                             //Bug contribution: G1boo
                             //=================================================================================================
                             //$options = array('replace_entities' => TRUE);
                             //$options = array('replace_entities' => TRUE, 'ignore_parser_warnings' => TRUE );
                             $options = array('replace_entities' => TRUE, 'ignore_parser_warnings' => TRUE );
                             //Load Document
                             $doc = phpQuery::newDocument($body);
                             phpQuery::selectDocument($doc);
                             //=================================================================================================
                            //Wrappers
                             
                             $str = '<div id="fieldsattach_footer">'.$str.'</div>';
                              
                             //REMOVE NOT ASCII CHARACTERS ****************************
                            // $str = preg_replace('/[^(\x20-\x7F)]*/','', $str);
                            // $str_options = preg_replace('/[^(\x20-\x7F)]*/','', $str_options);
                            
                             //INSERT FORM 
                             $pos = strrpos(  $body, 'id="access-rules' );
                             if ($pos === false) {                                 
                                 if($backendcategory)
                                    {
                                        //pq(':root form')->after($str);
                                        pq(':root .width-60 .adminform')->after($str);
                                         
                                        pq(':root #categories-sliders-'.JRequest::getVar("id"))->append("<span></span>".$str_options);
                                       // $body = qp($body, 'body', $options)->find(':root form')->after($str."<br /><br />")->find(':root #categories-sliders-'.JRequest::getVar("id"))->append($str_options) ;
                                    }else{
                                        //pq(':root form')->after($str);
                                         pq(':root .width-60 .adminform')->after($str);
                                        pq(':root #content-sliders-'.JRequest::getVar("id"))->append("<span></span>".$str_options);
                                        //$body = qp($body, 'body', $options)->find(':root form')->after($str."<br /><br />")->find(':root #content-sliders-'.JRequest::getVar("id"))->append($str_options) ;
                                        //$body = qp($body, 'body', $options)->find(':root form')->append($str."<br /><br />")->find(':root #content-sliders-'.JRequest::getVar("id"))->append($str_options) ;
                                   
                                 

                                    }
                                }else{
                                    if($backendcategory)
                                    {
                                        pq(':root #access-rules')->parent()->before($str);
                                        pq(':root #categories-sliders-'.JRequest::getVar("id"))->append("<span></span>".$str_options);
                                         //$body = qp($body, 'body', $options)->find(':root #access-rules')->parent()->before($str."<br /><br />")->find(':root #categories-sliders-'.JRequest::getVar("id"))->append($str_options) ;
                                    }else
                                    {   
                                        /*$rules = pq(':root #access-rules');
                                        if(!empty($rules)){ 
                                            pq(':root #access-rules')->parent()->before($str);
                                            //pq(':root .width-60 .adminform')->after($str);
                                        }else{
                                            //pq(':root .width-60 .adminform')->after($str);
                                        }*/
                                        pq(':root #access-rules')->parent()->before($str);
                                        pq(':root #content-sliders-'.JRequest::getVar("id"))->append("<span></span>".$str_options);
                                        //$body = qp($body, 'body', $options)->find(':root #access-rules')->parent()->before($str."<br /><br />")->find(':root #content-sliders-'.JRequest::getVar("id"))->append($str_options) ;
                                        //$body = qp($body, 'body', $options)->find(':root #access-rules')->parent()->before($str."<br /><br />")->find(':root #content-sliders-'.JRequest::getVar("id"))->append($str_options) ;
                                    }

                                }
                              
                             //EN las opciones ------------------
                              //$body->writeHTML();
                             //$body = $body->html();
                              //Return HTML
                              $body = pq('')->htmlOuter();
                              $writed=true;
                           //  }
                               
                   
                         }else
                         {
                            $finds = explode('</fieldset>', $body);
                            if(!empty ($id)) $finds[1] = $str.$finds[1];
                            $body = implode('</fieldset>', $finds); if ( !empty($pos)  ){  } 
                         }
                        
                        
		 } 
                 //Añadir   enctype="multipart/form-data"  
                JResponse::setBody($body);
		  //if(!$writed) JResponse::setBody($body);
                  //else JResponse::setBody("");
                  
                  
                

                

                
	}

        /**
	* Injects Insert Tags input box and drop down menu to adminForm
	*
	* @access	public
	* @since	1.5
	*/
	function onAfterRenderCategory()
	{
            $db = &JFactory::getDBO(  );
            $query = 'SELECT *  FROM #__extensions as a WHERE a.folder = "fieldsattachment"  AND a.enabled= 1';
            $db->setQuery( $query ); 
            $results_plugins = $db->loadObjectList();
            
            $body = JResponse::getBody();
            //echo "sssssssssssssssssssssssssssssssss:: ".count($results_plugins);
            $id = JRequest::getVar('id') ;
            if(!empty($id)){
                    $fields_tmp0 = fieldsattachHelper::getfieldsForAllCategory($id);
                    $fields = fieldsattachHelper::getfieldsCategory($id);
                    $fields = array_merge($fields_tmp0, $fields);
                }
            $idgroup = -1;
             
            if(count($fields)>0){
                 $exist = false;
                 //NEW
                 JPluginHelper::importPlugin('fieldsattachment'); // very important
                 foreach($fields as $field)
                    {
                        //select
                        foreach ($results_plugins as $obj)
                        {
                            $function  = "plgfieldsattachment_".$obj->element."::construct();";
                            $base = JPATH_BASE;
                            $base = str_replace("/administrator", "", $base);
                            $base = JPATH_SITE;
                            $file = $base.'/plugins/fieldsattachment/'.$obj->element.'/'.$obj->element.'.php';
                            
                            if( JFile::exists($file)){
                                //file exist
                                
                                eval($function);
                            }
                             
                            $i = count($this->array_fields); 
                            //$str .= "<br> ".$field->type." == ".$obj->element;
                            if (($field->type == $obj->element)&&($field->visible ))
                            {
                                $function  = "plgfieldsattachment_".$obj->element."::getHTML(".$id.",". $field->id.", true);";
                                //$sustitucion  = "<br> ".$function ;
                                // echo "<br>".$function;
                                eval("\$sustitucion   =".  $function."");
                               
                               // $str .= $function;
                            }
                        }
                         //echo "xxxxxxxxxx dd:".$idgroup;
                        // $body .=    $field->titlegroup.'sdddddddddddddddddddddddddddddddddddddddddddddddd<br>ss';
                        if(($field->visible )){
                            
                             $patron = "{fieldsattach_".$field->id."}"; 
                            // echo $patron;
                             $body = str_replace($patron, $sustitucion, $body) ;
                             $exist=true;
                             $idgroup = $field->idgroup;
                        }else{
                             $patron = "{fieldsattach_".$field->id."}"; 
                            // echo $patron;
                             $body = str_replace($patron, "", $body) ;
                              
                        }


                    } 
             }
            JResponse::setBody($body);
        }

        /**
	* Create a directory 
	*
	* @access	public
	* @since	1.5
	*/
        /*private function createDirectory($id)
        {
            $app = JFactory::getApplication(); 
            //JError::raiseWarning( 100, "CREAR DIR::: ".  $this->path .DS.  $id );
            if(!JFolder::exists($this->path .DS. $id))
             {
                //echo "<br >CREATE PATH __ : ".$this->path .DS.  $article->id;
                //
                if(!JFolder::create($this->path .DS.  $id))
                {
                    JError::raiseWarning( 100,   JTEXT::_("I haven't created:").$this->path .DS.  $id );
                }else
                {
                    $app->enqueueMessage( JTEXT::_("Folder created:").$this->path .DS. $id)   ;
                }
             } 
        }
*/
        /**
	* Get last id of content articles
	*
	* @access	public
	* @since	1.5
	*/

        private function  getlastId()
        {
        	
			$this->cleandatabase(); 
			
            $db	= & JFactory::getDBO();
            $user =& JFactory::getUser();
            $mysqldate = date( 'Y-m-d H:i:s' );
            
            //-----------------------
            $query = 'SELECT  id  FROM #__content WHERE created_by='.$user->get('id').' AND title= "" ';
             
             
            $db->setQuery( $query );
            $id = $db->loadResult(); 
 
            if(empty($id))
                { 
                //------------------
                //JController/getModel 
                $app = JFactory::getApplication();
                $filter_category_id = $app->getUserState('com_content.articles.filter.category_id');

                if(empty($filter_category_id)){
                    $body = JResponse::getBody();
                    $tmp = explode('name="jform[catid]" value="',$body);
                    if(count($tmp)>1)
                        {
                        $tmp = explode('"',$tmp[1]);
                        $filter_category_id = $tmp[0];
                    }
                   
                }

              if(empty($filter_category_id)){$filter_category_id = 1;}
                
                $valor = "";
                $query = 'INSERT INTO #__content(title, catid, created_by, created, state) VALUES ("", '.$filter_category_id.', '.$user->get("id").',"'.$mysqldate.'", 1)     ';
                $db->setQuery($query);

                $db->query(); 
                //-----------------------
                $query = 'SELECT  id  FROM #__content   ';
                $query .= ' order by id DESC '; 
                $db->setQuery( $query );
                $result = $db->loadObject();
                $id = $result->id;

                //Crear directorio ==============================================================
                //$this->createDirectory($id);
                }
            return $id;
        }

        /**
	* Get last id of content articles
	*
	* @access	public
	* @since	1.5
	*/

        private function  getcategorylastId()
        {
            $db	= & JFactory::getDBO();
            $user =& JFactory::getUser();
            $mysqldate = date( 'Y-m-d H:i:s' );

            //-----------------------
            $query = 'SELECT  id  FROM #__categories WHERE created_user_id='.$user->get(id).' AND title= "" ';

            //echo $query;
            $db->setQuery( $query );
            $id = $db->loadResult(); 
            if(empty($id))
                {
                $valor = "";
                $query = 'INSERT INTO #__categories(title, extension, created_user_id, created_time, published) VALUES ("", "com_content", '.$user->get("id").',"'.$mysqldate.'", 0)     ';
                $db->setQuery($query);
                $db->query();
               // echo "<br>".$query;
                //-----------------------
                $query = 'SELECT  id  FROM #__categories   ';
                $query .= ' order by id DESC ';
                //echo "<br>".$query;
                $db->setQuery( $query );
                $result = $db->loadObject();
                $id = $result->id;

                //Crear directorio ==============================================================
                //$this->createDirectory($id);
                }
            return $id;
        } 
        
        /**
	* Get list of fields to category
	*
	* @access	public
	* @since	1.5
	*/
        /*private function getfieldsCategory($catid)
        {

            $result = array();
            $db	= & JFactory::getDBO();
            $query = 'SELECT a.id, a.language FROM #__categories as a WHERE a.id='. $catid  ;
            $src="";

            $db->setQuery( $query );
            $elid = $db->loadObject();
            $idioma = $elid->language; 
            //$this->recursivecat($elid->id, "");
            fieldsattachHelper::recursivecat($elid->id, & $src);

            if(!empty($elid)){
                $db	= & JFactory::getDBO();

                $query = 'SELECT a.id as idgroup, a.title as titlegroup, a.description as descriptiongroup, a.position,  a.catid, a.language, a.recursive, b.* FROM #__fieldsattach_groups as a INNER JOIN #__fieldsattach as b ON a.id = b.groupid ';
                $query .= 'WHERE a.catid IN ('. $src .') AND a.published=1 AND b.published = 1 AND a.group_for = 1 ';
                //echo "Language: ".$query;
                if (  ($elid->language == $idioma ) ) {
                      $query .= ' AND (a.language="'.$elid->language.'" OR a.language="*" ) AND (b.language="'.$elid->language.'" OR b.language="*") ' ;
                      // echo "filter::". $app->getLanguageFilter();
                      // echo "filter::". JRequest::getVar("language");
                }
                 $query .='ORDER BY a.ordering, a.title, b.ordering';
                // echo $query;
                $db->setQuery( $query );
                $result = $db->loadObjectList();

                //**********************************************************************************************
                //Mirar cual de los grupos es RECURSIVO  ************************************************
                //***********************************************************************************************
                $cont = 0;
                foreach ($result as $field)
                {

                    if( $field->catid != $elid->id )
                    {
                        //Mirar si recursivamente si
                        if(!$field->recursive)
                            {
                                //echo "ELIMINO DE LA LISTA " ;
                                unset($result[$cont]);
                            }
                    }
                    $cont++;
                }
               // return $result;
            }
             return $result;

        }

*/
        /**
	* Get list of fields to content
	*
	* @access	public
	* @since	1.5
	*/
       /* private function getfields($id)
        {
             
            $result = array();
            $db	= & JFactory::getDBO(); 
            $query = 'SELECT a.catid, a.language FROM #__content as a WHERE a.id='. $id  ;
             
            $db->setQuery( $query );
            $elid = $db->loadObject();
            if(!empty($elid)){
            $idioma = $elid->language;

            $this->recursivecat($elid->catid, "");
            
            if(!empty($elid)){
                $db	= & JFactory::getDBO();

                $query = 'SELECT a.id as idgroup, a.title as titlegroup, a.description as descriptiongroup, a.position,  a.catid, a.language, a.recursive, b.* FROM #__fieldsattach_groups as a INNER JOIN #__fieldsattach as b ON a.id = b.groupid ';
                $query .= 'WHERE a.catid IN ('. $this->str .') AND a.published=1 AND b.published = 1 AND a.group_for = 0 ';
                //echo $elid->language."Language: ".$idioma;
                if (  ($elid->language == $idioma ) ) {
                      $query .= ' AND (a.language="'.$elid->language.'" OR a.language="*" ) AND (b.language="'.$elid->language.'" OR b.language="*") ' ;
                      // echo "filter::". $app->getLanguageFilter();
                      // echo "filter::". JRequest::getVar("language");
                }
                 $query .='ORDER BY a.ordering, a.title, b.ordering';
                 echo $query;
                $db->setQuery( $query );
                $result = $db->loadObjectList(); 

                //**********************************************************************************************
                //Mirar cual de los grupos es RECURSIVO  ************************************************
                //***********************************************************************************************
                $cont = 0;
                foreach ($result as $field)
                {
                    
                    if( $field->catid != $elid->catid )
                    {
                        //Mirar si recursivamente si
                        if(!$field->recursive)
                            {
                                //echo "ELIMINO DE LA LISTA " ;
                                unset($result[$cont]);
                            }
                    }
                    $cont++;
                }
                //return $result;
            }
            }

            return $result;
        }*/

        /**
	* Get value of one field content
	*
	* @access	public
	* @since	1.5
	*/
        /*public function getfieldsvalue($fieldsid, $articleid)
        {
            $result ="";
            $db	= & JFactory::getDBO();
            $query = 'SELECT a.value FROM #__fieldsattach_values as a WHERE a.fieldsid='. $fieldsid.' AND a.articleid='.$articleid  ;
            //echo $query;
            $db->setQuery( $query );
            $elid = $db->loadObject();
            $return ="";
            if(!empty($elid))  $return =$elid->value;
            return $return ;
        }*/

         /**
	* Get value of one field category
	*
	* @access	public
	* @since	1.5
	
        private function getfieldsvalueCategories($fieldsid, $catid)
        {
            $result ="";
            $db	= & JFactory::getDBO();
            $query = 'SELECT a.value FROM #__fieldsattach_categories_values as a WHERE a.fieldsid='. $fieldsid.' AND a.catid='.$catid  ;
            //echo $query;
            $db->setQuery( $query );
            $elid = $db->loadObject();
            $return ="";
            if(!empty($elid))  $return =$elid->value;
            return $return ;
        }
*/
        
         /**
	* Get value of one field content
	*
	* @access	public
	* @since	1.5
	*/
        private function getfieldsvaluearray($fieldsid, $articleid, $value)
        {
            $result ="";
            $db	= & JFactory::getDBO();
            $query = 'SELECT a.value FROM #__fieldsattach_values as a WHERE a.fieldsid='. $fieldsid.' AND a.articleid='.$articleid  ;
            //echo "<br>";
            $db->setQuery( $query );
            $elid = $db->loadObject();
            $return ="";
            if(!empty($elid))
            { 
                $tmp = explode(",",$elid->value); 
                foreach($tmp as $obj)
                {
                    $obj = str_replace(" ","",$obj);
                    $value = str_replace(" ","",$value);
                    //echo "<br>".$obj ."==". $value." -> ".strcmp($obj, $value)." (".strlen($obj).")";
                    if(strcmp($obj, $value) == 0)
                    {
                        //echo "SIIIIIIIIIIIIIIIII" ;
                        return true;
                    }
                }
            }
            return false ;
        }
        
        /**
	* recursive function
	*
	* @access	public
	* @since	1.5
	 
        function recursivecat($catid)
        {
             if(!empty($catid)){
                if(!empty($this->str)) $this->str .=  ",";
                $this->str .= $catid ;
                //echo "SUMO:".$str."<br>";
                $db	= & JFactory::getDBO();
                $query = 'SELECT parent_id FROM #__categories as a WHERE a.id='.$catid   ;
                //echo $query."<br>";
                $db->setQuery( $query );
                $tmp = $db->loadObject();
                
                if($tmp->parent_id>0) $this->recursivecat($tmp->parent_id);
             }
        }*/

        //IMAGE RESIZE FUNCTION FOLLOW ABOVE DIRECTIONS  
       /*
         private function resize($nombre,$archivo,$ancho,$alto,$id,$filter=NULL)
        {
            $path = JPATH_BASE ;
            $app = JFactory::getApplication();
             
            $arr1 = explode(".", $nombre );
            $tmp = $arr1[1];
             
            $nombre = $path."/".$this->path .DS. $id .DS. $nombre;
             $destarchivo = $this->path .DS. $id .DS. $archivo;
            $archivo =  $path."/".$this->path .DS. $id .DS. $archivo;
 
            //$app->enqueueMessage( JTEXT::_("Name file:  ").$nombre);
            //$app->enqueueMessage( JTEXT::_("New name:  ").$archivo);
             
            if(!file_exists($archivo)){
                JError::raiseWarning( 100, JTEXT::_("Not file exist ")  );
            }
            
            if (preg_match('/jpg|jpeg|JPG/',$archivo))
                {
                $imagen=imagecreatefromjpeg($archivo);
                }
            if (preg_match('/png|PNG/',$archivo))
                {
                $imagen=imagecreatefrompng($archivo);
                }
            if (preg_match('/gif|GIF/',$archivo))
                {
                $imagen=imagecreatefromgif($archivo);
                }
                
            $x=imageSX($imagen);
            $y=imageSY($imagen);
            if (!empty($ancho)) $w = $ancho; else $w = 0;
            if (!empty($alto)) $h = $alto; else $h = 0;

            $app->enqueueMessage( JTEXT::_("ORIGINAL: ")." width:".$x." height:".$y  );

            if($h > 0) { $ratio = ($y / $h); $w = round($x / $ratio);}
            else { $ratio = ($x / $w); $h = round($y / $ratio);}
 

            if(!empty($filter))
            {
                     
                    
                    if($filter =="IMG_FILTER_NEGATE") $filter = 0;
                    if($filter =="IMG_FILTER_GRAYSCALE") $filter = 1;
                    if($filter =="IMG_FILTER_BRIGHTNESS") $filter = 2;
                    if($filter =="IMG_FILTER_CONTRAST") $filter = 3;
                    if($filter =="IMG_FILTER_COLORIZE") $filter = 4;
                    if($filter =="IMG_FILTER_EDGEDETECT") $filter = 5;
                    if($filter =="IMG_FILTER_EMBOSS") $filter = 6;
                    if($filter =="IMG_FILTER_GAUSSIAN_BLUR") $filter = 7;
                    if($filter =="IMG_FILTER_SELECTIVE_BLUR") $filter = 8;
                    if($filter =="IMG_FILTER_MEAN_REMOVAL") $filter = 9;
                    if($filter =="IMG_FILTER_SMOOTH") $filter = 10;
                    if($filter =="IMG_FILTER_PIXELATE") $filter = 11;
                    if(imagefilter($imagen, $filter, 50))
                    { 
                        $app->enqueueMessage( JTEXT::_("Apply filter:").$filter  );
                    }  else {
                        JError::raiseWarning( 100,  JTEXT::_("Apply filter ERROR:").$filter   );
                    }
                    
            }

            // intentamos escalar la imagen original a la medida que nos interesa
            
             if(($w==0)||($h==0)) {$w=$x; $h=$y;}
            //$destino=ImageCreateTrueColor($w,$h);
             $app->enqueueMessage( JTEXT::_("IMAGE RESIZE: ")." width:".$w." height:".$h  );
            $destino = ImageCreateTrueColor($w,$h)
            or JError::raiseWarning( 100, JTEXT::_("Not created image  ")  );
            
            imagecopyresampled($destino,$imagen,0,0,0,0,$w,$h,$x,$y);

            if(!file_exists($archivo)){
                JError::raiseWarning( 100, JTEXT::_("Not file exist ")  );
            }else{ 
                //JFile::delete( $archivo );
                //$app->enqueueMessage( JTEXT::_("DELETE FILE   ").$archivo  );
            }

            $created = false;
            if (preg_match("/png/",$archivo))
                {
                $created = imagepng($destino,$archivo);
                }
            if (preg_match("/gif/",$archivo))
                {
                $created = imagegif($destino,$archivo);
                }
            else
                {
                $created = imagejpeg($destino,$archivo);
               
                }

             if($created){   $app->enqueueMessage( JTEXT::_("CREATE IMAGE OK   ").$archivo  );}
                else{JError::raiseWarning( 100, JTEXT::_("I can't create the image: ".$archivo)  );}
 
            imagedestroy($destino);
            imagedestroy($imagen);
        }
        * */
         
        /*
        private function get_extensions()
        {
            $array_fields  = array();
            $db = &JFactory::getDBO(  );
            $query = 'SELECT *  FROM #__extensions as a WHERE a.folder = "fieldsattachment"  AND a.enabled= 1';
            $db->setQuery( $query );

            $results = $db->loadObjectList();
            foreach ($results as $obj)
            {
                $array_fields[count($array_fields)] = $obj->element;
            }
            return $array_fields;
        } 
         */

        function addFieldsInCategory()
        {
             $body = JResponse::getBody();
             //echo "---------------------". $body;
             //$body = "";
        }
        
        
        /**
	* Copy the article with extrafields
	*
	* @access	public
	* @since	1.5
	*/
	public function copyArticle($article,$oldid)
        {
                //COPY AND SAVE LIKE COPY   
                $newid = $article->id;
                
                $db	= & JFactory::getDBO();
                //COPY __fieldsattach_values VALUES TABLE
                $query = 'SELECT * FROM #__fieldsattach_values as a  WHERE a.articleid = '. $oldid ;
                //echo "<br>".$query;
                $db->setQuery( $query );
                $results= $db->loadObjectList();
                if($results){
                    foreach ($results as $result)
                    {
                        $query = 'SELECT * FROM #__fieldsattach_values as a  WHERE a.articleid = '. $newid.' AND a.fieldsid='.$result->fieldsid ;
                        echo "<br>".$query;
                        $db->setQuery( $query );
                        $obj= $db->loadObject(); 
                        if(!empty($obj))
                        {
                             //update
                             //$query = 'UPDATE  #__fieldsattach_values SET value="'.$result->valor.'" WHERE id='.$result->id ;
                             //$db->setQuery($query);
                             //echo "<br>".$query;
                             //$db->query();
							 
                            
                        }else{
                            //insert
                             $query = 'INSERT INTO #__fieldsattach_values(articleid,fieldsid,value) VALUES ('.$newid.',\''.  $result->fieldsid .'\',\''.$result->value.'\' )     ';
                             $db->setQuery($query);
                             //echo "<br>".$query;
                             $db->query();
                        }
                        
                    }
                }
 

                //COPY  fieldsattach_images GALLERIES-----------------------------
                $query = 'SELECT * FROM #__fieldsattach_images as a  WHERE a.articleid = '. $oldid ;
                 
                $db->setQuery( $query );
                $results= $db->loadObjectList();
                if(count($results)>0){
                    foreach ($results as $result)
                    {
                        if(isset($result->fieldsid)){
                            $query = 'SELECT * FROM #__fieldsattach_images as a  WHERE a.articleid = '. $newid.' AND a.fieldsattachid='.$result->fieldsid ;
                            $db->setQuery( $query );
                            $obj= $db->loadObject();
                            if($obj)
                            {
                                //update
                                $query = 'UPDATE  #__fieldsattach_images SET image1="'.$result->title.'", image1="'.$result->image1.'", image2="'.$result->image2.'", image3="'.$result->image3.'", description="'.$result->description.'", ordering="'.$result->ordering.'", published="'.$result->published.'"  WHERE id='.$result->id ;
                                $db->setQuery($query);
                                $db->query();

                            }else{
                                //insert
                                $query = 'INSERT INTO #__fieldsattach_images(articleid,fieldsattachid,title,  image1, image2, image3, description, ordering, published) VALUES ('.$newid.',\''.  $result->fieldsattachid .'\',\''.$result->title.'\',\''.$result->image1.'\',\''.$result->image2.'\',\''.$result->image3.'\',\''.$result->description.'\',\''.$result->ordering.'\',\''.$result->published.'\' )     ';
                                $db->setQuery($query);
                                $db->query();
                            }
                        }
                    }
                }

                
                //copy documents and images
                $sitepath = JPATH_BASE ;
                $sitepath = str_replace ("administrator", "", $sitepath); 
                $sitepath = JPATH_SITE;
                //COPY  FOLDER -----------------------------
                $path= '..'.DS.'images'.DS.'documents';
                if ((JRequest::getVar('option')=='com_categories' && JRequest::getVar('layout')=="edit" && JRequest::getVar('extension')=="com_content"  )){
                     $path= '..'.DS.'images'.DS.'documentscategories';
                } 
                $app = JFactory::getApplication(); 
                $path = str_replace ("../", DS, $path);
                $source = $sitepath . $path .DS.  $oldid.DS;
                $dest = $sitepath.  $path .DS.  $newid.DS;
                //JFolder::copy($source, $dest);
                if(!JFolder::exists($dest))
                {
                    JFolder::create($dest);
                }

                $files =  JFolder::files($source);

                foreach ($files as $file)
                { 
                    if(Jfile::copy($source.$file, $dest.$file)) $app->enqueueMessage( JTEXT::_("Copy file ok:") . $file )   ;
                    else JError::raiseWarning( 100, "Cannot copy the file: ".  $source.$file." to ".$dest.$file );
                }
                
                //
                $app->enqueueMessage( JText::_("EXTRA FIELDS ADDED"), 'info'   )   ;
                

             
            //END COPY AND SAVE============================================================================

        }
        
         
       /* public function batch()
        {
            // Initialise variables.
		$input	= JFactory::getApplication()->input;
		$vars	= $input->post->get('batch', array(), 'array');
		$cid	= $input->post->get('cid', array(), 'array');
                
                 $app = JFactory::getApplication();
                 $db	= & JFactory::getDBO();
                

		// Build an array of item contexts to check
		$contexts = array();
		foreach ($cid as $id)
		{
			//echo "<br>ID:::".$id;
                        
                        $app->enqueueMessage( JTEXT::_("JLIB_APPLICATION_SUCCESS_BATCH") . $id )   ;
                        
                        $query = 'SELECT a.version,  a.hits, a.created, a.introtext, a.modified, a.state FROM #__content as a  WHERE  a.id ='. $id ; 
                        $db->setQuery($query); 
                        
                       // echo "<br>".$query;
                        
                        $row = $db->loadObject();  
                        
                        $version = $row->version;
                        $hits = $row->hits;
                        $created = $row->created;
                        $introtext = $row->introtext;
                        $modified = $row->modified;
                        $state = $row->state;
                         

                        
                        $query = 'SELECT a.id FROM #__content as a';
                        $query .=' WHERE ';
                        $query .=' version = "'.$version.'"';
                        $query .=' AND ';
                        $query .=' hits = "'.$hits.'"';
                        $query .=' AND ';
                        $query .=' created = "'.$created.'"';
                        $query .=' AND ';
                        $query .=' introtext = "'.$introtext.'"';
                        // endTime < DATE_SUB(CONVERT_TZ(NOW(), @@global.time_zone, 'GMT'), INTERVAL 30 MINUTE)
                        //$query .=' AND ';
                        //$query .=' modified >= DATE_SUB(CURDATE(),  INTERVAL 30 MINUTE)';
                        $query .=' AND ';
                        $query .=' state = "'.$state.'"';
                        $query .=' AND ';
                        $query .=' id > "'.$id.'"';
                        
                       // echo "<br>".$query;
                        
                        
                        $db->setQuery($query); 
                        $tmp = $db->loadObjectList();
                        
                        if(count($tmp)>0){
                        
                            $newid = $tmp[count($tmp)-1]->id;
                            //GET ARTICLE
                            if(!empty($newid))
                            {
                                $query = 'SELECT a.* FROM #__content as a  WHERE  a.id ='. $newid ; 
                                $db->setQuery($query);  

                                echo "<br><br>SSS: ".$query;

                                $article = $db->loadObject(); 
                                $oldid = $id;
                                $this->copyArticle($article, $oldid); 
                            }
                        }
                        
                        
                        //echo "<br>NEW ID:".$newid;
                        
                        
		}

		 
        }
        */


}
