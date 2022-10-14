<?php
//error_reporting(0);
ini_set('display_errors', 1);
ob_start();
session_start();
$_SESSION['userid']=$_SESSION['token'];
if(empty($_SESSION["userid"])){
    header("Location:../index.php");
  }
  

include_once 'classes/LoggerClass.php';
include_once 'dao/config.php';
include_once 'classes/LoginClass.php';
include_once 'classes/PdoClass.php';
include_once 'classes/MessageClass.php';
include_once 'classes/FormValidator.php';
include_once 'classes/EventRegister.php';
$objmessageClass = new MessageClass();
$objloginClass = new LoginClass();
$objpdoClass = new PdoClass();
$objEveRegeClass = new EventRegister();






if (isset($_POST['Submit'])) {

   $userDetails = $objEveRegeClass->navarangImageuploads($_POST, $connPdo, $objpdoClass, $objmessageClass);

   if ($userDetails) {
       echo "<script language='javascript' type='text/javascript'>";
       echo "alert('Thank you for your nomination.');";
       echo "</script>";

       $URL = "detail.php";
       echo "<script>location.href='$URL'</script>";
   }
}
$alluser = $objEveRegeClass->alluser($connPdo, $objpdoClass, $objmessageClass);
include_once 'dao/config.php';
include_once 'classes/EventRegister.php';
include_once 'classes/PdoClass.php';
$objEventRegister = new EventRegister();
$objpdoClass = new PdoClass(); 

$result = $objEventRegister->fetchbingo($_SESSION['userid'],$connPdo,$objpdoClass);


$query="select * from launchpad where userid='EM000'";
$query=mysqli_query($con,$query);
$query=mysqli_fetch_object($query);
$get_array=$query->array;
$get_data=$query->data;
$get_limit=$query->numLimit;
$prevent=$query->prevent;
$deserialize=unserialize($get_array);
$get_data=unserialize($get_data);
$launchStatus=$query->launchpos;

