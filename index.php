<?php
ini_set('display_errors',1); 
error_reporting(E_ALL);

$username = "navidileappphp";
$password = ini_get("mysqli.default_pw");
$hostname = "localhost";	
$mysqli = new mysqli($hostname, $username, $password, "pittmedstech_nav");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
$lec_id=$_GET['id'];

$statement = $mysqli->prepare("SELECT `podcast_url`, `course_name`, `name`, `rec_date`, `mediasite_url`,  `slide_base_url`, `image_refs`, `cyear` FROM recordings WHERE `idno` = ?");
$statement->bind_param('s', $lec_id);
$statement->execute();
$statement->bind_result($mpurl, $course_title, $title, $rec_date, $mediasite_url, $slide_base_url, $image_refs, $class_year);
while($statement->fetch()){}
$statement->free_result();


$previous_id="";
$statement2 = $mysqli->prepare("SELECT `idno` FROM recordings WHERE `course_name` = ? ORDER BY `rec_date` ");
$statement2->bind_param('s', $course_title);
$statement2->execute();
$statement2->bind_result($temp_idno);

$last_id="";
$next_id="";
while($statement2->fetch()){
	if($temp_idno == $lec_id) {
		$prev_id=$last_id;
}
	if($lec_id == $last_id)
	{
		$next_id=$temp_idno;
		break;
	}
	$last_id=$temp_idno;
}
$statement2->free_result();

$last_presentation = "http://students.medschool.pitt.edu/navidile/navidile_player/?id=".$prev_id;
$next_presentation  = "http://students.medschool.pitt.edu/navidile/navidile_player/?id=".$next_id;

if ($prev_id=="")
{
	$last_presentation="http://students.medschool.pitt.edu/navidile/" . $class_year ."-all-lr.html";
}
if ($next_id=="")
{
	$next_presentation ="http://students.medschool.pitt.edu/navidile/" . $class_year ."-all-lr.html";
}


?>
<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title><?php echo $title; ?></title>
  <script src="http://students.medschool.pitt.edu/navidile/navidile_player/shortcut.js" type="text/javascript"></script>
  <script type="text/javascript">
  var slidebaseurl="<?php echo $slide_base_url; ?>"
var mp3src="<?php echo $mpurl; ?>"
var refs =<?php echo $image_refs; ?>

var imgurl = 'http://mediasite.medschool.pitt.edu/'+slidebaseurl+'/slide_%NUMBER%_640_480.jpg';

var media_events = new Array();

var imgnum=0;
var coffset=0;

var media_properties = [ "currentTime",  "playbackRate" ];

var media_properties_elts = null;

var webm = null;



function  image_num(offset, refs1){
	//#document.write('hi')
	num=0
	for (i=0;i<=refs1.length;i++)
	{
		if (refs1[i]>=offset*1000)
		{

			return i
		}
	}
	return i;
}


function init() {
    document._video = document.getElementById("video");
	document.getElementById("mp3").src=mp3src
    webm = document.getElementById("webm");

    init_events();
 

    // properties are updated even if no event was triggered
    setInterval(update_properties, 10);
	

	shortcut.add("Space", function() {
		playpause();
	});
	shortcut.add("Right", function() {
		document._video.currentTime+=15;
	});
	shortcut.add("Left", function() {
		document._video.currentTime-=15;
	});
	shortcut.add("m", function() {
		document._video.currentTime+=120;
	});
	shortcut.add("n", function() {
		document._video.currentTime-=120;
	});
	shortcut.add("Up", function() {
		document.getElementById("slider").value=document._video.playbackRate+0.1;
	});
	shortcut.add("Down", function() {
		document.getElementById("slider").value=document._video.playbackRate-0.1;
	});

	
}
document.addEventListener("DOMContentLoaded", init, false);

function init_events() {

    for (key in media_events) {	
	document._video.addEventListener(key, capture, false);
    }

  


 
}


function playpause() {
	if (document._video.paused) {document._video.play();}
	else {document._video.pause();}

 
}
function init_properties() {
    var tbody = document.getElementById("properties");
    var i = 0;
    var tr = null;
    media_properties_elts = new Array(media_properties.length);
    do {
	if (tr == null) tr    = document.createElement("tr");
	var th = document.createElement("th");
	th.textContent = media_properties[i];
	var td = document.createElement("td");
	td.setAttribute("id", "p_" + media_properties[i]);
	var r = eval("document._video." + media_properties[i]);
	td.innerHTML = r;
	if (typeof(r) != "undefined") {
	    td.className = "true";
	} else {
	    td.className = "false";
	}
	tr.appendChild(th);
	tr.appendChild(td);
	media_properties_elts[i] = td;
	if ((++i % 3) == 0) {
	    tbody.appendChild(tr);
	    tr = null;
	}
    } while (i < media_properties.length);
    if (tr != null) tbody.appendChild(tr);
}

function capture(event) {
    media_events[event.type] = media_events[event.type] + 1;

    update_properties();
}


function update_properties() {

	document.getElementById("ratedisplay").value=eval("document._video.playbackRate")
    
	coffset=eval("document._video.currentTime")
	var new_img_num =image_num(coffset, refs);
	
	document._video.playbackRate = document.getElementById('slider').value
	
	if (new_img_num != imgnum)
	{
		imgnum=new_img_num;
		document.getElementById("slide").src = imgurl.replace("%NUMBER%", imgnum);
        document.getElementById("slidep").src = imgurl.replace("%NUMBER%", imgnum-1);
		
	}
}
  </script>
  <link href="http://students.medschool.pitt.edu/navidile/navidile_player/style.css" rel="stylesheet" type="text/css">
