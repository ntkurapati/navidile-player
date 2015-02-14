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
$class_year=$_GET['class_year'];

$statement = $mysqli->prepare("SELECT `podcast_url`, `course_name`, `name`, `rec_date`, `mediasite_url`,  `slide_base_url`, `image_refs`, `cyear` FROM recordings WHERE `idno` = ?");
$statement->bind_param('s', $lec_id);
$statement->execute();
$statement->bind_result($mpurl, $course_title, $title, $rec_date, $mediasite_url, $slide_base_url, $image_refs, $class_year);
print '<table>';
while($statement->fetch()){
print "<tr><td>" .$rec_date . "</td><td><a href=" . $mediasite_url . "rel=\'nofollow\'>" . $title . "</a></td><td>[navidile]</td><td>";
}
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

