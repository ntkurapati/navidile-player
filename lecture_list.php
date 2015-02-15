<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
   <script src="./js/bootstrap.min.js"></script>
  <title><?php echo "Navidile" .  $_GET['class_year']; ?></title>
  <link href="./style_list.css" rel="stylesheet" type="text/css">
       <div class="bs-component">
              <nav class="navbar navbar-default navbar-fixed-top">
                <div class="container-fluid">
                  <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="http://www.omed.pitt.edu/current-students/">Pitt Med</a>
                  </div>

                  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                      <li><a href="https://navigator.medschool.pitt.edu">Navigator <span class="sr-only">(current)</span></a></li>
                      <li><a href="http://zone.medschool.pitt.edu/Pages/default.aspx">Zone</a></li>
                      <li><a href="https://my.pitt.edu">My Pitt</a></li>
					  <li><a href="https://outlook.office365.com/owa/?realm=pitt.edu#path=/mail">Email</a></li>
					  <li><a href="http://students.medschool.pitt.edu/wiki/index.php/Main_Page">Pitt Med Wiki</a></li>
					  <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Class Navidile Links <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="http://students.medschool.pitt.edu/navidile/2018-all-lr.html">2018</a></li>
                          <li><a href="http://students.medschool.pitt.edu/navidile/2017-all-lr.html">2017</a></li>
                          <li><a href="http://students.medschool.pitt.edu/navidile/2016-all-lr.html">2016</a></li>
                        </ul>
                      </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                      <li><a href="http://www.omed.pitt.edu/current-students/">Pitt Med</a></li>
                    </ul>
                  </div>
                </div>
              </nav>
            <div id="source-button" class="btn btn-primary btn-xs" style="display: none;">&lt; &gt;</div></div>  
</head>
<body>
<div class="container">
<div class="row">
<div class="col-lg-12">
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
    print '<div class="page-header"><hr><h3 class="brand-name">' . $result['course_name'] . '<p class="bs-component"> <br/>';
    print " <a class=\"btn btn-primary btn-sm\" href=\"" . $result['mediasite_url'] .  "\">mediasite</a>";
    print " <a class=\"btn btn-primary btn-sm\" href=\"" . $result['podcast_url'] . "\">podcast</a>";
    print " <a class=\"btn btn-primary btn-sm\" href=\"" . $result['navigator_url'] . "\">navigator</a></p></h3></div>";

    $statement = $mysqli->prepare("SELECT `idno`, `podcast_url`, `course_name`, `name`, `rec_date`, `mediasite_url`  FROM recordings WHERE `cyear` = ? AND `course_uid` = ? ORDER BY `rec_date` DESC");
    $statement->bind_param('ss', $class_year, $result['course_uid']);
    $statement->execute();
    $statement->bind_result($lec_id, $mpurl, $course_title, $title, $rec_date, $mediasite_url);
    print '<table class="table">';
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
</div>
</div>
</div>
</body>
</html>
