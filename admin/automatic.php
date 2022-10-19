<?php
ob_start();
error_reporting(E_ALL);
session_start();
$settings=json_decode(file_get_contents("settings.js"),true)[0];
$organizationId=$_SESSION['organizationId'];
$sessionId=$_SESSION['sessionId'];
include_once '../dao/config.php';
include_once '../../admin_assets/triggers-new.php';

if(function_exists('date_default_timezone_set')) {
    date_default_timezone_set("Asia/Kolkata");
}
$timestamp = date('Y-m-d H:i:s');
$tabName="Automatic claims check (Beta)";

$all_categories=array("top_line","middle_line","bottom_line","four_corners","full_house","early_five","no_luck","unlucky_five");

$userId=$_SESSION['userId'];
$organizationId=$_SESSION['organizationId'];
$sessionId=$_SESSION['sessionId'];
$claim=$_GET["claim"];


$values_array=default_data("values_array");
$shuffle=default_data("shuffle");
$launchpos=default_data("launchpos");
$numlimit=default_data("numlimit");

$prevent_claim=toogles("prevent_claim");

$claimArray=array();

$launched=$launchpos*$numlimit;

for($i=0; $i<$launched; $i++){
    $current_shuffle=$shuffle[$i];
    array_push($claimArray,$values_array[$current_shuffle-1]);
}


?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
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


<div class="col-md-12">
			<div class="card" id="custom_card_height">
				<?php cardHeader("View all claims");?>
				<div class="card-content collapse show">
					<div class="card-body">
                        <div class="row">
                        <div class="col-md-12">
                            <?php 
                            for($i=0; $i<sizeof($all_categories); $i++){
                                echo '<a href="automatic.php?claim='.$all_categories[$i].'"><button class="btn btn-sm em-color" style="margin-left:10px;">'.str_replace("_"," ",$all_categories[$i]).'</button></a>';
                            }
                            ?>
                        <div class="table-responsive">
                   <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Category</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                       
$fetch="select * from collect where sessionId='$sessionId'";
$fetch=mysqli_query($con,$fetch);
while($get=mysqli_fetch_array($fetch)){
    echo "<tr>";
    $numbers=unserialize($get["numbers"]);
    $claim_id=$get["id"];
    $email=$get["email"];
    $full_name=$get["name"];
    $claimArray=array();

$launched=$launchpos*$numlimit;


for($i=0; $i<$launched; $i++){
    $current_shuffle=$shuffle[$i];
    array_push($claimArray,$email,$full_name,$claim,$values_array[$current_shuffle-1]);
}

$user_claim=array();

if($claim=="top_line"){
    for($i=0; $i<4; $i++){
        array_push($user_claim,$numbers[$i]);
    }
   claimCheck($claimArray,$email,$full_name,$claim,$user_claim,4,"normal");
}
else if($claim=="middle_line"){
    for($i=4; $i<8; $i++){
        array_push($user_claim,$numbers[$i]);
    }
   claimCheck($claimArray,$email,$full_name,$claim,$user_claim,4,"normal");
}
else if($claim=="bottom_line"){
    for($i=8; $i<12; $i++){
        array_push($user_claim,$numbers[$i]);
    }
    claimCheck($claimArray,$email,$full_name,$claim,$user_claim,4,"normal");
}
else if($claim=="four_corners"){
    array_push($user_claim,$numbers[0]);
    array_push($user_claim,$numbers[3]);
    array_push($user_claim,$numbers[8]);
    array_push($user_claim,$numbers[11]);
    claimCheck($claimArray,$email,$full_name,$claim,$user_claim,4,"normal");
}else if($claim=="full_house"){
    for($i=0; $i<12; $i++){
        array_push($user_claim,$numbers[$i]);
    }
    claimCheck($claimArray,$email,$full_name,$claim,$user_claim,12,"normal");
}else if($claim=="early_five"){
    for($i=0; $i<12; $i++){
        array_push($user_claim,$numbers[$i]);
    }
     claimCheck($claimArray,$email,$full_name,$claim,$user_claim,5,"normal");
}else if($claim=="no_luck"){
    if(sizeof($claimArray)>6){
        for($i=0; $i<12; $i++){
            array_push($user_claim,$numbers[$i]);
        }
         claimCheck($claimArray,$email,$full_name,$claim,$user_claim,0,"reverse");
    }else{
        // echo "Claim Rejected";
    }
}else if($claim=="unlucky_five"){
    if(sizeof($claimArray)>39){
        for($i=0; $i<12; $i++){
            array_push($user_claim,$numbers[$i]);
        }
         claimCheck($claimArray,$email,$full_name,$claim,$user_claim,7,"reverse");
    }else{
        //  echo "Claim Rejected";
    }
}

echo '</tr>';
}
// end while





