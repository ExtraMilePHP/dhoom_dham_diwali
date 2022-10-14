<?php



if (file_exists("../../env.php")) {
    include_once("../../env.php");
}
else {
    include_once("../env.php");
}
$database = "extramileplay_dhoom_dham_diwali";


$con=mysqli_connect($server,$username,$password,$database) or die("please check your database connection");
try {
    $connPdo = new PDO("mysql:host=" . $server . ";dbname=" . $database . "", "" . $username . "", "" . $password . "");
    $connPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    Logger::info("Error: Could not make a connection" . $e->getMessage());
    exit("Error: Could not make a connection" . $e->getMessage());
}
