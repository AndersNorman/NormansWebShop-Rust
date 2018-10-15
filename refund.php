<?php
include'historymanager.php';
include'settings.php';

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
    $amount = doubleval($row["amount"]);
	$jsonitem = json_decode(getitemjson($row["item"]),true);
	$price = doubleval($jsonitem["Price"]) * $amount;
	
	$fancyman = $jsonitem["Name"];
	addtohistory($row['steamid'],"Player refunded the purchase of " . $fancyman . " x ".$amount." for ".$price." coins");
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
	



$togive = getprice($_POST["itemnameid"]);
$currentcoin = getcoin($steamprofile["steamid"]);




$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
						
						if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
						}

						
						$sql = "UPDATE PLAYERS_COINS SET coins='".($currentcoin + $togive)."' WHERE steamid='".$steamprofile['steamid']."'";

						if ($conn->query($sql) === TRUE) {
							
							deleteitem($_POST["itemnameid"]);
							echo("norman+1");


						} else {
						  
						}

						$conn->close();




	
	
}
else{
echo'norman-1';	
}


?>