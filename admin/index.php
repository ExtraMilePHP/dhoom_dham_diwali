<?php
ob_start();
error_reporting(E_ALL);
session_start();
$settings=json_decode(file_get_contents("settings.js"),true)[0];
$organizationId=$_SESSION['organizationId'];
$sessionId=$_SESSION['sessionId'];
include_once '../dao/config.php';
include_once '../../admin_assets/triggers-new.php';



if (!$_SESSION['adminId']) {
    header('Location:../index.php?save');
} 

$tabName="Release Numbers / words";
$values_array=default_data("values_array");
$shuffle=default_data("shuffle");
$launchpos=default_data("launchpos");
$numlimit=default_data("numlimit");


?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.5.2/socket.io.js" integrity="sha512-VJ6+sp2E5rFQk05caiXXzQd1wBABpjEj1r5kMiLmGAAgwPItw1YpqsCCBtq8Yr1x6C49/mTpRdXtq8O2RcZhlQ==" crossorigin="anonymous"></script>    
<?php include_once("../../admin_assets/common-css.php");?>
<!-- Only unique css classes -->
    <style rel="stylesheet" type="text/css">

    </style>
<!-- Only unique css classes -->
  </head>


<body class="horizontal-layout horizontal-menu 2-columns" data-open="hover" data-menu="horizontal-menu" data-color="bg-gradient-x-purple-blue" data-col="2-columns">
<?php include_once("../../admin_assets/common-header.php");?>
<div class="app-content content">
      <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
          <div class="content-header-left col-md-4 col-12 mb-2">
            <h3 class="content-header-title"><?php echo $tabName;?></h3>
          </div>
        </div>
        <div class="content-body">
<section id="basic-form-layouts">
	<div class="row match-height">
<!-- add content here -->


		<div class="col-md-6">
			<div class="card" id="custom_card_height">
				<?php cardHeader("Release Numbers / Words");?>
				<div class="card-content collapse show">
					<div class="card-body">
                        <div class="row">
                        <div class="col-md-12 table-responsive">
                <div class="table-responsive text-center">
                        <table class="table table-bordered table-inverse mb-0">
                        <tbody>
<?php
   $for_word=$numlimit;
   $launchStatus=$launchpos*$numlimit;
   $launchStatus=$launchStatus+1;
   if($numlimit<3){
    $numlimit=5;
  }
  echo "<tr>";
  for ($i = 1; $i < $launchStatus; $i++) {
    if ($i % $numlimit == 1 && $i != 1) {
      echo "</tr><tr>";
    }
    $prepare_num=$shuffle[$i-1];
    echo  "<td>".$values_array[$prepare_num-1]."</td>";
  }
  echo "</tr>"
?>
</tbody>
</table>
</div>
					</div>
          <div class="col-md-12 text-center auto">
          <div class="form-container">
                <?php
                
                if(($launchpos*$for_word)==50){
                  echo '<div class="well" style="margin-top:10px; font-weight:bolder; color:red;">All 50 Number/words Released.</div>';
                }else{
                  echo '<a href="events.php?events=release_number"><button class="btn btn-lg em-color login-button" style="margin-top:15px;" type="submit" name="submit">Release '.$for_word.' Numbers or Words</button></a>';
                }
                  
                 ?>         
          </div>
          <div class="form-container">
              <div class="btn btn-success reload-button" style="margin-top:20px;">Force Reload </div>
          </div>
          <div class="form-container" style="margin-top:20px;">
                <?php
                    echo '<a href="'.PAGE_NAME.'?setting=launchpos&value=0"> <button class="btn btn-lg btn-danger login-button" style="margin-top:15px;" type="submit" name="submit">Reset Launch</button></a>';
                 ?>           
          </div>
            </div>
                </div>
</div>
</div>
        </div>
</div>
        <!-- NEW CARD -->
        <div class="col-md-6">
			<div class="card" id="custom_card_height">
			<?php cardHeader("Numbers or words to be Released");?>
				<div class="card-content collapse show">
					<div class="card-body">
                        <div class="row">
                        <div class="col-md-12 table-responsive">
                <div class="table-responsive text-center">
                        <table class="table table-bordered table-inverse mb-0">
                        <tbody>
<?php

  echo "<tr>";
  for ($i = 1; $i < 51; $i++) {
    if ($i % 5 == 1 && $i != 1) {
      echo "</tr><tr>";
    }
    $prepare_num=$shuffle[$i-1];
    echo  "<td>".$values_array[$prepare_num-1]."</td>";
  }
  echo "</tr>";
?>

</tbody>
</table>
</div>
</div>
<div class="col-md-12 text-center auto">
                <div class="form-container">
            
    <a href="events.php?events=shuffle"><button class="btn btn-md btn-danger login-button" style="margin-top:15px;" type="submit" name="submit">Shuffle</button></a>        
    <a href="custom-sequence.php"><button class="btn btn-md btn-info login-button" style="margin-top:15px;" type="submit" name="submit">Custom Sequence</button></a>                   
                </div>
                <div class="well" style="margin-top:10px; font-weight:bolder; color:red;">Do Not Shuffle after Releasing numbers</div>
            </div>
					</div>
                        </div>
              </div>
             </div>
        </div>


 <!-- add content here end -->     
          </div>
       </div>
          </section>
        </div>
      </div>
    </div>



    


<?php include("../../admin_assets/footer.php");?>
<?php include_once("../../admin_assets/common-js.php");?>
<script src="../env.js?v=1"></script>
<script type="text/javascript">
var socket = io(socketUrl, { transports: ['websocket', 'polling', 'flashsocket'],auth: {
          token: "<?php echo  $_SESSION['token'];?>"
        }});

    var msg = {
            'userId':"<?php echo $_SESSION['userId'];?>",
            'webinarId':"<?php echo $_SESSION['gameId'];?>",
            "userName":"<?php echo $name;?>",
            "mailId":"<?php  echo $email;?>",
            'companyName':"<?php echo $_SESSION['organizationName'];?>"  
      };

      socket.on('connect', function(){
        console.log('connected');
        socket.emit('joinWebinar',msg);
        socket.emit('getChat',msg.webinarId);
    });


    function adminReload(){
        socket.emit('adminRequest',msg.webinarId);
    }

    $(".reload-button").click(function(){
      adminReload();
    });
    socket.on('recievedReload', function(data){
             console.log("recievedReload");
             console.log(data);
    });

</script>
  </body>
</html>