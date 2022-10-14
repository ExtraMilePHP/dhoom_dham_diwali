<?php

session_start();
include_once '../classes/SessionCheckClass.php';
include_once '../classes/LoggerClass.php';
include_once '../dao/config.php';
include_once '../classes/EventRegister.php';
include_once '../classes/PdoClass.php';
$imageid = $_POST['imageid'];
$userid = $_POST['userid'];
$objEventRegister = new EventRegister();
$objpdoClass = new PdoClass();

$result = $objEventRegister->checkawardLike($imageid, $userid, $connPdo);

$tempArr = array();
if (count($result) >= 1) {
   
    $tempArr['error'] = "True";
    $tempArr['message'] = "You have already like this image";
}
else{
    //echo 'hello';exit;
    $result = $objEventRegister->addAwardimage($imageid, $userid, $connPdo);    
    if ($result) {        
        $imageCount = $objEventRegister->getSingleAwardImageCount($imageid, $connPdo); 
        //echo count($imageCount);exit;
        if(count($imageCount) == 0){
            $tempStr = 0;
        }else{
            $tempStr = $imageCount[0]['likecount'];
        }
        $tempArr['error'] = "False";
        $tempArr['message'] = "You have successfully liked this image";
        $tempArr['likecount'] = $tempStr;
    }
}

echo json_encode($tempArr);
?>
