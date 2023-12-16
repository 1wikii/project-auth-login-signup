<?php
   require_once('objects/user.php');
   
   if ( isset($_POST['submit']) ){     
      $username = $_POST['username'];
      $password = $_POST['password']; 

      if( !empty($username) && !empty($password) ){

         $account = new USER($username, $password);
         $account->LOGIN();

         $verif = $account->getVerificationStatus();

         // Validation 
         if( !$verif['username'] ){
            $isUsernameInvalid = true;
         }
         if( !$verif['password'] ){
            $isPasswordInvalid = true;
         }

         if( $verif['username'] && $verif['password'] ){
   
            // make a SESSION to store data user after success logging in
            session_start();
            $_SESSION["username"] = $verif['usernameInDB'];
            header("Location: index.php");
            exit;
         }

         // Checking number, space and minimum 8 char 
         $isUsernameContainNumber = $account->isUsernameContainNumber();
         $isPasswordShorterThan8Char = $account->isPasswordShorterThan8Char();
         $isPasswordContainSpace = $account->isUsernameContainSpace();
      }
      else{
         if( empty($username)){
            $isUsernameBlank = true;
         }
         if( empty($password)){
            $isPasswordBlank = true;
         }
      }
   

   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Log In</title>

   <link rel="stylesheet" href="./style/global.css">
   <link rel="stylesheet" href="./style/index.css">
   <link rel="icon" href="./assets/web-icon.png">

</head>
<body> 
<?php include('header.php'); ?>

<div class="background-container">
   <div class="div-center-container"> 

   <form class="form-center-container" action=" <?php $_SERVER['PHP_SELF'] ?> " method="POST">
      <span class="title-text" style="display: block;"> Log In </span>

      <div class="input-container">

         <div class="username-container">
            <img class="icon-username" src="./assets/username.png" alt="icon not found!">  
            <input class="col-input-username" type="text" name="username" placeholder="Username or Email">
         </div>

            <!-- check for blank or invalid username-->
            <?php if( isset($isUsernameBlank) && $isUsernameBlank ): ?>
               <p> Username is required</p>
            <?php elseif( isset($isUsernameContainNumber) && $isUsernameContainNumber ): ?>
               <p> Username cannot contain number</p>
            <?php elseif( isset($isUsernameInvalid) && $isUsernameInvalid ): ?>
               <p> Invalid username</p>
            <?php endif; ?>

         <div class="separator" style="height: 10px;" ></div>

         <div class="password-container">
            <img class="icon-password" src="./assets/password.png" alt="icon not found!">

            <input class="col-input-password" type="password" name="password" placeholder="Password">
            <button class="toggle-button-icon-eye" type="button" id="toggle-button-icon-eye" > 
               <img class="icon-eye" src="./assets/eye.svg" alt="Image Not Found!"> 
            </button>

         </div>

         <div class="password-error-container">
            <div class="error-text">
               <!-- check for blank or invalid password -->
               <?php if( isset($isPasswordBlank) && $isPasswordBlank ): ?>
                  <p> Password is required</p>
               <?php elseif(isset($isPasswordShorterThan8Char) && $isPasswordShorterThan8Char ): ?>
                  <p>Password at least 8 letter</p>
               <?php elseif(isset($isPasswordContainSpace) && $isPasswordContainSpace ): ?>
                  <p>Password cannot contain space</p>
               <?php elseif( isset($isPasswordInvalid) && $isPasswordInvalid): ?>
                  <p> Invalid password</p>
               <?php endif; ?>

            </div>

            <div class="error-forgot-text">
               <a class="error-forgot-text-a" href="./forgot-password.php" >Forgot Password?</a>
            </div>

         </div>
      </div>

      <!--------MAKE BUTTON LOG IN DISABLE WHEN THERES A SESSION ALIVE-------->
      <?php  if(isset($_SESSION['username'])): ?>
         <input class="login-sign-up-button" type="submit" name="submit" value="Log In" disabled>
      <?php else: ?>
         <input class="login-sign-up-button" type="submit" name="submit" value="Log In">
      <?php endif; ?>

      <div class="account-text-container">
            <span class="account-text"> Don't have an Account ? <a class="account-text-a" href="./sign-up.php">Sign Up</a> </span>
      </div>

      <div class="warning-text-credential-container"></div>

   </form>
   </div>

</div>

<script  src="./js/main.js"></script>

 </body>
</html>