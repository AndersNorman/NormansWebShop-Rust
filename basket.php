 <?php

include'settings.php';

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
	if(is_numeric($amount) && !strpos($amount,".")){
	include ('steamauth/userInfo.php');
	$jsonitem = getitem($itemname);
	$coins = getcoin($steamprofile['steamid']);
	if($jsonitem != null){
			if($coins != null){
						
						$amount = doubleval($amount);
						$price = doubleval($jsonitem["Price"]);
						$coins = doubleval($coins);
						$total = $amount * $price;
						if($total > $coins){

						echo'norman-1';
						
						
						
						}
						else{
						
						echo'norman+1';


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
						
						
						
						
						

			}
		}
	}
}
?>