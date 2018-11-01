<?php
include'codemanager.php';
include'settings.php';

require 'steamauth/steamauth.php';



if(!isset($_SESSION['steamid'])) {
	

}  else {

    include ('steamauth/userInfo.php'); //To access the $steamprofile array
	
	if(isset($_GET["command"])){
	$command = $_GET["command"];
	
	$gdate = getdate();
	$commandexecutedatestring = $gdate["year"]."-".$gdate["mon"]."-".$gdate["mday"]." ".$gdate["hours"]."-".$gdate["minutes"]."-".$gdate["seconds"];
		if($command == "downloadhistory"){
			
			

		header('Content-type: text/plain');
		
		header('Content-Disposition: attachment;filename="history - '.$commandexecutedatestring.'.txt"');
		/*
		assign file content to a PHP Variable $content
		*/
		
		echo gethistory($steamprofile["steamid"]);
		}
		if($command=="maxpage"){
			if(isset($_GET["size"]) && isset($steamprofile["steamid"])){
				$size = $_GET["size"];
				$alltogheter = gethistorylines($steamprofile["steamid"]);
				$alltogheter = ceil($alltogheter / $size);
				echo($alltogheter);
			}
		}
		
		if($command="downloadhistoryhtml"){
			if(isset($_GET["page"])){
			$page = $_GET["page"];
				if(isset($_GET["size"])){
				$size = $_GET["size"];
					$endstart = ($page * $size);
					$endend = $endstart + $size;
					if(isset($steamprofile["steamid"])){
					
					echo gethistoryhtml($steamprofile["steamid"],$endstart,$endend);
					}
				}
			}
		}
	
	}




	
}

	?>