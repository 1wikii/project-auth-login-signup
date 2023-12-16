<?php
   require_once("objects/user.php");

   if( isset($_POST['sendRequest']) ){

      // Warning text variable
      $isAccountExist = false;
      $isEmailBlank = false;
      $isEmailHasBeenSent = false;

      $email = $_POST['email'];

      if(!empty($email)){
         $account = new FORGOT_PASSWORD($email);

         if($account->isAccountAlreadyExist($email)){
            $account->generateToken();
            $token = $account->getToken();
            $isAccountExist = true;

            $mail = require_once("./config/mailer.php");
            $mail->setFrom("noreply@example.com", );
            $mail->addAddress($email);
            $mail->Subject = "Password Reset";      
            $mail->Body = <<<END
            
            Click <a href="http://localhost/Main-Project-Login/reset-password.php?token=$token">Here</a>
            to reset your password!

            END; // http://1wikii.42web.io/Main-Project-Login/reset-password.php?token=$token

            try{
               $mail->send();

               $isEmailHasBeenSent = true;

            }catch(Exception $e){
               echo "ERROR SENDING EMAIL: {$mail->ErrorInfo}";
            }

         }

         // Make Session to Store Email for reset-password.php proccess 
         session_start();
         $_SESSION['email'] = $email;

      }
      else{
         if(empty($email)){
            $isEmailBlank = true;
         }
      }
   }

   ?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Forgot Password</title>

   <link rel="stylesheet" href="./style/global.css">
   <link rel="stylesheet" href="./style/index.css">
   <link rel="icon" href="assets/web-icon.png">
</head>
<body>
   
   <form class="form-center-container" action=" <?php $_SERVER['PHP_SELF'] ?> " method="POST">
      <span class="title-text" style="display: block;"> Forgot Password </span>
      
      <div class="input-container">
         <div class="email-container">
            <img class="icon-email" src="./assets/email.png" alt="icon not found!">  
            <input class="col-input-email" type="email" name="email" placeholder="Email">
         </div>
         <?php if( isset($isEmailBlank) && $isEmailBlank ): ?>
            <p> Email is required </p>
         <?php elseif(isset($isAccountExist) && !$isAccountExist ): ?>
            <p> Email not found </p>
         <?php endif; ?>
      </div>

      <input class="login-sign-up-button-forgot-password" type="submit" name="sendRequest" value="Send Request">

      <div class="warning-text-credential-container">   
         <?php if( isset($isEmailHasBeenSent) && $isEmailHasBeenSent ): ?>
         <span class="warning-text-credential-success">  Email sent successfully </span>
         <?php endif; ?>

      </div>
   </form>

</body>
</html>