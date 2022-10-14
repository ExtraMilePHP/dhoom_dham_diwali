<?php
error_reporting(E_ALL);
session_start();
include_once 'dao/config.php';
include_once '../check-rating.php';
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.14.3/video-js.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.14.3/video.min.js"></script>
    <script src="https://player.live-video.net/1.13.0/amazon-ivs-videojs-tech.min.js"></script>
    <script src="aws-sdk-2.1232.0.js"></script>
  <link rel="stylesheet" type="text/css" href="css/display.css">
</head>
<body>
<?php include("../actions-default.php");  back("index.php?save");?>
<div class="container-fluid">
<div class="row">
<div class="col-md-11 auto page-vertical-container nopadding-mob">
<div class="col-md-6 nopadding-mob">
<!-- <div id="html5videoplayer" class="col-md-12 nopadding"
                            style="font-family: Arial Unicode MS; ">
                            <video id="videoarea" class="video-js vjs-default-skin vjs-big-play-centered" playsinline poster="images/backdrop-new-2.jpg" controls
                                preload="auto" autoplay="true" data-setup='{"fluid": true}'>
                                <source
                                    src="https://3ae97e9482b0d011.mediapackage.us-west-2.amazonaws.com/out/v1/c4241bbef4d84f8bb734032064311ea2/index.m3u8"
                                    type="application/x-mpegURL">
                            </video>
                        </div> -->
                        <div id="html5videoplayer">
                        <video id="amazon-ivs-videojs" class="video-js vjs-4-3 vjs-big-play-centered" controls autoplay playsinline></video>
                        </div>
<!-- <img src="images/screenshot-test.png" id="html5videoplayer" style="width:100%;"/>                      -->
<div class="col-md-12 nopadding-mob">
    <div class="action-button" action-data="chat"><img src="images/icon-chat.png"/>Chat</div>
    <div class="action-button" action-data="submit-claim"><img src="images/icon-claim.png"/>Submit Claim</div>
    <div class="action-button" action-data="words"><img src="images/icon-words.png"/>Words</div>
    <div class="action-button" action-data="leaderboard"><img src="images/icon-leaderboard.png"/>Leaderboard</div>
    <div class="action-button show-ticket" action-data="ticket"><img src="images/icon-claim.png"/><span>Show Ticket</span></div>
</div>
</div>
<div class="col-md-6 tickit-and-display nopadding-web nopadding-mob">
<div class="col-md-12 nopadding-web text-center ticket-container">
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
    <div class="col-md-12" style="margin-top:30px;">
    <div class="release-word-container" style="display:none;">
     <div class="container-title">Words Release So Far</div>
     <table class="release-table"></table>
    </div>
    <div class="claim-container" style="display:none;">
     <div class="container-title">Claim</div>
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
    </div>
    <div class="leaderboard-container" style="display:none;">
     <div class="container-title">Leaderboard</div>
     <table class="leaderboard-fetch">
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Timestamp</th>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
      </tr>
     </table>
    </div>
    <div class="chat-contianer" style="display:none;">
    <div class="container-title">Chatbox</div>
    <!-- <div id="messenger" style="height:300px;border:1px solid black;"></div> -->
    </div>
    </div>
</div>
</div>
</div>
</div>

<img src="images/bottom-gif.gif" class="bottom-gif desk"/>
<img src="images/bottom-gif-mob.gif" class="bottom-gif mob"/>
<script>

  
//     function generateJWT(sub, email) {
//     var secretString = "only_for_demo_purposes";
 
//     // token generation courtesy of https://github.com/kjur/jsrsasign
//     // see https://jwt.io/ for a available libraries
 
//     // token valid for 2 hours
//     var dateSoon = new Date();
//     dateSoon.setHours(dateSoon.getHours() + 24);
 
//     // return the signed token
//     return KJUR.jws.JWS.sign(null, 
//       { 
//         alg: "HS256", 
//         typ: "JWT" 
//       }, 
//       { 
//         iss: "extramile", 
//         client_id: "extramile",
//         sub: sub, 
//         exp: dateSoon.getTime() / 1000,      
//         email: email      
//       }, 
//       secretString);
// }

// console.log(generateJWT("em001","rohit@extramile.in"));

//     const weavy = new Weavy({
//       url: "https://extramile-playstream.weavy.io",
//       tokenFactory: async (refresh) => "wyu_viRX7wj0y5IDQf6udMTO9MQI0LTztG0LFYLv"
//     });

//     const app = weavy.app({
//       uid: "product-256",
//       type: "chat",
//       container: "#my-container"
// })


(function play() {
            // Get playback URL from Amazon IVS API
            var PLAYBACK_URL = 'https://c1b95bb3aeb3.ap-south-1.playback.live-video.net/api/video/v1/ap-south-1.784725293398.channel.BJ42wi3uaiyV.m3u8';
            
            // Register Amazon IVS as playback technology for Video.js
            registerIVSTech(videojs);

            // Initialize player
            var player = videojs('amazon-ivs-videojs', {
               techOrder: ["AmazonIVS"]
            }, () => {
               console.log('Player is ready to use!');
               // Play stream
               player.src(PLAYBACK_URL);
            });
        })();


    
