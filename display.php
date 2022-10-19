<?php
error_reporting(E_ALL);
session_start();
include_once 'dao/config.php';
include_once '../check-rating.php';

if(empty($_SESSION['userId'])){
  header("Location:index.php");
}
// echo $_SESSION['token'];

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
    $tickit_select=unserialize($fetch->highlight);
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
  $display_chat=toogles("display_chat");
  $text="Words";
  if(is_numeric($array_values[0])){
    $text="Numbers";
  }

  if(!$prevent_submit){
    if(!$isExist){
         header("Location:create.php");
    }
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dhoom Dhaam Diwali Tambola</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.14.3/video-js.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.14.3/video.min.js"></script>
    <script src="https://player.live-video.net/1.13.0/amazon-ivs-videojs-tech.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.5.2/socket.io.js" integrity="sha512-VJ6+sp2E5rFQk05caiXXzQd1wBABpjEj1r5kMiLmGAAgwPItw1YpqsCCBtq8Yr1x6C49/mTpRdXtq8O2RcZhlQ==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/display.css?v=41">
</head>
<body>
<?php include("../actions-default.php");  back("index.php?save");?>
<canvas id="mycanvas"></canvas>
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
                        <video id="amazon-ivs-videojs"  poster="https://uat.extramileplay.com/php/dhoom_dham_diwali/img/banner-data.jpg?v=1" class="video-js vjs-4-3 vjs-big-play-centered" controls autoplay playsinline></video>
                        </div>
<!-- <img src="images/screenshot-test.png" id="html5videoplayer" style="width:100%;"/>                      -->
<div class="col-md-12 nopadding-mob text-center">
    <div class="action-button" pos="0" action-data="chat"><img src="images/icon-chat.png"/>Chat</div>
    <div class="action-button" pos="1" action-data="submit-claim"><img src="images/icon-claim.png"/>Submit Claim</div>
    <div class="action-button" pos="2" action-data="words"><img src="images/icon-words.png"/>Words</div>
    <div class="action-button" pos="3" action-data="leaderboard" style="display:none;"><img src="images/icon-leaderboard.png"/>Winner</div>
</div>
</div>
<div class="col-md-6 tickit-and-display nopadding-web nopadding-mob">
  <div class="col-md-12 text-center ticket-button-container">
  <div class="show-ticket" action-data="ticket">Ticket &nbsp;<label class="switch">
  <input type="checkbox" checked>
  <span class="slider round"></span>
</label></div>
  </div>
<div class="col-md-12 info-container"></div>
<div class="col-md-12 nopadding-web text-center ticket-container">
        <div id="ticket-box">
            <div class="off" pos="0"></div>
            <div class="on"></div>
            <div class="off" pos="1"></div>
            <div class="on"></div>
            <div class="off" pos="2"></div>
            <div class="on"></div>
            <div class="off" pos="3"></div>
            <div class="on"></div>
            <div class="on"></div>
            <div class="off" pos="4"></div>
            <div class="on"></div>
            <div class="off" pos="5"></div>
            <div class="on"></div>
            <div class="off" pos="6"></div>
            <div class="on"></div>
            <div class="off" pos="7"></div>
            <div class="off" pos="8"></div>
            <div class="on"></div>
            <div class="off" pos="9"></div>
            <div class="on"></div>
            <div class="off" pos="10"></div>
            <div class="on"></div>
            <div class="off" pos="11"></div>
            <div class="on"></div>
        </div>
        <img src="images/ticket.png" class="ticket-image"/>
    </div>
    <div class="col-md-12 nopadding-mob" style="margin-top:15px;">
    <div class="release-word-container" style="display:none;">
     <div class="container-title">Words Released So Far</div>
     <button value="" class="refresh-button"><i class="material-icons">refresh</i></button>
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
     <div class="col-md-12 text-center">
    <select name="cars" id="claim-data" class="select-tag-two" style="margin-top:10px;">
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
    </div>
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
    <div class="col-md-8 col-md-offset-2 chat-container nopadding-mob" id="chat-container"  style="<?php if(!$display_chat){ echo "display:none;";}?>">
                                    <div class="pinned-msg">
                                        <div class="title">HOST MESSAGE</div>
                                        <div class="message" style="width:100%; text-align:center;">Host message</div>
                                    </div>
                                </div>
                                <div class="col-md-8 col-md-offset-2"
                                    style="padding-left: 0px; padding-right: 0px; display:inline; <?php if(!$display_chat){ echo "display:none;";}?>" >
                                    <input type="text" id="chat-input" placeholder="Chat as <?php echo $name;?>" /><img src="img/smile.png" id="emoji-display"/> <input type="button" rules id="send" style="" value="Send" />
                                </div>
                        </div>
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
<script src="env.js?v=1"></script>
<script src="https://vjs.zencdn.net/7.6.6/video.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.1/gsap.min.js'></script>
<script src='https://pixijs.download/release/pixi.js'></script>
<script src='https://cdn.jsdelivr.net/npm/pixi-filters@latest/dist/pixi-filters.js'></script>
<script src="js/fireworks.js"></script>
<script type="text/javascript">

const button = document.querySelector('#emoji-display');

const picker = new EmojiButton();

button.addEventListener('click', () => {
  picker.togglePicker(button);
  
});

  picker.on('emoji', emoji => {
    document.querySelector('#chat-input').value += emoji;
  });

function css_handle(){
  var boxHeight= document.getElementById("ticket-box").offsetHeight;
  var cellHeight=boxHeight/3;
  $(".on,.off").css("height",cellHeight+"px");
  // $(".on,.off").css("line-height",cellHeight+"px");
}

css_handle();

var userId="<?php echo $_SESSION['userId'];?>";

$(".action-button").click(function(){
  var gameData=$(this).attr("action-data");
  var pos=$(this).attr("pos");
  $(".action-button").css("background","#ff5400");
  $(".action-button").css("color","white");
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
        getLeaderboard("top_line");
    }
    if(gameData=="chat"){
        $(".chat-contianer").show();
    }
    $(".action-button").eq(pos).css("background","#00bcd4");
    $(".action-button").eq(pos).css("color","black");
});

