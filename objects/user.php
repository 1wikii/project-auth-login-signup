<?php

require_once('config/database.php'); 


//------User class is one packed with database cause inherit the database class-------//
class USER extends DATABASE{

   // User Attribute
   private $username;
   private $password;
   private $notHashedPassword;
   private $date;
   protected $email;

   // state process
   private $isAccountAlreadyExist;
   private $isUsernameExist;
   private $successCreatingAccount;

   // verification variable
   private $user;
   private $verificationStatus;

   /*------------CONSTRUCTOR----------------*/
   public function __construct($username, $password, $email=null){
      $this->username = $username;
      $this->password = $this->hashingPassword($password);
      $this->notHashedPassword = $password;
      $this->email = $email;
      $this->date= $this->getDateTime();

      $this->isAccountAlreadyExist = false;
      $this->isUsernameExist = false;
      $this->successCreatingAccount = false;

      $this->getConnection();
   }

   public function SIGNUP(){

      /*----------------CHECKING VIOLATION ACCOUNT---------------*/
      if($this->isAccountAlreadyExist($this->email)) {
         $this->isAccountAlreadyExist = true;
         return ;
      }

      if( $this->isUsernameContainNumber() || $this->isUsernameContainSpace() || $this->isPasswordShorterThan8Char() ){
         return ;
      }

      try {

         $query = "INSERT INTO ". $this->getTableName() ." (username, password, email, date)
                  VALUES (:username, :password, :email, :date)";

         // preparing query
         $stmt = $this->DB->prepare($query);    // to helps prevent SQL injection attacks by parameterizing the query
         $stmt->bindParam(":username" , $this->username);
         $stmt->bindParam(":password" , $this->password);
         $stmt->bindParam(":email" , $this->email);
         $stmt->bindParam(":date" , $this->date);

         // create transaction to overcome error query with ROLLBACK
         $this->DB->beginTransaction();

         // executing query
         $stmt->execute();

         // commit the change
         $this->DB->commit();

         $this->successCreatingAccount = true;

      } catch (PDOException $e){
         $this->DB->rollBack();

         if ($e->getCode() == 23000){    // Error code 23000 is about violation of unique column
            $this->isUsernameExist = true; 

         } else{
            exit("Query Failed Sign Up : " . $e->getMessage());
         }
      }
   }


   public function LOGIN(){

   // FETCHING DATA FROM DATABASE
     try {

      $query = "SELECT username, password, email FROM ". $this->getTableName() ." 
                     WHERE username =:usernameOrEmail OR email =:usernameOrEmail; ";

      $stmt = $this->DB->prepare($query);
      $stmt->bindParam(":usernameOrEmail" , $this->username);
      $stmt->execute();

      $this->user = $stmt->fetch(PDO::FETCH_ASSOC);

      // handling if not any data user in database matching with username or password
         if(!$this->user){
            $this->user['username'] = "";
            $this->user['password'] = "";
            $this->user['email'] = "";
         }

      } catch (PDOException $e){
         die("ERROR LOGIN : ". $e -> getMessage());
      }

      //  VERIFY Username or Email and Password
      $hashedPassword = $this->user['password']; 
      $status = array( "username" => false, "password" => false, "usernameInDB" => null);

      if($this->user['username'] == $this->username){
         $status['username'] = true;
      }

      if($this->user['email'] == $this->username){
         $status['username'] = true;
      }

      if( password_verify($this->notHashedPassword , $hashedPassword) ){
         $status['password'] = true; 
      }

      // store the DB_username for header after finish login
      $status['usernameInDB'] = $this->user['username'];

      $this->verificationStatus = $status;
   }


   /*----------------GET-------------------*/
   public function getDateTime(){
      $timeZone = new DateTimeZone ('Asia/Jakarta');
      $currentTime = new DateTime('now', $timeZone);
      $formattedTime = $currentTime->format('Y-m-d H:i:s');

      return $formattedTime;
   }