//   const ivs = new AWS.Ivschat();
//   const result = await ivs.createChatToken(params).promise();
//   console.log("New token created", result.token);
//   /* If the duration is 60 seconds or less (minimum allowed),
//      generate a new token every 30 seconds. Otherwise,
//      generate a new token every duration minus 60 seconds.
//   */
//   const regenerateFrequencyInSeconds = params.duration <= 60 ? 30 : params.duration - 60;
//   setTimeout(() => createChatToken(params), regenerateFrequencyInSeconds*1000);
// }


// const params = {
//   "attributes": {
//     "displayName": "DemoUser",
//   },
//   "capabilities": ["SEND_MESSAGE"],
//   "roomIdentifier": "arn:aws:ivschat:ap-south-1:784725293398:room/7oy0areDlPUn",
//   "userId": "em001"
// };
// createChatToken(params);
        
  </script>
</body>
<script src="env.js"></script>
<script src="https://vjs.zencdn.net/7.6.6/video.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">

function css_handle(){
  var boxHeight= document.getElementById("ticket-box").offsetHeight;
  var cellHeight=boxHeight/3;
  $(".on,.off").css("height",cellHeight+"px");
  // $(".on,.off").css("line-height",cellHeight+"px");
}

css_handle();


$(".action-button").click(function(){
  var gameData=$(this).attr("action-data");
  if(gameData!="ticket"){
    $(".release-word-container,.claim-container,.leaderboard-container,.chat-contianer").hide();
  }
    if(gameData=="words"){
        $(".release-word-container").show();
    }
    if(gameData=="submit-claim"){
        $(".claim-container").show();
    }
    if(gameData=="leaderboard"){
        $(".leaderboard-container").show();
        getLeaderboard();
    }
    if(gameData=="chat"){
        $(".chat-contianer").show();
    }
});

var hideTicket=false;
$(".show-ticket").click(function(){
        if(hideTicket){
          $(this).find("span").html("Hide Ticket");
          $(".ticket-container").show();
          css_handle();
          hideTicket=false;
        }else{
          $(this).find("span").html("Show Ticket")
          $(".ticket-container").hide();
          hideTicket=true;
        }
});


ini();
function ini(){
    $.ajax({ 
        "url": "fetch_release.php",
        "method": "POST",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/json"
        },
        "data": JSON.stringify({
            "team_id":"test"
        }),
        success: function(data){
            console.log(data);
            var data = JSON.parse(data);
            console.log(data);
 var prepareData="";
     prepareData+="<tr>";
  for (i = 1; i < data.launchpos; i++) {
    if (i % data.numlimit == 1 && i != 1) {
        prepareData+="</tr><tr>";
    }
         var prepare_num=data.shuffle[i-1];
         prepareData+="<td>"+data.array_values[prepare_num-1]+"</td>";
     }
         prepareData+="</tr>"

         $(".release-table").html(prepareData);
        } 
    }); 
}

var responseNumbers=<?php if($isExist){ echo json_encode($numbers);} else {echo "false";}?>;
function fillAllNumbers(){
  document.body.scrollTop = 0;
  var final_numbers=$(".off");
  for(var i=0; i<final_numbers.length; i++){
      $(".off").eq(i).html(responseNumbers[i]);
  }
  window.scrollTo(0, 0);
}


if(responseNumbers==false){
  $(".ticket-container").html('<div class="notice">Note: You have not created ticket. in time</div>');
  $(".ticket-container").css("height","auto");
  $(".action-button").eq(1).hide();
  $(".action-button").eq(2).hide();
  $(".action-button").eq(4).hide();
}

    fillAllNumbers();

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



function getLeaderboard(){
  $.ajax({ 
       type: "POST", 
       url: "leaderboard-data.php", 
       data: "", 
       success: function(result) {
        console.log(result);
        var data = JSON.parse(result); 
           console.log(data);
           var prepareData="";
           prepareData+="<tr><th>Name</th><th>Email</th><th>Timestamp</th></tr>";
           console.log(data.boardData);
           for(i=0; i<data.boardData.length; i++){
            prepareData+="<tr>";
            prepareData+="<td>"+data.boardData[i][0]+"</td>";
            prepareData+="<td>"+data.boardData[i][1]+"</td>";
            prepareData+="<td>"+data.boardData[i][6]+"</td>";
            prepareData+="</tr>";
           }
           console.log(prepareData);
           $(".leaderboard-fetch").html(prepareData);
        } 
});
}

getLeaderboard();

var opening = {
                'request': "OPENING_REQUEST",
                'userId': '<?php echo $userId;?>',
                'name': '<?php echo $name;?>',
                'email': '<?php echo $email;?>'
    };


    var conn = new WebSocket(socketUrl);
    conn.onmessage = function(e) {
        var final = JSON.parse(e.data);
           console.log(data);
        if (Array.isArray(final)) {
            console.log("don't need to show");
        } else {
            showRequests(final);
        }
    };

    conn.onopen = function(e) {
        conn.send(JSON.stringify(opening));
    };

    
    conn.onclose = function(e) {
        console.log("connection closed!");
    };


    function showRequests(final) {
        if(final.request == "REFRESH_DATA"){
             ini();
        }
    }

</script>
</html>
