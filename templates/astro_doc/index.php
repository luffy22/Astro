<?php
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/* The following line loads the MooTools JavaScript Library */
//JHtml::_('behavior.framework', true);

//Import filesystem libraries. Not mandatory but in other templates
jimport('joomla.filesystem.file');
//JHtml::_('bootstrap.framework');
//JHtml::_('jquery.framework');
/* The following line gets the application object for things like displaying the site name */
$app = JFactory::getApplication();
?>
<?php echo '<?'; ?>xml version="1.0" encoding="<?php echo $this->_charset ?>"?>
<!DOCTYPE HTML>
<!--
	ZeroFour 1.0 by HTML5 Up!
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
    <!-- The following JDOC Head tag loads all the header and meta information from your site config and content. -->
    <jdoc:include type="head" />
    
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,800" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript">
        // The no-conflict mode
        jQuery.noConflict();
    </script>
    <!-- The following line loads the template CSS file located in the template folder. -->
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap-responsive.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/5grid/core-desktop.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/5grid/core-1200px.css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/5grid/core-1000px.css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/5grid/core.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/5grid/core-noscript.css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/style-1000px.css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/style.css" />
 
    <!-- The following line loads the template JavaScript file located in the template folder. It's blank by default. -->
    
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/bootstrap.js"></script>
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/common.js"></script>
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/init.js?use=mobile,desktop,1000px&amp;mobileUI=1&amp;mobileUI.theme=none" ></script>
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery.dropotron-1.2.js"></script>
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/init.js"></script>
   <noscript>
        <h2>Please enable Javascript.</h2>
   </noscript>
</head>
<body class="left-sidebar">
    <!-- Div below is supports the facebook script tag.-->
    <!-- Header Wrapper -->
    <div id="header-wrapper">
        <div class="5grid-layout">
            <div class="row">
                <!--<div class="12u">-->
           
                <!-- Header -->
                  <header id="header">
                       <div class="inner">
                            
                        <!-- Logo -->
                          <!-- <h1><a href="#" class="mobileUI-site-name">Astro Doctor</a></h1>-->

                <!-- Nav -->
                           <!-- <nav id="nav" class="mobileUI-site-nav">-->
                                <div id="header_menu" class="mobileUI-site-name">
                                    <div id="menu1">
                                        <jdoc:include type="modules" name="topmenu1" style="none" />
                                    </div>
                                    <div id="menu2">
                                        <jdoc:include type="modules" name="topmenu2" style="none" />
                                    </div>
                                    <div id="menu3">
                                        <jdoc:include type="modules" name="topmenu3" style="none" />
                                    </div>
                                    <div id="menu4">
                                        <jdoc:include type="modules" name="topmenu4" style="none" />
                                    </div>
                                    <div id="menu5">
                                        <jdoc:include type="modules" name="topmenu5" style="none" />
                                    </div>
                                </div>
                           <!-- </nav>-->

                        </div>
                  </header>
                <!-- Banner -->
                 <!--   <div id="banner">
                        <h2>Banner Goes Here</h2>
                        
                    </div>-->
               </div>
            </div>
        </div>
    </div>
    <!-- Main Wrapper -->
    <div id="main-wrapper">
        <div class="main-wrapper-style2">
            <div class="inner">
                <div class="5grid-layout 5grid">
                    <div class="row">
                        <div class="4u">
                                <div id="sidebar">

                            <!-- Sidebar -->

                                    <div class="social_plugs">
					<div class="plugin_title">
						Recommend Us
					</div>
                                              <jdoc:include type="modules" name="social plugins" style="none" />
                                    </div>
				    <!--<hr/>
                                         <div class="paypal">
                                            <jdoc:include type="modules" name="paypal_right" style="none" />
                                        </div>
                                    -->
                                    <div class="loginform">
                                               <jdoc:include type="modules" name="login" style="none" />
                                    </div>
                                </div>
                            </div>
                            <div class="8u mobileUI-main-content">
                               <div id="content">

                                    <!-- Content -->

                                    <article>

                                        <span class="byline"><jdoc:include type="modules" name="breadcrumb" style="none" /></span>
                                                  

                                            

                                                    <?php //if ($this->countModules('position-12')): 
                                                            //<div id="top"><jdoc:include type="modules" name="position-12"   />
                                                            //</div>
                                                    //<?php// endif; ?>

                                                    <!--<jdoc:include type="message" />-->
                                                    <jdoc:include type="component" />

						
                                    </article>
		<!--<jdoc:include type="modules" name="debug" />-->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
    </div>

    <div id="footer-wrapper">
        <footer id="footer" class="5grid-layout 5grid">
            <div class="row">
                    <div class="12u">
                            <div id="copyright">
                                   2013 Astro Doctor | Images: <a href="http://fotogrph.com/">Fotogrph</a> + <a href="http://iconify.it/">Iconify.it</a> | Designer: <a href="https://www.facebook.com/luffy.mugiwara.58173" title="Facebook Profile">Luffy Mugiwara</a> | Developer: <a href="http://www.linkedin.com/profile/view?id=246015976&trk=hb_tab_pro_top" title="Linkedin Profile">Luffy Mugiwara</a>
                            </div>
                    </div>
            </div>
        </footer>
</div>
     <!--
     // This works fine. But without proper design so commented
    <div class="loginform">
        <jdoc:include type="modules" name="breadcrumb" style="none" />
    </div>
    <div class="leftmenu">
        <jdoc:include type="modules" name="left" style="none" />
    </div>
    -->
<div id="fb-root"></div>
    <script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
  <script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>  
</body>
</html>