   public function getEmail(){
      return $this->email;
   }

   public function getVerificationStatus(){
      return $this->verificationStatus;
   }

   /*-----------FUNCTION TO MAKE SURE THE PROCESS--------------*/
   public function isAccountAlreadyExist($email){

      $query_db = $this->getDBName();
      $query_table = $this->getTableName();

      $stmt = $this->DB->prepare("SELECT COUNT(*) FROM $query_table WHERE email = :email");
      $stmt->bindParam(":email" , $email);
      $stmt->execute();
      $result = $stmt->fetchColumn(); 

      if( $result >= 1 ) {  return true; }

      return false;
   }

   public function hashingPassword($password){
      $hash = password_hash($password, PASSWORD_DEFAULT);
      return $hash;
   }

   public function isPasswordShorterThan8Char(){
      if( strlen($this->notHashedPassword) < 8 ) { return true; }
      
      return false;
   }

   public function isUsernameContainSpace( ){
      for($i = 0; $i < strlen($this->notHashedPassword); $i++){
         if($this->notHashedPassword[$i] == " " ){
            return true;
         }
      }

      return false;
   }

   public function isUsernameContainNumber(){
      $number = ['0','1','2','3','4','5','6','7','8','9'];
      
      for($char=0; $char < strlen($this->username); $char++){
         for($num=0; $num < sizeof($number); $num++){

            if($this->username[$char] == $number[$num]){
               return true;
            }
         }
      }

      return false;
   }

   public function getIsAccountAlreadyExist(){
      return $this->isAccountAlreadyExist;
   }

   public function isUsernameExist(){
      return $this->isUsernameExist;
   }

   public function successCreatingAccount(){
      return $this->successCreatingAccount;
   }

}


class FORGOT_PASSWORD extends USER{

   private $token;

   public function __construct($email=null){
      $this->email = $email;
      $this->token = null;

      $this->getConnection();
   }

   public function getToken(){
      return $this->token;
   }

   public function generateToken(){

      try {
         $rand_token = bin2hex(random_bytes(16));    // Random string
         $token_hash = hash("sha256", $rand_token);   // Making the token 64 char long

         $expiry = date( "Y-m-d H-i-s" , time() + (60 * 1) );      // Token expire in 1 minutes with format datetime
      
         $query = "UPDATE ". $this->getTableName() ." SET reset_token_hash =:token_hash, reset_token_expires_at =:expire_token
                  WHERE email =:email"; 

         $stmt = $this->DB->prepare($query);
         $stmt->bindParam(":token_hash", $token_hash);
         $stmt->bindParam(":expire_token", $expiry);
         $stmt->bindParam(":email", $this->email);
         $stmt->execute();

         $this->token = $rand_token;

      }catch(PDOException $e){
         die("ERROR GENERATE TOKEN");
      }
   }

   public function fetchToken($token){

      try {
         $query_table = $this->getTableName();
         $token_hash = hash("sha256", $token);

         $stmt = $this->DB->prepare("SELECT reset_token_hash, reset_token_expires_at
                                    FROM $query_table WHERE reset_token_hash = :token_hash");
            
         $stmt->bindParam(":token_hash" , $token_hash);
         $stmt->execute();

         $result = $stmt->fetch(PDO::FETCH_ASSOC);  

         if( !$result ) { 
            echo "INI GAGAL";
            return null; 
         }

         return $result;

      }catch(PDOException $e){
         die("ERROR GET TOKEN");
      }
   }

   public function updatePassword($notHashedPassword){

      try {
         $password = $this->hashingPassword($notHashedPassword);
         $query_table = $this->getTableName();

         $stmt = $this->DB->prepare("UPDATE $query_table
                                    SET password = :password WHERE email = :email");
         
         $stmt->bindParam(":password" , $password);
         $stmt->bindParam(":email" , $this->email);
         $stmt->execute();
      
      }catch(PDOException $e){
         die("ERORR UPDATE PASSWORD!");
      }
      
   }

}