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
    echo("your alert is " . $alert . "<br>");
    if($alert) {
        $newsubs='';
        $subpieces = explode(',', $subscriptions);
        $reqpieces = explode(':', $alert);
        $reqcyear = $reqpieces[0];
        $reqalert = substr($reqpieces[1], 1);
        echo('::' . $reqalert ."::");
        $reqaction = substr($reqpieces[1],0, 1);
        foreach($subpieces as $subpiece) {
            $pieces = explode(':', $subpiece);
            $cyear = $pieces[0];
            $alerts = substr($pieces[1], 1);
            if($cyear == $reqcyear){
                if($reqaction == '-') {
                    $subpiece=str_replace($reqalert, '', $subpiece );
                }
                if($reqaction == '+') {
                    $subpiece .=$reqalert;
                }
            }
            $newsubs .=  $subpiece . ',';
        }
        $newsubs = trim($newsubs, ',');

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
else{
echo("i didn't find you in the db");
}


?>