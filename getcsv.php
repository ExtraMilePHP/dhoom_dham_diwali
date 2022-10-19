<?php
 ob_start();
 session_start();
 include_once 'dao/config.php';

		

 $sql = "SELECT userid,organizationId,name,email,orgName,numbers from collect";

$qur = mysqli_query($con,$sql);
 
// Enable to download this file
$filename = "all_collect.csv";
 
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: text/csv");
 
$display = fopen("php://output", 'w');
 
$flag = false;
while($row = mysqli_fetch_assoc($qur)) {
    if(!$flag) {
      // display field/column names as first row
      fputcsv($display, array_keys($row), ",", '"');
      $flag = true;
    }
    fputcsv($display, array_values($row), ",", '"');
  }
 
fclose($display);