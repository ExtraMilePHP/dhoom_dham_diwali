<?php
ob_start();
error_reporting(0);
session_start();
include_once '../dao/config.php';
$words_array=$_POST["variables_array"];
$words_array=serialize($words_array);

$setpos="UPDATE `launchpad` SET `data`='$words_array' WHERE userid='EM000'";
if(mysqli_query($con,$setpos)){
    echo "true";
}else{
    echo "something went wrong".mysqli_error($con);
}
?>