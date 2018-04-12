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
// Create connection
$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// sql to create table
$sql = "CREATE TABLE PLAYERS_COINS (
id INT(64) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
steamid VARCHAR(255),
coins VARCHAR(50),
reg_date TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
} else {
}

$conn->close();

$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// sql to create table
$sql = "CREATE TABLE players_orders (
id INT(64) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
steamid VARCHAR(255),
item VARCHAR(50),
amount VARCHAR(50),
reg_date TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
	
} else {
	
}

$conn->close();

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

function getitem($shortname){
	$cdir = scandir("jsonitems",0);
	$cdir[0] = null;
	$cdir[1] = null;
	foreach($cdir as $s){
	
		if($s != null){
		$myfile = fopen("jsonitems/".$s, "r") or die("Unable to open file!");
		$contnet = fread($myfile,filesize("jsonitems/".$s));
		$obj = json_decode($contnet,true);
			if($obj["ShortName"] == $shortname){
			return $obj;
			}
		}
	}
	
	
	return null;
}

require 'steamauth/steamauth.php';

if(isset($_POST["amount"]) && isset($_POST["itemname"]) && isset($_SESSION['steamid'])){
	$amount = $_POST["amount"];
	$itemname = $_POST["itemname"];
	if(is_numeric($amount)){
	include ('steamauth/userInfo.php');
	$jsonitem = getitem($itemname);
	$coins = getcoin($steamprofile['steamid']);
	if($jsonitem != null){
			if($coins != null){
						echo('<center><form id="itemframe"><h1>');
						echo("Valid order!");
						echo("<br> counting price..");
						$amount = intval($amount);
						$price = intval($jsonitem["Price"]);
						$coins = intval($coins);
						$total = $amount * $price;
						if($total > $coins){

						echo("<br>You can't affor to purchase these! the price is ".$total." coins and you currently have ".$coins." coins.");
						
						
						
						
						}
						else{
						echo("Order has been added, to claim them do /claim ingame!");
						
						


						// Create connection
						$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
						// Check connection
						if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
						}

						$sql = "INSERT INTO players_orders (steamid, item, amount)
						VALUES ('".$steamprofile['steamid']."', '".$itemname."', '".$amount."')";

						if ($conn->query($sql) === TRUE) {
							
						} else {
							
						}

						$conn->close();
						
						
						$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
						
						if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
						}

						
						$sql = "UPDATE PLAYERS_COINS SET coins='".($coins - $total)."' WHERE steamid='".$steamprofile['steamid']."'";

						if ($conn->query($sql) === TRUE) {
							
						} else {
							
						}

						$conn->close();
						
						}
						echo'
						<br>Redirecting in 5 seconds.
						<meta http-equiv="refresh" content="5; url=shop.php" />
						';
						echo('<br></form></center>');
						
						
						
						

			}
		}
	}
}
?>
<br>