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
$pw=$_GET['id'];

$statement = $mysqli->prepare("SELECT `email_addr`, `subscriptions`, `class_years`,  FROM recordings WHERE `password` = ?");
$statement->bind_param('s', $pw);
$statement->execute();
$statement->bind_result($email_addr, $subscriptions, $class_years);
while($statement->fetch())
{
    echo($email_addr);
    echo($subscriptions);
    echo($class_years);
}
$statement->free_result();

?>