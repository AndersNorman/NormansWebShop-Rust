<?php
include'settings.php';
require'items.php';

require 'steamauth/steamauth.php';

$ServerName = "Normans rust store";

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
	font-family: "Arial Black";
    display: inline-block;
    font-size: 16px;
}
 </style>

 
';

if(!isset($_SESSION['steamid'])) {
	echo'<center>
	<a href="?login" class="button" id="disconnect">Login</a>
	</center>
	';

}  else {

    include ('steamauth/userInfo.php'); //To access the $steamprofile array
	
	echo('<form id="userframe">');
	echo('<img src="'.$steamprofile['avatarmedium'].'g" alt="User avatar">');
	 echo('<br><h1><img src="coin.ico" alt="" width="25" height="25"> <thecoins id="coins"></theitem></img></h1>');

	 echo'
	 
	 <br>
	 
	<br>
	 <a class="btn" href="shop.php" id="disconnect">Shop</a>
	 <button name="logout" type="submit" action="" method="get" id="disconnect">Logout</button>
	 </form>
	 <br>
	 ';

		
	$theitems = getinventory($steamprofile["steamid"]);
	
	foreach($theitems as $thearray){	
	$jsonitem = json_decode(getitemjson($thearray[2]),true);
	$theitemcost = (doubleval($jsonitem["Price"]) * doubleval($thearray[3]));
		 echo'<form id="itemframe" style="width: 250" onsubmit="return false;" name="'.$jsonitem["ShortName"].'">
		 
		 <font face="verdana" size="4" color="white">item: '.$jsonitem["Name"].' X '.$thearray[3].'<br><br>
	      <img src="coin.ico" alt="" width="25" height="25"> '.$theitemcost.'</img>
		  
		  <center>	      <img src="'.$jsonitem["Icon"].'" alt="" width="100" height="100"></img></center><br>
		  
		  		   
				   
				   <input type="hidden" name="itemname" value="'.$jsonitem["ShortName"].'"/> 
				   <input type="hidden" name="itemnameid" value="'.$thearray[0].'"/> 
				   
				   <center><button onclick="sendrefundrequest('."'".$jsonitem["ShortName"]."'".')" id="btn" value="Refund">Refund</center></button>
				   </form>

		  <br>
		 </font>
		 
	
		
		 ';
	}
  
}  


?>




	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="notify.js"></script>

<script>

	function sendrefundrequest(theitem){
	var x = document.getElementsByName(theitem)[0].elements.namedItem("itemname").value;
	var y = document.getElementsByName(theitem)[0].elements.namedItem("itemnameid").value;

	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {

		  getcoins();
		  var thereturnmessage = this.responseText;
		  
		  if(thereturnmessage.includes("norman+1")){
			 		  $.notify("You got a refund without any errors!","success");
					  var element = document.getElementsByName(theitem)[0];
					  element.outerHTML = "";
					  delete element;
		  }
		  else{
			  			 		  $.notify("There was a error!","warn");
								  

		  }
		}
	  };
	  xhttp.open("POST", "refund.php", true);
	  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	  xhttp.send("itemnameid="+y+"&itemname="+x);
	}

function getcoins(){

	var xmlHttp = new XMLHttpRequest();
		xmlHttp.onreadystatechange = function() { 
			if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
				
			document.getElementById("coins").innerHTML = xmlHttp.responseText;
		}
		xmlHttp.open("GET", "getinfo.php?item=coins", true); // true for asynchronous 
		xmlHttp.send(null);	
		
}

	getcoins();


</script>