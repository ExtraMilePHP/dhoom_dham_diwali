<?php
ob_start();
error_reporting(E_ALL);
session_start();
$settings=json_decode(file_get_contents("settings.js"),true)[0];
$organizationId=$_SESSION['organizationId'];
$sessionId=$_SESSION['sessionId'];
include_once '../dao/config.php';
include_once '../../admin_assets/triggers-new.php';



if (!$_SESSION['adminId']) {
    header('Location:../index.php?save');
} 

$tabName="Settings";
$values_array=default_data("values_array");
$shuffle=default_data("shuffle");
$launchpos=default_data("launchpos");
$numlimit=default_data("numlimit");
$claims=default_data("claims");
$winnerList=default_data("winnerList");
$rules=default_data("rules");



$prevent_submit=toogles("prevent_submit");
$prevent_claim=toogles("prevent_claim");
$display_leaderboard=toogles("display_leaderboard");
$multiple_claims=toogles("multiple_claims");
$winner_restrictions=toogles("winner_restrictions");
$auto_generate=toogles("auto_generate");
$display_chat=toogles("display_chat");




?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
      
<?php include_once("../../admin_assets/common-css.php");?>
<!-- Only unique css classes -->
    <style rel="stylesheet" type="text/css">
         .claim-type{
            padding-top: 10px;
         }
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


		<div class="col-md-4">
			<div class="card" id="custom_card_height">
				<?php cardHeader("Full House Settings");?>
				<div class="card-content collapse show">
					<div class="card-body">
                        <div class="row">
                        <div class="col-md-12">
                        <div class="form-group pb-1">
                          <?php 
                    
                          if($prevent_submit){
                            echo '<a href="'.PAGE_NAME.'?setting=prevent_submit&value=false"><input type="checkbox" id="switchery2" class="switchery" data-size="sm" '.switchery($prevent_submit).'/></a>';
                          }else{
                            echo '<a href="'.PAGE_NAME.'?setting=prevent_submit&value=true"><input type="checkbox" id="switchery2" class="switchery" data-size="sm" '.switchery($prevent_submit).'/></a>';
                          }
                          ?>
              
              <label for="switchery2" class="font-medium-2 text-bold-600 ml-1">Disable Ticket Creation</label>
            </div>
            
            <div class="form-group pb-1">
                          <?php 
                    
                          if($prevent_claim){
                            echo '<a href="'.PAGE_NAME.'?setting=prevent_claim&value=false"><input type="checkbox" id="switchery2" class="switchery" data-size="sm" '.switchery($prevent_claim).'/></a>';
                          }else{
                            echo '<a href="'.PAGE_NAME.'?setting=prevent_claim&value=true"><input type="checkbox" id="switchery2" class="switchery" data-size="sm" '.switchery($prevent_claim).'/></a>';
                          }
                          ?>
              
              <label for="switchery2" class="font-medium-2 text-bold-600 ml-1">Disable Claims</label>
            </div>

            <div class="form-group pb-1">
                          <?php 
                    
                          if($auto_generate){
                            echo '<a href="'.PAGE_NAME.'?setting=auto_generate&value=false"><input type="checkbox" id="switchery2" class="switchery" data-size="sm" '.switchery($auto_generate).'/></a>';
                          }else{
                            echo '<a href="'.PAGE_NAME.'?setting=auto_generate&value=true"><input type="checkbox" id="switchery2" class="switchery" data-size="sm" '.switchery($auto_generate).'/></a>';
                          }
                          ?>
              
              <label for="switchery2" class="font-medium-2 text-bold-600 ml-1">Disable Auto Generate Button</label>
            </div>

            <div class="form-group pb-1">
                          <?php 
                    
                          if($display_leaderboard){
                            echo '<a href="'.PAGE_NAME.'?setting=display_leaderboard&value=false"><input type="checkbox" id="switchery2" class="switchery" data-size="sm" '.switchery($display_leaderboard).'/></a>';
                          }else{
                            echo '<a href="'.PAGE_NAME.'?setting=display_leaderboard&value=true"><input type="checkbox" id="switchery2" class="switchery" data-size="sm" '.switchery($display_leaderboard).'/></a>';
                          }
                          ?>
              
              <label for="switchery2" class="font-medium-2 text-bold-600 ml-1">Show Leaderboard to everyone</label>
            </div>
            <div class="form-group pb-1">
                          <?php 
                    
                          if($multiple_claims){
                            echo '<a href="'.PAGE_NAME.'?setting=multiple_claims&value=false"><input type="checkbox" id="switchery2" class="switchery" data-size="sm" '.switchery($multiple_claims).'/></a>';
                          }else{
                            echo '<a href="'.PAGE_NAME.'?setting=multiple_claims&value=true"><input type="checkbox" id="switchery2" class="switchery" data-size="sm" '.switchery($multiple_claims).'/></a>';
                          }
                          ?>
              
              <label for="switchery2" class="font-medium-2 text-bold-600 ml-1">Multiple Claims</label>
            </div>
            <div class="form-group pb-1">
                          <?php 
                    
                          if($winner_restrictions){
                            echo '<a href="'.PAGE_NAME.'?setting=winner_restrictions&value=false"><input type="checkbox" id="switchery2" class="switchery" data-size="sm" '.switchery($winner_restrictions).'/></a>';
                          }else{
                            echo '<a href="'.PAGE_NAME.'?setting=winner_restrictions&value=true"><input type="checkbox" id="switchery2" class="switchery" data-size="sm" '.switchery($winner_restrictions).'/></a>';
                          }
                          ?>
              
              <label for="switchery2" class="font-medium-2 text-bold-600 ml-1">Winner Restriction</label>
            </div>
            <div class="form-group pb-1">
                          <?php 
                    
                          if($display_chat){
                            echo '<a href="'.PAGE_NAME.'?setting=display_chat&value=false"><input type="checkbox" id="switchery2" class="switchery" data-size="sm" '.switchery($display_chat).'/></a>';
                          }else{
                            echo '<a href="'.PAGE_NAME.'?setting=display_chat&value=true"><input type="checkbox" id="switchery2" class="switchery" data-size="sm" '.switchery($display_chat).'/></a>';
                          }
                          ?>
              
              <label for="switchery2" class="font-medium-2 text-bold-600 ml-1">Show Chat</label>
            </div>
            <div class="well" style="margin-top:10px; font-weight:bolder; color:red;">Toogle switch to Disable / Enable</div>
