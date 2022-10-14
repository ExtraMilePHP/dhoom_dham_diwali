<?php
error_reporting(0);
session_start();
include_once '../dao/config.php';
include_once '../classes/EventRegister.php';
include_once '../classes/PdoClass.php';
$objEventRegister = new EventRegister();
$objpdoClass = new PdoClass();
$result = $objEventRegister->updateBingoPrint($_GET['userid'],$connPdo);


?>