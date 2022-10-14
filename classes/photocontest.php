<?php

session_start();

include_once 'classes/SessionCheckClass.php';
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
$objEventRegister = new EventRegister();

if ($_POST['Submit']) {
    $tempOffice = $_POST['officeid'];
    $result = $objEventRegister->uploadImage($_POST, $_FILES, $connPdo, $objpdoClass, $objmessageClass);
    if ($result) {
        echo "<script type='text/javascript'>window.location = 'photocontest.php?id=" . $tempOffice . "';</script>";
        die;
    }
}
$officeId = $_GET['id'];
$imageArr = $objEventRegister->getUploadImage($officeId, $connPdo, $objpdoClass);
$officeName = $objEventRegister->getOfficeDetails($officeId, $connPdo, $objpdoClass);
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Navratri 2015</title>
        <link rel='stylesheet' type='text/css' href='css/style.css'>
        <link rel='stylesheet' type='text/css' href='css/responsive.css'>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>

        <script type="text/javascript" src="js/html5shiv-2.js"></script>
        <!-- For Validation -->
        <script type="text/javascript" src="js/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="js/jquery.validate.js"></script>
        <script type="text/javascript">
            function imageLike(imageid, userid) {
                $.ajax({
                    type: "POST",
                    url: 'ajaxcall/ajaxImageLike.php',
                    data: {
                        'imageid': imageid,
                        'userid': userid
                    },
                    success: function (msg) {
                        var jsonVar = JSON.parse(msg);
                        if (jsonVar.error == "True") {
                            alert(jsonVar.message)
                        } else {
                            $("#img_" + imageid).html(jsonVar.likecount)
                        }
                    }
                });
            }
        </script>
    </head>

    <body>
        <!--header start-->
        <?php include('includes/header.php'); ?>
        <!--header end-->
        <!--Main Section Start-->
        <section class="mainBG">
            <!--Shell start-->
            <div class="shell"><div class="contentBG">
                    <div class="contentDIV" style="min-height:700px;">

                        <!--<div class="topTEXTInside">Here comes Navrang with colour and joy,<br/>
                        
                        Workshops and celebrations for you to enjoy<br/>
                        
                        <strong>Engage in the programs to learn something new,</strong><br/>
                        
                        A host of activities are planned just for YOU</div>
                        
                        <div class="godInside"><img src="images/ambe.png"></div>-->
                        <!--WhiteBG start-->
                        <div class="contentWhite bgheight">
                            <div class="officeHead"><?php echo $officeName[0]['office_name']; ?></div>
                            <div class="contestlines">
                                <?php // echo $mes;   ?>
                                <span class="colorGreen">      Upload your best selfie everyday from 10am to 3:30 pm as per the dress code for the day. </span><br/>
                                <span class="colorGreen"> Voting starts from 3:30 pm until 5pm</span><br/>
                                <span class="colorGreen"> The pics with maximum LIKES win prizes everyday. </span><br/>
                                <span class="colorGreen"> The exception - On 16th Oct, time for upload is 10am to 12pm and voting is from 12-2:30pm</span><br/>
                                <span class="colorGreen"> Good luck! May the best dressed win :-) </span>
                                

                              
                            </div>
                            <div class="buttons">
                                <span><a href="office_list.php" class="regButton">Back</a></span>
                            </div>
                            <div class="dividerDIV"></div>
                            <?php
                           // echo date('H:i');
                               if(strtotime(date('H:i')) < strtotime("15:30")){
                            ?>
                            <form action="photocontest.php" method="post" enctype="multipart/form-data">
                                <table width="30%" align="center">
                                    <tr>
                                        <td>
                                            <div class="field">
                                                <input type="hidden" name="userid" value="<?php echo $_SESSION['userid']; ?>"/>
                                                <input type="hidden" name="officeid" value="<?php echo $officeId; ?>"/>
                                            </div>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td>

                                            <div class="field">

                                                <div class="browseDiv">

                                                    <input type="file" name="uploadimage" id="uploadimage" value="" />

                                                </div>

                                            </div>

                                        </td>

                                    </tr>

                                    <tr>
                                        <td><div class="fieldDiv1"><input type="submit" class="submitButton1" value="SUBMIT" name="Submit"></div></td>
                                    </tr>

                                </table>


                            </form>

                            <br/>
                            <br/>
                            <?php
                               }
                            ?>
                            <div class="photocontest">
                                <ul>
                                    <?php
                                    foreach ($imageArr as $key => $imgArr) {
                                        ?>
                                        <li>
                                            <div class="contestentPic"><img src="images/userimage/<?php echo $imgArr['image']; ?> "/></div>
                                            <div class="contestentDiv"><div class="contestentName"><?php echo $imgArr['name']; ?></div>
                                                <?php
                                                //echo strtotime(date('H:i')).'<br/>'.strtotime("12:00");
                                                 // if(strtotime(date('H:i')) <= strtotime("15:30")){
                                                ?>
                                                <div id="like1" class="like">
                                                    <?php
                                                   // if(strtotime(date('H:i')) >= strtotime("15:30")){
                                                    ?>
                                                    <img src="images/likeIcon.png" onclick="imageLike(<?php echo $imgArr['id']; ?>,<?php echo $_SESSION['userid']; ?>)" />
                                                    <?php
                                                  //  }
                                                    ?>
                                                    <div class="count" id="img_<?php echo $imgArr['id']; ?>"><?php echo $imgArr['likecount'] ?> </div>

                                                </div>
                                                <?php
                                                //  }
                                                ?>
    <!--                                            <div class="dateDiv"><?php echo $images_rows['created_date']; ?></div>-->
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!--WhiteBG end-->

                </div></div></div>
        <!--Shell end-->
    </section>
    <!--Main Section End-->
    <script type="javascript">
        document.getElementById("uploadBtn").onchange = function () {
        document.getElementById("uploadFile").value = this.value;
        };
    </script>
</body>
</html>