if($prevent==1){
    $submitButton='style="display:none;"';
}else{
    $submitButton='';
}
//print_r($result);
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>bingo</title>
        <link rel='stylesheet' type='text/css' href='css/style_birla.css'>
        <link rel='stylesheet' type='text/css' href='css/responsive.css'>

        <style type="text/css" rel="stylesheet">
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
        </style>
        <!-- For Validation -->
        <!--<script type="text/javascript" src="js/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="js/jquery.validates.js"></script>
        <script src="lib/js/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>-->
        <!-------NEW------>
        <script src="https://code.jquery.com/jquery-1.11.3.js"></script>
        <script type="text/javascript" src="js/html5shiv-2.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.js"></script>
		<style>
            
		.selected_class{
			background-color : yellow !important;
        }
        .gen_button{
    border: 0;
    border-radius: 20px;
    color: black;
    font-size: 19px;
    font-weight: 600;
    margin: 10px 0 0;
    padding: 9px 30px;
    box-shadow: 0px 1px 1px 1px #4c4c4c;
    cursor: pointer;
    width: 287px;
    margin: 0 auto;
    margin-top: 20px;
    background-image: linear-gradient(to right, #E25569 , #FB9946);
    color:white;
        }
        .main-top-logo{
            width:100%;
        }
        .main-top-logo img{
            width:100%;
        }
		</style>
    </head>

    <body class="body-background" style="background: url(images/bingo-background.png);     background-size: contain;">
        <!--header start-->
 
        <!--header end-->
        <!--Main Section Start-->
        <section class="xmainBG">
                   
            <!--Shell start-->
            <div class="shell xworkoholic_bg">

                <div class="whole-workoholic-cont_bg workoholic_boder">
                    <div class="">
						<?php
						if(empty($result)){
                        ?>	
  
                        <div class="workholic_head">
                            <div class="topBand">
                                <img src="img/feeling_lucky.png" alt="Feeling Lucky" style="width: 240px; margin-top: 10px;"/>
                            </div>
                        </div>
                        <div class="full-color">
                        <div class="workholic_cont_sec" style="padding:10px;">
                            <p style="font-size: 28px; color: black; margin-top: 10px;">
                            Follow the 2 steps below, try your luck and win!
                            </p>
                            <div class="steps" style="color:black;">
                               <span class="step-highlight"> Step 1 : </span> Select any 12 of the 50 numbers shown below by clicking on the numbers one at a time<br/>
                               <span class="step-highlight"> Step 2 : </span> Press submit
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
  echo  "<td><span>".$get_data[$i-1]."</span></td>";
}
echo "</tr>";
?>
                                </table>
                            </div>
                            
                            <div class="workholic_head">
                            <div class="topBand" style="display:none;">
                                <div class="gen_button">Click To generate Automatic Ticket</div>
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

                                            <td valign="top" style="background-color:#0097da;">
                                                <table cellpadding="0" cellspacing="0" class="ticketTable">
                                                    <tr>
                                                        <td class="whiteBG"><input id="one-1" class="one-1 valclass" type="text" autocomplete="off" readonly /></td>
                                                        <td class="blackBG">&nbsp;</td>
                                                        <td class="whiteBG"><input id="two-2" class="two-2 valclass" type="text" autocomplete="off" readonly /></td>
                                                        <td class="blackBG">&nbsp;</td>
                                                        <td class="whiteBG"><input id="three-3" class="three-3 valclass" type="text" autocomplete="off" readonly /></td>
                                                        <td class="blackBG">&nbsp;</td>
                                                        <td class="whiteBG"><input id="four-4" class="four-4 valclass" type="text" autocomplete="off" readonly /></td>
                                                        <td class="blackBG">&nbsp;</td>
                                                    </tr>
                                                    <tr>

                                                        <td class="blackBG">&nbsp;</td>
                                                        <td class="whiteBG"><input id="five-5" class="five-5 valclass" type="text" autocomplete="off" readonly /></td>
                                                        <td class="blackBG">&nbsp;</td>
                                                        <td class="whiteBG"><input id="six-6" class="six-6 valclass" type="text" autocomplete="off" readonly /> </td>
                                                        <td class="blackBG">&nbsp;</td>
                                                        <td class="whiteBG"><input id="seven-7" class="seven-7 valclass" type="text" autocomplete="off" readonly /></td>
                                                        <td class="blackBG">&nbsp;</td>
                                                        <td class="whiteBG"><input id="eight-8" class="eight-8 valclass" type="text" autocomplete="off" readonly /></td>

                                                    </tr>
                                                    <tr>
                                                        <td class="whiteBG"><input id="nine-9" class="nine-9 valclass" type="text" autocomplete="off" readonly /></td>
                                                        <td class="blackBG">&nbsp;</td>
                                                        <td class="whiteBG"><input id="ten-10" class="ten-10 valclass" type="text" autocomplete="off" readonly /></td>
                                                        <td class="blackBG">&nbsp;</td>
                                                        <td class="whiteBG"><input id="eleven-11" class="eleven-11 valclass" type="text" autocomplete="off" readonly /></td>
                                                        <td class="blackBG">&nbsp;</td>
                                                        <td class="whiteBG"><input id="twelve-12" class="twelve-12 valclass" type="text" autocomplete="off" readonly /></td>
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
                                <div class="form-control" style="display:none;"><label>Your Name</label> <input type="text" name="name" id="name" autocomplete="off" value="false"/></div><div id="errorsname"></div>
                                <div class="form-control" style="display:none;"><label>Your Email</label> <input type="text" name="email" id="email" autocomplete="off" value="noemail@gmail.com" /></div><div id="errors"></div><div id="emailid"></div>
                                <div class="form-control" style="display:none;"><label>Your Location</label> <input type="text" name="location" id="location" autocomplete="off" value="false" /></div><div id="errorsloc"></div>
                                <div class="form-control" style="display:none;"><label>Your Employee/Agent Code</label> <input type="text" name="agent_code" id="agent_code" autocomplete="off" value="false"/></div><div id="errorsAgentcode"></div>
                                <div class="form-control">
                                <input type="button" <?php echo $submitButton; ?>name="Submit" id="createtic" value="Submit" /></div>
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
				    <?php
						}

					if(!empty($result)){
                         $message='';
                         $classess1='';$classess2='';$classess3='';$classess4=''; $classess5='';
                         $classess6=''; $classess7=''; $classess8=''; $classess9=''; $classess10='';
                         $classess11=''; $classess12='';
					?>
							<div class="ticketshow">
                            <!--<div class="top-logo">
                        <img src="img/top-logo.jpg"/>
                        </div>-->
                        <div class="workholic_head" style="margin-top:10px;">

                        </div>
                            <div class="full-color"">
							<div class="workholic_cont_sec">
                            <p>
                                <?php 
                                              
                                $all_users=" Thank you for creating your own Tambola ticket! This ticket is now saved under your email id. You can now log into the same link shared with you to view your ticket and participate in the game by selecting the words that will flash on this page shortly.<br><br>If you win any of the following contests, please send us a screenshot to contact@extramile.in as soon as you can with your name, location and contest won.<br><br>
								1. Quick 5 <br>
								2. Top, Middle and Bottom lines <br>
                                3. Full House <br/><br/> Win exciting goodies! <br/><br/>
                               <span style='margin-bottom:15px'>  We will start announcing the words from Thursday 3 pm onwards.</span><br>
                               <span style='color:#cbcfd1;'>----------<span>
				<img src='images/round1.jpg' style='width:70%;margin-bottom:10px;display:none;'>
                                <img src='images/round2.jpg' style='width:70%;margin-bottom:10px;display:none;'>
                                <img src='images/round3.jpg' style='width:70%;margin-bottom:10px;display:none;'> ";

                                $only_specific="
                                <span style='color:white;'>Thank you for creating your bingo ticket to participate in JINGALALA Friday!</span><br>
                                <span style='color:white;'>Login with the same details to participate in the game on Friday 26th June at 3pm.<br><br>
                                
                               <b> Jaldi 5 -</b> First 5 numbers on your ticket (any order)<br>
                               <b> Top line -</b> All 4 numbers in the top most line<br>
                               <b> Middle line -</b> All 4 numbers in the middle line<br>
                               <b> Bottom Line -</b> All 4 numbers in the bottom most line<br>
                               <b>  Full House -</b> All 12 Numbers in your ticket<br>
                                <br><br>
                                May the smartest and luckiest win!
                                </span>
                                <br>
                                              <span style='color:#cbcfd1;'>----------<span>
              ";
                                
                           /*   if($_SESSION['username'] == "pooja@extramile.in"){
                                $print_result_data=$all_users;
                            }else{
                                $print_result_data=$only_specific;
                            }
                            */
                                
                                echo $only_specific;?>
                            </p>
							</div>
								<?php
								$one=explode(' ',$result[0]['one']);
								$two=explode(' ',$result[0]['two']);
								$three=explode(' ',$result[0]['three']);
								$four=explode(' ',$result[0]['four']);
								$five=explode(' ',$result[0]['five']);
								$six=explode(' ',$result[0]['six']);
								$seven=explode(' ',$result[0]['seven']);
								$eight=explode(' ',$result[0]['eight']);
								$nine=explode(' ',$result[0]['nine']);
								$ten=explode(' ',$result[0]['ten']);
								$eleven=explode(' ',$result[0]['eleven']);
								$twelve=explode(' ',$result[0]['twelve']);
								if($result[0]['one_color']==1){
									$classess1 = 'selected_class ';
								}
								if($result[0]['two_color']==2){
									$classess2 = 'selected_class ';
								}
								if($result[0]['three_color']==3){
									$classess3 = 'selected_class ';
								}
								if($result[0]['four_color']==4){
									$classess4 = 'selected_class ';
								}
								if($result[0]['five_color']==5){
									$classess5 = 'selected_class ';
								}
								if($result[0]['six_color']==6){
									$classess6 = 'selected_class ';
								}
								if($result[0]['seven_color']==7){
									$classess7 = 'selected_class ';
								}
								if($result[0]['eight_color']==8){
									$classess8 = 'selected_class ';
								}
								if($result[0]['nine_color']==9){
									$classess9 = 'selected_class ';
								}
								if($result[0]['ten_color']==10){
									$classess10 = 'selected_class ';
								}
								if($result[0]['eleven_color']==11){ 
									$classess11 = 'selected_class ';
								}
								if($result[0]['twelve_color']==12){
									$classess12 = 'selected_class ';
								}
							echo '<table id="Table_01" width="798" height="457" border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto">
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

									<td valign="top" style="background-color: #8cc841;">
										<table cellpadding="0" cellspacing="0" style="margin: 20px auto;height: 256px">
											<tr> 
												<td class="'.$classess1.'toggleclass" inputs="1" style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">
													'.$one[0].'<br>'.$one[1].'
												</td>
												<td  style="background-color:#0096a9;width: 20px;padding: 10px 4px">&nbsp;</td>
												<td class="'.$classess2.'toggleclass" inputs="2" style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">
													'.$two[0].'<br>'.$two[1].'
												</td>
												<td style="background-color:#0096a9;width:20px;padding: 10px 4px">&nbsp;</td>
												<td class="'.$classess3.'toggleclass" inputs="3" style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">
													'.$three[0].'<br>'.$three[1].'
												</td>
												<td style="background-color:#0096a9;width: 20px;padding: 10px 4px">
												  &nbsp;
												</td>
												<td class="'.$classess4.'toggleclass" inputs="4" style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px"> '.$four[0].'<br>'.$four[1].'</td>
												<td style="background-color:#0096a9;width: 20px;padding: 10px 4px">&nbsp;</td>
											</tr>

											<tr>
												<td style="background-color:#0096a9;width: 20px;padding: 10px 4px">&nbsp;</td>
												<td class="'.$classess5.'toggleclass" inputs="5" style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">'.$five[0].'<br>'.$five[1].'</td>
												<td style="background-color:#0096a9;width: 20px;padding: 10px 4px">&nbsp;</td>
												<td class="'.$classess6.'toggleclass" inputs="6" style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">'.$six[0].'<br>'.$six[1].' </td>
												<td style="background-color:#0096a9;width: 20px;padding: 10px 4px">&nbsp;</td>
												<td class="'.$classess7.'toggleclass" inputs="7" style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">'.$seven[0].'<br>'.$seven[1].'</td>
												<td style="background-color:#0096a9;width: 20px;padding: 10px 4px">&nbsp;</td>
												<td class="'.$classess8.'toggleclass" inputs="8" style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">'.$eight[0].'<br>'.$eight[1].'</td>
											</tr>
											<tr> 
												<td class="'.$classess9.'toggleclass" inputs="9" style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">'.$nine[0].'<br>'.$nine[1].'</td>
												<td style="background-color:#0096a9;width: 20px;padding: 10px 4px">&nbsp;</td>
												<td class="'.$classess10.'toggleclass" inputs="10" style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">'.$ten[0].'<br>'.$ten[1].'</td>
												<td style="background-color:#0096a9;width: 20px;padding: 10px 4px">&nbsp;</td>
												<td class="'.$classess11.'toggleclass" inputs="11" style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">'.$eleven[0].'<br>'.$eleven[1].'</td>
												<td style="background-color:#0096a9;width: 20px;padding: 10px 4px">&nbsp;</td>
												<td class="'.$classess12.'toggleclass" inputs="12" style="background-color: #ffffff;width: 100px;padding: 10px 4px;color:#B0000C;text-align: center;word-break: break-all;font-size:13px">'.$twelve[0].'<br>'.$twelve[1].'</td>
												<td style="background-color:#0096a9;width: 20px;padding: 10px 4px">&nbsp;</td>
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
<script>

</script>

';
           
							
                            ?>
  <div class="table-container">
<table bordercolor="white" style="width:500px; margin:0 auto; margin-top:20px; color:white; border:2px solid white; text-align:center;">
                        <tbody>
<?php
   $launchStatus=$launchStatus*$get_limit;
   $launchStatus=$launchStatus+1;
   if($get_limit<3){
     $get_limit=5;
   }
  echo "<tr>";
  for ($i = 1; $i < $launchStatus; $i++) {
    if ($i % $get_limit == 1 && $i != 1) {
      echo "</tr><tr>";
    }
    $prepare_num=$deserialize[$i-1];
    echo  "<td>".$get_data[$prepare_num-1]."</td>";
  }
  echo "</tr>"
?>
  </table>
</div>


                            
                            <div class="workholic_head" style="height:1000px;">
                            <div class="topBand">
                               
                            </div>
                        </div>

                        </div>
							</div> 	
							
							<?php
							}
							?>
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
    </body>