$(".chat-contianer").show();
var hideTicket=false;
$(".slider").click(function(){
        if(hideTicket){
          // $(this).find("span").html("Hide Ticket");
          $(".ticket-container").show();
          css_handle();
          hideTicket=false;
          $(".chat-container").css("height","200px");
        }else{
          // $(this).find("span").html("Show Ticket")
          $(".ticket-container").hide();
          $(".chat-container").css("height","400px");
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
           var reverse_values=data.array_values;
           var reverse_shuffle=data.shuffle;

       var loadArray=[];
     for (i = 1; i < data.launchpos; i++) {
     if (i % data.numlimit == 1 && i != 1) { }
         var prepare_num=reverse_shuffle[i-1];
        loadArray.push(reverse_values[prepare_num-1]);
     }

        //  loadArray.reverse();
         totalLoadArray=loadArray.length+1;
         var prepareData="";
          prepareData+="<tr>";
             for (i = 1; i < totalLoadArray; i++) {
               if (i % data.numlimit == 1 && i != 1) {
                    prepareData+="</tr><tr>";
              }
           prepareData+="<td>"+loadArray[i-1]+"</td>";
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
  $(".info-container").html('<div class="notice" style="margin-top:10px;">Note: The game has begun, all ticket creations are now paused! Sit back and enjoy the show.</div>');
  $(".ticket-container").hide();
  $(".action-button").eq(1).hide();
  $(".action-button").eq(2).hide();
  $(".action-button").eq(4).hide();
  $(".chat-container").css("height","280px");
  $(".show-ticket").hide();
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



function getLeaderboard(value){
  $.ajax({ 
       type: "POST", 
       url: "leaderboard-data.php", 
       data: "type="+value, 
       success: function(result) {
        console.log(result);
        var data = JSON.parse(result); 
           console.log(data);
           var prepareData="";
           prepareData+="<tr><th>Rank</th><th>Name</th><th>Organization</th></tr>";
           console.log(data.boardData);
           for(i=0; i<data.boardData.length; i++){
            prepareData+="<tr>";
            prepareData+="<td>"+data.boardData[i][2]+"</td>";
            prepareData+="<td>"+data.boardData[i][0]+"</td>";
            prepareData+="<td>"+data.boardData[i][6]+"</td>";
            prepareData+="</tr>";
           }
           console.log(prepareData);
           $(".leaderboard-fetch").html(prepareData);
        } 
});
}

$(".refresh-button").click(function(){
  ini();
});

$('.select-tag-two').change(function(){ 
    var value = $(this).val();
    getLeaderboard(value);
});
getLeaderboard("top_line");



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

    function sendChat(value){
        window.socket.emit("chats",{"userId":msg.userId,"webinarId":msg.webinarId,"userName":msg.userName,"mailId":msg.mailId,"companyName":msg.companyName,"message":value,"createdAt":"18-10-2022"});
    }

    socket.on('recievedReload', function(data){
            console.log("recievedReload");
            ini();
            StartFireworks();
    });
  


    socket.on('chatsMessage', function(data){
      $(".chat-container").html("");
            // console.log("working");
            //  console.log(data);
            //  console.log(data.length);
            var last10=data.slice(-10); 
             for(i=0; i<last10.length; i++){
                const myDate = last10[i].createdAt;
                const time = new Date(myDate).toLocaleTimeString('en',
                 { timeStyle: 'short', hour12: false, timeZone: 'UTC' });
                 if(last10[i].userId==userId){
                       $(".chat-container").append(
                        '<div class="msg_handler"><div class="personal-chat"><div class="chat-title">' + last10[i].userName + '</div>' + last10[i].message + '<div class="chat-time">' + time +
                        '</div></div></div>');
                 }else{
                       $(".chat-container").append(
                        '<div class="msg_handler"><div class="public-chat"><div class="chat-title">' + last10[i].userName + '</div>' + last10[i].message + '<div class="chat-time">' + time +
                        '</div></div></div>');
                 }
              
             }
             var objDiv = document.getElementById("chat-container");
            objDiv.scrollTop = objDiv.scrollHeight;
    });

    $("#send").click(function() {
        messagePacket();
    });
    $(document).on('keypress', function(e) {
        if (e.which == 13) {
            messagePacket();
        }
    });

    function messagePacket() {
        var getText = $("#chat-input").val();
        if (getText != "") {
            sendChat(getText);
            $("#chat-input").val("");
        }
    }

    remove_item = function(arr, value) {
 var b = '';
 for (b in arr) {
  if (arr[b] === value) {
   arr.splice(b, 1);
   break;
  }
 }
 return arr;
};
    var tickit_select=<?php echo json_encode($tickit_select);?>;
    $(".off").click(function() {
        var hisAttri = $(this).attr("value");
        var pos=$(this).attr("pos");
        if (hisAttri != "selected") {
            $(this).css("background", "yellow");
            $(this).attr("value", "selected");
            if(!tickit_select.includes(pos)){
                tickit_select.push(pos);
                update_selection();
            }
        } else {
            $(this).css("background", "white");
            $(this).attr("value", "deselect");
            if(tickit_select.includes(pos)){
                remove_item(tickit_select,pos);
                update_selection();
            }
        }
    });

   
    for(i=0; i<tickit_select.length; i++){
    var pos=tickit_select[i];
            $(".off").eq(pos).css("background", "yellow");
            $(".off").eq(pos).attr("value", "selected");
}

    function update_selection(){
    $.ajax({
        "url": "update_selection.php",
        "method": "POST",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/json"
        },
        "data": JSON.stringify({
            "tickit_select":tickit_select
        }),
        success: function(data){
            console.log(data);
        }
    });
}

// var opening = {
//                 'request': "OPENING_REQUEST",
//                 'userId': '<?php echo $userId;?>',
//                 'name': '<?php echo $name;?>',
//                 'email': '<?php echo $email;?>'
//     };


//     var conn = new WebSocket(socketUrl);
//     conn.onmessage = function(e) {
//         var final = JSON.parse(e.data);
//            console.log(e.data);
//            if(final.response=="REFRESH_DATA"){
//             ini();
//             StartFireworks();
//            }
//         // if (Array.isArray(final)) {
//         //     console.log("don't need to show");
//         // } else {
//         //     showRequests(final);
//         // }
//     };

//     conn.onopen = function(e) {
//         conn.send(JSON.stringify(opening));
//     };

    
//     conn.onclose = function(e) {
//         console.log("connection closed!");
//     };


    

</script>
</html>
