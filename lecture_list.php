<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title><?php echo "Navidile" .  $_GET['class_year']; ?></title>

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

$statement2 = $mysqli->prepare("SELECT `unique_id`, `mediasite_url`, `podcast_url`, `navigator_url`, `name` FROM courses WHERE `cyear` = ? ORDER BY `start_date` DESC");
$statement2->bind_param('s', $class_year);
$statement2->execute();
$statement2->bind_result($course_uid, $mediasite_url, $podcast_url, $navigator_url, $course_name);
$results = array();
$i=0;
while ($statement2->fetch()) {
  $results[$i]['course_uid'] = $course_uid;
  $results[$i]['mediasite_url'] = $mediasite_url;
  $results[$i]['podcast_url'] = $podcast_url;
  $results[$i]['navigator_url'] = $navigator_url;
  $results[$i]['course_name'] = $course_name;
  $i=$i+1;
}
$statement2->free_result();



foreach( $results as $result) {
    print "<hr />";
    print "<h1>$result['course_name']</h1><br/>";
    print " [<a href=\"" . $result['mediasite_url'] .  "\">mediasite</a>]";
    print " [<a href=\"" . $result['podcast_url'] . "\">podcast</a>]";
    print " [<a href=\"" . $result['navigator_url'] . "\">navigator</a>]";

    $statement = $mysqli->prepare("SELECT `idno`, `podcast_url`, `course_name`, `name`, `rec_date`, `mediasite_url`  FROM recordings WHERE `cyear` = ? AND `course_uid` = ? ORDER BY `rec_date` DESC");
    $statement->bind_param('ss', $class_year, $result['course_uid']);
    $statement->execute();
    $statement->bind_result($lec_id, $mpurl, $course_title, $title, $rec_date, $mediasite_url);
    print '<table>';
    while($statement->fetch()) {
        print "<tr>";
        print "<td>$rec_date</td>";
        print "<td><a href=\"$mediasite_url\" rel=\"nofollow\">$title</a></td>";
        print "<td>[<a href=\"$mpurl\" "  . 'rel="nofollow">'  ."mp3</a>]</td>";
        print '<td>[<a href="index.php?id=' . $lec_id . '" rel="nofollow">'  ."navidile</a>]</td>";
        print "</tr>";
    }
    print "</table>";
    $statement->free_result();
 }
?>
</html>