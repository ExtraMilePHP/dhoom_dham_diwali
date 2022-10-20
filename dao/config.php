<?php


$database = "extramileplay_dhoom_dham_diwali";
if (file_exists("../../env.php")) {
    include_once("../../env.php");
}
else {
    include_once("../env.php");
}


$error_msg='<script>swal("","Seems like there was a problem please click here to try again", {
    buttons: {
        catch: {
            text: "Retry",
            value: "Yes",
        }
    },
})
.then((value) => {
    switch (value) {
        case "no":
            break;

        case "Yes":
            location.reload();
            break;

        default:

    }
});</script>';
$con=mysqli_connect($server,$username,$password,$database) or die($error_msg);
// try {
//     $connPdo = new PDO("mysql:host=" . $server . ";dbname=" . $database . "", "" . $username . "", "" . $password . "");
//     $connPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     Logger::info("Error: Could not make a connection" . $e->getMessage());
//     exit("Error: Could not make a connection" . $e->getMessage());
// }
