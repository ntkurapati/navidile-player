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

    }
    $statement->free_result();
    echo("your subscriptions for ".$email_addr. " are: ". $subscriptions . "<br>");
    $alert = $_GET['alert'];
    $action = $_GET['action'];
    echo("your alert to" . $action . " is " . $alert . "<br>");
    if($alert and $action ) {
        if($action == "unsubscribe"){
            $newsubs = str_replace( $alert, '', $subscriptions);
        }
        else($action == 'subscribe') {
            $newsubs = $alert . ',' . $subscriptions;
        }

        echo('<br>subscription change: ' . $subscriptions . '-->' . $newsubs);
        if($statement2 = $mysqli->prepare("UPDATE subscribers SET `subscriptions`= ? WHERE `password` = ? "))
        {
            $statement2->bind_param('ss', $newsubs, $pw);
            $statement2->execute();
            print 'Success!!';
            }
        else {
            print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
        }
    }
}





}
else{
echo("i didn't find you in the db");
}


?>