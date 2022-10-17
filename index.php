
<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once("../seo-head.php");?>
  <title>Dhoom Dham Diwali</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/styles.css">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
<?php include_once("../seo-body.php");?>
<?php
ob_start();
session_start();
error_reporting(0);
include_once 'dao/config.php';
$whiteLogo=true;
include_once("../login-default.php");

$userid=$_SESSION["userId"];
$organizationId=$_SESSION["organizationId"];
$sessionId=$_SESSION['sessionId'];
$organizationName=$_SESSION['organizationName'];
$fullName=$_SESSION["firstName"]." ".$_SESSION["lastName"];
$email=$_SESSION["email"];

if(function_exists('date_default_timezone_set')) {
  date_default_timezone_set("Asia/Kolkata");
}
$timestamp = date('Y-m-d H:i:s');

$check="select * from log_data where userid='$userid'";
$check=mysqli_query($con,$check);
$check=mysqli_num_rows($check);
if($check==0){
  $insert="INSERT INTO `log_data`(`userId`, `organizationId`,`sessionId`, `name`, `email`, `timestamp`, `timestamp_update`) VALUES ('$userid','$organizationId','$sessionId','$fullName','$email','$timestamp','$timestamp')";
  mysqli_query($con,$insert);
}else{
  $update="update log_data set timestamp_update='$timestamp' where userid='$userid'";
  mysqli_query($con,$update);
}

// if($loginSuccess){
//   $userId=$_SESSION['userId'];
//   $organizationId=$_SESSION['organizationId'];
//   $sessionId=$_SESSION['sessionId'];
//   $check="select * from collect where userid='$userId' and organizationId='$organizationId' and sessionId='$sessionId'";
//   $check=mysqli_query($con,$check);
//   $check=mysqli_num_rows($check);
//   if($check>0){
//     header("Location:full-house.php");
//   }
// }
?>
<?php include("../actions-default.php");  back("https://extramileplay.com");?>
<div class="container-fluid container-control">
<div class="row">
<div class="col-md-4 auto"></div>
<div class="col-md-4 auto"><img src="images/welcome-logo.gif" class="welcome-logo"/></div>
<div class="col-md-4 auto"></div>
<div class="col-md-12 text-center">
<?php if(isset($loginSuccess)){
  echo '<a href="create.php"><div class="btn btn-info begin">BEGIN PLAY</div></a>';
}?>
</div>
</div>
</div>


<script>

</script>
</body>
</html>
