 <?php

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