// $allClaims=array("top_line","middle_line","bottom_line","four_corners","full_house","early_five");






function claimCheck($released,$email,$full_name,$claim,$user_claim,$expectedValue,$check_flow){
    global $claim,$claim_id,$con,$timestamp,$prevent_claim;
    $success=false;
    $when_released=sizeof($released);
    $claimValue=0;
    for($i=0; $i<sizeof($user_claim); $i++){
        $userData=$user_claim[$i];
        for($x=0; $x<sizeof($released); $x++){
            if($userData==$released[$x]){
                $claimValue=$claimValue+1;
            }
        }
    }

    if($prevent_claim){
             echo "tikit claim disabled by admin";
    }else{
        $claimValid=false;
        if($check_flow=="normal"){
            if($claim=="early_five"){
                if($claimValue==$expectedValue){
                    $claimValid=true;
                }
            }else{
            if($claimValue>=$expectedValue){
                $claimValid=true;
            }
            }
        }else if($check_flow=="reverse"){
            if($claimValue==$expectedValue){
                $claimValid=true;
            }
        }
        if($claimValid){
            $check="select * from claims where claim_id='$claim_id' and claim='$claim'";
            $check=mysqli_query($con,$check);
            $check=mysqli_num_rows($check);
            if($check>0){
                // echo "You have already claim this category.";
                $success=false;
            }else{
                $success=true;
                // $sql="INSERT INTO `claims`(`claim_id`, `claim`,`when_released`,`timestamp`) VALUES ('$claim_id','$claim','$when_released','$timestamp')";
                // if(mysqli_query($con,$sql)){
                //     echo "true";
                // }else{
                //     echo mysqli_error($con);
                // }
            }
        }else{
                $success=false;
        }
    }

    if($success){
        echo '<td>'.$full_name.'</td>';
        echo '<td>'.$email.'</td>';
        echo '<td>'.$claim.'</td>';
    }else{
        // echo "claim Rejected for $claim ".$email."<br>";
    }
}

                                       ?>
                                </tbody>
                            </table>
                            </div>
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

    <div class="modal fade" id="viewTikit" role="dialog" style="z-index:2000;">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
        <div class="table-responsive">
        <table id="Table_01" width="798" height="457" border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto">
								<tr>
									<td valign="top" style="line-height: 0">
										<img src="../img/tickits/1.png" width="100" height="76" alt=""></td>
									<td valign="top" style="line-height: 0">
										<img src="../img/tickits/2.png" width="589" height="76" alt=""></td>
									<td valign="top" style="line-height: 0">
										<img src="../img/tickits/3.png" width="109" height="76" alt=""></td>
								</tr>
								<tr>
									<td valign="top" style="line-height: 0">
										<img src="../img/tickits/4.png" width="100" height="307" alt=""></td>

									<td valign="top" style="background-color: #0097da;">
										<table cellpadding="0" cellspacing="0" style="margin: 20px auto;height: 256px">
											<tr> 
												<td class="final_numbers"  style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">
													1
												</td>
												<td  style="background-color: #2480c3;width: 20px;padding: 10px 4px">&nbsp;</td>
												<td class="final_numbers"  style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">
													2
												</td>
												<td style="background-color: #2480c3;width:20px;padding: 10px 4px">&nbsp;</td>
												<td class="final_numbers"  style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">
													3
												</td>
												<td style="background-color: #2480c3;width: 20px;padding: 10px 4px">
												  &nbsp;
												</td>
												<td class="final_numbers"  style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px"> 4</td>
												<td style="background-color: #2480c3;width: 20px;padding: 10px 4px">&nbsp;</td>
											</tr>

											<tr>
												<td style="background-color: #2480c3;width: 20px;padding: 10px 4px">&nbsp;</td>
												<td class="final_numbers"  style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">5</td>
												<td style="background-color: #2480c3;width: 20px;padding: 10px 4px">&nbsp;</td>
												<td class="final_numbers"  style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">6</td>
												<td style="background-color: #2480c3;width: 20px;padding: 10px 4px">&nbsp;</td>
												<td class="final_numbers"  style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">7</td>
												<td style="background-color: #2480c3;width: 20px;padding: 10px 4px">&nbsp;</td>
												<td class="final_numbers"  style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">8'</td>
											</tr>
											<tr> 
												<td class="final_numbers"  style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">9</td>
												<td style="background-color: #2480c3;width: 20px;padding: 10px 4px">&nbsp;</td>
												<td class="final_numbers"   style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">10</td>
												<td style="background-color: #2480c3;width: 20px;padding: 10px 4px">&nbsp;</td>
												<td class="final_numbers"   style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">11</td>
												<td style="background-color: #2480c3;width: 20px;padding: 10px 4px">&nbsp;</td>
												<td class="final_numbers"   style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">12</td>
												<td style="background-color: #2480c3;width: 20px;padding: 10px 4px">&nbsp;</td>
											</tr>

										</table>
									</td>

									<td valign="top" style="line-height: 0">
										<img src="../img/tickits/6.png" width="109" height="307" alt="">
									</td>
								</tr>
								<tr>
									<td valign="top" style="line-height: 0">
										<img src="../img/tickits/9.png" width="100" height="74" alt=""></td>
									<td valign="top" style="line-height: 0">
										<img src="../img/tickits/8.png" width="589" height="74" alt=""></td>
									<td valign="top" style="line-height: 0">
										<img src="../img/tickits/11.png" width="109" height="74" alt=""></td>
								</tr>
                            </table>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

    


