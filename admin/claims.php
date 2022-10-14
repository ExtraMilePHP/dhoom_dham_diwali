<?php
ob_start();
error_reporting(E_ALL);
session_start();
$settings=json_decode(file_get_contents("settings.js"),true)[0];
$organizationId=$_SESSION['organizationId'];
$sessionId=$_SESSION['sessionId'];
include_once '../dao/config.php';
include_once '../../admin_assets/triggers.php';

if (!$_SESSION['adminId']) {
    header('Location:../index.php?save');
} 
$tabName="All Claims";
$values_array=default_data("values_array");
$shuffle=default_data("shuffle");
$claims=default_data("claims");

$currentClaim=$_GET["claim"];

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

foreach ($claims[0] as $key => $val){
  if($val){
    echo '<a href="claims.php?claim='.$key.'"><button class="btn btn-sm em-color" style="margin-left:10px;">'.str_replace("_"," ",$key).'</button></a>';
  }
}

                            ?>
                        <div class="table-responsive" style="margin-top:10px;">
                   <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Rank</th>
                                        <th>Category</th>
                                        <th>Tikit</th>
                                        <th>Rel Pos</th>
                                        <th>Approve</th>
                                        <th>Timestamp</th>
                                    </tr>
                                </thead>
                                <?php 
                                           $sql="SELECT t1.id,t1.allowed,
                                           t2.numbers,t1.claim,t2.email,t2.name,t1.timestamp,t1.when_released FROM
                                           claims t1
                                           LEFT JOIN collect t2 
                                           ON t1.claim_id= t2.id where  t1.claim='$currentClaim' order by t1.timestamp asc";
                                          //  echo $sql;
                                           $sql=mysqli_query($con,$sql);
                                           $rank=0;
                                           while($get=mysqli_fetch_array($sql)){
                                             $when=$get["when_released"];
                                             $released_num_sim=array();
                                             for($i=0; $i<$when; $i++){
                                              $prepare_num=$shuffle[$i];
                                              array_push($released_num_sim,$values_array[$prepare_num-1]);
                                             }
                                             $rank=$rank+1;

                                             if($get["allowed"]=="0"){
                                              $approval='<input type="checkbox" id="switchery2" class="switchery switch-data" data-size="md" current_status="disapprove" current_id='.$get["id"].' '.switchery(false).'/>&nbsp;<span class="data-mention"> Disapproved</span>';
                                            }else{
                                              $approval='<input type="checkbox" id="switchery2" class="switchery switch-data" data-size="md" current_status="approve" current_id='.$get["id"].' '.switchery(true).'/>&nbsp;<span class="data-mention"> Approved </span>';
                                            }
                                            ?>   
                                            <tr>
                                               <td><?php echo $get["name"];?></td>
                                               <td><?php echo $get["email"];?></td>
                                               <td><?php echo $rank;?></td>
                                               <td><?php echo ucwords(str_replace("_"," ",$get["claim"]));?></td>
                                               <td><div class="btn btn-success btn-sm view-tikit" data='<?php echo json_encode(unserialize($get["numbers"]));?>' method='<?php echo $get["claim"];?>' data_two='<?php echo json_encode($released_num_sim);?>' method='<?php echo $get["claim"];?>' when='<?php echo $get["when_released"];?>'>Open</div></td>
                                               <td><?php echo $when;?></td>
                                               <td><?php echo $approval;?></td>
                                               <td><?php echo $get["timestamp"];?></td>
                                            </tr>
                                       <?php     }  ?>
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

$(".view-tikit").click(function(){
          $(".final_numbers").css("background","white");
         var data=$(this).attr("data");
       
         var data_two=$(this).attr("data_two");
    
         var method=$(this).attr("method");
         data=JSON.parse(data);
         data_two=JSON.parse(data_two);
         var final_numbers=$(".final_numbers");
         var when=$(this).attr("when");
         for(var i=0; i<final_numbers.length; i++){
           $(".final_numbers").eq(i).html(data[i]);
         }
         $("#viewTikit").modal("show");
         if(method=="full_house"){
              for(var i=0; i<final_numbers.length; i++){
              $(".final_numbers").eq(i).css("background","yellow");
            }
         }if(method=="full_house_two"){
              for(var i=0; i<final_numbers.length; i++){
              $(".final_numbers").eq(i).css("background","yellow");
            }
         }if(method=="full_house_three"){
              for(var i=0; i<final_numbers.length; i++){
              $(".final_numbers").eq(i).css("background","yellow");
            }
         }else if(method=="top_line"){
             for(var i=0; i<4; i++){
               $(".final_numbers").eq(i).css("background","yellow");
             }
         }else if(method=="middle_line"){
             for(var i=4; i<8; i++){
               $(".final_numbers").eq(i).css("background","yellow");
             }
         }else if(method=="bottom_line"){
             for(var i=8; i<12; i++){
               $(".final_numbers").eq(i).css("background","yellow");
             }
         }else if(method=="four_corners"){
               $(".final_numbers").eq(0).css("background","yellow");
               $(".final_numbers").eq(3).css("background","yellow");
               $(".final_numbers").eq(8).css("background","yellow");
               $(".final_numbers").eq(11).css("background","yellow");
         }else if(method=="early_five"){
          //  console.log(data_two);
          //  console.log(data);
           for(i=0; i<data_two.length; i++){
               var data_value=data_two[i];
               for(x=0; x<data.length; x++){
                 if(data_value==data[x]){
                  $(".final_numbers").eq(x).css("background","yellow");
                  console.log(i);
                 }
               }
           }
         }else if(method=="unlucky_five"){
          //  console.log(data_two);
          //  console.log(data);
           for(i=0; i<data_two.length; i++){
               var data_value=data_two[i];
               for(x=0; x<data.length; x++){
                 if(data_value==data[x]){
                  $(".final_numbers").eq(x).css("background","yellow");
                  console.log(i);
                 }
               }
           }
         }
         
         
         
     });


     $(".switchery").click(function(){
      var switchData=$(this).prev().attr("current_id");
      var status=$(this).prev().attr("current_status");
      if(status=="disapprove"){
         var pushData=1;
         $(this).nextAll('.data-mention:first').html("&nbsp; Approved");
         $(this).prev().attr("current_status","approve");
      }else{
         var pushData=0;
         $(this).nextAll('.data-mention:first').html("&nbsp; Disapproved");
         $(this).prev().attr("current_status","disapprove");
      }
      $.ajax({ 
       type: "POST", 
       url: "events.php?events=approve&action="+pushData+"&target="+switchData, 
       data: "", 
       success: function(result) { 
              console.log(result);
        } 
}); 
    });

</script>
  </body>
</html>