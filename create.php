<?php
error_reporting(E_ALL);
session_start();
include_once 'dao/config.php';
include_once '../check-rating.php';

if(empty($_SESSION['userId'])){
    header("Location:index.php");
}

$isRated=check_rating();
$userId=$_SESSION['userId'];
$organizationId=$_SESSION['organizationId'];
$sessionId=$_SESSION['sessionId'];
$name=$_SESSION['firstName']." ".$_SESSION['lastName'];
$email=$_SESSION['email'];
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
include_once '../admin_assets/triggers-new.php';

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

  if($prevent_submit){
     header("Location:display.php");
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dhoom Dham Diwali</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/create.css?v=8">
</head>
<body>
<?php include("../actions-default.php");  back("index.php?save");?>

<div class="em-popup" style="opacity:0; ">
        <div class="em-modal">
            <div class="em-icon-container" style="display:none;"><img src="images/popup-like.png"/></div>
            <div class="em-content" style="display:none;">---</div>
            <div class="em-rules">
                <div class="em-rules-Button">Rules:</div>
                <ol>
                   <li>You can create your ticket only once</li>
                   <li>It will get saved under your login user details</li>
                   <li>Enjoy the game together on 19th Oct 6PM IST.</li>
                </ol>
            </div>
            <button class="em-popup-button" value="OK" style="">OK</button>
        </div>
    </div>

<div class="container-fluid">
<div class="row">
<div class="col-md-11 col-sm-11 col-lg-11 col-xs-12 auto page-vertical-container">
<div class="col-md-12 text-center">
    <!-- <img src="img/feeling_lucky.png" class="feeling-lucky"/> -->
     <div class="feeling-lucky-title">Feeling Lucky ?</div>
     <div class="col-md-12">
     <div class="rules-button text-center">Rules</div>
     </div>

</div>
<div class="col-md-10 col-md-offset-1 page-rule-container">
    <div class="page-rule-title">Follow the 2 steps below, try your luck and win!</div>
    <ul>
        <li><span>Step 1: </span>Select any 12 of the 50 words shown below by clicking on the words one at a time.</li>
        <li><span>Step 2: </span>Press submit.</li>
    </ul>
</div>
<div class="col-md-5 npadding-web">
<div class="col-md-11 col-md-offset-1 npadding-web">
<table class="default-words">
<?php 
echo "<tr>";
for ($i = 1; $i < 51; $i++) {
  if ($i % 5 == 1 && $i != 1) {
    echo "</tr><tr>";
  }
  echo  "<td>".$array_values[$i-1]."</td>";
}
echo "</tr>";
?>                                 
</table>
</div>
</div>

<div class="col-md-7 nopadding-mob">
<div class="col-md-12 nopadding-mob">
    <div class="col-md-12 text-center ticket-container">
        <div id="ticket-box">
            <div class="off"></div>
            <div class="on"></div>
            <div class="off"></div>
            <div class="on"></div>
            <div class="off"></div>
            <div class="on"></div>
            <div class="off"></div>
            <div class="on"></div>
            <div class="on"></div>
            <div class="off"></div>
            <div class="on"></div>
            <div class="off"></div>
            <div class="on"></div>
            <div class="off"></div>
            <div class="on"></div>
            <div class="off"></div>
            <div class="off"></div>
            <div class="on"></div>
            <div class="off"></div>
            <div class="on"></div>
            <div class="off"></div>
            <div class="on"></div>
            <div class="off"></div>
            <div class="on"></div>
        </div>
        <img src="images/ticket.png" class="ticket-image"/>
    </div>
</div>
<div class="col-md-12 text-center">
    <button class="autogenerate">Click to Generate Automatic Ticket</button>
</div>
<div class="col-md-12 text-center">
<button class="create-ticket">Submit</button>
</div>
</div>

</div>
</div>
</div>
<img src="images/bottom-gif.gif" class="bottom-gif desk"/>
<img src="images/bottom-gif-mob.gif" class="bottom-gif mob"/>
</body>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="em-popup.js"></script>
<script type="text/javascript">

<?php echo $demoprint;?>

<?php
if($isExist){
    echo 'location.href=("display.php");';
}
?>

var responseNumbers=<?php if($isExist){ echo json_encode($numbers);} else {echo "false";}?>;
let userId = "<?php echo $userId;?>";
var organizationId="<?php echo $organizationId;?>";
var token = "<?php echo $_SESSION['token']; ?>";




function css_handle(){
  var boxHeight= document.getElementById("ticket-box").offsetHeight;
  var cellHeight=boxHeight/3;
  $(".on,.off").css("height",cellHeight+"px");
  // $(".on,.off").css("line-height",cellHeight+"px");
}

css_handle();

var dataArray=<?php echo json_encode($array_values);?>;
var autogen=false;
var bingoCount=0;
var empty_pos=[];
var virtualArray=[];
    var wordsdiv=$(".default-words td").length;
    $(".default-words td").click(function(){
        if(autogen==true){
            bingoCount=0;
            for(var i=0; i<12; i++){
                      $(".off").eq(i).html("");
                 }
                 for(var i=0; i<wordsdiv; i++){
                    $(".default-words td").removeAttr("capture");
                    $(".default-words td").css("background","transparent");
                    $(".default-words td").css("color","white");
                 }
        }


            var hisAttri=$(this).attr("capture");
            if(hisAttri=="true"){
                $(this).css("background","transparent");
                $(this).css("color","white");
                var getText=$(this).html();
                $(this).attr("capture","false");
                for(var i=0; i<virtualArray.length; i++){
                    if(virtualArray[i]==getText){
                        $(".off").eq(i).html("");
                        empty_pos.push(i);
                    }
                }
                bingoCount=bingoCount-1;
                autogen=false;
            }else{
                if(bingoCount<12){
                $(this).css("background","black");
                $(this).css("color","white");
                var getText=$(this).html();
                if(empty_pos.length > 0){
                    var newPos=empty_pos[0];
                    $(".off").eq(newPos).html(getText);
                    virtualArray[newPos]=getText;
                    empty_pos.shift();
                    console.log(empty_pos);
                }else{
                    $(".off").eq(bingoCount).html(getText);
                    virtualArray.push(getText);
                }

                console.log(virtualArray);
                $(this).attr("capture","true");
                bingoCount=bingoCount+1;
                autogen=false;
                console.log(getText);
            }else{
            alert("only 12 Words can be selected");
        }
            }
    
    })


    $(".autogenerate").click(function(){
        for(var i=0; i<wordsdiv; i++){
                    $(".default-words td").removeAttr("selected");
                    $(".default-words td").css("background","transparent");
                    $(".default-words td").css("color","white");
                 }
   genRandom();
   swal("Ticket Generated", "", "success");
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
        $(".off").eq(i).html(dataArray[getUniqueNumber]);
        $(".default-words td").eq(uniqueNumbers[i]).css("background","black");
        $(".default-words td").eq(uniqueNumbers[i]).css("color","white");
        $(".default-words td").eq(uniqueNumbers[i]).attr("selected","true");
    }
}

$(".rules-button").click(function(){
    emPop("", {
                  reload: false
                });
});


$(".create-ticket").click(function(){
        collectAnswers=[];
        isAllCollected=true;
        var allInputs= $(".off");
        for(var i=0; i<allInputs.length; i++){
            if(allInputs.eq(i).html()!=""){
                collectAnswers.push(allInputs.eq(i).html());
            }else{
                isAllCollected=false;
                allInputs.eq(i).css("border","1px solid red");
                swal("", "you must select all 12 words", "error");
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
                         location.href=("display.php");
                     }else{
                   swal("",data.error,"error")
              }
         } 
    });    
        }
    });
</script>
</html>
