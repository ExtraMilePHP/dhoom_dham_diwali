<?php
ob_start();
error_reporting(0);
session_start();
if($_SESSION['token'] == ""){
   header('location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Full House</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/styles.css">
  <style rel="stylesheet" type="text/css">
  body{
    width:100%;
    /* overflow: hidden; */
    background-color: white;
    /* background-image: url(images/rules-background.png); */
    background-repeat: no-repeat;
    background-size: 100% 100%;
}
.rules-text{
  width: 150px;
  margin-top: -10px;
  margin-bottom:20px;
}
.container-control{
    margin-top: 15px;
}
.next{
    width:130px;
    font-size: 18px;
    background: #e9695e;
}

@media (min-width:100px) and (max-width:500px){
    body{
        /* overflow: scroll;
        background-image: url(images/rules-background-mob.png); */
        background-repeat: repeat;
    }
    .desk{
        display: none;
    }
    .mob{
        display: block;
    }
    .container-control{
        margin-top:45px;
    }
    .rules-text{
        margin-top:14px;
    }
   }
    .rule-list li {
        list-style-type: none;
        background-image: url(img/arrow.png);
        background-repeat: no-repeat;
        text-align: left;
        width: 100%;
        padding-bottom: 10px;
        font-size: 20px;
        font-weight: 500;
        color: black;
        padding-left: 40px;
    }
    body{
    font-family: 'FiraSans-Medium';
}

  </style>
</head>
<body>
<?php include("../actions-default.php");  back("index.php?save");?>
<div class="container-fluid container-control">
<div class="row">
<div class="col-md-3 auto"></div>
<div class="col-md-6 auto"><img src="images/welcome-logo.png" class="welcome-logo"/></div>
<div class="col-md-3 auto"></div>
</div>
<div class="row">
<div class="col-md-12 text-center">
<img class="rules-text" src="images/rules-text.png"/>
</div>
<div class="col-md-6 col-md-offset-3">
<ul class="rule-list">
<li>You can create your ticket only once</li>
<li>It will get saved under your login user details</li>
<li>Enjoy the game together on a LIVE VC call</li>
</ul>
</div>
<div class="col-md-12 text-center">
<a href="full-house.php"><div style="font-weight:bold;" class="btn btn-info next">Next</div></a>
</div>
</div>
</div>


</body>
</html>
