<?php
   require_once('objects/user.php');

   if ( isset($_POST['submit']) ){      
      $username = $_POST['username'];
      $email = $_POST['email'];
      $password = $_POST['password'];

      if( !empty($username) && !empty($email) && !empty($password) ){
         
         $new_user = new USER($username, $password, $email);
         $new_user->SIGNUP();

         // Warning Text Variable
         $isAccountAlreadyExist = $new_user->getIsAccountAlreadyExist();      
         $isUsernameExist = $new_user->isUsernameExist();
         $isUsernameContainNumber = $new_user->isUsernameContainNumber();
         $isPasswordShorterThan8Char = $new_user->isPasswordShorterThan8Char();
         $isPasswordContainSpace = $new_user->isUsernameContainSpace();

         $successSignUp = $new_user->successCreatingAccount();

      }
      else{
         if( empty($username) ){
            $isUsernameColumnBlank = true;
         }
         if( empty($email) ){
            $isEmailColumnBlank = true;
         }
         if( empty($password) ){
            $isPasswordColumnBlank = true;
         }
      }
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sign UP</title>

   <link rel="stylesheet" href="./style/global.css">
   <link rel="stylesheet" href="./style/index.css">
   <link rel="icon" href="./assets/web-icon.png">

</head>
<body>
<?php include('header.php'); ?>

  <div class="div-center-container">
   <form class="form-center-container" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" >
      <span class="title-text" style="display: block;"> Creat Account </span>
      
      <div class="input-container">
         <div class="username-container">
            <img class="icon-username" src="./assets/username.png" alt="icon not found!">  
            <input class="col-input-username" type="text" name="username" placeholder="Username">
         </div>
            <!-- check for blank username -->
            <?php if( isset($isUsernameColumnBlank) && $isUsernameColumnBlank ): ?>
               <p> Username is required</p>
            <?php elseif(isset($isUsernameContainNumber) && $isUsernameContainNumber && !$isAccountAlreadyExist && !$isUsernameExist): ?>
               <p> Username cannot contain number</p>
            <?php endif; ?>

         <div class="separator" style="height: 10px;" ></div>
         
         <div class="email-container">
            <img class="icon-email" src="./assets/email.png" alt="icon not found!">  
            <input class="col-input-email" type="email" name="email" placeholder="Email">   
         </div>
            <!-- check for blank email -->
            <?php if( isset($isEmailColumnBlank) && $isEmailColumnBlank ): ?>
               <p >Email is required</p>
            <?php endif; ?>

         <div class="separator" style="height: 10px;" ></div>
         <div class="password-container">
            <img class="icon-password" src="./assets/password.png" alt="icon not found!">

            <input class="col-input-password" type="password" name="password" id="password" placeholder="Password">
            <button class="toggle-button-icon-eye" type="button" id="toggle-button-icon-eye">
               <img class="icon-eye" src="./assets/eye.svg" alt="Image Not Found!">
            </button>
         </div>
               
            <div class="password-error-container">
               <div class="error-text">
                  <!-- check for blank password -->
                  <?php if( isset($isPasswordColumnBlank) && $isPasswordColumnBlank ): ?>
                     <p >Password is required</p>
                  <?php elseif(isset($isPasswordShorterThan8Char) && $isPasswordShorterThan8Char && !$isAccountAlreadyExist && !$isUsernameExist): ?>
                     <p>Password at least 8 letter</p>
                  <?php elseif(isset($isPasswordContainSpace) && $isPasswordContainSpace && !$isAccountAlreadyExist && !$isUsernameExist): ?>
                     <p>Password cannot contain space</p>
                  <?php endif; ?>
               </div>

            </div>

      </div>
      
      <!--------MAKE BUTTON LOG IN DISABLE WHEN THERES A SESSION ALIVE-------->
      <?php  if(isset($_SESSION['username'])): ?>
         <input class="login-sign-up-button" type="submit" name="submit" value="Sign Up" disabled>
      <?php else: ?>
         <input class="login-sign-up-button" type="submit" name="submit" value="Sign Up">
      <?php endif; ?>
      
      <div class="account-text-container">
            <span class="account-text"> Already have an Account ? <a class="account-text-a" href="./index.php">Log In</a> </span>
      </div>

      <div class="warning-text-credential-container">   
         <?php if( isset($isAccountAlreadyExist) && $isAccountAlreadyExist ): ?>
         <span class="warning-text-credential">
            <span style='font-style: italic;'> <?php echo $email ?> </span>
               <span style='display: inline-block;'> <strong> already exist! </strong> </span> 
         </span>
         
         <?php elseif( isset($isUsernameExist) && $isUsernameExist ): ?>
         <span class="warning-text-credential"> <strong> Username already exist! </strong> </span>
         
         <?php elseif( isset($successSignUp) && $successSignUp ): ?>
         <span class="warning-text-credential-success">  Success creating account! </span>
         <?php endif; ?>

      </div>
   </form>
  </div>

   <script src="./js/main.js" ></script>


</body>
</html>


