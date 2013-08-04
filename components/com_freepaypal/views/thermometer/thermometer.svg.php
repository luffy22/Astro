<?php 
defined('_JEXEC') or die('Restricted access'); 
function generateSVGThermometer($therm_unit='$',$therm_max=100,$therm_goal=100,$therm_min=0,$therm_width=32,
				$therm_height=320,$therm_value=33,$therm_textcolor="#000000",
				$therm_boundcolor="#000000",$therm_scale=1.0,$numBigDivs=7,
				$numSmallDivsPerBigDiv=5,$therm_bulbpct=1,$therm_fees=0,$therm_show_timeinterval=1,
				$therm_show_remainder=1, $therm_amount_view=1) {
$svgFile="<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>
<svg xmlns:svg=\"http://www.w3.org/2000/svg\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:sodipodi=\"http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd\" xmlns:inkscape=\"http://www.inkscape.org/namespaces/inkscape\" width=\"256\" height=\"512\" id=\"svg2\" sodipodi:version=\"0.32\" inkscape:version=\"0.44\" version=\"1.0\" sodipodi:docbase=\"/home/twb\" sodipodi:docname=\"thermometer.svg\">"; 

$therm_bulboffset=10;
$numSmallDivs = $numBigDivs*$numSmallDivsPerBigDiv;
$left_offset = 48;
$right_offset = $left_offset+$therm_width;
$therm_top=64;
$therm_peak=$therm_top/2;
$therm_bottom=$therm_top + $therm_height;
$therm_middle=0.5*($therm_top+$therm_bottom);
$bigDivLen = $therm_width;
$smallDivLen = $therm_width/2;

$svgFile .= "<g inkscape:label=\"Layer 1\" inkscape:groupmode=\"layer\" id=\"layer1\" transform=\"scale(".$therm_scale.")\">";
$linestyle = "fill: none; fill-opacity: 0.75; fill-rule: evenodd; stroke: ".$therm_textcolor."; stroke-width: 4; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; stroke-miterlimit: 4; stroke-dasharray: none;"; 
$textstyle="font-size: 20px; font-style: normal; font-variant: normal; font-weight: normal; font-stretch: normal; text-align: start; line-height: 125%; text-anchor: start; fill: ".$therm_textcolor."; fill-opacity: 1; stroke: none; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-family: Bitstream Vera Sans;";
$x_start = $right_offset;
$x_end = $x_start+$bigDivLen;
$y_offset = ($therm_bottom - $therm_top)/$numBigDivs;
$therm_offset = ($therm_max - $therm_min)/$numBigDivs;
for ($i=0; $i <= $numBigDivs; $i++) {
  $value = $therm_max - $i*$therm_offset;
  $therm_label = $therm_unit . round($value);
  $y_start = $therm_top + $i*$y_offset;
  $y_end = $y_start;
  $svgFile .= svgDrawLine($x_start,$x_end,$y_start,$y_end, $linestyle);
  $svgFile .= svgDrawText( $x_end+10, $y_end+5, $therm_label, $textstyle);
}

$y_offset = ($therm_bottom - $therm_top)/$numSmallDivs;
$x_end = $x_start+$smallDivLen;
for ($i=1; $i < $numSmallDivs; $i++) {
$y_start = $therm_top+$i*$y_offset;
$y_end = $y_start;
$svgFile .= svgDrawLine($x_start,$x_end,$y_start,$y_end, $linestyle);
}

/*$width=32; $height=416; $x=48; $y=32;*/
$round_x=$therm_width/2;
$round_y=$therm_width/2;
$style = "fill: rgb(238, 238, 236); fill-opacity: 1; fill-rule: nonzero; stroke: ".$therm_boundcolor."; stroke-width: 4; stroke-linecap: round; stroke-linejoin: round; stroke-miterlimit: 4; stroke-dasharray: none; stroke-dashoffset: 0pt; stroke-opacity: 1;";
// white thermometer background
$svgFile .= svgDrawRect( $therm_width, $therm_height+$therm_bulboffset+2*$round_x+10, $left_offset, $therm_peak, $round_x, $round_y, $style );

// draw rectangle to extend thermometer into bulb -- all red
$style = "fill: rgb(204, 0, 0); fill-opacity: 1; fill-rule: nonzero; stroke: none; stroke-width: 4; stroke-linecap: round; stroke-linejoin: round; stroke-miterlimit: 4; stroke-dasharray: none; stroke-dashoffset: 0pt; stroke-opacity: 1;";
$svgFile .= svgDrawRect( $therm_width, $therm_bulboffset+5, $left_offset, $therm_bottom, 0, 0, $style );

/*$width=32; $height=64; $x=48; $y=384;*/
$therm_temp = $therm_height*($therm_value-$therm_min)/($therm_max - $therm_min);
$therm_ypos = $therm_bottom-$therm_temp;
if ($therm_ypos < $therm_top) {
  $therm_ypos = $therm_peak;
  $therm_temp = $therm_height+$therm_bulboffset+2*$round_x+20;
}
if ($therm_value > $therm_goal) {
  $round_x=$therm_width/2;
  $round_y=$therm_width/2;
  //$therm_ypos = $therm_peak;
  //$therm_temp = $therm_height+$therm_bulboffset+2*$round_x+20;
  // Draw actual value -- 
  $svgFile .= svgDrawText( $right_offset+10, $therm_ypos+10, $therm_unit . $therm_value . "!!", $textstyle);
  // TODO: burst thermometer?
  // TODO: put "goal reached!" diagonally across thermometer flashing?
}
else {
  $round_x=0;
  $round_y=0;
}
$style = "fill: rgb(204, 0, 0); fill-opacity: 1; fill-rule: nonzero; stroke: none; stroke-width: 4; stroke-linecap: round; stroke-linejoin: round; stroke-miterlimit: 4; stroke-dasharray: none; stroke-dashoffset: 0pt; stroke-opacity: 1;";
// red thermometer foreground
$svgFile .= svgDrawRect( $therm_width, $therm_temp, $left_offset, $therm_ypos, $round_x, $round_y, $style );
$y_bulbtoshaft = $therm_bottom+$therm_bulboffset;
$bulbstyle = "fill: rgb(204, 0, 0); fill-opacity: 1; fill-rule: nonzero; stroke: ".$therm_boundcolor."; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; stroke-miterlimit: 4; stroke-dasharray: none; stroke-dashoffset: 0pt; stroke-opacity: 1;";
// draw the thermometer bulb
$svgFile .= svgDrawCircularArc($left_offset,$y_bulbtoshaft,40,40,$right_offset,$y_bulbtoshaft, $bulbstyle);
if ($therm_bulbpct == 1) {
  $therm_pct = round(100*($therm_value-$therm_min)/($therm_goal - $therm_min));
  if ($therm_pct < 10)
    $x_offset=-12;
  else if ($therm_pct < 100)
    $x_offset=-20;
  else
    $x_offset=-27;
  $bulb_ctr_x = $left_offset + 0.5*$therm_width+$x_offset;
  $bulb_ctr_y = $therm_bottom + $therm_bulboffset + 40;
  $bulb_textstyle="font-size: 20px; font-style: normal; font-variant: normal; font-weight: bold; font-stretch: normal; text-align: start; line-height: 125%; text-anchor: start; fill: ".$therm_textcolor."; fill-opacity: 1; stroke: none; stroke-width: 1px; stroke-linecap: butt; stroke-linejoin: miter; stroke-opacity: 1; font-family: Bitstream Vera Sans;";
  $svgFile .= svgDrawText( $bulb_ctr_x, $bulb_ctr_y, $therm_pct . "%", $bulb_textstyle);
}
$svgFile .= "\n </g>\n</svg>";
return $svgFile;
}

 function svgDrawLine($x_start, $x_end, $y_start, $y_end, $style) {
  $svg = "<path style=\"".$style."\" d=\"M ".$x_start.",".$y_start." L ".$x_end.",".$y_end."\" />";
  return $svg; 
 }

 function svgDrawRect($width, $height, $x, $y, $rx, $ry, $style, $transform='') {
   $svg = "<rect style=\"".$style."\" width=\"".$width."\" height=\"".$height."\" x=\"".$x."\" y=\"".$y."\" rx=\"".$rx."\" ry=\"".$ry."\" ";
   if (!empty($transform)) {
     $svg .= " transform=\"".$transform."\"";
   }
   $svg .= " />";
   return $svg;
 }

 function svgDrawText($x, $y, $text, $style, $transform='') {
   $svg = "<text sodipodi:linespacing=\"125%\" y=\"".$y."\" x=\"".$x."\" style=\"".$style."\" xml:space=\"preserve\" >";
   if (!empty($transform)) {
     $svg .= " transform=\"".$transform."\"";
   }
   $svg .= "<tspan y=\"".$y."\" x=\"".$x."\" sodipodi:role=\"line\">".$text."</tspan></text>";
   return $svg;
 }

 function svgDrawCircularArc($startx, $starty, $rx, $ry, $endx, $endy, $style) {
   //$xend = $cx+$rx*cos(M_PI*($theta1-$theta0)/180);
   //$yend = $cy+$ry*sin(M_PI*($theta1-$theta0)/180);
   $xend = $endx-$startx;
   $yend = $endy-$starty;
   $svg = "<path style=\"".$style."\" d=\"M ".$startx.",".$starty." a".$rx.",".$ry." 0 1,0 ".$xend.",".$yend." \" fill=\"red\" stroke=\"blue\" stroke-width=\"5\" />";
   return $svg;
 }
?>
