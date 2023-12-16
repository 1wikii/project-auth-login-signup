<?php
   session_start();
   if (isset($_SESSION['email'])){
      $email = $_SESSION['email'];
   }  

?>

<?php
   require_once("objects/user.php");

   $token = $_GET['token'];

   $account = new FORGOT_PASSWORD($email);
   $fetchedToken = $account->fetchToken($token);

   if($fetchedToken == null){
      header("Location: forgot-password.php");
   }

   if( strtotime($fetchedToken['reset_token_expires_at']) <= time() ){
      echo "<script> alert('Token Expired!'); document.location.href = './forgot-password.php'; </script>";
   }


   if(isset($_POST['updatePassword'])){

      $isNewPasswordBlank = false;
      $isConfirmPasswordBlank = false;
      $isPasswordMatch = false;

      $new = $_POST['newPassword'];
      $confirm = $_POST['confirmPassword'];

      if( !empty($new) && !empty($confirm)){

         if($new == $confirm){
            $account->updatePassword($new);
            header("Location: index.php");

            session_unset();
            session_destroy();
            exit;
         }

      }
      else{

         if(empty($new)){
            $isNewPasswordBlank = true;
         }

         if(empty($confirm)){
            $isConfirmPasswordBlank = true;
         }
      }
   }


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reset Password</title>

   <link rel="stylesheet" href="./style/global.css">
   <link rel="stylesheet" href="./style/index.css">
   <link rel="icon" href="./assets/web-icon.png">
</head>
<body>

<form class="form-center-container" action=" <?php $_SERVER['PHP_SELF'] ?> " method="POST">
   <span class="title-text" style="display: block;"> Reset Password </span>
   
   <div class="input-container">
      <div class="password-container">
         <img class="icon-password" src="./assets/password.png" alt="icon not found!">

         <input class="col-input-password" type="password" name="newPassword" placeholder="Password Baru">
         <button class="toggle-button-icon-eye" type="button" id="toggle-button-icon-eye" > 
            <img class="icon-eye" src="assets/eye.svg" alt="Image Not Found!"> 
         </button>
      </div>
         <?php if( isset($isNewPasswordBlank) && $isNewPasswordBlank ): ?>
            <p> Password is required</p>
         <?php endif; ?>

      <div class="separator" style="height: 10px;" ></div>

      <div class="password-container">
         <img class="icon-password" src="./assets/password.png" alt="icon not found!">

         <input class="col-input-password-confirm" type="password" name="confirmPassword" placeholder="Konfirmasi Password">
         <button class="toggle-button-icon-eye-confirm" type="button" id="toggle-button-icon-eye-confirm" > 
            <img class="icon-eye" src="./assets/eye.svg" alt="Image Not Found!"> 
         </button>
      </div>
         <?php if( isset($isConfirmPasswordBlank) && $isConfirmPasswordBlank ): ?>
            <p> Password is required</p>
         <?php elseif(isset($isPasswordMatch) && !$isPasswordMatch ): ?>
            <p>Password Doesn't Match</p>
         <?php endif; ?>
   </div>

   <input class="login-sign-up-button-forgot-password" type="submit" name="updatePassword" value="Update Password">

   <div class="warning-text-credential-container">   
      <?php if( isset($isEmailHasBeenSent) && $isEmailHasBeenSent ): ?>
      <span class="warning-text-credential-success">  Email sent successfully </span>
      <?php endif; ?>

   </div>
</form>
   
<script  src="./js/main.js"></script>

</body>
</html>