</head>
<body>
  <center>
  <h1><?php echo $course_title; ?> (Navidile v. 0.93b)</h1>
  <h2><?php echo $title; ?></h2>
  <img src="http://students.medschool.pitt.edu/navidile/navidile_player/1<?php if (date('M')=='Oct') {echo '-halloween';} ?>.jpg" border="0" height = "240" width="320" id="slidep" align="center" style="display:none">

  <img src="http://students.medschool.pitt.edu/navidile/navidile_player/1<?php if (date('M')=='Oct') {echo '-halloween';} ?>.jpg" border="0" height = "480" width="640" id="slide" align="center">


  <div>
    <audio id="video" controls="" preload="none" width="640">
      <source id="mp3"  type="audio/mp3">

      <p>Your user agent does not support the HTML5 Video element.</p>
    </audio>
    <div id="buttons">

	  <input type="image" src="http://students.medschool.pitt.edu/navidile/navidile_player/back15.jpg" onmouseover="this.src='http://students.medschool.pitt.edu/navidile/navidile_player/back15-light.jpg';"   onmouseout="this.src='http://students.medschool.pitt.edu/navidile/navidile_player/back15.jpg';"   onclick="document._video.currentTime-=15" />
	  <input type="image" src="http://students.medschool.pitt.edu/navidile/navidile_player/play.jpg" onmouseover="this.src='http://students.medschool.pitt.edu/navidile/navidile_player/play-light.jpg';"   onmouseout="this.src='http://students.medschool.pitt.edu/navidile/navidile_player/play.jpg';"   onclick="document._video.play()" />
	  <input type="image" src="http://students.medschool.pitt.edu/navidile/navidile_player/pause.jpg" onmouseover="this.src='http://students.medschool.pitt.edu/navidile/navidile_player/pause-light.jpg';"   onmouseout="this.src='http://students.medschool.pitt.edu/navidile/navidile_player/pause.jpg';"   onclick="document._video.pause()" />
	  <input type="image" src="http://students.medschool.pitt.edu/navidile/navidile_player/forward15.jpg" onmouseover="this.src='http://students.medschool.pitt.edu/navidile/navidile_player/forward15-light.jpg';"   onmouseout="this.src='http://students.medschool.pitt.edu/navidile/navidile_player/forward15.jpg';"   onclick="document._video.currentTime+=15" />
<br>
	  speed control:
	  <input type="range" id="slider"  min="0.5" max="5" value="1" step="0.1" onchange="document._video.playbackRate=this.value"/><input type="text" id="ratedisplay" value="1" >
<br>
<a href = "<?php echo $last_presentation; ?>"><img src="http://students.medschool.pitt.edu/navidile/navidile_player/lastpres.jpg" onmouseover="this.src='http://students.medschool.pitt.edu/navidile/navidile_player/lastpres-light.jpg';"   onmouseout="this.src='http://students.medschool.pitt.edu/navidile/navidile_player/lastpres.jpg';" title="go to prev lecture" /></a>
<a href = "<?php echo $next_presentation; ?>"><img src="http://students.medschool.pitt.edu/navidile/navidile_player/nextpres.jpg" onmouseover="this.src='http://students.medschool.pitt.edu/navidile/navidile_player/nextpres-light.jpg';"   onmouseout="this.src='http://students.medschool.pitt.edu/navidile/navidile_player/nextpres.jpg';" title="go to next lecture" /></a>
	  <br>
	  <br>
<input type="button" value="Show Rearview Mirror" onclick="document.getElementById('slidep').style.display = '';" />
<input type="button" value="Remove Rearview Mirror" onclick="document.getElementById('slidep').style.display='none';" />
<input type="button" value="Fullview Picture" onclick="document.getElementById('slide').style.height='90%';  document.getElementById('slide').style.width='90%';  " />
<input type="button" value="Fixedwidth Picture" onclick="document.getElementById('slide').style.height='480';  document.getElementById('slide').style.width='640';  " />
	  <!--
      <button onclick="document._video.volume+=0.1">volume+=0.1</button>
      <button onclick="document._video.volume-=0.1">volume-=0.1</button>
      <button onclick="document._video.muted=true">mute</button>
      <button onclick="document._video.muted=false">unmute</button><br> -->
    </div>
	
    
    <p> This player currently works with Google Chrome (both desktop and Android versions).</p>
	<p> Shortcuts: Space pauses. Up/Down arrows changes speed. Right and Left arrows skip 15 seconds. n and m skip 2 minutes.  Use F11 to switch to/back from fullscreen (experimental).</p>
    <p><a href = '<?php echo $mediasite_url; ?> '>Mediasite Player for this lecture</a></p>
    <p>This recording published on <?php echo $rec_date; ?></p>
	<hr>
	<p><a href="http://students.medschool.pitt.edu/wiki/index.php/Navidile_Feedback">Responses to questions and feedback submitted in below form</a></p>
	<!-- iframe src="https://docs.google.com/spreadsheet/embeddedform?formkey=dE44bll1WEpkcXRlR05CWXpzZ1JsVFE6MQ" width="760" height="767" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>
<!--
  <div id='canPlayType'>
      // @@TODO canPlayType
    </div>
    -->
  </div>
  </center>

<hr>




</body></html>
