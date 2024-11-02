<?php
/*
Plugin Name: Chinposin
Plugin URI: http://blog.abesh.net/2008/06/30/chinpose-on-your-wordpress-blog/
Description: Chinposin Widget to display your Chinposin avatar's timeline. For more info visit <a href="http://www.chinposin.com">Chinposin</a>
Author: Abesh Bhattacharjee
Version: 1.0
Author URI: http://www.abesh.net/
*/


define("CHINPOSIN_URL", "http://chinposin.com/a/[avatarname]/avatars.xml");

function widget_chinposin_control(){
		$options = $newoptions = get_option('widget_chinposin');
		if ( $_POST['chinposin-submit'] ) {
			$newoptions['title'] = $_POST['chinposin-title'];
			$newoptions['avatarname'] = $_POST['chinposin-avatarname'];
		}
		$newoptions['title'] = 'Test';
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_chinposin', $options);
		}

?>
<div style="text-align:right">
	<label for="chinposin-title" style="line-height:35px;display:block;"><?php _e('Widget Title:', 'widgets'); ?> <input type="text" id="chinposin-title" name="chinposin-title" value="<?php echo wp_specialchars($options['title'], true); ?>" /></label>
	<label for="chinposin-avatarname" style="line-height:35px;display:block;"><?php _e('Chinposin Avatar Name:', 'widgets'); ?> <input type="text" id="chinposin-avatarname" name="chinposin-avatarname" value="<?php echo wp_specialchars($options['avatarname'], true); ?>" /></label>
	<input type="hidden" name="chinposin-submit" id="chinposin-submit" value="1" />
<?php
}


function widget_chinposin($args) {
  extract($args);
$options = (array) get_option('widget_chinposin');  
?>
    <?php echo $before_widget; ?>
    <?php echo $before_title . "{$options['title']}" . $after_title; ?>
<?
// Reads and returns the content of a site for a given URL.
function getFeedContent($feedurl) {
    
    # Init Curl
    $ch = curl_init();
    
    # Now get XML feed
    curl_setopt($ch, CURLOPT_URL, $feedurl);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 0);
    
    // Go for it!!!
    $result = curl_exec($ch);
          
    // Look at the returned header
    $resultArray = curl_getinfo($ch);
    
    if ($resultArray['http_code'] != "200") {
        echo "could not open XML input: ".$event;
    }
    
    # close curl
    curl_close($ch);

    return $result;
}

