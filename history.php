<center><div class="boxed">
<?php
include'historymanager.php';
include'settings.php';
require 'steamauth/steamauth.php';


echo'
 <style>
 
 .boxed {
   width: 1000px;
    padding: 10px;
    margin: 0;
	background:brown;
	border-radius: 25px;
} 
 
 body{
    background-image: url("background.jpg");
    background-repeat: no-repeat;
    background-attachment: fixed;
}
 
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
 
  #textwindow{
	color: black;
	display: inline-block;
    text-align: left;
	font-size: 115%;
	height: 350px;
	min-width: 500px;
	max-width: 1000px;


	
	background: white;
    border-radius: 5px;
	 padding: 10px 20px 10px 20px;

	
 }
 
  #navigator{
	color: white;
	display: inline-block;
    text-align: center;
	font-size: 125%;

	
	background: brown;
    border-radius: 5px;
	  padding: 10px 20px 10px 20px;

	
 }
 
  #pagedisplay {
  background: #202224;
  background-image: -webkit-linear-gradient(top, #202224, #2f3438);
  background-image: -moz-linear-gradient(top, #202224, #2f3438);
  background-image: -ms-linear-gradient(top, #202224, #2f3438);
  background-image: -o-linear-gradient(top, #202224, #2f3438);
  background-image: linear-gradient(to bottom, #008000, #008000);
  -webkit-border-radius: 28;
  -moz-border-radius: 28;
  border-radius: 28px;
  font-family: Arial;
  color: #ffffff;
  font-size: 10px;
  padding: 10px 20px 10px 20px;
  text-decoration: none;
}
 #btn {
  background: #202224;
  background-image: -webkit-linear-gradient(top, #202224, #2f3438);
  background-image: -moz-linear-gradient(top, #202224, #2f3438);
  background-image: -ms-linear-gradient(top, #202224, #2f3438);
  background-image: -o-linear-gradient(top, #202224, #2f3438);
  background-image: linear-gradient(to bottom, #008000, #008000);
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

#texter{
color: black;	
}
#pagetexter{
	font-family: "Arial Black";
		font-size: 125%;
color: black;	
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
    top: 50%;
    left: 50%;
    margin-left: -25px;

}
</style>
';

if(!isset($_SESSION['steamid'])) {
	echo'
	<a href="?login" class="button" id="disconnect2">Login</a>
	
	';

}  else {

    include ('steamauth/userInfo.php'); //To access the $steamprofile array
	
	echo('<form id="userframe">');
	echo('<img src="'.$steamprofile['avatarmedium'].'g" alt="User avatar">');
	 echo('<br><h1><img src="coin.ico" alt="" width="25" height="25"><thecoins id="coins"></theitem></img></h1>');

	 echo'
	 
	 
	 <br>
	 
	<br>
		 <a class="btn" href="shop.php" id="disconnect">Shop</a>

	<a class="btn" href="inventory.php" id="disconnect">Refund</a>
	 <button name="logout" type="submit" action="" method="get" id="disconnect">Logout</button><br>
	 	<br>
		

	 </form>
	 
	
		<br>


	 ';
	
	 
	 
	
  
}  


?>


<form id="textwindow"><h1 id="texter" name="pagecontainment">
</h1></form><br>
	
	<form id="navigator" onsubmit="return false">
	<span id="pagetexter"> 1 </span><br>
	<button onclick="nextpageminus()" value=">" id="btn" type="submit"><</button>
	<button onclick="nextpageplus()" value=">" type="submit" id="btn">></button><br><br>
	<input type="button" id="btn" onclick="location.href='/commands.php?command=downloadhistory';" value="Download history" />
	</form>
		
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="notify.js"></script>

	
	<script>
	
	var gethighestwidths = 0;
	var gethighestwidthe = 0;
	var gethighestheights = 0;
	var gethighestheighte = 0;
	var page = 0;
	var maxpage = 0;
	maxpageget();
	downloadhistory();
	
	getcoins();
	function getcoins(){

	var xmlHttp = new XMLHttpRequest();
		xmlHttp.onreadystatechange = function() { 
			if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
				
			document.getElementById("coins").innerHTML = xmlHttp.responseText;
		}
		xmlHttp.open("GET", "getinfo.php?item=coins", true); // true for asynchronous 
		xmlHttp.send(null);	
		
	}
	
	
	function nextpageplus(){
			if(page + 1 < maxpage){
			page = page + 1;
			document.getElementById("pagetexter").innerHTML = (page + 1) + "/"+maxpage;
		}
		downloadhistory();
		
	}
	function nextpageminus(){
		if(page > 0){
		page = page - 1;
				document.getElementById("pagetexter").innerHTML = (page + 1) + "/"+maxpage;

		downloadhistory();
		}
	}
	
	
	function maxpageget(){

	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		
		  var thereturnmessage = parseInt(this.responseText);
			if(thereturnmessage == 0){
			thereturnmessage = 1;	
			}
			document.getElementById("pagetexter").innerHTML = (page + 1) + "/"+thereturnmessage;

		  maxpage = thereturnmessage;
		}
	  };
	  xhttp.open("GET", "commands.php?command=maxpage&size=15", true);
	  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	  xhttp.send("");
	}
	
	
	
	function downloadhistory(){

	var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		
		  var thereturnmessage = this.responseText;
		  document.getElementById("textwindow").innerHTML = thereturnmessage;
		  
		}
	  };
	  xhttp.open("GET", "commands.php?command=downloadhistoryhtml&page="+page+"&size=15", true);
	  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	  xhttp.send("");
	}
	
	

	function sendtext(arg){
		
	}

	</script>
	

</div></center>

