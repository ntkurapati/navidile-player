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




$statement = $mysqli->prepare("SELECT `idno`, `podcast_url`, `course_name`, `name`, `rec_date`, `mediasite_url`  FROM recordings WHERE `cyear` = ? ORDER BY `rec_date`");
$statement->bind_param('s', $class_year);
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
