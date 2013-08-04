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
    <!-- The following five lines load the Blueprint CSS Framework (http://blueprintcss.org). If you don't want to use this framework, delete these lines. -->
    <!--<link rel="stylesheet" href="<?php //echo $this->baseurl ?>/templates/<?php //echo $this->template ?>/css/blueprint/screen.css" type="text/css" media="screen, projection" /> -->
    <!-- <link rel="stylesheet" href="<?php //echo $this->baseurl ?>/templates/<?php //echo $this->template ?>/css/blueprint/print.css" type="text/css" media="print" /> -->
    <!--[if lt IE 8]><link rel="stylesheet" href="blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
    <!-- <link rel="stylesheet" href="<?php //echo $this->baseurl ?>/templates/<?php //echo $this->template ?>/css/blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection" />
    <link rel="stylesheet" href="<?php //echo $this->baseurl ?>/templates/<?php //echo $this->template ?>/css/blueprint/plugins/joomla-nav/screen.css" type="text/css" media="screen" /> -->

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
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/style-desktop.css" />
  <!--
    <link rel="stylesheet" href="css/5grid/core.css" />
    <link rel="stylesheet" href="css/5grid/core-desktop.css" />
    <link rel="stylesheet" href="css/5grid/core-1200px.css" />
    <link rel="stylesheet" href="css/5grid/core-noscript.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/style-desktop.css" />
  -->
    <!-- The following four lines load the Blueprint CSS Framework and the template CSS file for right-to-left languages. If you don't want to use these, delete these lines. -->
    <?php /*if($this->direction == 'rtl') : 
            <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/blueprint/plugins/rtl/screen.css" type="text/css" />
            <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template_rtl.css" type="text/css" />
     endif; */ ?>

    <!-- The following line loads the template JavaScript file located in the template folder. It's blank by default. -->
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/bootstrap.js"></script>
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/common.js"></script>
     <script type="text/javascript">
        // The no-conflict mode
        var $j = jQuery.noConflict();
    </script>
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/init.js?use=mobile,desktop,1000px&amp;mobileUI=1&amp;mobileUI.theme=none" ></script>
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery.dropotron-1.2"></script>
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php// echo $this->template ?>/js/init.js"></script>
   <noscript>
        <h2>Please enable Javascript.</h2>
        <!--<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/5grid/core.css" />
        <link rel="stylesheet" href="css/5grid/core-desktop.css" />
        <link rel="stylesheet" href="css/5grid/core-1200px.css" />
        <link rel="stylesheet" href="css/5grid/core-noscript.css" />
        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="css/style-desktop.css" /> -->
    </noscript>
</head>
<body class="left-sidebar">
    <!-- Div below is supports the facebook script tag.-->
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
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
                                        <jdoc:include type="modules" name="menu1_(top)" style="none" />
                                    </div>
                                    <div id="menu2">
                                        <jdoc:include type="modules" name="menu2_(top)" style="none" />
                                    </div>
                                    <div id="menu3">
                                        <jdoc:include type="modules" name="menu3_(top)" style="none" />
                                    </div>
                                    <div id="menu4">
                                        <jdoc:include type="modules" name="menu4_(top)" style="none" />
                                    </div>
                                    <div id="menu5">
                                        <jdoc:include type="modules" name="menu5_(top)" style="none" />
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

                                    <div class="leftmenu">
                                        <div class="plugins">
                                            <jdoc:include type="modules" name="header_(top)" style="none" />
                                        </div>
                                        <div class="plugins">
                                            <jdoc:include type="modules" name="header_(middle)" style="none" />
                                        </div>
                                        <div class="plugins">
                                            <jdoc:include type="modules" name="header_(right)" style="none" />
                                        </div>
                                        <div class="plugins2">
                                            <jdoc:include type="modules" name="header_(left)" style="none" />
                                        </div>
                                        <hr/>
                                         <div class="paypal">
                                            <jdoc:include type="modules" name="paypal_right" style="none" />
                                        </div>
                                    </div>

                                    

                                </div>
                            </div>
                            <div class="8u mobileUI-main-content">
                               <div id="content">

                                    <!-- Content -->

                                    <article>

                                        <span class="byline"><jdoc:include type="modules" name="breadcrumb_(left top)" style="none" /></span>
                                                  

                                            <div class="container">
                                                <div id="main">

                                                    <?php //if ($this->countModules('position-12')): 
                                                            //<div id="top"><jdoc:include type="modules" name="position-12"   />
                                                            //</div>
                                                    //<?php// endif; ?>

                                                    <!--<jdoc:include type="message" />-->
                                                    <jdoc:include type="component" />

						</div><!-- end main -->
                                            </div>
                                    </article>
		<!--<jdoc:include type="modules" name="debug" />-->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--
            // Wrapper Style commented 
            <div class="main-wrapper-style3">
                    <div class="inner">
                            <div class="5grid-layout 5grid">
                                    <div class="row">
                                            <div class="8u">

                                                    <!-- Article list -->
                                                    <!--
                                                            <section class="box-article-list">
                                                                    <h2 class="icon icon-news">From the blog</h2>

                                                                    <!-- Excerpt -->
                                                                    <!--
                                                                            <article class="box-excerpt">
                                                                                    <a href="#" class="image image-left"><img src="images/pic04.jpg" alt=""></a>
                                                                                    <div>
                                                                                            <header>
                                                                                                    <span class="date">December 20, 2012</span>
                                                                                                    <h3><a href="#">On the eve of the Mayanocalypse</a></h3>
                                                                                            </header>
                                                                                            <p>Phasellus quam turpis, feugiat sit amet ornare in, hendrerit in lectus 
                                                                                            semper mod quisturpis nisi consequat etiam lorem. Phasellus quam turpis, 
                                                                                            feugiat et sit amet ornare in, hendrerit in lectus semper mod quis eget mi dolore.</p>
                                                                                    </div>
                                                                            </article>

                                                                    <!-- Excerpt -->
                                                                    <!--
                                                                            <article class="box-excerpt">
                                                                                    <a href="#" class="image image-left"><img src="images/pic05.jpg" alt=""></a>
                                                                                    <div>
                                                                                            <header>
                                                                                                    <span class="date">December 15, 2012</span>
                                                                                                    <h3><a href="#">Life as a self-aware meme</a></h3>
                                                                                            </header>
                                                                                            <p>Phasellus quam turpis, feugiat sit amet ornare in, hendrerit in lectus 
                                                                                            semper mod quisturpis nisi consequat etiam lorem. Phasellus quam turpis, 
                                                                                            feugiat et sit amet ornare in, hendrerit in lectus semper mod quis eget mi dolore.</p>
                                                                                    </div>
                                                                            </article>

                                                                    <!-- Excerpt -->
                                                                    <!--
                                                                            <article class="box-excerpt">
                                                                                    <a href="#" class="image image-left"><img src="images/pic06.jpg" alt=""></a>
                                                                                    <div>
                                                                                            <header>
                                                                                                    <span class="date">December 12, 2012</span>
                                                                                                    <h3><a href="#">Facepalm moments in history</a></h3>
                                                                                            </header>
                                                                                            <p>Phasellus quam turpis, feugiat sit amet ornare in, hendrerit in lectus 
                                                                                            semper mod quisturpis nisi consequat etiam lorem. Phasellus quam turpis, 
                                                                                            feugiat et sit amet ornare in, hendrerit in lectus semper mod quis eget mi dolore.</p>
                                                                                    </div>
                                                                            </article>

                                                            </section>
                                            </div>
                                     -->
                                    <!--
                                            <div class="4u">

                                                    <!-- Spotlight -->
                                                    <!--
                                                            <section class="box-spotlight pad-left">
                                                                    <h2 class="icon icon-paper">Spotlight</h2>
                                                                    <article>
                                                                            <a href="#" class="image image-full"><img src="images/pic07.jpg" alt=""></a>
                                                                            <header>
                                                                                    <h3><a href="#">Why staplers matter</a></h3>
                                                                                    <span class="byline">They hold things together</span>
                                                                            </header>
                                                                            <p>Phasellus quam turpis, feugiat sit amet ornare in, hendrerit in lectus semper mod 
                                                                            quisturpis nisi consequat ornare in, hendrerit in lectus semper mod quis eget mi quat etiam 
                                                                            lorem. Phasellus quam turpis, feugiat sed et lorem ipsum dolor consequat dolor feugiat sed
                                                                            et tempus consequat etiam.</p>
                                                                            <p>Lorem ipsum dolor quam turpis, feugiat sit amet ornare in, hendrerit in lectus semper 
                                                                            mod quisturpis nisi consequat etiam lorem sed amet quam turpis.</p>
                                                                            <footer>
                                                                                    <a href="#" class="button button-alt button-icon button-icon-paper">Continue Reading</a>
                                                                            </footer>
                                                                    </article>
                                                            </section>

                                            </div>
                                     -->
                                     <!--
                                    </div>
                            </div>
                    </div>
            </div>
        
        -->
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
  <script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>  
</body>
</html>
