<?php
ob_start();
error_reporting(E_ALL);
session_start();
include_once 'dao/config.php';
if($_SESSION['token'] == ""){
   header('location:index.php');
}

$organizationId=$_SESSION['organizationId'];
$sessionId=$_SESSION['sessionId'];
$settings=json_decode(file_get_contents("admin/settings.js"),true)[0];
include_once '../admin_assets/triggers.php';


$display_leaderboard=toogles("display_leaderboard");
$values_array=default_data("values_array");
$shuffle=default_data("shuffle");
$claims=default_data("claims");

$currentClaim=$_GET["claim"];
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

th{
        font-size:17px;
    }
    th,td{
        text-align:center;
        font-weight: 500;
    }
    thead{
        background-image: linear-gradient(to right, #E25569 , #FB9946);
        color: white;
    }
    .leaderboard-text{
        text-align: center;
    font-size: 30px;
    margin: 10px 0px;
    font-weight: bolder;
    }

  </style>
</head>
<body>
<?php include("../actions-default.php");  back("full-house.php");?>
<div class="container-fluid container-control">
<div class="row">
                <div class="col-md-12 leaderboard-text">Leaderboard</div>
                <div class="col-md-8 col-md-offset-2 text-center">
                  <?php 
foreach ($claims[0] as $key => $val){
  if($val){
    echo '<a href="leaderboard.php?claim='.$key.'"><button class="btn btn-sm" style="margin-left:10px; background:#e9695e; color:white;">'.str_replace("_"," ",$key).'</button></a>';
  }
}

                  ?>
                </div>
                <div class="col-md-8 col-md-offset-2 text-center" style="margin-top:15px;">
                <table class="table table-striped">
    <thead>
      <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Rank</th>
                                        <th>Category</th>
                                        <th>Ticket</th>
                                        <th>Rel Pos</th>
                                        <th>Timestamp</th>
      </tr>
    </thead>
    <tbody>
    <?php 
     $rank=0;
        if($display_leaderboard){
          $sql="SELECT 
          t2.numbers,t1.claim,t2.email,t2.name,t1.timestamp,t1.when_released FROM
          claims t1
          LEFT JOIN collect t2 
          ON t1.claim_id= t2.id where t2.sessionId='$sessionId' and t1.claim='$currentClaim' order by t1.timestamp asc";
          $sql=mysqli_query($con,$sql);
          while($get=mysqli_fetch_array($sql)){
            $when=$get["when_released"];
            $released_num_sim=array();
           $rank=$rank+1;
            for($i=0; $i<$when; $i++){
             $prepare_num=$shuffle[$i];
          
             array_push($released_num_sim,$values_array[$prepare_num-1]);
            }
           ?>   
           <tr>
              <td><?php echo $get["name"];?></td>
              <td><?php echo $get["email"];?></td>
              <td><?php echo $rank;?></td>
              <td><?php echo ucwords(str_replace("_"," ",$get["claim"]));?></td>
              <td><div class="btn btn-success btn-sm view-tikit" data='<?php echo json_encode(unserialize($get["numbers"]));?>' method='<?php echo $get["claim"];?>' data_two='<?php echo json_encode($released_num_sim);?>' method='<?php echo $get["claim"];?>' when='<?php echo $get["when_released"];?>'>Open</div></td>
              <td><?php echo $when;?></td>
              <td><?php echo $get["timestamp"];?></td>
           </tr>
                                       <?php     } }else{
                                           echo '<td colspan="5">Leaderboard is disabled by admin</td>';
                                       }?>
                                </tbody>
  </table>
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
										<img src="img/tickits/1.png" width="100" height="76" alt=""></td>
									<td valign="top" style="line-height: 0">
										<img src="img/tickits/2.png" width="589" height="76" alt=""></td>
									<td valign="top" style="line-height: 0">
										<img src="img/tickits/3.png" width="109" height="76" alt=""></td>
								</tr>
								<tr>
									<td valign="top" style="line-height: 0">
										<img src="img/tickits/4.png" width="100" height="307" alt=""></td>

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
										<img src="img/tickits/6.png" width="109" height="307" alt="">
									</td>
								</tr>
								<tr>
									<td valign="top" style="line-height: 0">
										<img src="img/tickits/9.png" width="100" height="74" alt=""></td>
									<td valign="top" style="line-height: 0">
										<img src="img/tickits/8.png" width="589" height="74" alt=""></td>
									<td valign="top" style="line-height: 0">
										<img src="img/tickits/11.png" width="109" height="74" alt=""></td>
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

</script>

</body>
</html>
