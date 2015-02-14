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
$email=$_POST['email_addr'];
$alerts=$_POST['alerts'];
echo($email.' '.$alerts)

$statement = $mysqli->prepare("INSERT INTO subscriptions ");
if($statement){
    $statement->bind_param('s', $pw);
    $statement->execute();
    $statement->bind_result($email_addr, $subscriptions);
INSERT INTO <table> (field1, field2, field3, ...)
VALUES ('value1', 'value2','value3', ...)
ON DUPLICATE KEY UPDATE
field1='value1', field2='value2', field3='value3', ...

?>