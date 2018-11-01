 <?php
 
 
 
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

function addorder($steamid,$itemname,$amount){

$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
						// Check connection
						if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
						}

						$sql = "INSERT INTO players_orders (steamid, item, amount)
						VALUES ('".$steamid."', '".$itemname."', '".$amount."')";

						if ($conn->query($sql) === TRUE) {
							
						} else {
							
						}

						$conn->close();
						
						
						$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
						
						if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
						}	
		
	
}

function changemoney($steamid,$amount){
	
	$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
						// Check connection
						if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
						}

						$sql = "UPDATE PLAYERS_COINS SET coins='".$amount."' WHERE steamid='".$steamid."'";

						if ($conn->query($sql) === TRUE) {
							
						} else {
							
						}

						$conn->close();
						
						
						$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
						
						if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
						}	
	
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

function addtohistory($steamid,$message){

$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO player_history (steamid, historymessage)
VALUES ('".$steamid."', '".$message."')";

if ($conn->query($sql) === TRUE) {
    
} else {
    
}

$conn->close();	
	
}

function gethistory($steamid){
$alltogheter = "";
$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, steamid, historymessage, reg_date FROM player_history where steamid='".$steamid."'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
$alltogheter = $alltogheter . $row["reg_date"]. ": ". $row["historymessage"].
'
';
    }
} else {

}
$conn->close();
	return $alltogheter;
}

function gethistorylines($steamid){
		$at = 0;

$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, steamid, historymessage FROM player_history where steamid='".$steamid."'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    $at = $at + 1;
	}
} else {

}
$conn->close();
	return $at;
}

function gethistoryhtml($steamid,$start,$end){
$alltogheter = "";
$conn = new mysqli(servernameget(), usernameget(), passwordget(), dbnameget());
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, steamid, historymessage, reg_date FROM player_history where steamid='".$steamid."'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
	$at = 0;
    while($row = $result->fetch_assoc()) {
	
	if($at >= $start && $at <= $end){

	$alltogheter = $alltogheter . $row["reg_date"].": ". $row["historymessage"]. "<br>";
	}
    $at = $at + 1;
	}
} else {

}
$conn->close();
	return $alltogheter;
}



?>