<?php
include'settings.php';

echo'
 <body background="background.jpg">
 <style>
 #itemframe{
	color: brown;
	display: inline-block;
    text-align: center;

	
	background: brown;
    border-radius: 5px;
	padding: 10px 20px 10px 20px;

	
 }
 h1{
 color:white;
 }
 #userframe{
	color: gray;
	display: inline-block;
    text-align: center;

	
	background: brown;
    border-radius: 5px;
	  padding: 10px 20px 10px 20px;

	
 }
 #btn {
  background: #202224;
  background-image: -webkit-linear-gradient(top, #202224, #2f3438);
  background-image: -moz-linear-gradient(top, #202224, #2f3438);
  background-image: -ms-linear-gradient(top, #202224, #2f3438);
  background-image: -o-linear-gradient(top, #202224, #2f3438);
  background-image: linear-gradient(to bottom, #202224, #2f3438);
  -webkit-border-radius: 28;
  -moz-border-radius: 28;
  border-radius: 28px;
  font-family: Arial;
  color: #ffffff;
  font-size: 10px;
  padding: 10px 20px 10px 20px;
  text-decoration: none;
}
h1{
color: white;	
}

#disconnect {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
}
 </style>

 
';

function getinventory($steamid){
	$orders = array();
	$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, steamid, item, amount FROM players_orders";
$result = $conn->query($sql);
$id = 0;

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      if($row["steamid"] == $steamid){
	  $itemarray = array($row["id"],$row["steamid"],$row["item"],$row["amount"]);
	  $orders[$id] = $itemarray;
	  $id = $id + 1;
	  }
    }
} else {
    echo "0 results";
}
$conn->close();	
return $orders;
}

function getitemjson($shortname){
	$cdir = scandir("jsonitems",0);
	$cdir[0] = null;
	$cdir[1] = null;
	foreach($cdir as $ser){
		
		if($ser != null){
		
			$myfile = fopen("jsonitems/".$ser, "r") or die("Unable to open file!");
		$contnet = fread($myfile,filesize("jsonitems/".$ser));
		 $obj = json_decode($contnet,true);
		 if($obj["ShortName"] == $shortname){
			 
			 return $contnet;
		 }
		
		}
	
}
}

function getcoin($steamid){



// Create connection
$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, steamid, coins FROM PLAYERS_COINS";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      if($row["steamid"] == $steamid){
		return ($row["coins"]);  
	  }
    }
} else {
    echo "0 results";
}
$conn->close();
return "0";
}

function getprice($itemid){
	
	$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM players_orders WHERE id=".$itemid;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    $amount = intval($row["amount"]);
	$jsonitem = json_decode(getitemjson($row["item"]),true);
	$price = intval($jsonitem["Price"]) * $amount;
	return $price;
	}
} else {
}
$conn->close();
	
}

function deleteitem($itemid){
	
	$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// sql to delete a record
$sql = "DELETE FROM players_orders WHERE id=".$itemid;

if ($conn->query($sql) === TRUE) {
} else {
}

$conn->close();
	
}

require 'steamauth/steamauth.php';

$ServerName = "Normans rust store";

include ('steamauth/userInfo.php');

if(isset($_POST["itemname"]) && isset($_POST["itemnameid"]) && isset($_SESSION['steamid'])){

	$itemname = $_POST["itemname"];
	
	echo'
<center><form id="itemframe"><h1>';


$togive = getprice($_POST["itemnameid"]);
$currentcoin = getcoin($steamprofile["steamid"]);



echo("Giving a refund for ".$togive." coins.<br>");

$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
						
						if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
						}

						
						$sql = "UPDATE PLAYERS_COINS SET coins='".($currentcoin + $togive)."' WHERE steamid='".$steamprofile['steamid']."'";

						if ($conn->query($sql) === TRUE) {
							
							echo'You have now been given a refund!<br>';
							echo'Removing item... <br>';
							deleteitem($_POST["itemnameid"]);
							echo'Removed item!<br>';


						} else {
							
						}

						$conn->close();



echo'<br>Redirecting in 2 seconds.
<meta http-equiv="refresh" content="2; url=inventory.php" />						
<h1></form></center>
';
	
	
}
else{
echo'
<center><form id="itemframe"><h1>
Error!
<br>Redirecting in 5 seconds.
<meta http-equiv="refresh" content="5; url=inventory.php" />						
<h1></form></center>
';
}

?>