<?php
//Author Domenico Monaco
//simple example of recursive require and read *.ext of files
$dir = "./path_file/"; //Set dir
$dh = opendir($dir);
 
while (($file = readdir($dh)) !== false) {
 
  $fExt= end(explode(".", $file));
 
  if ($fExt=='php'){ //if ext of file is php or other *.ext
    $rFile[]=$file; //add file to array
  }
}
 
closedir($dh);
 
// recursive inlcuding of file of array
for ($d=0;$d<$dmax=(sizeof($rFile));$d++){
  require ($dir.$rFile[$d]);  
}
?>
