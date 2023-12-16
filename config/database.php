<?php
   class DATABASE {
      private $host = 'localhost';
      private $DB_name = 'login_signup';
      private $table_name = 'users';
      private $DB_username = 'root';
      private $DB_password = '';
         public $DB;

      public function getConnection(){
         $this->DB = null;

         try{
            $this->DB = new PDO("mysql:host=$this->host; dbname=$this->DB_name" , $this->DB_username , $this->DB_password);
            $this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            $this->DB->exec("set names utf8");     // utf8 to handle unicode characters
            
         }
         catch(PDOException $exception){
            echo "Connection Error : " . $exception->getMessage();
         }
      }

      public function getHostName(){
         return $this->host;
      }

      public function getDBName(){
         return $this->DB_name;
      }

      public function getTableName(){
         return $this->table_name;
      }

      public function getUsername(){
         return $this->DB_username;
      }

      public function getPassword(){
         return $this->DB_password;
      }
   }
?>