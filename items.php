<?php

function getallitems(){

$files1 = scandir("jsonitems/",1);
$files1[1] = null;
$files2[2] = null;

return $files1;
}

function getitemjson($shortname){
	$cdir = scandir("jsonitems",0);
	$cdir[0] = null;
	$cdir[1] = null;
	foreach($cdir as $ser){
		
		if($ser != null){
		
			$myfile = fopen("jsonitems/".$ser, "r") or die("Unable to open file!");
		$contnet = fread($myfile,filesize("jsonitems/".$ser));
		 $obj = json_decode($contnet,true);
		 if($obj["ShortName"] == $shortname){
			 
			 return $contnet;
		 }
		
		}
	
}
}

?>