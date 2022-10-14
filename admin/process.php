<?php
error_reporting(-1);
include_once '../dao/config.php';
session_start();
$organizationId=$_SESSION['organizationId'];
$sessionId=$_SESSION['sessionId'];
$preexist_array=array("words");    // first row of excel to detect this data
$database_fields=array("value"); 
$table_name="settings"; // table name
$filename=$_FILES['file']; // filename

require_once("insertCSV.php");
$insertCSV=new insertCSV($filename,$preexist_array,$database_fields,$table_name,$con,$organizationId,$sessionId);
$insertCSV->run();
/*
WARNING ******************
Truncate function added in insertCSV 
*/


?>