<?php include("../../admin_assets/footer.php");?>
<?php include_once("../../admin_assets/common-js.php");?>
<script type="text/javascript">

// $(".view-tikit").click(function(){
//           $(".final_numbers").css("background","white");
//          var data=$(this).attr("data");
       
//          var data_two=$(this).attr("data_two");
    
//          var method=$(this).attr("method");
//          data=JSON.parse(data);
//          data_two=JSON.parse(data_two);
//          var final_numbers=$(".final_numbers");
//          var when=$(this).attr("when");
//          for(var i=0; i<final_numbers.length; i++){
//            $(".final_numbers").eq(i).html(data[i]);
//          }
//          $("#viewTikit").modal("show");
//          if(method=="full_house"){
//               for(var i=0; i<final_numbers.length; i++){
//               $(".final_numbers").eq(i).css("background","yellow");
//             }
//          }if(method=="full_house_two"){
//               for(var i=0; i<final_numbers.length; i++){
//               $(".final_numbers").eq(i).css("background","yellow");
//             }
//          }if(method=="full_house_three"){
//               for(var i=0; i<final_numbers.length; i++){
//               $(".final_numbers").eq(i).css("background","yellow");
//             }
//          }else if(method=="top_line"){
//              for(var i=0; i<4; i++){
//                $(".final_numbers").eq(i).css("background","yellow");
//              }
//          }else if(method=="middle_line"){
//              for(var i=4; i<8; i++){
//                $(".final_numbers").eq(i).css("background","yellow");
//              }
//          }else if(method=="bottom_line"){
//              for(var i=8; i<12; i++){
//                $(".final_numbers").eq(i).css("background","yellow");
//              }
//          }else if(method=="four_corners"){
//                $(".final_numbers").eq(0).css("background","yellow");
//                $(".final_numbers").eq(3).css("background","yellow");
//                $(".final_numbers").eq(8).css("background","yellow");
//                $(".final_numbers").eq(11).css("background","yellow");
//          }else if(method=="early_five"){
//           //  console.log(data_two);
//           //  console.log(data);
//            for(i=0; i<data_two.length; i++){
//                var data_value=data_two[i];
//                for(x=0; x<data.length; x++){
//                  if(data_value==data[x]){
//                   $(".final_numbers").eq(x).css("background","yellow");
//                   console.log(i);
//                  }
//                }
//            }
//          }else if(method=="unlucky_five"){
//           //  console.log(data_two);
//           //  console.log(data);
//            for(i=0; i<data_two.length; i++){
//                var data_value=data_two[i];
//                for(x=0; x<data.length; x++){
//                  if(data_value==data[x]){
//                   $(".final_numbers").eq(x).css("background","yellow");
//                   console.log(i);
//                  }
//                }
//            }
//          }
         
         
         
//      });


</script>
  </body>
</html>