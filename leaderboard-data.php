<?php
ob_start();
error_reporting(E_ALL);
session_start();
include_once 'dao/config.php';
if($_SESSION['token'] == ""){
   header('location:index.php');
}

$organizationId=$_SESSION['organizationId'];
$sessionId=$_SESSION['sessionId'];
$settings=json_decode(file_get_contents("admin/settings.js"),true)[0];
include_once '../admin_assets/triggers-new.php';


$display_leaderboard=toogles("display_leaderboard");
$values_array=default_data("values_array");
$shuffle=default_data("shuffle");
$claims=default_data("claims");

$currentClaim="top_line";


$rank=0;
$generate_data=array();
if($display_leaderboard){
  $sql="SELECT 
  t2.numbers,t1.claim,t2.email,t2.name,t1.timestamp,t1.when_released FROM
  claims t1
  LEFT JOIN collect t2 
  ON t1.claim_id= t2.id where allowed='1' order by t1.timestamp asc";
  $sql=mysqli_query($con,$sql);
  while($get=mysqli_fetch_array($sql)){
    $when=$get["when_released"];
    $released_num_sim=array();
    $rank=$rank+1;
    for($i=0; $i<$when; $i++){
     $prepare_num=$shuffle[$i];
     array_push($released_num_sim,$values_array[$prepare_num-1]);
    } 
        $uc_words=ucwords(str_replace("_"," ",$get["claim"]));
        $unserialize_number=json_encode(unserialize($get["numbers"]));
        $release_number_simulation=json_encode($released_num_sim);
        array_push($generate_data,array($get["name"],$get["email"],$rank,$currentClaim,$release_number_simulation,$when,$get["timestamp"]));
    
  } 
}

echo json_encode(array("boardData"=>$generate_data,"allowed"=>$display_leaderboard));

?>