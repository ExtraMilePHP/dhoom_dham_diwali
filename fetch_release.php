<?php
error_reporting(E_ALL);
session_start();
include_once 'dao/config.php';

$settings=json_decode(file_get_contents("admin/settings.js"),true)[0];
$data = json_decode(file_get_contents('php://input'), true);
$userId=$_SESSION['userId'];
$email=$_SESSION['email'];
$sessionId=$_SESSION['sessionId'];
$organizationId=$_SESSION['organizationId'];
include_once '../admin_assets/triggers-new.php';

$array_values=default_data("values_array");
$shuffle=default_data("shuffle");
$launchpos=default_data("launchpos");
$numlimit=default_data("numlimit");


   $launchpos=$launchpos*$numlimit;
   $launchpos=$launchpos+1;
   if($numlimit<3){
     $numlimit=5;
   }

  //  print_r($array_values);
   echo json_encode(array("array_values"=>$array_values,"shuffle"=>$shuffle,"launchpos"=>$launchpos,"numlimit"=>$numlimit));
  // echo "<tr>";
  // for ($i = 1; $i < $launchpos; $i++) {
  //   if ($i % $numlimit == 1 && $i != 1) {
  //     echo "</tr><tr>";
  //   }
  //   $prepare_num=$shuffle[$i-1];
  //   echo  "<td style='border:1px solid black;'>".$array_values[$prepare_num-1]."</td>";
  // }
  // echo "</tr>"
?>