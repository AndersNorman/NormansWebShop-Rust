 <?php

include'codemanager.php';
include'settings.php';


$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// sql to create table
$sql = "CREATE TABLE player_history (
id INT(64) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
steamid VARCHAR(255),
historymessage TEXT CHARACTER SET utf8,
reg_date TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
} else {
}

$conn->close();

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

require 'steamauth/steamauth.php';

if(isset($_POST["amount"]) && isset($_POST["itemname"]) && isset($_SESSION['steamid'])){
	$amount = $_POST["amount"];
	$itemname = $_POST["itemname"];
	if(is_numeric($amount) && !strpos($amount,".")){
	include ('steamauth/userInfo.php');
	$jsonitem = getitem($itemname);
	$coins = getcoin($steamprofile['steamid']);
	if($jsonitem != null){
			if($coins != null){
						
						$amount = floor(doubleval($amount));
						$price = doubleval($jsonitem["Price"]);
						$coins = doubleval($coins);
						$total = $amount * $price;
						if($total > $coins || $amount < 1){

						$fancyman = getitem($itemname)["Name"];
						addtohistory($steamprofile['steamid'],"Player failed purchase of item " . $fancyman . " x ".$amount." for ".$total." coins");
						echo'norman-1';
						
						
						
						}
						else{
						
						echo'norman+1';


						// add order
						addorder($steamprofile["steamid"],$itemname,$amount);

						
						changemoney($steamprofile["steamid"],$coins - $total);
						
						addtohistory($steamprofile['steamid'],"Player purchased item " . $fancyman . " x ".$amount." for ".$total." coins");


						
						
						
						
						
						}

			}
		}
	}
}
?>