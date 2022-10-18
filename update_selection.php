<?php
session_start();
include_once 'dao/config.php';
$userId=$_SESSION['userId'];
// $organizationId=$_SESSION['organizationId'];
// $sessionId=$_SESSION['sessionId'];
$data = json_decode(file_get_contents('php://input'), true);
$tickit_select=$data["tickit_select"];
$tickit_select=serialize($tickit_select);
$update="UPDATE `collect` SET `highlight`='$tickit_select' WHERE userid='$userId'";
if(mysqli_query($con,$update)){
    echo "updated";
}
?>