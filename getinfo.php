<?php

include'settings.php';
require 'steamauth/steamauth.php';
include ('steamauth/userInfo.php'); //To access the $steamprofile array



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
			echo'0';
		}
		$conn->close();
		return "0";
		
	}

if(isset($_GET["item"])){

	$value = $_GET["item"];
	
	if($value == "coins"){
		if(isset($_SESSION['steamid'])){
			echo(getcoin($steamprofile["steamid"]));
		}
		else{
		echo'0';	
		}
	}
	
}

?>