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

$tabName="Custom Release Sequence";
$values_array=default_data("values_array");
$shuffle=default_data("shuffle");
$launchpos=default_data("launchpos");
$numlimit=default_data("numlimit");

$List = implode(', ', $values_array); 

?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
      
<?php include_once("../../admin_assets/common-css.php");?>
<!-- Only unique css classes -->
    <style rel="stylesheet" type="text/css">
.selectData{
    position:relative;
    cursor:pointer;
}
.number-data{
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 17px;
    background: #00000069;
    width: 30px;
    line-height: 30px;
    text-align: center;
    height: 30px;
    border: 1px solid white;
    border-radius: 50%;
}
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


		<div class="col-md-7">
			<div class="card" id="custom_card_height">
				<?php cardHeader("Click on words/numbers");?>
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
    echo  "<td class='selectData' currentData='$i' selectedCheck='false'>".$values_array[$i-1]."</td>";
  }
  echo "</tr>";
?>

</tbody>
</table>
</div>
</div>
<div class="col-md-12 text-center auto">
                <div class="form-container">
                <button class="btn btn-lg em-color login-button" style="margin-top:15px; display:none;" id="tagButton" type="submit" name="submit">Save</button>    
                <a href="custom-sequence.php"><button class="btn btn-lg btn-info" style="margin-top:15px;" id="tagButton" type="submit" name="submit">Reset</button></a>   
                </div>
            </div>
 
                </div>
</div>
</div>
        </div>
</div>
        <!-- NEW CARD -->
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
<script type="text/javascript">

var saveArray=[];
var pos=0;
$(".selectData").click(function(){
         var selected=$(this).attr("selectedCheck");
         var currentdata=$(this).attr("currentdata");
         if(selected=="false"){
            $(this).attr("selectedCheck","true");
            $(this).css("background","black");
            $(this).append("<div class='number-data'>"+(pos+1)+"</div>");
            saveArray.push(currentdata);
            pos=pos+1;
         }
         if(saveArray.length==50){
            $(".login-button").show();
         }else{
            $(".login-button").hide();
         }
});


$(".login-button").click(function(){
        $.ajax({ 
       type: "POST", 
       url: "events.php?events=custom_sequence", 
       data: {variables_array : saveArray}, 
       success: function(result) { 
              if(result=="true"){
                swal('Success', 'Data Updated', 'success');
                setTimeout(() => {
                    location.href=("index.php");
                }, 1500);
              }else{
                swal('Error', result, 'error');
              }
        } 
});   
})

</script>
  </body>
</html>