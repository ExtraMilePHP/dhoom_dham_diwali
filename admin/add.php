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

$tabName="Add Numbers / words";
$values_array=default_data("values_array");
$shuffle=default_data("shuffle");
$launchpos=default_data("launchpos");
$numlimit=default_data("numlimit");

$List = implode(', ', $values_array); 

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


		<div class="col-md-6">
			<div class="card" id="custom_card_height">
				<?php cardHeader("Add Words or Numbers");?>
				<div class="card-content collapse show">
					<div class="card-body">
                        <div class="row">
                        <div class="col-md-12">
                        <fieldset>
                            <label>These Numbers or words Will Reflect on Landing Page</label>
                            <div class="form-group">
                                <div class="bingo-custom form-control" data-tags-input-name="bingo-custom"><?php print_r($List);?></div>
                              
                                <p class="text-muted"><code>* Duplicate Words Not Allowed   <br>* Word Must be less than 9 Characters</code></p>
                                <button type="button" class="btn btn-sm btn-primary em-color" id="showLength" style="margin-left:10px;">
    words 0 / 50
  </button>
                            </div>
                        </fieldset>

</div>
<div class="col-md-12 text-center auto">
                <div class="form-container">
                <button class="btn btn-lg em-color login-button" style="margin-top:15px;" id="tagButton" type="submit" name="submit">Save</button>           
                </div>
            </div>
 
                </div>
</div>
</div>
        </div>
</div>
        <!-- NEW CARD -->
        <div class="col-md-6">
			<div class="card" id="custom_card_height">
			<?php cardHeader("Themes");?>
				<div class="card-content collapse show">
					<div class="card-body">
                    <div class="col-md-12" style="margin-top:20px;">
    <div class="form-group text-center">
                            <!-- Buttons Shadow -->
                            
                            <button type="button" class="btn btn-primary btn-min-width box-shadow-1 mr-1 mb-1" id="AddNumbers" style="background:black;">Add 50 Numbers</button>
                            <button type="button" class="btn btn-primary btn-min-width box-shadow-3 mr-1 mb-1" id="AddWords" style="background:black;">Default 50 Words</button>
                            <button type="button" class="btn btn-danger btn-min-width box-shadow-3 mr-1 mb-1" id="resetField">Clear Field</button>
                            <p class="text-muted"><code>* Clear Input field first to select your preferred theme</code></p>
                        </div>

    </div>
    <div class="col-md-12 from-area" style="margin-top:20px;">
        <h4>Upload words/numbers from CSV file</h4>
                        <form id="myform">
                        <fieldset class="form-group">
                            <div class="custom-file">
                                <input type="file" id="file" class="custom-file-input" id="inputGroupFile01" required>
                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                            </div>
                            <p class="text-muted"><code><br>* use CSV file only<br>* whitespaces will remove automatically</code></p>
                        <div class="progress" style="display:none; margin-top:10px;">
	  <div class="progress-bar" role="progressbar" aria-valuenow="70"
	  aria-valuemin="0" aria-valuemax="100" style="width:70%">
		0%
	  </div>
	</div>
    <div class="form-actions">
								<button  class="btn btn-success em-color" type="submit">
									<i class="ft-upload-cloud"></i> Upload
								</button>
                                <a href="sample.csv"><div  class="btn btn-success">
									<i class="ft-upload-cloud"></i> Download Sample CSV
</div></a>
              </div>  
                        </fieldset>
</form>

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
$("#showLength").hide();
var canSubmit=true;
var bingo=$( ".bingo-custom");

bingo.tagging({
        "bingo-custom": !1,
        "no-spacebar":true,
        "no-duplicate-callback": duplicateTag
    })

    bingo.on( "add:after", function ( el, text, tagging ) {
        customCode(text);
});
bingo.on( "remove:after", function ( el, text, tagging ) {
        customCode(text)
});

