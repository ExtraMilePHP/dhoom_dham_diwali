<?php 

error_reporting(E_ALL);
session_start();
include_once 'dao/config.php';


if(empty($_SESSION['userId'])){
  header("Location:index.php");
}
?>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@weavy/dropin-js@13.0.0/dist/weavy-dropin.js" crossorigin="anonymous"></script>
</head>
<body>
<div id="messenger" style="width:400; height:600px;border:1px solid black;"></div>
<script type="text/javascript">

const weavy = new Weavy({
    url: "https://extramile-playstream.weavy.io",
    tokenFactory: async (refresh) => "wyu_DY46HXMZ0yOB835EGWymDGYCM0mr161T5Bks"
  });

  const messenger = weavy.app({
    uid: "product-257",
    type: "files",    
    container: "#messenger",
  });


// const messenger = weavy.app({
//       uid: "messenger-demo",
//       type: "messenger",    
//       container: "#messenger"
//     });



</script>
</body>
</html>


