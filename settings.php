 <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rust";

$bodycss = '

 body{
    background-color: black;
    background-repeat: no-repeat;
    background-attachment: fixed;
}

';

function servernameget(){
	return $GLOBALS['servername'];
}
function usernameget(){
		return $GLOBALS['username'];
}

function passwordget(){
		return $GLOBALS['password'];
}

function dbnameget(){
return $GLOBALS['dbname'];	
}

 ?>