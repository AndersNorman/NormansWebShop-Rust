<?php

function getallitems(){

$files1 = scandir("jsonitems/",1);
$files1[1] = null;
$files2[2] = null;

return $files1;
}


?>