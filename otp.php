<?php
require 'includes/init.php';
$user_email=$_GET['email'];
if(isset($_POST['submit'])){
$result = $user_obj->match_otp($user_email,$_POST['otpvalue']);
}
if(isset($_POST['resend'])){
    $re_result= $user_obj->resend_otp($user_email);
}
if(isset($_POST['nemail'])){
   $user_obj->deleteuser($user_email);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OTP</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
</head>
<body style="background-color:powderblue;">
  <div class="main_container login_signup_container">
    <h1>OTP Verification</h1>
    <form action="" method="POST" novalidate>
      <label for="password">OTP</label>
      <input type="password" id="password" name="otpvalue" placeholder="Enter your OTP" required>
      <input name="submit" type="submit" class="form_link_sub" value="SUBMIT">
      <input type="submit" class="form_link_re" name="resend" value="Resend OTP">
      <input type="submit" class="form_link_n" name="nemail" value="Another Email">
    </form>
    <div>
        <?php
        if(isset($result['errorMessage'])){
          echo '<p class="errorMsg">'.$result['errorMessage'].'</p>';
        }
        if(isset($result['successMessage'])){
          echo '<p class="successMsg">'.$result['successMessage'].' Redirecting in a moment...</p>';
            $user_obj->thankyou_mail($user_email);
            $frnd_obj->make_admin_req($user_email);
            echo "<script>setTimeout(\"location.href = './index.php';\",2000);</script>";
        }
        if(isset($re_result['errorMessage'])){
          echo '<p class="errorMsg">'.$re_result['errorMessage'].'</p>';
        }
        if(isset($re_result['successMessage'])){
          echo '<p class="successMsg">'.$re_result['successMessage'].'Enter OTP to continue</p>';
        }
      ?>
    </div>
  </div>
</body>
</html>

