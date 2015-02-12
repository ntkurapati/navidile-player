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
        echo("your subscriptions for ".$email_addr. " are: ". $subscriptions . "<br>");
        $alert = $_GET['alert'];
        echo("your alert is " . $alert . "<br>");
        if($alert) {
            $subpieces = explode(',', $subscriptions);
            $reqpieces = explode(':', $alert);
            $reqcyear = $reqpieces[0];
            $reqalert = substr($reqpieces[1], 1);
            $reqaction = substr($reqpieces[1],0, 1);
            foreach($subpieces as $subpiece) {
                $pieces = explode(':', $subpiece);
                $cyear = $pieces[0];
                $alerts = substr($pieces[1], 1);
                if($cyear == $reqcyear){
                    if($reqaction == '-') {
                        $subpiece=str_replace($subpiece, $reqalert, '');
                    }
                }
            }
            $newsubs = implode(',', $subpieces);
            echo('<br>subscription change: ' . $subscriptions . '-->' . $newsubs);
            $statement2 = $mysqli->prepare("UPDATE subscribers SET `subscriptions` = ? WHERE `password` = ?");
            $statement2->execute(array($newsubs, $pw));
        }
    }
    $statement->free_result();



}
else{
echo("i didn't find you in the db");
}


?>