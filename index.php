
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dhoom Dhaam Diwali Tambola</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/styles.css">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
<?php
session_start();
error_reporting(0);
include_once 'dao/config.php';
include_once 'local.php';
$curl = curl_init();
// $certificate_location = �cacert.pem�;
// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $certificate_location);
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $certificate_location);

// echo $api;

function swal($message,$type,$redirect=''){
  $redirect_script='';
  if(!empty($redirect)){
      $redirect_script='window.location = "'.$redirect.'";';
  }
  echo '<script>swal("'.$message.'", "", "'.$type.'").then(() => {
    '.$redirect_script.'
   });</script>';
}


    $otp = $_GET['otp']; 
    $data = json_encode(["otp"=>$otp]);
    curl_setopt_array($curl, array(
      CURLOPT_URL => "$api",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$data,
      CURLOPT_HTTPHEADER => array(
        'client-secret: c8cd8589f49601a287b0e269f43cca07a31858c91918c1479e325b96a1d1d239',
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);
    curl_close($curl);

  // print_r($response);
    

    $message=json_decode($response,true)['message'];
    $response_data = json_decode($response,true)['data'];

    if($message=="LOGIN SUCCESS"){
      $_SESSION['userId'] = $response_data['userId'];
      $_SESSION['organizationId'] = $response_data['organizationId'];
      $_SESSION['firstName']=$response_data['firstName'];
      $_SESSION['lastName']=$response_data['lastName'];
      $_SESSION['email']=$response_data['email'];
      $_SESSION['organizationName']=$response_data['organizationName'];
      $_SESSION['sessionId']=$response_data['sessionId'];
      $_SESSION['adminId']=$response_data['userId'];
      $_SESSION['token']=$response_data['token'];
      $loginSuccess=true;
    }else{
      swal("You are not allowed to play this game","error","");
    }

?>
<style type="text/css" rel="stylesheet">
    .home-default{
        background-image: linear-gradient(to right, #E25569 , #FB9946);
        color: white;
        font-size: 16px;
        padding: 3px 10px;
        border: none;
        font-weight:bold;
        border-radius:5px;
    }
    .back-default{
        background: #e9695e;
        color: white;
        font-size: 18px;
        padding: 1px 8px;
        border: none;
        margin-left: 10px;
        margin-right:15px;
        margin-top: 0px;
        font-weight:bold;
        border-radius:5px;
    }
    .extramileplay-logo{
        max-height:75px;
        max-width:150px;
        margin-top:2px;
    }
    .logo-holder{
        width: auto;
        margin-top:3px;
        display:inline-block;
        padding-left: 15px;
        margin-right: 5px;
    }
    .back-holder{
        width:100px;
        display:inline-block;
    }
    .auto{
      margin:0 auto;
      float:unset;
    }
    .button-login{
      background-color: #fb7100;
    color: black;
    font-size: 19px;
    padding: 4px 20px;
    color: white;
    border-radius: 10px;
    font-weight: bolder;
    }

    .submit-login{
      color: #333;
    background-color: #fff;
    border-color: #ccc;
    background: black;
    color: white;
    font-size: 18px;
    }
    </style>
<div class="container-fluid upperaction" style="margin-top:10px;">
    <div class="row">
    <div class="logo-holder"><a href="<?php echo $base_url;?>"><img src="img/play-white.png" class="extramileplay-logo" style=""></a></div>
    <div class="back-holder" style="border-left:3px solid black;"><a href="<?php echo $base_url;?>"><div class="btn btn-info btn-md back-default">BACK</div></a></div>
    </div>
    </div>
<div class="container-fluid container-control">
<div class="row">
<div class="col-md-4 auto"></div>
<div class="col-md-4 auto"><img src="images/welcome-logo.gif" class="welcome-logo"/></div>
<div class="col-md-4 auto"></div>
<div class="col-md-12 text-center">
<?php if(isset($loginSuccess)){
  echo '<a href="create.php"><div class="btn btn-info begin">BEGIN PLAY</div></a>';
}?>
</div>
</div>
</div>


<script>

</script>
</body>
</html>
