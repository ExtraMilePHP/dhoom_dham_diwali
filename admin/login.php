<?php
ob_start();
error_reporting(0);
session_start();
$_SESSION["userId"]='';
$_SESSION['adminId']='';
if(isset($_POST["submit"])){
      $username=$_POST["username"];
      $password=$_POST["password"];
      if($username=="admin" && $password=="admin"){
        $_SESSION["userId"]="true";
        $_SESSION['adminId']="1111";
        header("Location:index.php");
      }else{
        $error=true;
      }
}
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
  <!-- BEGIN: Head-->
  
<!-- Mirrored from themeselection.com/demo/chameleon-admin-template/html/ltr/horizontal-menu-template-nav/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 22 Mar 2020 09:05:36 GMT -->
<head>
<script>


    </script>
    <?php include_once("../../admin_assets/common-css.php");?>

  </head>
  <!-- END: Head-->

  <!-- BEGIN: Body-->
  <body class="horizontal-layout horizontal-menu 1-column  bg-full-screen-image blank-page blank-page" data-open="hover" data-menu="horizontal-menu" data-color="bg-gradient-x-purple-blue" data-col="1-column">
    <!-- BEGIN: Content-->
    <div class="app-content content">
      <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
        </div>
        <div class="content-body"><section class="flexbox-container">
    <div class="col-12 d-flex align-items-center justify-content-center">
        <div class="col-lg-4 col-md-6 col-10 box-shadow-2 p-0">
            <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
                <div class="card-header border-0">
                <!--
                    <div class="text-center mb-1">
                            <img src="app-assets/images/logo/logo.png" alt="branding logo">
                    </div>
                    -->
                    <div class="font-large-1  text-center">                       
                        Admin Login
                    </div>
                </div>
                <div class="card-content">
                   
                    <div class="card-body">
                        <form class="form-horizontal" method="post">
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control round" id="user-name" placeholder="Your Username" name="username" required>
                                <div class="form-control-position">
                                    <i class="ft-user"></i>
                                </div>
                            </fieldset>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="password" class="form-control round" id="user-password" placeholder="Enter Password" name="password">
                                <div class="form-control-position">
                                    <i class="ft-lock"></i>
                                </div>
                            </fieldset>
                            <div class="form-group row">
                                <div class="col-md-6 col-12 text-center text-sm-left">
                                   
                                </div>
          
                            </div>                           
                            <div class="form-group text-center">
                                <button type="submit" name="submit" class="btn round btn-block btn-glow btn-bg-gradient-x-purple-blue col-12 mr-1 mb-1">Login</button>    
                            </div>
                           
                        </form>
                    </div>
                         
                </div>
            </div>
        </div>
    </div>
</section>

        </div>
      </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <?php include("../../admin_assets/footer.php");?>
    <?php include_once("../../admin_assets/common-js.php");?>
    <script>
      <?php
      if(isset($error)){
       echo "swal('Authentication Error', 'Please check your username & password', 'error');";
      }
      ?>
    </script>
  </body>
  <!-- END: Body-->

<!-- Mirrored from themeselection.com/demo/chameleon-admin-template/html/ltr/horizontal-menu-template-nav/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 22 Mar 2020 09:05:37 GMT -->
</html>