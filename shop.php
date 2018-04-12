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
	 echo('<br><h1><img src="coin.ico" alt="" width="25" height="25"> '.getcoin($steamprofile['steamid']).'</img></h1>');

	 echo'
	 
	 <form action="" method="get">
	 <button name="logout" type="submit" id="disconnect">Logout</button>
	 </form>
	 </form>
	 <br>
	 ';

	require("items.php");
	
	$cdir = scandir("jsonitems",0);
	$cdir[0] = null;
	$cdir[1] = null;
	foreach($cdir as $ser){
		
		if($ser != null){
		$myfile = fopen("jsonitems/".$ser, "r") or die("Unable to open file!");
		$contnet = fread($myfile,filesize("jsonitems/".$ser));
		 $obj = json_decode($contnet,true);
		 echo'<form id="itemframe" style="width: 250" action="basket.php" method="post" name="'.$obj["ShortName"].'">
		 
		 <font face="verdana" size="4" color="white">item: '.$obj["Name"].'<br><br>
	      <img src="coin.ico" alt="" width="25" height="25"> '.$obj["Price"].'</img>
		  
		  <center>	      <img src="'.$obj["Icon"].'" alt="" width="100" height="100"></img></center><br>
		  
					<center><input type="text" name="amount" value="1" size="2"/></center>

		  		   
				   
				   <input type="hidden" name="itemname" value="'.$obj["ShortName"].'"/> 
				   
				   <center><input type="submit" id="btn" value="Purchase"></center>
				   </form>

		  <br>
		 </font>
		 
	
		
		 ';
		
		}
	
	}
	
	
  
}  


?>