function customCode(text){
    // if(text.length > 25){
    //     bingo.tagging("remove", text);
    //     swal('Unallowed Length', 'word character limit must be less than 10', 'error');
    // }else{
        $("#showLength").removeClass("btn-success");
        $("#showLength").addClass("btn-primary");
        var t;
        t = bingo.tagging("getTags");
        wordlength=t.length;
        $("#showLength").show();
        $("#showLength").html("Words "+wordlength+" / 50");
        canSubmit=false;
        if(wordlength>50){
            wordlength=wordlength-1;
            bingo.tagging("remove", text);
            $("#showLength").html("Words "+wordlength+" / 50");
            swal('Exceed Word Limit', 'Only 50 Words are Allowed', 'error');
        }
        if(wordlength==50){
            canSubmit=true;
            $("#showLength").removeClass("btn-primary");
            $("#showLength").addClass("btn-success");
        }
    // }
}

$("#tagButton").click(function(){
    if(canSubmit==true){
        var t;
        t = bingo.tagging("getTags");
        $.ajax({ 
       type: "POST", 
       url: "events.php?events=add_words", 
       data: {variables_array : t}, 
       success: function(result) { 
              if(result=="true"){
                swal('Success', 'Data Updated', 'success');
              }else{
                swal('Error', result, 'error');
              }
        } 
});   
    }else{
        swal('Length Error', '50 Words must be included', 'error');
    }
})

$("#AddNumbers").click(function(){
    var numbers=[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50];
    bingo.tagging( "removeAll" );
    bingo.tagging( "add",numbers);
})

$("#AddWords").click(function(){
    var words=["Padma Shri","Mango","Raazi","Mithali Raj","Virat Kohli","Education","Voting","Saffron","Miss Universe","Nirmala Sitaraman","Mary Kom","Priyanka Chopra","Cricket","Indira Gandhi","Dhoti","Bharat Ratna","Kurta","Karan Johar","Sushma Swaraj","Arabian Sea","DDLJ","Delhi","Kolkata","Chess","Mumbai","Badminton","Tata Group","Netflix","Goa","Sachin Tendulkar","Democracy","Green","Liberty","Infosys","Instagram","Ambani","Rang De Basanti","Sushmita Sen","Equality","Freida Pinto","Elephant","P.V. Sindhu","Tiger","Social","Justice","Sustainability","Forest Essentials","Taj Mahal","Power","Rural"];
    bingo.tagging( "removeAll" );
    bingo.tagging( "add",words);
    $("#custom_card_height").css("height","auto");
})
$("#resetField").click(function(){
    bingo.tagging( "removeAll" );
})
function duplicateTag(){
    swal('Duplicate Word', 'Duplicate Word not Allowed', 'error');
}

var myform = document.querySelector('#myform');
var inputfile = document.querySelector('#file');
var bar = document.getElementsByClassName("progress-bar")[0];
var progress=document.getElementsByClassName("progress")[0];
var request = new XMLHttpRequest();
  request.upload.addEventListener('progress',function(e){
  bar.style.width=Math.round(e.loaded/e.total * 100)+'%';
  bar.innerHTML=Math.round(e.loaded/e.total * 100)+'% please wait..';
  if(Math.round(e.loaded/e.total * 100) == 100){
    bar.style.width=Math.round(e.loaded/e.total * 100)+'%';
    bar.innerHTML=Math.round(e.loaded/e.total * 100)+'% please wait..';
  }
},false);

myform.addEventListener('submit',function(e){
    progress.style.display="block";
    $(".submit-button").css("display","none");
	e.preventDefault();
	var formData = new FormData();
  	formData.append('file',inputfile.files[0]);
    request.open('post','process.php',true);

	request.onreadystatechange=function(){
		if(request.readyState == 4 && request.status == 200){
             if(request.responseText=="1010"){
                progress.style.display="none";
                swal("SuccessFully Updated", "SuccessFully Updated All Values", "success");
                setTimeout(function(){
                    location.reload();
                },1000)
             }else{
                swal("Error", request.responseText, "error");
                setTimeout(function(){
                   //location.href="all-categories.php";
                },1000)
             }
              clearInterval(setID);
		}
	}
	request.send(formData);

},false);
</script>
  </body>
</html>