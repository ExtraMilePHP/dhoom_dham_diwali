<?php
include_once '../dao/config.php';
include_once '../classes/EventRegister.php';
include_once '../classes/PdoClass.php';
$objEventRegister = new EventRegister();
$objpdoClass = new PdoClass();
$result = $objEventRegister->updateinputs($_GET['userid'],$_GET['inputs'],$_GET['identify'],$connPdo,$objpdoClass);
?>