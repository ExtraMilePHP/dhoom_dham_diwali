<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);
error_reporting(0);
session_start();
include_once '../dao/config.php';
include_once '../classes/EventRegister.php';
include_once '../classes/PdoClass.php';
$objEventRegister = new EventRegister();
$objpdoClass = new PdoClass();
if($_POST['calltype']=='fetch'){
$result = $objEventRegister->getuserbingo($_POST['userid'],$connPdo,$objpdoClass);
}else{
$result = $objEventRegister->addUserPointsBingo($_POST,$connPdo,$objpdoClass);
}
/* 
$tempArr = array();
if ($result[0]['likecount'] >= 1) {
    $tempArr['error'] = "True";
    $tempArr['message'] = "You have already liked";
}
else{
    $result = $objEventRegister->addUserImageLike($imageid, $userid, $connPdo, $objpdoClass);    
    if ($result) {        
        $imageCount = $objEventRegister->getSingleImageCount($imageid, $connPdo, $objpdoClass);        
        if(count($imageCount) == 0){
            $tempStr = 0;
        }else{
            $tempStr = $imageCount[0]['likecount'];
        }
        $tempArr['error'] = "False";
        $tempArr['message'] = "You have successfully liked";
        $tempArr['likecount'] = $tempStr;
    }
}

echo json_encode($tempArr); */
?>
