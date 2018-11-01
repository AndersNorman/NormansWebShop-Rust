<?php
include'settings.php';

require 'steamauth/steamauth.php';

$ServerName = "Normans rust store";


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
<style>
 
'.$bodycss.'
 
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
	font-family: "Arial Black";
    text-decoration: none;
    display: inline-block;
    font-size: 16px;

}

#disconnect2 {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
	font-family: "Arial Black";
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
	position: fixed;
	width:120px;
    top: 50%;
    left: 50%;
    margin-left: -100px;

}
 </style>
';




if(!isset($_SESSION['steamid'])) {
	echo'<center><a href="?login" class="button" id="disconnect2">Login</a></center>
	';

}  else {

    include ('steamauth/userInfo.php'); //To access the $steamprofile array
	
	echo('<center><form id="userframe">');
	echo('<img src="'.$steamprofile['avatarmedium'].'g" alt="User avatar">');
	 echo('<br><h1><img src="coin.ico" alt="" width="25" height="25"><thecoins id="coins"></theitem></img></h1>');

	 echo'
	 
	 
	 <br>
	 
	<br>
	<a class="btn" href="inventory.php" id="disconnect">Refund</a>
		<a class="btn" href="history.php" id="disconnect">History</a>

	 <button name="logout" type="submit" action="" method="get" id="disconnect">Logout</button>
	 </form>
	 
	  
	 <br>
	 </center>';

	require("items.php");
	
	$cdir = scandir("jsonitems",0);
	$cdir[0] = null;
	$cdir[1] = null;
	
	$theitems = array();
	$thetabs = array();

	$where = 0;
	foreach($cdir as $ser){
		
		if($ser != null){
		$myfile = fopen("jsonitems/".$ser, "r") or die("Unable to open file!");
		$contnet = fread($myfile,filesize("jsonitems/".$ser));
		 $obj = json_decode($contnet,true);
		 $obj["Price"] = doubleval($obj["Price"]);
		 $theitems[$where] = $obj;
		 
		$where = $where + 1;
		}
	
	}
	
	$cdir = scandir("jsontabs",0);
	$cdir[0] = null;
	$cdir[1] = null;
	
	foreach($cdir as $ser){
		
		if($ser != null){
		$myfile = fopen("jsontabs/".$ser, "r") or die("Unable to open file!");
		$contnet = fread($myfile,filesize("jsontabs/".$ser));
		 $obj = json_decode($contnet,true);
		 $thetabs[$where] = $obj;
		 
		$where = $where + 1;
		}
	
	}
	
	usort($theitems, function($a, $b) { //Sort the array using a user defined function
    return $a["Price"] > $b["Price"] ? -1 : 1; //Compare the scores
	});

	foreach($thetabs as $thetab){
		$alltoghetertext = "";
		echo'<center><br><h1><img src="'.$thetab["Icon"].'" alt="" width="64" height="64" style="vertical-align: middle;"></img><span style="vertical-align: middle;">'.$thetab["Tab"].'</h1></span><br></center>';
		foreach($theitems as $obj){
			if($obj["Tab"] == $thetab["Tab"]){
			$alltoghetertext = $alltoghetertext . '<form id="itemframe" style="width: 250" onsubmit="return false;" name="'.$obj["ShortName"].'">
			 
			 <font face="verdana" size="4" color="white">'.$obj["Name"].'<br><br>
			  <img src="coin.ico" alt="" width="25" height="25"> '.$obj["Price"].'</img>
			  
			  <center>	      <img src="'.$obj["Icon"].'" alt="" width="100" height="100"></img></center><br>
			  
						<center><input type="text" name="amount" value="1" size="2"/></center>

					   
					   
					   <input type="hidden" name="itemname" value="'.$obj["ShortName"].'"/> 
					   
					   <center><button onclick="sendpurchaserequest('."'".$obj["ShortName"]."'".')" id="btn" value="Purchase">Purchase</center></button>
					   </form>

			  <br>
			 </font>
			 
		
			
			 ';
			}
			
		}
				echo("<center>".$alltoghetertext."</center>");

	}
	
	
	
	
  
}  


?>



	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="notify.js"></script>

	<script>
	// Java scripts for seeing money and adding orders!
	

	function getcoins(){

	var xmlHttp = new XMLHttpRequest();
		xmlHttp.onreadystatechange = function() { 
			if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
				
			document.getElementById("coins").innerHTML = xmlHttp.responseText;
		}
		xmlHttp.open("GET", "getinfo.php?item=coins", true); // true for asynchronous 
		xmlHttp.send(null);	
		
	}
	
	function sendpurchaserequest(theitem){
	var x = document.getElementsByName(theitem)[0].elements.namedItem("itemname").value;
	var y = document.getElementsByName(theitem)[0].elements.namedItem("amount").value;

	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {

		  getcoins();
		  var thereturnmessage = this.responseText;
		  
		  if(thereturnmessage.includes("norman+1")){
			 		  $.notify("You purchased the item without any errors!","success");

		  }
		  else{
			  			 		  $.notify("You couldn't buy this item!","warn");

		  }
		}
	  };
	  xhttp.open("POST", "basket.php", true);
	  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	  xhttp.send("amount="+y+"&itemname="+x);
	}

	function sendtext(arg){
		
	}

	getcoins();
	</script>
	

