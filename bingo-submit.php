<?php 
session_start();
include_once 'dao/config.php';
include_once '../add_report.php';
$data = json_decode(file_get_contents('php://input'), true);
$userid=$data["userId"];
$organizationId=$data["organizationId"];
$numbers=$data["numbers"];
$sessionId=$_SESSION['sessionId'];
$organizationName=$_SESSION['organizationName'];


$settings=json_decode(file_get_contents("admin/settings.js"),true)[0];
include_once '../admin_assets/triggers-new.php';
$prevent_submit=toogles("prevent_submit");
$prevent_claim=toogles("prevent_claim");

$numbers=serialize($numbers);
$fullName=$_SESSION["firstName"]." ".$_SESSION["lastName"];
$email=$_SESSION["email"];
$insert="INSERT INTO `collect`(`userid`, `organizationId`,`sessionId`,`orgName`,`email`,`name`,`numbers`) VALUES ('$userid','$organizationId','$sessionId','$organizationName','$email','$fullName','$numbers')";

if($prevent_submit){
    echo json_encode(array("success"=>false,"error"=>"Tikit Submit disabled by admin"));
}else{
    $check="select * from collect where userid='$userid'";
    $check=mysqli_query($con,$check);
    $check=mysqli_num_rows($check);
    if($check==0){
        if(mysqli_query($con,$insert)){
            function successResponse($tools){
                echo json_encode(array("success"=>true,"isdemo"=>$tools["isdemo"]));
            }
            $data=["points"=>"0","time"=>"NA"];
            addReport($data);
        }else{
            echo json_encode(array("success"=>false,"error"=>"something went wrong ".mysqli_error($con)));
        }
    }else{
            echo json_encode(array("success"=>false,"error"=>"Tikit already created."));
    }
}

?>