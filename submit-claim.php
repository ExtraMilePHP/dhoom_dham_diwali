<?php
ob_start();
error_reporting(E_ALL);
session_start();
$settings=json_decode(file_get_contents("admin/settings.js"),true)[0];
$organizationId=$_SESSION['organizationId'];
$sessionId=$_SESSION['sessionId'];
include_once 'dao/config.php';
include_once '../admin_assets/triggers-new.php';

if(function_exists('date_default_timezone_set')) {
    date_default_timezone_set("Asia/Kolkata");
}
$timestamp = date('Y-m-d H:i:s');

$userId=$_SESSION['userId'];
$organizationId=$_SESSION['organizationId'];
$sessionId=$_SESSION['sessionId'];
$fetch="select * from collect where userid='$userId'";
$fetch=mysqli_query($con,$fetch);
$fetch=mysqli_fetch_object($fetch);
$numbers=unserialize($fetch->numbers);
$claim_id=$fetch->id;
$winnerList=default_data("winnerList");


$values_array=default_data("values_array");
$shuffle=default_data("shuffle");
$launchpos=default_data("launchpos");
$numlimit=default_data("numlimit");
$numlimit=default_data("numlimit");

$prevent_claim=toogles("prevent_claim");
$winner_restrictions=toogles("winner_restrictions");
$multiple_claims=toogles("multiple_claims");


$claim=$_POST["claim"];

$claimArray=array();

$launched=$launchpos*$numlimit;


for($i=0; $i<$launched; $i++){
    $current_shuffle=$shuffle[$i];
    array_push($claimArray,$values_array[$current_shuffle-1]);
}

$user_claim=array();

if($claim=="top_line"){
    for($i=0; $i<4; $i++){
        array_push($user_claim,$numbers[$i]);
    }
   claimCheck($claimArray,$user_claim,4,"normal");
}
else if($claim=="middle_line"){
    for($i=4; $i<8; $i++){
        array_push($user_claim,$numbers[$i]);
    }
   claimCheck($claimArray,$user_claim,4,"normal");
}
else if($claim=="bottom_line"){
    for($i=8; $i<12; $i++){
        array_push($user_claim,$numbers[$i]);
    }
    claimCheck($claimArray,$user_claim,4,"normal");
}
else if($claim=="four_corners"){
    array_push($user_claim,$numbers[0]);
    array_push($user_claim,$numbers[3]);
    array_push($user_claim,$numbers[8]);
    array_push($user_claim,$numbers[11]);
    claimCheck($claimArray,$user_claim,4,"normal");
}else if($claim=="full_house"){
    for($i=0; $i<12; $i++){
        array_push($user_claim,$numbers[$i]);
    }
    claimCheck($claimArray,$user_claim,12,"normal");
}else if($claim=="early_five"){
    for($i=0; $i<12; $i++){
        array_push($user_claim,$numbers[$i]);
    }
     claimCheck($claimArray,$user_claim,5,"normal");
}else if($claim=="no_luck"){
    if(sizeof($claimArray)>6){
        for($i=0; $i<12; $i++){
            array_push($user_claim,$numbers[$i]);
        }
         claimCheck($claimArray,$user_claim,0,"reverse");
    }else{
        echo "Claim Rejected \n As you have not completed the claimed category";
    }
}else if($claim=="unlucky_five"){
    if(sizeof($claimArray)>39){
        for($i=0; $i<12; $i++){
            array_push($user_claim,$numbers[$i]);
        }
         claimCheck($claimArray,$user_claim,7,"reverse");
    }else{
         echo "Claim Rejected \n As you have not completed the claimed category";
    }
}


function claimCheck($released,$user_claim,$expectedValue,$check_flow){
    global $claim,$claim_id,$con,$timestamp,$prevent_claim,$sessionId,$winner_restrictions,$winnerList,$multiple_claims;
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

    function sendData(){
        
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
                echo "You have already claim this category.";
            }else{
                $checkCategory="SELECT * FROM claims LEFT JOIN collect ON claims.claim_id= collect.id where claims.claim_id='$claim_id'";
                $checkCategory=mysqli_query($con,$checkCategory);
                $checkArray=array();
                while($get=mysqli_fetch_array($checkCategory)){
                         array_push($checkArray,$get["claim"]);
                }
                // $club=array("top_line","middle_line","bottom_line","four_corners","early_five","no_luck","unlucky_five");
                $categoryRestriction=false;
                if(sizeof($checkArray)>0){
                    if($claim!="full_house"){
                        for($i=0; $i<sizeof($checkArray); $i++){
                            if($checkArray[$i]!="full_house"){
                                $categoryRestriction=true;
                            }
                            // if(in_array($club,$checkArray)){ $categoryRestriction=true;}
                        }
                    }
                }
                $canSend=false;
                $restriction_permission=false;
                $ifWinnerFalse=false;
                if(!$multiple_claims){
                    if(!$categoryRestriction){
                      $restriction_permission=true;
                    }else{
                      $canSend=false;
                      $ifWinnerFalse=true;
                      echo "You Have already claimed other category.\n";
                    }
                }else{
                      $restriction_permission=true;
                }

                $winner_check="SELECT * FROM claims LEFT JOIN collect ON claims.claim_id= collect.id where  claims.claim='$claim'";
                $winner_check=mysqli_query($con,$winner_check);
                $winner_check=mysqli_num_rows($winner_check);
                if($winner_restrictions){
                    if($winner_check<$winnerList[0][$claim]){
                        if($restriction_permission){
                            $canSend=true;
                        }
                    }else{
                        $canSend=false;
                        echo "All Prizes Claim in this category\n";
                    }
                }else{
                    if(!$restriction_permission){
                        $canSend=true;
                    }
                    if($ifWinnerFalse){
                        $canSend=false;
                    }else{
                        $canSend=true;
                    }
                }

                if($canSend){
                    $sql="INSERT INTO `claims`(`claim_id`, `claim`,`when_released`,`timestamp`) VALUES ('$claim_id','$claim','$when_released','$timestamp')";
                    if(mysqli_query($con,$sql)){
                        echo "true";
                    }else{
                        echo mysqli_error($con);
                    }
                }else{
                    // echo "terminated";
                }
            }
        }else{
               echo "Claim Rejected \n As you have not completed the claimed category";
        }
    }
}




?>