</div>
 
                </div>
</div>
</div>
        </div>
</div>


<div class="col-md-5">
			<div class="card" id="custom_card_height">
				<?php cardHeader("Full House Settings");?>
				<div class="card-content collapse show">
					<div class="card-body">
                        <div class="row">
                        <div class="col-md-12">
                        <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#home">Settings</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#menu1">Content</a>
    </li>
  </ul>
                        </div>


  <!-- Tab panes -->
  <div class="tab-content col-md-12">
    <div id="home" class="container tab-pane active"><br>
   <!-- tab content-->
   <div class="col-md-12">
   <div class="col-md-12">
                            <h4 class="card-title" id="basic-layout-form">Release Limit In Numbers</h4>
                            <input type="number" id="relimit" value="<?php echo $numlimit;?>" class="form-control" id="basicInput" >
                            <label for="relimit" class="font-medium-2 text-bold-600 text-danger">Do not Change After Release</label>
                       </div>
            <div class="col-md-12  auto">
                <div class="form-container">
                <button class="btn btn-nd em-color login-button" style="margin-top:0px; margin-bottom:15px;" id="settings" type="submit" name="submit">Save</button>           
                </div>
            </div> 
</div>
   <!-- tab content end-->
    </div>
    <div id="menu1" class="container tab-pane fade"><br>
      <!-- tab content-->
      <div class="col-md-12">
                          <h4 class="card-title">Add Custom Rules:</h4>
                        <div class="form-group col-12 mb-2 rules-repeater">

<div data-repeater-list="repeater-group">
  <?php 
  for($i=0; $i<sizeof($rules); $i++){
     echo ' <div class="input-group mb-1" data-repeater-item>
     <input type="text" placeholder="Rules" name="rules" value="'.$rules[$i].'" class="form-control" id="example-ql-input">
     <span class="input-group-append" id="button-addon2">
         <button class="btn btn-danger" type="button" data-repeater-delete>
             <i class="ft-x"></i>
         </button>
     </span>
 </div>';
  }
  ?>
   
</div>

<button type="button" data-repeater-create class="btn btn-primary em-color">
    <i class="ft-plus"></i> Add new rule
</button>
<p class="text-muted"><code>* Keep 6 rules at the most <br> * At least 1 rule is required <br> * character limit of each rule is 200</code></p>
</div>

                        </div>

