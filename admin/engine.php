<?php
ob_start();
error_reporting(E_ALL);
session_start();
$settings=json_decode(file_get_contents("settings.js"),true)[0];
$organizationId=$_SESSION['organizationId'];
$sessionId=$_SESSION['sessionId'];
include_once '../dao/config.php';
include_once '../../admin_assets/triggers-new.php';

if(function_exists('date_default_timezone_set')) {
    date_default_timezone_set("Asia/Kolkata");
}
$timestamp = date('Y-m-d H:i:s');

$userId=$_SESSION['userId'];
$organizationId=$_SESSION['organizationId'];
$sessionId=$_SESSION['sessionId'];
$claim=$_GET["claim"];


$values_array=default_data("values_array");
$shuffle=default_data("shuffle");
$launchpos=default_data("launchpos");
$numlimit=default_data("numlimit");

$prevent_claim=toogles("prevent_claim");

$claimArray=array();

$launched=$launchpos*$numlimit;

for($i=0; $i<$launched; $i++){
    $current_shuffle=$shuffle[$i];
    array_push($claimArray,$values_array[$current_shuffle-1]);
}

$fetch="select * from collect where sessionId='$sessionId'";
$fetch=mysqli_query($con,$fetch);
while($get=mysqli_fetch_array($fetch)){
    $numbers=unserialize($get["numbers"]);
    $claim_id=$get["id"];
    $email=$get["email"];
    $claimArray=array();

$launched=$launchpos*$numlimit;


for($i=0; $i<$launched; $i++){
    $current_shuffle=$shuffle[$i];
    array_push($claimArray,$email,$claim,$values_array[$current_shuffle-1]);
}

$user_claim=array();

if($claim=="top_line"){
    for($i=0; $i<4; $i++){
        array_push($user_claim,$numbers[$i]);
    }
   claimCheck($claimArray,$email,$claim,$user_claim,4,"normal");
}
else if($claim=="middle_line"){
    for($i=4; $i<8; $i++){
        array_push($user_claim,$numbers[$i]);
    }
   claimCheck($claimArray,$email,$claim,$user_claim,4,"normal");
}
else if($claim=="bottom_line"){
    for($i=8; $i<12; $i++){
        array_push($user_claim,$numbers[$i]);
    }
    claimCheck($claimArray,$email,$claim,$user_claim,4,"normal");
}
else if($claim=="four_corners"){
    array_push($user_claim,$numbers[0]);
    array_push($user_claim,$numbers[3]);
    array_push($user_claim,$numbers[8]);
    array_push($user_claim,$numbers[11]);
    claimCheck($claimArray,$email,$claim,$user_claim,4);
}else if($claim=="full_house"){
    for($i=0; $i<12; $i++){
        array_push($user_claim,$numbers[$i]);
    }
    claimCheck($claimArray,$email,$claim,$user_claim,12,"normal");
}else if($claim=="early_five"){
    for($i=0; $i<12; $i++){
        array_push($user_claim,$numbers[$i]);
    }
     claimCheck($claimArray,$email,$claim,$user_claim,5,"normal");
}else if($claim=="no_luck"){
    if(sizeof($claimArray)>6){
        for($i=0; $i<12; $i++){
            array_push($user_claim,$numbers[$i]);
        }
         claimCheck($claimArray,$user_claim,0,"reverse");
    }else{
        echo "Claim Rejected";
    }
}else if($claim=="unlucky_five"){
    if(sizeof($claimArray)>39){
        for($i=0; $i<12; $i++){
            array_push($user_claim,$numbers[$i]);
        }
         claimCheck($claimArray,$user_claim,7,"reverse");
    }else{
         echo "Claim Rejected";
    }
}
}
// end while





// $allClaims=array("top_line","middle_line","bottom_line","four_corners","full_house","early_five");






function claimCheck($released,$email,$claim,$user_claim,$expectedValue,$check_flow){
    global $claim,$claim_id,$con,$timestamp,$prevent_claim;
    $success=false;
    $when_released=sizeof($released);
    $claimValue=0;
    for($i=0; $i<sizeof($user_claim); $i++){
        $userData=$user_claim[$i];
        for($x=0; $x<sizeof($released); $x++){
            if($userData==$released[$x]){
                $claimValue=$claimValue+1;
            }
        }
    }

    if($prevent_claim){
             echo "tikit claim disabled by admin";
    }else{
        $claimValid=false;
        if($check_flow=="normal"){
            if($claim=="early_five"){
                if($claimValue==$expectedValue){
                    $claimValid=true;
                }
            }else{
            if($claimValue>=$expectedValue){
                $claimValid=true;
            }
            }
        }else if($check_flow=="reverse"){
            if($claimValue==$expectedValue){
                $claimValid=true;
            }
        }
        if($claimValid){
            $check="select * from claims where claim_id='$claim_id' and claim='$claim'";
            $check=mysqli_query($con,$check);
            $check=mysqli_num_rows($check);
            if($check>0){
                // echo "You have already claim this category.";
                $success=false;
            }else{
                $success=true;
                // $sql="INSERT INTO `claims`(`claim_id`, `claim`,`when_released`,`timestamp`) VALUES ('$claim_id','$claim','$when_released','$timestamp')";
                // if(mysqli_query($con,$sql)){
                //     echo "true";
                // }else{
                //     echo mysqli_error($con);
                // }
            }
        }else{
                $success=false;
        }
    }

    if($success){
        echo "Claim Accepted for $claim ".$email."<br>";
    }else{
        echo "claim Rejected for $claim ".$email."<br>";
    }
}




?>