//Start Slideshow Code
if ($options['avatarname'] <> ""){
	$chinposin_timeline = str_replace("[avatarname]", $options['avatarname'], CHINPOSIN_URL);
	$content = getFeedContent($chinposin_timeline, false);
  $dom = new DOMDocument('1.0', 'UTF-8');
  if ($dom->loadXML($content) === false) { 
   echo('XML Parsing failed'); 
   return;
  }else{
  $xpath = new DOMXPath($dom);
  $query = '//rss/channel/item/media:thumbnail/@url';
  $entries = $xpath->query($query);
  ?> 
  <style type="text/css">
  .centerdiv{ /*IE method of centering a relative div*/
  text-align: center;
  }
  
  .centerdiv>div{ /*Proper way to center a relative div*/
  margin: 0 auto;
  }
  </style>  
  <SCRIPT type="text/javascript">
  var fadeimages=new Array();
  <?php
    $i=0;
	  foreach ($entries as $entry){

?>
fadeimages[<?php echo $i ?>]=["<?php echo $entry->value ?>", "http://chinposin.com/home/<?php echo $options['avatarname'] ?>", "_new"];
<?php	
  $i++;
	}
?>

    
//***********************************************
//* Ultimate Fade-In Slideshow (v1.51): © Dynamic Drive (http://www.dynamicdrive.com)
//* This notice MUST stay intact for legal use
//* Visit http://www.dynamicdrive.com/ for this script and 100s more.
//***********************************************/
 
var fadebgcolor="transparent"

////NO need to edit beyond here/////////////
 
var fadearray=new Array() //array to cache fadeshow instances
var fadeclear=new Array() //array to cache corresponding clearinterval pointers
 
var dom=(document.getElementById) //modern dom browsers
var iebrowser=document.all
 
function fadeshow(theimages, fadewidth, fadeheight, borderwidth, delay, pause, displayorder){
this.pausecheck=pause
this.mouseovercheck=0
this.delay=delay
this.degree=10 //initial opacity degree (10%)
this.curimageindex=0
this.nextimageindex=1
fadearray[fadearray.length]=this
this.slideshowid=fadearray.length-1
this.canvasbase="canvas"+this.slideshowid
this.curcanvas=this.canvasbase+"_0"
if (typeof displayorder!="undefined")
theimages.sort(function() {return 0.5 - Math.random();}) //thanks to Mike (aka Mwinter) :)
this.theimages=theimages
this.imageborder=parseInt(borderwidth)
this.postimages=new Array() //preload images
for (p=0;p<theimages.length;p++){
this.postimages[p]=new Image()
this.postimages[p].src=theimages[p][0]
}
 
var fadewidth=fadewidth+this.imageborder*2
var fadeheight=fadeheight+this.imageborder*2
 
if (iebrowser&&dom||dom) //if IE5+ or modern browsers (ie: Firefox)
document.write('<div id="master'+this.slideshowid+'" style="position:relative;width:'+fadewidth+'px;height:'+fadeheight+'px;overflow:hidden;"><div id="'+this.canvasbase+'_0" style="position:absolute;width:'+fadewidth+'px;height:'+fadeheight+'px;top:0;left:0;filter:progid:DXImageTransform.Microsoft.alpha(opacity=10);opacity:0.1;-moz-opacity:0.1;-khtml-opacity:0.1;background-color:'+fadebgcolor+'"></div><div id="'+this.canvasbase+'_1" style="position:absolute;width:'+fadewidth+'px;height:'+fadeheight+'px;top:0;left:0;filter:progid:DXImageTransform.Microsoft.alpha(opacity=10);opacity:0.1;-moz-opacity:0.1;-khtml-opacity:0.1;background-color:'+fadebgcolor+'"></div></div>')
else
document.write('<div><img name="defaultslide'+this.slideshowid+'" src="'+this.postimages[0].src+'"></div>')
 
if (iebrowser&&dom||dom) //if IE5+ or modern browsers such as Firefox
this.startit()
else{
this.curimageindex++
setInterval("fadearray["+this.slideshowid+"].rotateimage()", this.delay)
}
}

function fadepic(obj){
if (obj.degree<100){
obj.degree+=10
if (obj.tempobj.filters&&obj.tempobj.filters[0]){
if (typeof obj.tempobj.filters[0].opacity=="number") //if IE6+
obj.tempobj.filters[0].opacity=obj.degree
else //else if IE5.5-
obj.tempobj.style.filter="alpha(opacity="+obj.degree+")"
}
else if (obj.tempobj.style.MozOpacity)
obj.tempobj.style.MozOpacity=obj.degree/101
else if (obj.tempobj.style.KhtmlOpacity)
obj.tempobj.style.KhtmlOpacity=obj.degree/100
else if (obj.tempobj.style.opacity&&!obj.tempobj.filters)
obj.tempobj.style.opacity=obj.degree/101
}
else{
clearInterval(fadeclear[obj.slideshowid])
obj.nextcanvas=(obj.curcanvas==obj.canvasbase+"_0")? obj.canvasbase+"_0" : obj.canvasbase+"_1"
obj.tempobj=iebrowser? iebrowser[obj.nextcanvas] : document.getElementById(obj.nextcanvas)
obj.populateslide(obj.tempobj, obj.nextimageindex)
obj.nextimageindex=(obj.nextimageindex<obj.postimages.length-1)? obj.nextimageindex+1 : 0
setTimeout("fadearray["+obj.slideshowid+"].rotateimage()", obj.delay)
}
}
 
fadeshow.prototype.populateslide=function(picobj, picindex){
var slideHTML=""
if (this.theimages[picindex][1]!="") //if associated link exists for image
slideHTML='<a href="'+this.theimages[picindex][1]+'" target="'+this.theimages[picindex][2]+'">'
slideHTML+='<img src="'+this.postimages[picindex].src+'" border="'+this.imageborder+'px">'
if (this.theimages[picindex][1]!="") //if associated link exists for image
slideHTML+='</a>'
picobj.innerHTML=slideHTML
}
 
 
fadeshow.prototype.rotateimage=function(){
if (this.pausecheck==1) //if pause onMouseover enabled, cache object
var cacheobj=this
if (this.mouseovercheck==1)
setTimeout(function(){cacheobj.rotateimage()}, 100)
else if (iebrowser&&dom||dom){
this.resetit()
var crossobj=this.tempobj=iebrowser? iebrowser[this.curcanvas] : document.getElementById(this.curcanvas)
crossobj.style.zIndex++
fadeclear[this.slideshowid]=setInterval("fadepic(fadearray["+this.slideshowid+"])",50)
this.curcanvas=(this.curcanvas==this.canvasbase+"_0")? this.canvasbase+"_1" : this.canvasbase+"_0"
}
else{
var ns4imgobj=document.images['defaultslide'+this.slideshowid]
ns4imgobj.src=this.postimages[this.curimageindex].src
}
this.curimageindex=(this.curimageindex<this.postimages.length-1)? this.curimageindex+1 : 0
}
 
fadeshow.prototype.resetit=function(){
this.degree=10
var crossobj=iebrowser? iebrowser[this.curcanvas] : document.getElementById(this.curcanvas)
if (crossobj.filters&&crossobj.filters[0]){
if (typeof crossobj.filters[0].opacity=="number") //if IE6+
crossobj.filters(0).opacity=this.degree
else //else if IE5.5-
crossobj.style.filter="alpha(opacity="+this.degree+")"
}
else if (crossobj.style.MozOpacity)
crossobj.style.MozOpacity=this.degree/101
else if (crossobj.style.KhtmlOpacity)
crossobj.style.KhtmlOpacity=this.degree/100
else if (crossobj.style.opacity&&!crossobj.filters)
crossobj.style.opacity=this.degree/101
}
 
 
fadeshow.prototype.startit=function(){
var crossobj=iebrowser? iebrowser[this.curcanvas] : document.getElementById(this.curcanvas)
this.populateslide(crossobj, this.curimageindex)
if (this.pausecheck==1){ //IF SLIDESHOW SHOULD PAUSE ONMOUSEOVER
var cacheobj=this
var crossobjcontainer=iebrowser? iebrowser["master"+this.slideshowid] : document.getElementById("master"+this.slideshowid)
crossobjcontainer.onmouseover=function(){cacheobj.mouseovercheck=1}
crossobjcontainer.onmouseout=function(){cacheobj.mouseovercheck=0}
}
this.rotateimage()
}


</script>
<div class="centerdiv">
<script type="text/javascript">
//new fadeshow(IMAGES_ARRAY_NAME, slideshow_width, slideshow_height, borderwidth, delay, pause (0=no, 1=yes), optionalRandomOrder)
new fadeshow(fadeimages, 100, 100, 0, 10000, 1, "R") 
</script>
</div>

<?php	
}
}
//end slideshow code
  echo $after_widget;

}
function init_chinposin(){
	register_sidebar_widget(_('Chinposin'), 'widget_chinposin');
	register_widget_control(   'Chinposin', 'widget_chinposin_control', 350, 250 );
}
add_action("plugins_loaded", "init_chinposin");
?>