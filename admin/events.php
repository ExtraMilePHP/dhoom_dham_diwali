<?php

ob_start();
error_reporting(E_ALL);
session_start();
$settings=json_decode(file_get_contents("settings.js"),true)[0];
$organizationId=$_SESSION['organizationId'];
$sessionId=$_SESSION['sessionId'];
include_once '../dao/config.php';
include_once '../../admin_assets/triggers-new.php';

$launchpos=default_data("launchpos");
$numlimit=default_data("numlimit");
$claims=default_data("claims");
$winnerList=default_data("winnerList");
$multiple_claims=toogles("multiple_claims");
$winner_restrictions=toogles("winner_restrictions");

$events=$_GET["events"];
if(isset($events)){
    if($events=="shuffle"){
        if($launchpos>0){
            echo "Error : unable to shuffle. values are already released. Reset launch first.";
        }else{
            $numbers = range(1, 50);
            shuffle($numbers);
            $serialize=serialize($numbers);
            updateValues("shuffle",$serialize);
            header("Location:index.php");
        }
    }

    if($events=="add_words"){
        if($launchpos>0){
            echo "Error : unable to update. values are already released. Reset launch first.";
        }else{
            $words_array=$_POST["variables_array"];
            $words_array=serialize($words_array);
            updateValues("values_array",$words_array);
            echo "true";
        }
    }

    if($events=="add_rules"){
        $rules=$_POST["rules"];
        $rules=serialize($rules);
        updateValues("rules",$rules);
        echo "true";
    }


    if($events=="custom_sequence"){
        if($launchpos>0){
            echo "Error : unable to update. values are already released. Reset launch first.";
        }else{
            $words_array=$_POST["variables_array"];
            $words_array=serialize($words_array);
            updateValues("shuffle",$words_array);
            echo "true";
        }
    }


    if($events=="change_limit"){
        $value=$_GET["value"];
        if($launchpos>0){
            echo "Error : unable to update. values are already released. Reset launch first.";
        }else{
            if($value>10){
                echo "Error : unable to update. Max limit is 10";
            }else{
                if($value==0){
                    echo "Error : unable to update. insert number between 1 to 10";
                }else{
                    updateValues("numlimit",$value);
                    header("Location:index.php");
                    echo "true";
                }
            }
        }
    }

    if($events=="release_number"){
        $predict=($launchpos+1)*$numlimit;
        if($predict>50){
            updateValues("launchpos","10");
            updateValues("numlimit","5");
            header("Location:index.php");
        }else{
            $launchpos=$launchpos+1;
            updateValues("launchpos",$launchpos);
            header("Location:index.php");
        }
    }

    if($events=="approve"){
        $action=$_GET["action"];
        $target=$_GET["target"];
        $update="update claims SET allowed='$action' where id='$target'";
        if(mysqli_query($con,$update)){
            echo "true";
        }
        // header("Location:view-uploads.php");
    }

    if($events=="claim_toogle"){
            $getValue=($_GET["value"]=="true")? true:false;
            // $check='SELECT * FROM claims LEFT JOIN collect ON claims.claim_id= collect.id where collect.sessionId='$sessionId'';
            // $check=mysqli_query($con,$check);
            // $check=mysqli_num_rows($check);
            $sendClaims=$claims;
            $claim_type=$_GET["claim_type"];
            foreach($sendClaims as $key => $value){
                   $sendClaims[$key][$claim_type] = $getValue;
                }
                $count=0;
                foreach($sendClaims[0] as $key => $value){
                   if($value){
                    $count=$count+1;
                   }
                 }
                 if($count==0){
                    echo "Error : at least one claim category must be activated.";
                 }else{
            $sendClaims=serialize($sendClaims);
            updateValues("claims",$sendClaims);
            header("Location:settings.php");
                 }
    }

    if($events=="winner_data"){
        $words_array=$_POST["winner_data"];
        $send=array_merge($winnerList[0],$words_array);
        $send=array($send);
        $zeroDetected=false;
        foreach($send[0] as $key => $value){
            if($value<=0){
                $zeroDetected=true;
            }
         }
         if($zeroDetected){
            echo 'Winner length cannot be zero';
         }else{
            $send=serialize($send);
            updateValues("winnerList",$send);
            echo "true";
         }
    }

}

?>