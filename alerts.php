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
$pw=$_GET['pw'];

$statement = $mysqli->prepare("SELECT `email_addr`, `subscriptions` FROM subscribers WHERE `password` = ?");
if($statement){
    $statement->bind_param('s', $pw);
    $statement->execute();
    $statement->bind_result($email_addr, $subscriptions);
    while($statement->fetch())
    {
        echo("your subscriptions for ".$email_addr. " are: ". $subscriptions);

    }
    $statement->free_result();
}
?>