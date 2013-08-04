<?php
/*
 * v1.0.0
 * Friday Sep-02-2011
 * @component JooComments
 * @copyright Copyright (C) Abhiram Mishra www.bullraider.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal','a.modal');?>
<script
	src="components/com_joocomments/assets/js/sl_slider_min.js"></script>
<script>
window.addEvent('domready', function() {
    
    //slider variables for making things easier below
    var itemsHolder = $('jooAddcontainer');
    var myItems = $$(itemsHolder.getElements('.itemJoo'));

    var theControls = $('controls1');
	var thePlayBtn = $(theControls.getElement('.play_btn'));
	var thePrevBtn = $(theControls.getElement('.prev_btn'));
	var theNextBtn = $(theControls.getElement('.next_btn'));
	
   
    //create instance of the slider, and start it up       
    var mySlider = new SL_Slider({
        slideTimer: 4000,
        orientation: 'horizontal',
        fade: false,
        isPaused: false,
        container: itemsHolder,
        items: myItems,
        playBtn: thePlayBtn,
		prevBtn: thePrevBtn,
		nextBtn: theNextBtn
    });
    mySlider.start();          
});
</script>

<style type="text/css">
.itemJoo {
	display: block;
}

#controls1{
	    background: none repeat scroll 0 0 white;
    border: 1px solid #CCCCCC;
    float: right;
    margin: 0px 15px;
	padding: 0px 15px;
    position: relative;
    width: 35%;
}
#jooAddcontainer {
	background: none repeat scroll 0 0 white;
	float: right;
	height: 155px;
	width: 35%;
	position: relative;
	margin: 15px 15px 0px 15px;
	padding: 15px 15px 0px 15px;
	border: 1px solid #CCCCCC;
}

.icon-48-frontpage {
	background:
		url("components/com_joocomments/assets/icon-48-frontpage.png")
}

p.error {
	color: red !important;
	font-weight: bold;
}

p.success {
	color: green !important;
	font-weight: bold;
}
.prev_btn,.play_btn,.next_btn{
float:left;cursor: pointer;padding-right:12px;
}

</style>

<div class="cpanel-left">
<div
	style="border: 1px solid #ccc; background: #fff; margin: 15px; padding: 15px">
<div style="float: right; margin: 10px;"><?php echo JHTML::_('image', 'administrator/components/com_joocomments/assets/joocomments_logo.png', 'bullraider.com' );?>
</div>
<h3>Current Version:</h3>
<p><?php echo $this->curVersion;?></p>
<p><?php echo $this->message;?></p>
<h3>Copyright:</h3>
<p>&copy; 2009 - 2011 Bullraider.com,Abhiram Mishra</p>
<h3>License</h3>
<p><a target="_blank" href="http://www.gnu.org/licenses/gpl-2.0.html">GPLv2</a>
<p><a target="_blank" href="http://www.bullraider.com/">www.bullraider.com</a></p>
<h3>F.A.Q and Troubleshooting</h3>
<p><a target="_blank"
	href="http://www.bullraider.com/joomla/extensions/joocomments/faq-joocomments/">Click
here</a></p>
<h3>Report problem</h3>
<p><a target="_blank"
	href="http://www.bullraider.com/joomla/extensions/joocomments/submit-bugs/">Report
Bug on bullraider.com</a> or Write a quick <a class="modal"
	rel="{handler: 'iframe', size: {x: 620, y: 370}}"
	href="index.php?option=com_joocomments&view=mail&layout=modal&tmpl=component&header=Send bug details to author&name=Bullraider&toMail=bullraider@gmx.com&title=Bug Description">E-Mail</a>
to the author</p>
<h3>Suggestions or Feature request</h3>
<p><a target="_blank"
	href="http://www.bullraider.com/joomla/extensions/joocomments/feature-request">Click
here</a></p>

</div>
</div>
<div id="jooAddcontainer">
<div class="itemJoo">
<div
	style="border: 1px solid #ccc; background: #fff; margin: 15px; padding: 15px; text-align: center;">
<p align="center">Please support JooComments</p>
<form target="paypal" method="post"
	action="https://www.paypal.com/en/cgi-bin/webscr"><input type="hidden"
	value="_donations" name="cmd"> <input type="hidden"
	value="aaraksheet@gmail.com" name="business"> <input type="hidden"
	value="http://www.bullraider.com" name="return"> <input type="hidden"
	value="0" name="undefined_quantity"> <input type="hidden"
	value="Donate to Bullraider.com" name="item_name"> Amount:&nbsp;<input
	type="text" style="text-align: right;" value="" maxlength="10" size="4"
	name="amount"> <select name="currency_code">
	<option value="USD">USD</option>
	<option value="EUR">EUR</option>
	<option value="GBP">GBP</option>
	<option value="CHF">CHF</option>
	<option value="AUD">AUD</option>
	<option value="HKD">HKD</option>
	<option value="CAD">CAD</option>
	<option value="JPY">JPY</option>
	<option value="NZD">NZD</option>
	<option value="SGD">SGD</option>
	<option value="SEK">SEK</option>
	<option value="DKK">DKK</option>
	<option value="PLN">PLN</option>
	<option value="HUF">HUF</option>
	<option value="CZK">CZK</option>
	<option value="ILS">ILS</option>
	<option value="MXN">MXN</option>
</select> <input type="hidden" value="utf-8" name="charset"> <input
	type="hidden" value="1" name="no_shipping"> <input type="hidden"
	value="http://www.bullraider.com/images/stories/bullraider.gif"
	name="image_url"> <input type="hidden"
	value="http://www.bullraider.com" name="cancel_return"> <input
	type="hidden" value="0" name="no_note"><br>
<br>
<input type="image" alt="PayPal secure payments." name="submit"
	src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif"></form>
</div>
</div>
<div class="itemJoo">
<p><strong>Special Thanks</strong></p>
<br />
<p><strong>Translations:</strong></p>
<p>Aschwin van Loon,Permata Harahap (Perry),Linas,Manuel H,Ghulam,Robert<br />
Sorry if missed any names<br />
</p>
<p><strong>Testing</strong>:<br />
Philip Blais</p>
</div>

<div class="itemJoo">
<p><strong>I also help create New extension development:</strong></p>
<p style="text-align: justify;width:375px;">Finding an extension which is very
specific your need is difficult. You search through the extension
directory of Joomla but could not find any close match of your need. I
help my customers with extension development from beginning with the
design to the installation and support there after.</p>
<br />
<p>Write me <a
	href="index.php?option=com_joocomments&amp;view=mail&amp;layout=modal&amp;tmpl=component&amp;header=Send a service reqeust to bullraider&amp;name=Bullraider&amp;toMail=bullraider@gmx.com&amp;title=Service Request"
	rel="{handler: 'iframe', size: {x: 620, y: 370}}" class="modal">E-Mail</a>
or read more about <a target="_blank"
	href="http://www.bullraider.com/service">services.</a></p>
</div>
<div class="itemJoo">
<p><strong>Hire me to customize existing Extension:</strong></p>
<p style="text-align: justify;width:375px;">There are many free extensions which you
want to use or even using it<br />
, but it doesn't fit exactly how you wanted it to be. You put a feature
request to the extension owner but they don't either pay attention or
promise you to get back, but you can't wait. The customization may be a
simple User interface change or even a customized feature just for your
business.Choose any free extension which you want it customized, and I
help you do it.</p>
<br />
<p>Write me <a
	href="index.php?option=com_joocomments&amp;view=mail&amp;layout=modal&amp;tmpl=component&amp;header=Send a service reqeust to bullraider&amp;name=Bullraider&amp;toMail=bullraider@gmx.com&amp;title=Service Request"
	rel="{handler: 'iframe', size: {x: 620, y: 370}}" class="modal">E-Mail</a>
or read more about <a target="_blank"
	href="http://www.bullraider.com/service">services.</a></p>
</div>
<div class="itemJoo">
<p style="font-size: 12px; text-align: center"><strong>Hire me for
Joomla Development </strong></p>
<br />
<p>Write me <a
	href="index.php?option=com_joocomments&amp;view=mail&amp;layout=modal&amp;tmpl=component&amp;header=Send a service reqeust to bullraider&amp;name=Bullraider&amp;toMail=bullraider@gmx.com&amp;title=Service Request"
	rel="{handler: 'iframe', size: {x: 620, y: 370}}" class="modal">E-Mail</a>
or read more about <a target="_blank"
	href="http://www.bullraider.com/service">services.</a></p>
</div>


</div>
<div id="controls1" align="center">
<div class="prev_btn">&lt prev</div>
<div class="play_btn">pause</div>
<div class="next_btn">next &gt;</div>

</div>


