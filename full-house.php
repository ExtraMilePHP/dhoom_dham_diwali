<?php 
ob_start();
error_reporting(E_ALL);
session_start();
include_once 'dao/config.php';
include_once '../check-rating.php';
$isRated=check_rating();
$userId=$_SESSION['userId'];
$organizationId=$_SESSION['organizationId'];
$sessionId=$_SESSION['sessionId'];
$fetch="select * from collect where userid='$userId'";
$fetch=mysqli_query($con,$fetch);
$find=mysqli_num_rows($fetch);
$numbers=null;
$isExist=false;
if($find>0){
    $fetch=mysqli_fetch_object($fetch);
    $numbers=unserialize($fetch->numbers);
    $isExist=true;
}

$settings=json_decode(file_get_contents("admin/settings.js"),true)[0];
include_once '../admin_assets/triggers.php';

  if($_SESSION['firstName']=="demo"){
    $demoprint="var isdemo=true;";
  }else{
    $demoprint="var isdemo=false;";
  }
  
  $array_values=default_data("values_array");
  $shuffle=default_data("shuffle");
  $launchpos=default_data("launchpos");
  $numlimit=default_data("numlimit");
  $claims=default_data("claims");
  $rules=default_data("rules");

  $prevent_submit=toogles("prevent_submit");
  $prevent_claim=toogles("prevent_claim");
  $display_leaderboard=toogles("display_leaderboard");
  $auto_generate=toogles("auto_generate");
  $text="Words";
  if(is_numeric($array_values[0])){
    $text="Numbers";
  }
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Full House</title>
        <link rel='stylesheet' type='text/css' href='css/style_birla.css'>
        <link rel='stylesheet' type='text/css' href='css/responsive.css'>
        <script src="https://code.jquery.com/jquery-1.11.3.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.js"></script>
		<style>
        .table-container{
            width:70%;
            margin-left:15%;
            margin-right:15%;
        }
        .table-container table{
            width:100%;
            margin-top:20px;
            border-collapse: collapse;
        }
        .table-container table,.table-container td, .table-container th {
  border: 1px solid white;
  font-weight:bolder;
}
.table-container td{
    box-sizing:border-box;
    padding-top:5px;
    padding-bottom:5px;
    text-align:center;
}
.table-container tr td:nth-child(2){
}