<div class="col-md-12 text-center auto">
                <div class="form-container">
                <button class="btn btn-md btn-success login-button core-button-color" style="margin-top:15px;" id="tagButton" type="submit" name="submit">Save</button>           
                </div>
                <div class="col-md-12" style="margin-top:20px;">
         
                </div>
            </div>
        <!-- tab content end-->
    </div>

  </div>
 

            <?php 
            if($winner_restrictions){

              echo '<h4 class="card-title col-md-12 text-center" id="basic-layout-form">Winner Restrictions</h4>';
              for($i=0; $i<sizeof($winnerList); $i++){
                foreach ($winnerList[$i] as $key => $val){
                  if($claims[0][$key]){
                    echo '<div class="col-md-6">
                    <div class="form-group row">
                     <label class="col-md-6 label-control claim-type" for="projectinput5">'.$key.'</label>
                     <div class="col-md-6">
                            <input type="number" id="projectinput5" class="form-control form-data claim-input" placeholder="News" category='.$key.' value="'.$val.'" name="reward" required>
                        </div>
                    </div>
                  </div>';
                  }
                }
               }

               echo '<div class="col-md-12  text-center">
                      <div class="form-container">
                     <button class="btn btn-nd em-color login-button" style="margin-top:0px; margin-bottom:15px;" id="winners" type="submit" name="submit">Save</button>           
                    </div>
                   </div>';
            }
              
           ?>
            
                     </div>
                </div>
</div>
</div>
        </div>

        <div class="col-md-3">
			<div class="card" id="custom_card_height">
				<?php cardHeader("Claims Type");?>
				<div class="card-content collapse show">
					<div class="card-body">
                        <div class="row">
                        <div class="col-md-12">
                        <h4 class="card-title" id="basic-layout-form">Toogle Claim types</h4>
                          <?php 
               for($i=0; $i<sizeof($claims); $i++){
                foreach ($claims[$i] as $key => $val){
                  // echo "Key-value pair is: "."(".$key.", ".$val.")";
                  echo '<div class="col-md-12">';
                  if($val){
                    echo '<a href="events.php?events=claim_toogle&claim_type='.$key.'&value=false"><input type="checkbox" id="switchery2" class="switchery" data-size="sm" '.switchery($val).'/></a>';
                  }else{
                    echo '<a href="events.php?events=claim_toogle&claim_type='.$key.'&value=true"><input type="checkbox" id="switchery2" class="switchery" data-size="sm" '.switchery($val).'/></a>';
                  }
                  echo '<label for="switchery2" class="font-medium-2 text-bold-600 ml-1">'.$key.'</label>';
                  echo '</div>';
                }
               }
           ?>
            </div>

</div>
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



    


<?php include("../../admin_assets/footer.php");?>
<?php include_once("../../admin_assets/common-js.php");?>
<script type="text/javascript">
   $("#settings").click(function(){
   var getNum=$("#relimit").val();
   location.href=("events.php?events=change_limit&value="+getNum);
})

$("#winners").click(function(){
  var inputData=$(".claim-input");
  var getData={};
  for(i=0; i<inputData.length; i++){
    var saveData=inputData.eq(i).val();
    var createKey=inputData.eq(i).attr("category");   
    getData[createKey] = saveData;
   }
   console.log(getData); 
   $.ajax({ 
       type: "POST", 
       url: "events.php?events=winner_data", 
       data: {'winner_data' : getData}, 
       success: function(result){ 
        console.log(result);
              if(result=="true"){
                 swal('Success', 'Data Updated', 'success');
                 setTimeout(() => {
                  location.reload();
                 }, 1500);
               }else{
                 swal('Error', result, 'error');
                 setTimeout(() => {
                  location.reload();
                 }, 2000);
            }
       } 
});  
});


$('.rules-repeater').repeater({
  show: function () {

if( $(this).parents(".rules-repeater").find("div[data-repeater-item]").length <= 6 ){
$(this).slideDown();
} else {
$(this).remove();
}
}
});

var rules=[];
function getValues(){
  $('input[name*="rules"]').each(function(e)
{
      rules.push($(this).val()); 
});
}


$(".login-button").click(function(){
      if($('.rules-repeater').find("div[data-repeater-item]").length!=0){
        getValues();
      $.ajax({ 
       type: "POST", 
       url: "events.php?events=add_rules", 
       data: {"rules":rules}, 
       success: function(result) { 
              if(result=="true"){
                swal('Success', 'Data Updated', 'success');
                setTimeout(() => {
                  location.reload();
                }, 1000);
              }else{
                swal('Error', result, 'error');
              }
        } 
        });  
      }else{
        alert("at least 1 rule required.");
      }
    });
</script>
  </body>
</html>