<?php
/*
 * $Id$
 *	v1.0.4
 * Friday Sep-02-2011
 * @component JooComments
 * @copyright Copyright (C) Abhiram Mishra www.bullraider.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
jimport ( 'joomla.application.component.model' );

class plgContentJooComments extends JPlugin
{
	function plgContentJooComments( &$subject, $config )
	{
		parent::__construct( $subject, $config );
		//incase language strings are not available do load the default en-GB
		$jlang =& JFactory::getLanguage();
		$jlang->load('com_joocomments', JPATH_SITE, 'en-GB', true);
		$jlang->load('com_joocomments', JPATH_SITE, $jlang->getDefault(), true);
		$jlang->load('com_joocomments', JPATH_SITE, null, true);
		$this->loadLanguage('com_joocomments',JPATH_SITE);
	}

	//display top of content
	function onContentAfterDisplay($context, &$row, &$params, $page=0){
		$app = JFactory::getApplication();
		$component = JComponentHelper::getComponent( 'com_joocomments' );
		if ( $app->isAdmin() ) { return; }
		if(!file_exists(JPATH_SITE.DS.'components'.DS.'com_joocomments'.DS.'joocomments.php')){
			return '';
		}
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd("view");
		if($option != "com_content" ){
			return '';
		}
		$var=$row->params->toArray();
		$isCommentsEnabled=array_key_exists('comments_enabled', $var);
		$isCommentsEnabled=$isCommentsEnabled ?($var['comments_enabled']=='0'?false:true): !$isCommentsEnabled;

		if($isCommentsEnabled==false){
			return '';
		}
		if($view == "article"){
			$isCommentsAllowed=array_key_exists('comments_allowed', $var);
			$isCommentsAllowed=$isCommentsAllowed ? ($var['comments_allowed']=='0'?false:true):$isCommentsAllowed;
			$captchaEnabled=$component->params->get( 'captcha_enabled', '0' );
			$this->commentDisplay($row,$isCommentsAllowed,$captchaEnabled);
		}
		if(($view=="featured" && $component->params->get( 'frontend-comment_feature_link', '0' )==1)
			|| ($view=="category" && $component->params->get( 'frontend-comment_category_link', '0' )==1)){
				$vtm=JRegistryFormatJSON::getInstance('json');
						$ctm=$vtm->stringToObject($row->attribs);
						$isCtsEnabled=isset($ctm->comments_enabled)?($ctm->comments_enabled=='0'?0:1): 1;
						$isCtsAllowed=isset($ctm->comments_allowed)?($ctm->comments_allowed=='0'?0:1):0;
						if($isCtsEnabled)
						$this->addLink($view,$row);
				
		}
	}
	function addLink($view,$row){
			$component = JComponentHelper::getComponent( 'com_joocomments' );
			$captchaEnabled=$component->params->get( 'captcha_enabled', '0' );
			$vari='';
			$link=JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catid));
			if($component->params->get( 'frontend-comment_count_in_link', '0' )==1){
			if(file_exists(JPATH_SITE.DS.'components'.DS.'com_joocomments'.DS.'models'.DS.'commentpage.php')){
				require_once JPATH_SITE.DS.'components'.DS.'com_joocomments'.DS.'models'.DS.'commentpage.php';
				$s=new  JooCommentsModelCommentpage();
				$vari=' ('. count($s->retriveComments($row->id)).')';
			}
			}
			$row->introtext=$row->introtext.'<p class=\'readmore\'><a href=\''.$link.'#comment-wrapper'.'\'>'.JText::_('COM_JOOCOMMENTS_LINK_NAME_VALUE').$vari.'</a></p>';
	
	}
	function commentDisplay(&$article,$isCommentsAllowed,$captchaEnabled)
	{
		$view = JRequest::getVar('view');
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		//make sure the mootools-more.js is loaded
		JHtml::_('behavior.framework',true);
		$doc = JFactory::getDocument();

		require_once dirname(__FILE__).DS.'helpers'.DS.'Helper.php';
		if(file_exists(JPATH_SITE.DS.'components'.DS.'com_joocomments'.DS.'joocomments.php')){
			if ( $app->isAdmin() ) { return; }
			ob_start();
			?>

<div id="comment-wrapper"></div>
<?php if($isCommentsAllowed==true) echo JText::_('COM_JOOCOOMENTS_NEW_COMMENTS_DISABLED').'<div id="comments"></div>';?>
			<?php
			//need to add these stylesheet in the header,Which JHTML:stylesheet() will do,
			JHTML::stylesheet(JURI::root().'components/com_joocomments/assets/css/main.css');
			JHTML::stylesheet(JURI::root().'components/com_joocomments/assets/css/wmd.css');
			JHTML::stylesheet(JURI::root().'components/com_joocomments/assets/css/message.css');
			?>
<script
	src='<?php echo JURI::root().'components/com_joocomments/assets/js/showdown.js';?>'
	type='text/javascript'></script>
<script language="javascript" type="text/javascript"><!--
            //<![CDATA[
			// version 1.0.4
		   	var currentLanguage='<?php echo Helper::languageString($doc->language);?>';
            var arr=Locale.list().toString();
            var tempUrl='';
            var waitTitle='<?php echo JText::_('COM_JOOCOMMENTS_WAITING_TITLE');?>';
            var waitMessage='<?php echo JText::_('COM_JOOCOMMENTS_WAITING_MESSAGE');?>';
            
            
           // check if current language is already available in Locale
           if(arr.indexOf(currentLanguage)==-1){
               <?php
                       echo 'Locale.define(\''.Helper::languageString($doc->language).'\', \'FormValidator\', {';
                       echo 'required: \''.JText::sprintf('COM_JOOCOOMENTS_COMMENTS_FORM_FIELD_REQUIRED_ERROR').'\',';
                       echo 'email: \''.JText::sprintf('COM_JOOCOOMENTS_COMMENTS_FORM_FIELD_EMAIL_WRONG_ERROR').'\',';
                       echo 'errorPrefix: \''.JText::sprintf('COM_JOOCOOMENTS_COMMENTS_FORM_FIELD_ERROR_PREFIX').'\'';
                       echo '});'
                       ?>
           }        
	   		Locale.use('<?php echo Helper::languageString($doc->language);?>');
            var article_id="<?php echo $article->id; ?>";
            var urlCurrentPage=window.location;
        	var valCU=urlCurrentPage.toString().indexOf("://www.", 0);
        	tempUrl = "<?php echo JURI::base(); ?>index.php?option=com_joocomments";
			var valTU=tempUrl.toString().indexOf("://www.",0);
        	if((valCU==4 || valCU==5) && valTU <4 ){tempUrl=tempUrl.replace("://","://www.");}
            if(valCU<4 && (valTU==4 || valTU==5)){tempUrl=tempUrl.replace("://www.","://");}
            var paImg='';
            window.addEvent("load", function() {
            	initialize(tempUrl);
            	paImg='<?php echo JURI::root().'components/com_joocomments/assets/img/' ?>';
            	var myImages = Asset.images([paImg+'okMedium.png',paImg+'cautionMedium.png',paImg+'blackWaiter.gif']);
                //comments are not closed ie 0 then only load.
            	<?php  if($isCommentsAllowed==false){ ?>
            	
               //	url = "<?php echo JURI::base(); ?>index.php?option=com_joocomments&random=" + Math.random();     
               	document.id('comment-wrapper').set('html', '<?php  echo '<img src="'.JURI::root().'components/com_joocomments/assets/img/spinner.gif'.'" />'.JText::_('COM_JOOCOOMENTS_COMMENTS_LIST_LOADING');?>');      	            	        
				var req = new Request({
					 method: 'get',
                         url: tempUrl+"&random=" + Math.random(),                         
                         onComplete: function(text) { 
						document.id('comment-wrapper').innerHTML = "" + text;	
						loadTextEditor();																	 						
                        }						
					}).send();		
				<?php }else{?>
				showComments();
				<?php }?>			 							
            	});	
            <?php  if($isCommentsAllowed==false){ ?>
            function loadTextEditor(){
                wmd_options.ajaxForm=true;   
            	Attacklab.wmd();
            	Attacklab.wmd_defaults={buttons:"<?php echo $this->getConfiguredButtons(); ?>"};
            	Attacklab.wmdBase();
            	Attacklab.loadEnv();
            	Attacklab.Util.startEditor();
                var myForm = document.id('myForm');
                myForm.action=tempUrl+"&task=postComment";
            // Labels over the inputs.
            myForm.getElements('[type=text], textarea').each(function(el){
                new OverText(el);
            });
            // Validation.
            var formValidator=new Form.Validator.Inline(myForm,{evaluateFieldsOnBlur:false,
                												evaluateFieldsOnChange:false,
                												onFormValidate:function(s,ele,on){
																<?php if($captchaEnabled=='0' || ($captchaEnabled=='2' && $user->guest)){?>refreshCaptcha();
																document.id('captchaText').value="";
																<?php }?>		
																if(sp!=null)sp.hide(true);										
																}
																}																
														);
           <?php if($captchaEnabled=='0' || ($captchaEnabled=='2' && $user->guest)){?>
            formValidator.add('captchaValidator',{errorMsg:'<?php echo JText::sprintf('COM_JOOCOOMENTS_COMMENTS_FORM_CAPTCHA_ERROR');?>',
                									  test:function(field){
				  									return validateCaptcha(field);
				  									  }
			  									  }
			  				);<?php }?>

            // Ajax (integrates with the validator).
            new Form.Request(myForm,null, {
            	onComplete:function(){showComments();showEffect(arguments);<?php if($captchaEnabled=='0' || ($captchaEnabled=='2' && $user->guest)){?>refreshCaptcha();<?php }?>if(sp!=null)sp.hide(true);}
                ,async:false,
                requestOptions: {'spinnerTarget': 'progress'},
                extraData: {'article_id': article_id}
             	});
            	showComments();
            }<?php }?>
            function showComments(){
            	var parameter="&article_id="+article_id;
            	var htmlRequest = new Request.HTML({url: tempUrl+'&task=showComments&view=commentpage'+parameter,
            		onRequest: function(){
            		document.id('comments').set('html', '<?php  echo '<img src="'.JURI::root().'components/com_joocomments/assets/img/spinner.gif'.'"/>'.JText::_('COM_JOOCOOMENTS_COMMENTS_LIST_LOADING');?>');
                },
                onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript){
                	document.id('comments').empty();
                    document.id('comments').innerHTML=responseHTML;
                    
                    <?php if($captchaEnabled=='0' || ($captchaEnabled=='2' && $user->guest)){?> refreshCaptcha();<?php }?>
            	}}).send();
            }
				//]]>						
				--></script>
<script
	src='<?php echo JURI::root().'components/com_joocomments/assets/js/wmd.js';?>'
	type='text/javascript'></script>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			$pagination='';
			if (!empty($article->pagination) AND $article->pagination AND $article->paginationposition AND!$article->paginationrelative){
				$pagination=$article->pagination;
				$article->pagination='';
				}
            $article->text=$article->text.$pagination.$output.$this->dynamicCss().'<center>Powered by <a href="http://www.bullraider.com" title="bullraider">Bullraider.com</a></center>';
			JHTML::script(JURI::root().'components/com_joocomments/assets/js/message.js');
            JHTML::script(JURI::root().'components/com_joocomments/assets/js/main.js');
			return true;
		}else{
			return false;
		}
	}
	function getConfiguredButtons(){
		$component = JComponentHelper::getComponent( 'com_joocomments' );
		$originalList=array('bold', 'italic', 'link','blockquote', 'code', 'image', 'ol', 'ul', 'heading', 'hr');
		$optionList=array('wmd-bold-button','wmd-italic-button','wmd-link-button','wmd-quote-button','wmd-code-button','wmd-image-button','wmd-olist-button','wmd-ulist-button','wmd-heading-button','wmd-hr-button');
		$buttonValues=array();
		for($j=0;$j<=9;$j++){
			if($component->params->get(str_replace("-", "_", $optionList[$j]),'1')=='1'){
			$buttonValues[$j]=$originalList[$j];
			}
		}
		return implode(" ", $buttonValues);
	}
	function dynamicCss(){
			$component = JComponentHelper::getComponent( 'com_joocomments' );
			$arr=array('wmd-bold-button','wmd-italic-button','wmd-spacer1','wmd-link-button','wmd-quote-button','wmd-code-button','wmd-image-button','wmd-spacer2','wmd-olist-button','wmd-ulist-button','wmd-heading-button','wmd-hr-button','wmd-spacer3','wmd-undo-button','wmd-redo-button' );
			$buttonValues=array();
			for($j=0;$j<13;$j++){
				$buttonValues[$arr[$j]]=$component->params->get(str_replace("-", "_", $arr[$j]),'1');
				if($j==2){
					if($buttonValues['wmd-bold-button']=='1' || $buttonValues['wmd-italic-button']=='1'){
						$buttonValues[$arr[2]]='1';
					}else{
						$buttonValues[$arr[2]]='0';
					}
				}
				if($j==7){
					if($buttonValues['wmd-link-button']=='1' || $buttonValues['wmd-quote-button']=='1' ||$buttonValues['wmd-code-button']=='1' ||$buttonValues['wmd-image-button']=='1'){
						$buttonValues[$arr[7]]='1';
					}else{
						$buttonValues[$arr[7]]='0';
					}
				}
				if($j==12){
				if($buttonValues['wmd-olist-button']=='1' || $buttonValues['wmd-ulist-button']=='1' ||$buttonValues['wmd-heading-button']=='1' ||$buttonValues['wmd-hr-button']=='1'){
				$buttonValues[$arr[12]]='1';
			}else{
				$buttonValues[$arr[12]]='0';
				}
			}
			}
			$str='';
			$counter=0;
			//count the number of buttons enabled
			for($i=0;$i<15;$i++){
				if($i<12){
				if($buttonValues[$arr[$i]]=='1'){
				$str.='#wmd-button-bar #'.$arr[$i].'{left: '.($counter*25).'px !important;}';
				$counter++;
				}}else{
				$str.='#wmd-button-bar #'.$arr[$i].'{left: '.($counter*25).'px !important;}';
				$counter++;
				}
			}
			return '<style>'.$str.
			'</style>';
	}
}