b{
    font-weight:bolder;
}
.button-home{
    border: 1px solid #840707;
    z-index: 9;
    font-size: 14px;
    padding: 5px 14px;
    text-align: center;
    border-radius: 5px;
    margin: 5px 10px;
    background: black;
    color: white !important;
    width: 100px;
    display: inline-block;
    margin-top: 20px;
        }
        a{
            text-decoration:none;
            color:inherit;
        }
            
		.selected_class{
			background-color : yellow !important;
        }
        .top-logo{
            width:100%;
        }
        .top-logo img{
            width:100%;
        }
        .gen_button{
            background-color: #e9695e;
    border: 0;
    border-radius: 20px;
    color: white;
    font-size: 19px;
    margin: 10px 0 0;
    padding: 9px 30px;
    box-shadow: 0px 1px 1px 1px #4c4c4c;
    cursor: pointer;
    width: max-content;
    margin: 0 auto;
    margin-top: 20px;
        }
        .step-highlight{
            color:black;
        }
        .rate{
            background-image: linear-gradient(to right, #E25569 , #FB9946);
    color: white;
    border: 0;
    border-radius: 20px;
    color: white;
    font-size: 15px;
    font-weight: 600;
    margin: 10px 0 0;
    padding: 9px 30px;
    box-shadow: 0px 1px 1px 1px #4c4c4c;
    cursor: pointer;
        }
        .home-button{
            background-image: linear-gradient(to right, #E25569 , #FB9946);
    color: white;
    font-size: 16px;
    padding: 0px 10px;
    border: none;
    width: 70px;
    height: 32px;
    line-height: 32px;
    border-radius: 5px;
    text-align: center;
    font-weight: bolder;
    margin-top: 15px;
    float:right;
        }
		</style>
        <style type="text/css" rel="stylesheet">
    .home-default{
        background-image: linear-gradient(to right, #E25569 , #FB9946);
        color: white;
        font-size: 16px;
        padding: 2px 0px;
        border: none;
        font-weight:bold;
        border-radius:5px;
    }
    .back-default{
        background: #e9695e;
        color: white;
        font-size: 18px;
        padding: 2px 10px !important;
        border: none;
        margin-left: 10px;
        margin-right:5px !important;
        margin-top: 0px;
        font-weight:bold;
        border-radius:5px;
        text-align:center;
    }
    .extramileplay-logo{
        width: 100%;
        margin-top:2px;
    }
    .logo-holder{
        width: 200px;
        margin-top:3px;
        display:inline-block;
        padding-left: 15px;
        margin-right: 5px;
    }
    .back-holder{
        width:auto !important;
        display:inline-block;
    }


    @font-face {
        font-family: 'FiraSans-Medium';
        src: url('fonts/FiraSans-Medium.otf');
    }


    body {
        font-family: 'FiraSans-Medium';
        margin: 0px;
        padding: 0px;
    }
    .select-tag{
    width: 300px;
    font-size: 18px;
    height: 30px;
    text-align: center;
    background: #795548;
    color: white;
    border-radius: 10px;
    border: none;
    font-family:'FiraSans-Medium';
    }
    .claim_prize{
    font-size: 19px;
    background: #e9695e;
    margin-top: 10px;
    width: max-content;
    border: none;
    padding: 7px 10px;
    color: white;
    font-family:'FiraSans-Medium';
    }
    .notice{
        color: red;
        font-size: 18px;
    }
    .leaderboard-button{
        font-size: 18px;
    width: max-content;
    background: #e9695e;
    padding: 7px 10px;
    border-radius: 4px;
    color: white;
    cursor: pointer;
    margin: 26px auto;
    }



    .em-popup{
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    text-align: center;
    overflow-y: auto;
    background-color: rgba(0,0,0,.4);
    z-index: 10000;
    pointer-events: none;
    opacity: 0;
    transition: opacity .3s;
   }
   .em-modal{
    width: 400px;
    margin: 0 auto;
    margin-top: 60px;
    z-index: 999;
    background: white;
    padding: 10px;
    border-radius: 10px;
   }
   .em-icon-container{
     width:100%;
   }
   .em-title{
     font-size:21px;
   }
  .em-body{
    text-align:left;
  }
  .em-body ol{
    font-size:18px;
  }
  .em-popup-button{
    width: max-content;
    padding: 7px 15px;
    color: black;
    margin: 0 auto;
    font-size: 16px;
    border-radius: 10px;
    border: 0.5px solid #0000009e;
    cursor:pointer;
  }

  .rules-button{
    background:#e9695e;
    color:white;
    font-size:20px;
    padding:5px 10px;
    width:max-content;
    float:right;
    margin-right:15px;
    cursor:pointer;
  }
  


   
    </style>
    </head>
    <?php include("../actions-default.php");  back("rules.php");?>
    <!--
    <div style="width:100%; margin-top:10px;">
    <div class="logo-holder"><a href="https://extramileplay.com"><img src="https://extramileplay.com/php/imp/logo/extramileplay-new.png" class="extramileplay-logo"/></a></div>
    <div class="back-holder" style="border-left:3px solid black;"><a href="rules.php"><div class="btn btn-info btn-md back-default">BACK</div></a></div>
</div>
-->

<div class="em-popup">
    <div class="em-modal">
       <div class="em-title">Rules</div>
       <div class="em-body">
        <ol>
        <?php 
    for($i=0; $i<sizeof($rules); $i++){
        echo '<li>'.$rules[$i].'</li>';
    }
    ?>
        </ol>
       </div>
       <div class="em-bottom"><div class="em-popup-button">Close</div></div>
    </div>
</div>
    <body class="body-background">
        <!--header start-->
 
        <!--header end-->
        <!--Main Section Start-->
        <div style="width:100%;">
            <div class="rules-button">Rules</div>
        </div>
        <section class="xmainBG">
            <!--Shell start-->
            <div class="shell xworkoholic_bg">

                <div class="whole-workoholic-cont_bg workoholic_boder">
                    <div class="requestClose">
                    <div class="workholic_head" style="width:100%; display:inline-block;">
                            </div>
                            <div class="workholic_head">
                                <div class="topBand">
                                    <img src="img/feeling_lucky.png" alt="Feeling Lucky" style="width: 240px;  margin-top:15px;"/>
                                </div>
                            </div>
                            <div class="workholic_cont_sec">
                                <p style="font-size: 22px; color: #6e6e6e; margin-top: 10px;">
                                Follow the 2 steps below, try your luck and win!
                                </p>
                                <div class="steps" style="color:#6e6e6e; font-size:20px;">
                                   <span class="step-highlight"> Step 1 : </span>Select any 12 of the 50 <?php echo $text;?> shown below by clicking on the numbers one at a time <br/>
                                   <span class="step-highlight"> Step 2 : </span> Press submit <br>
                                </div>
    

    
    
                            </div>
                            
                            <div class="whole_warkohalic_box_sec">  
                                <div class="wordsdiv"> 
                                    <table rules="all">
                                    <?php 
echo "<tr>";
for ($i = 1; $i < 51; $i++) {
  if ($i % 5 == 1 && $i != 1) {
    echo "</tr><tr>";
  }
  echo  "<td><span>".$array_values[$i-1]."</span></td>";
}
echo "</tr>";
?>

                                     
                                    </table>
                                </div>
                                
                                <div class="workholic_head">
                                <div class="topBand">
                                    <?php 
                                    if(!$auto_generate){
                                        echo '<div class="gen_button">Click To generate Automatic Ticket</div>';
                                    }
                                    
                                    ?>
                                   
                                </div>
                            </div>
                <!--                <div style="margin-top:10px;color:black;">PLEASE DRAG AND DROP THE WORDS IN WHITE BOXES ONLY AND FILL UP ALL 12 BOXES</div> -->
                                <div class="bingo_FORM bingo_FORM_BG">
                                <div class="ticketdiv">
                                    <form method="post" action="" id="bingo">
                                        <table id="Table_01" width="798" height="457" border="0" cellpadding="0" cellspacing="0">
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
                                                    <table cellpadding="0" cellspacing="0" class="ticketTable">
                                                        <tr>
                                                            <td class="whiteBG"><input class="valclass" type="text" autocomplete="off" readonly /></td>
                                                            <td class="blackBG">&nbsp;</td>
                                                            <td class="whiteBG"><input class="valclass" type="text" autocomplete="off" readonly /></td>
                                                            <td class="blackBG">&nbsp;</td>
                                                            <td class="whiteBG"><input class="valclass" type="text" autocomplete="off" readonly /></td>
                                                            <td class="blackBG">&nbsp;</td>
                                                            <td class="whiteBG"><input class="valclass" type="text" autocomplete="off" readonly /></td>
                                                            <td class="blackBG">&nbsp;</td>
                                                        </tr>
                                                        <tr>
    
                                                            <td class="blackBG">&nbsp;</td>
                                                            <td class="whiteBG"><input class="valclass" type="text" autocomplete="off" readonly /></td>
                                                            <td class="blackBG">&nbsp;</td>
                                                            <td class="whiteBG"><input class="valclass" type="text" autocomplete="off" readonly /> </td>
                                                            <td class="blackBG">&nbsp;</td>
                                                            <td class="whiteBG"><input class="valclass" type="text" autocomplete="off" readonly /></td>
                                                            <td class="blackBG">&nbsp;</td>
                                                            <td class="whiteBG"><input class="valclass" type="text" autocomplete="off" readonly /></td>
    
                                                        </tr>
                                                        <tr>
                                                            <td class="whiteBG"><input class="valclass" type="text" autocomplete="off" readonly /></td>
                                                            <td class="blackBG">&nbsp;</td>
                                                            <td class="whiteBG"><input class="valclass" type="text" autocomplete="off" readonly /></td>
                                                            <td class="blackBG">&nbsp;</td>
                                                            <td class="whiteBG"><input class="valclass" type="text" autocomplete="off" readonly /></td>
                                                            <td class="blackBG">&nbsp;</td>
                                                            <td class="whiteBG"><input class="valclass" type="text" autocomplete="off" readonly /></td>
                                                            <td class="blackBG">&nbsp;</td>
    
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
                                
                                <div id="err"></div>
    
                                <div class="formdiv formBG">
                                    <form>
                                    <div class="form-control">
                                    <?php 
                                    if($prevent_submit){
                                        echo '<div class="notice">Submit Tikit are Disabled by admin</div>';
                                    }else{
                                        echo '<input type="button" name="Submit" id="submitNumbers" value="Submit" /> ';
                                    }
                                    ?>
                                    </div>
                                    </form>
                                </div>
                                <div class="ul-main-container" style="display:none;">
                                <div class="ul-container">
                                <div class="title-bold">Please note:</div>
                                <ul>
                                <li>You can create a ticket only once.</li>
                                <li>Your ticket will appear on this webpage.</li>
                                <li>It will be saved under your login details. </li>
                                </ul>
                                </div>
                                </div>
    
                                <div class="workholic_head" style="height:20px;">
                                <div class="topBand">
                                   
                                </div>
                            </div>
                                <!--onclick="PrintDiv();"-->
                            </div> 

                    </div>
                </div> 
				   
							<div class="ticketshow">
                            <!--<div class="top-logo">
                        <img src="img/top-logo.jpg"/>
                        </div>-->
							<div class="" style="color:black; text-align: center;">
                            <p style="font-size: 22px;
    font-weight: bolder;
    margin-top: 30px;
    margin-bottom: 30px;
    color: #E25569;">    
                                Thank you for creating your ticket.<br> Highlight the numbers by clicking on your ticket one at a time.
   
                            </p>
							</div>
							
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

                            <div class="table-container">
<div style="color:black; margin-top:20px; font-size:18px; text-align:center;">Released Numbers will appear here </div>
<table bordercolor="black" style="width:500px; margin:0 auto; margin-top:20px; color:black; border:2px solid black; text-align:center;">
                        <tbody>
<?php
   $launchpos=$launchpos*$numlimit;
   $launchpos=$launchpos+1;
   if($numlimit<3){
     $numlimit=5;
   }
  echo "<tr>";
  for ($i = 1; $i < $launchpos; $i++) {
    if ($i % $numlimit == 1 && $i != 1) {
      echo "</tr><tr>";
    }
    $prepare_num=$shuffle[$i-1];
    echo  "<td style='border:1px solid black;'>".$array_values[$prepare_num-1]."</td>";
  }
  echo "</tr>"
?>
  </table>
</div>
<div style="width:100%; text-align:center;">
<div style="color:black; margin-top:20px; font-size:18px; text-align:center;">Claim </div>
<select name="cars" id="claims" class="select-tag" style="margin-top:10px;">
<?php 
               for($i=0; $i<sizeof($claims); $i++){
                foreach ($claims[$i] as $key => $val){
                  if($val){
                    echo '<option value="'.$key.'">'.str_replace("_"," ",$key).'</option>';
                  }
                }
               }
?>
</select>
<br>
<?php 
                                    if($prevent_claim){
                                        echo '<div class="notice" style="margin-top:15px;">Submit Claim are Disabled by admin</div>';
                                    }else{
                                        echo '<input type="button"  class="claim_prize" value="Submit Claim" /> ';
                                    }
                                    ?>


<?php 
if($display_leaderboard){
    echo '<a href="leaderboard.php"><div class="leaderboard-button">View Leaderboard</div></a>';
}

?>


</div>




                            
                            <div class="workholic_head" style="height:100px;">
                            <div class="topBand">
                               
                            </div>
                        </div>


							</div> 	
							
							
                    
                <!--Shell end-->
        </section>
        <div id="divToPrint" style="display:none;">
            <div style="width:auto;height:300px;background-color:white;">
                <div class="ticketdiv">

                </div>      
            </div>
        </div>
        <div>
        </div>
        <!--Main Section End-->
        <script src="em-popup.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>

$.noConflict();

<?php echo $demoprint;?>
var responseNumbers=<?php if($isExist){ echo json_encode($numbers);} else {echo "false";}?>;
let userId = "<?php echo $userId;?>";
var organizationId="<?php echo $organizationId;?>";
var token = "<?php echo $_SESSION['token']; ?>";

<?php
if($isExist){
    echo 'fillAllNumbers();';
}else{
    echo '$(".ticketshow").eq(0).hide();';
}
?>

$(".rules-button").click(function(){
    emPop("");
});

$(".claim_prize").click(function(){
    var claim=$("#claims").val();
    $.ajax({ 
       type: "POST", 
       url: "submit-claim.php", 
       data: {claim : claim}, 
       success: function(result) { 
               if(result=="true"){
                  swal("","Claim Accepted","success");
               }else{
                  swal("",result,"error");
               }
        } 
});   
});

var dataArray=<?php echo json_encode($array_values);?>;
var autogen=false;
var bingoCount=0;
var empty_pos=[];
var virtualArray=[];
    var wordsdiv=$(".wordsdiv td").length;
    $(".wordsdiv td").click(function(){
        if(autogen==true){
            bingoCount=0;
            for(var i=0; i<12; i++){
                      $(".valclass").eq(i).val("");
                 }
                 for(var i=0; i<wordsdiv; i++){
                    $(".wordsdiv td").removeAttr("capture");
                    $(".wordsdiv td").css("background","white");
                    $(".wordsdiv td").children("span").css("color","black");
                 }
        }


            var hisAttri=$(this).attr("capture");
            if(hisAttri=="true"){
                $(this).css("background","white");
                $(this).children("span").css("color","black");
                var getText=$(this).children("span").html();
                $(this).attr("capture","false");
                for(var i=0; i<virtualArray.length; i++){
                    if(virtualArray[i]==getText){
                        $(".valclass").eq(i).val("");
                        empty_pos.push(i);
                    }
                }
                bingoCount=bingoCount-1;
                autogen=false;
            }else{
                if(bingoCount<12){
                $(this).css("background","black");
                $(this).children("span").css("color","white");
                var getText=$(this).children("span").html();
                if(empty_pos.length > 0){
                    var newPos=empty_pos[0];
                    $(".valclass").eq(newPos).val(getText);
                    virtualArray[newPos]=getText;
                    empty_pos.shift();
                    console.log(empty_pos);
                }else{
                    $(".valclass").eq(bingoCount).val(getText);
                    virtualArray.push(getText);
                }

                console.log(virtualArray);
                $(this).attr("capture","true");
                bingoCount=bingoCount+1;
                autogen=false;
                console.log(getText);
            }else{
            alert("only 12 Numbers can be selected");
        }
            }
    
    })

                           
$(".gen_button").click(function(){
    for(var i=0; i<wordsdiv; i++){
                    $(".wordsdiv td").removeAttr("selected");
                    $(".wordsdiv td").css("background","white");
                    $(".wordsdiv td").children("span").css("color","black");
                 }
   genRandom();
   swal("Ticket Generated", "Fill Your Details Below to save your ticket", "success");
                setTimeout(function(){
                    console.log("activate");
                    autogen=true;
                },1500);
})

function genRandom(){
    var uniqueNumbers = [];
while(uniqueNumbers.length < 12){
    var r = Math.floor(Math.random() * 49) + 1;                      /// change 16 to number of question available in database
    if(uniqueNumbers.indexOf(r) === -1) uniqueNumbers.push(r);
}
    for(var i=0; i<uniqueNumbers.length; i++){
        var getUniqueNumber=uniqueNumbers[i];
        $(".valclass").eq(i).val(dataArray[getUniqueNumber]);
        $(".wordsdiv td").eq(uniqueNumbers[i]).css("background","black");
        $(".wordsdiv td").eq(uniqueNumbers[i]).children("span").css("color","white");
        $(".wordsdiv td").eq(uniqueNumbers[i]).attr("selected","true");
    }
}




    //// submit
    var isAllCollected=true;
    var collectAnswers=[];

   

    $("#submitNumbers").click(function(){
        collectAnswers=[];
        isAllCollected=true;
        var allInputs= $(".valclass");
        for(var i=0; i<allInputs.length; i++){
            if(allInputs.eq(i).val()!=""){
                collectAnswers.push(allInputs.eq(i).val());
            }else{
                isAllCollected=false;
                allInputs.eq(i).css("border","1px solid red");
                swal("", "you must select all 12 numbers", "error");
            }
        }
        if(isAllCollected){
            console.log(collectAnswers);
            $.ajax({ 
        "url": "bingo-submit.php",
        "method": "POST",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/json"
        },
        "data": JSON.stringify({
            "userId":userId,
            "numbers":collectAnswers,
            "organizationId":organizationId
        }),
        success: function(data){ 
            console.log(data);
            var data = JSON.parse(data);
                    if(data.success){
                           if(data.isdemo){
                                swal("Subscribe to any PLAN to play with your peers.", "", "success").then(() => {
                                location.reload();
                              });
                       }else{
                                location.reload();
                         }
                }else{
                   swal("",data.error,"error")
                    }
        } 
    });    
        }
    });


function fillAllNumbers(){
  $(".requestClose").eq(0).hide();
  document.body.scrollTop = 0;
  var final_numbers=$(".final_numbers");
  for(var i=0; i<final_numbers.length; i++){
      $(".final_numbers").eq(i).html(responseNumbers[i]);
  }
  window.scrollTo(0, 0);
}




$(".final_numbers").click(function(){
    var hisAttri=$(this).attr("value");
    if(hisAttri!="selected"){
        $(this).css("background","yellow");
        $(this).attr("value","selected");
    }else{
        $(this).css("background","white");
        $(this).attr("value","deselect");
    }
});
</script>
    </body>
</html>