</html>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
var dataArray=<?php echo json_encode($get_data);?>;
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
                    $(".wordsdiv td").removeAttr("capture");
                    $(".wordsdiv td").css("background","white");
                    $(".wordsdiv td").children("span").css("color","black");
                 }
   genRandom();
   swal("Ticket Generated", "", "success");
                setTimeout(function(){
                    console.log("activate");
                    autogen=true;
                },1500);
})

function genRandom(){
    var empty_pos=[];
    var virtualArray=[];
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
        $(".wordsdiv td").eq(uniqueNumbers[i]).attr("capture","true");
    }
}


                           

<?php
/*
if(function_exists('date_default_timezone_set')) {
    date_default_timezone_set("Asia/Kolkata");
}
$d=mktime(12, 15, 00, 4, 23, 2020);
$launchDate=date('Y-m-d H:i:s',$d);
echo "console.log('".$launchDate."');   
";
*/
?>


function LaunchSequence(){

}
	/*
   var bingoCount=0;
    $(".wordsdiv").length;
    $(".wordsdiv td").click(function(){
        if(bingoCount<12){
            var hisAttri=$(this).attr("selected");
            console.log(hisAttri);
            if(hisAttri=="selected"){
                alert("This Number Already Selected");
            }else{
                $(this).css("background","black");
                $(this).children("span").css("color","white");
                var getText=$(this).children("span").html();
                $(".valclass").eq(bingoCount).val(getText);
                $(this).attr("selected","true");
               bingoCount=bingoCount+1;
               console.log(getText);
            }
        }else{
            alert("only 12 Numbers can be selected")
        }
    })
    */

	jQuery(".toggleclass").click(function () {
	  var box=$(this).attr('class');
	  var res = box.split(" ");
	  if(res[0]=='toggleclass'){
		$(this).addClass('selected_class');
	  }else{
		$(this).removeClass('selected_class');
	  }
	  if(res[1]=='selected_class'){
		$(this).removeClass('selected_class');
	  }
	  if(res[0]=='selected_class'){
		var identify = 'disselect';
	  }else{
		var identify = 'select';
	  }
				var userid = '<?php echo $_SESSION['userid'] ?>';
					$.ajax({
					type: 'get',
					data: 'userid=' + userid + '&inputs='+$(this).attr('inputs')+'&identify='+identify,
					url: 'ajaxcall/update.php',
					success: function (res) {  
					
					}
            }); 				
	});
	
	jQuery(".selected_class").click(function () {
			$(this).removeClass('.selected_class');
	});

    $("#one-1,#two-2,#three-3,#four-4,#five-5,#six-6,#seven-7,#eight-8,#nine-9,#ten-10,#eleven-11,#twelve-12").droppable({
        drop: function (event, ui) {
            $(this).val(ui.draggable.text());
            $(this).val(ui.draggable.text());
            //$('.ui-draggable').hide()
            var str = $(this).attr('class');
            var res = str.split("-");
            $('.ui-draggable-dragging').addClass(res[0]);
            $('.' + res[0]).css('display', 'none');
        }
    });
   // $("span").draggable();
    $(document).ready(function () {
        $.noConflict();
        $('#createtic').on('click', function () {
            // alert()
            var userid = '<?php echo $_SESSION['userid'] ?>';
            $.ajax({
                type: 'post',
                data: 'userid=' + userid + '&calltype=fetch',
                url: 'ajaxcall/bingo.php',
                success: function (res) {
                    //res=0;
					$( "#name,#email,#location" ).blur(function() {
					  $('#err,#errorsname,#emailid,#errorsloc').hide();
					});
                    if (res == 1) {
                        alert('Already Played');
                    } else {
                        var one = $('#one-1').val();
                        var two = $('#two-2').val();
                        var three = $('#three-3').val();
                        var four = $('#four-4').val();
                        var five = $('#five-5').val();
                        var six = $('#six-6').val();
                        var seven = $('#seven-7').val();
                        var eight = $('#eight-8').val();
                        var nine = $('#nine-9').val();
                        var ten = $('#ten-10').val();
                        var eleven = $('#eleven-11').val();
                        var twelve = $('#twelve-12').val();
                        var name = $('#name').val();
                        var email = $('#email').val();
                        var location = $('#location').val();
                        var agentCode = $('#agent_code').val();
                        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
                        var testemail = pattern.test(email);
                        if (one == '') {
							$('#err').html("<h3 style='color:red'>All fields are mandatory</h3>");
                            $('#one-1').css("border", "2px solid red");
                            return false;
                        } else if (two == '') {
							$('#err').html("<h3 style='color:red'>All fields are mandatory</h3>");
                            $('#two-2').css("border", "2px solid red");
                            return false;
                        } else if (three == '') {
							$('#err').html("<h3 style='color:red'>All fields are mandatory</h3>");
                            $('#three-3').css("border", "2px solid red");
                            return false;
                        } else if (four == '') {
							$('#err').html("<h3 style='color:red'>All fields are mandatory</h3>");
                            $('#four-4').css("border", "2px solid red");
                            return false;
                        } else if (five == '') {
							$('#err').html("<h3 style='color:red'>All fields are mandatory</h3>");
                            $('#five-5').css("border", "2px solid red");
                            return false;
                        } else if (six == '') {
							$('#err').html("<h3 style='color:red'>All fields are mandatory</h3>");
                            $('#six-6').css("border", "2px solid red");
                            return false;
                        } else if (seven == '') {
							$('#err').html("<h3 style='color:red'>All fields are mandatory</h3>");
                            $('#seven-7').css("border", "2px solid red");
                            return false;
                        } else if (eight == '') {
							$('#err').html("<h3 style='color:red'>All fields are mandatory</h3>");
                            $('#eight-8').css("border", "2px solid red");
                            return false;
                        } else if (nine == '') {
							$('#err').html("<h3 style='color:red'>All fields are mandatory</h3>");
                            $('#nine-9').css("border", "2px solid red");
                            return false;
                        } else if (ten == '') {
							$('#err').html("<h3 style='color:red'>All fields are mandatory</h3>");
                            $('#ten-10').css("border", "2px solid red");
                            return false;
                        } else if (eleven == '') {
                            $('#eleven-11').css("border", "2px solid red");
                            return false;
                        } else if (twelve == '') {
							$('#err').html("<h3 style='color:red'>All fields are mandatory</h3>");
                            $('#twelve-12').css("border", "2px solid red");
                            return false;
                        } else if (name == '') {
							$('#err').html("<h3 style='color:red'>All fields are mandatory</h3>");
                            $('#name').css("border", "2px solid red");
                            $('#errorsname').html("Please Enter name");
                            return false;
                        } else if (email == '') {
							$('#err').html("<h3 style='color:red'>All fields are mandatory</h3>");
                            $('#email').css("border", "2px solid red");
                            $('#emailid').html("Please enter Email-id");
                            return false;
                        } else if (!testemail) {
                            $('#errors').html("Not valid email");
                            return false;
                        } else if (location == '') {
							$('#err').html("<h3 style='color:red'>All fields are mandatory</h3>");
                            $('#location').css("border", "2px solid red");
                            $('#errorsloc').html("Please enter Location");
                            return false;
                        }
                        else if (agentCode == '') {
							$('#err').html("<h3 style='color:red'>All fields are mandatory</h3>");
                            $('#location').css("border", "2px solid red");
                            $('#errorsAgentcode').html("Please enter your employee code");
                            return false;
                        }

                        $.ajax({
                            type: 'post',
                            data: 'userid=' + userid + '&one=' + one + '&two=' + two + '&three=' + three + '&four=' + four + '&five=' + five + '&six=' + six + '&seven=' + seven + '&eight=' + eight + '&nine=' + nine + '&ten=' + ten + '&eleven=' + eleven + '&twelve=' + twelve + '&name=' + name + '&email=' + email + '&location=' + location+'&agent_code='+agentCode,
                            url: 'ajaxcall/print.php',
                            success: function (res) {
								$('#err').hide();
                                $('.ticketdiv').html(res);
                                window.location.href = 'bingo.php';
                                //var divToPrint = document.getElementById('divToPrint');
                                //var popupWin = window.open('printticket.php');
                                //popupWin.document.open();
                                //popupWin.document.write('<html><body>' + divToPrint.innerHTML + '</html>');
                                //popupWin.document.close();
                            }
                        });
                    }
                }
            }); 

            /*$.ajax({
             type:'post',  
             data:'userid='+userid+'&calltype=fetch',
             url: 'ajaxcall/bingo.php',
             success:function(res){
             if(res==1){
             alert('already Played');
             }else{
             //var divToPrint = document.getElementById('divToPrint');
             //var popupWin = window.open('', '_blank');
             //popupWin.document.open();
             //popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
             //popupWin.document.close();
             
             $.ajax({
             type:'post', 
             data:'userid='+userid+'&one='+one+'&two='+two+'&three='+three+'&four='+four+'&five='+five+'&six='+six+'&seven='+seven+'&eight='+eight+'&nine='+nine+'&ten='+ten+'&eleven='+eleven+'&twelve='+twelve+'&name='+name+'&email='+email+'&location='+location,
             url: 'ajaxcall/bingo.php',
             success:function(res){
             }
             });
             
             }
             }
             });*/
        });
    });
</script>