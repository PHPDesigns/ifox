<?php

    include('constants.php');

    class MySQLDB
    {
       var $connection;
       var $num_active_users;
       var $num_active_guests;
       var $num_members;

       function MySQLDB(){
          $this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die(mysqli_error($this->connection));
         // mysqli_select_db(DB_NAME, $this->connection) or die(mysqli_error($this->connection));

          $this->num_members = -1;
          
          if(TRACK_VISITORS){
             $this->calcNumActiveUsers();
             $this->calcNumActiveGuests();
          }
       }

       function confirmUserPass($username, $password){
          if(!get_magic_quotes_gpc()) {
    	      $username = addslashes($username);
          }

          $q = "SELECT password FROM ".TBL_USERS." WHERE username = '$username'";
          $result = mysqli_query($this->connection, $q);
          if(!$result || (mysqli_num_rows($result) < 1)){
             return 1;
          }

          $dbarray = mysqli_fetch_array($result);
          $dbarray['password'] = stripslashes($dbarray['password']);
          $password = stripslashes($password);

          if($password == $dbarray['password']){
             return 0;
          }
          else{
             return 2;
          }
       }

       function confirmUserID($username, $userid){
          if(!get_magic_quotes_gpc()) {
    	      $username = addslashes($username);
          }

          $q = "SELECT userid FROM ".TBL_USERS." WHERE username = '$username'";
          $result = mysqli_query($this->connection, $q);
          if(!$result || (mysqli_num_rows($result) < 1)){
             return 1;
          }

          $dbarray = mysqli_fetch_array($result);
          $dbarray['userid'] = stripslashes($dbarray['userid']);
          $userid = stripslashes($userid);

          if($userid == $dbarray['userid']){
             return 0;
          }
          else{
             return 2;
          }
       }

       function usernameTaken($username){
          if(!get_magic_quotes_gpc()){
             $username = addslashes($username);
          }
          $q = "SELECT username FROM ".TBL_USERS." WHERE username = '$username'";
          $result = mysqli_query($this->connection, $q);
          return (mysqli_num_rows($result) > 0);
       }

       function usernameBanned($username){
          if(!get_magic_quotes_gpc()){
             $username = addslashes($username);
          }
          $q = "SELECT username FROM ".TBL_BANNED_USERS." WHERE username = '$username'";
          $result = mysqli_query($this->connection, $q);
          return (mysqli_num_rows($result) > 0);
       }

       function addNewUser($username, $password, $email){
          $time = time();

          if(strcasecmp($username, ADMIN_NAME) == 0){
             $ulevel = ADMIN_LEVEL;
          }else{
             $ulevel = USER_LEVEL;
          }
          $q = "INSERT INTO ".TBL_USERS." VALUES ('$username', '$password', '0', $ulevel, '$email', $time)";
          return mysqli_query($this->connection, $q);
       }

       function updateUserField($username, $field, $value){
          $q = "UPDATE ".TBL_USERS." SET ".$field." = '$value' WHERE username = '$username'";
          return mysqli_query($this->connection, $q);
       }

       function getUserInfo($username){
          $q = "SELECT * FROM ".TBL_USERS." WHERE username = '$username'";
          $result = mysqli_query($this->connection, $q);

          if(!$result || (mysqli_num_rows($result) < 1)){
             return NULL;
          }

          $dbarray = mysqli_fetch_array($result);
          return $dbarray;
       }

       function getNumMembers(){
          if($this->num_members < 0){
   
             $result = mysqli_query($this->connection, "SELECT * FROM ".TBL_USERS);
             $this->num_members = mysqli_num_rows($result);
          }
          return $this->num_members;
       }

       function calcNumActiveUsers(){

          $result = mysqli_query($this->connection, "SELECT * FROM ".TBL_ACTIVE_USERS);
          $this->num_active_users = mysqli_num_rows($result);
       }

       function calcNumActiveGuests(){

          $result = mysqli_query($this->connection, "SELECT * FROM ".TBL_ACTIVE_GUESTS);
          $this->num_active_guests = mysqli_num_rows($result);
       }

       function addActiveUser($username, $time){
          $q = "UPDATE ".TBL_USERS." SET timestamp = '$time' WHERE username = '$username'";
          mysqli_query($this->connection, $q);
          
          if(!TRACK_VISITORS) return;
          $q = "REPLACE INTO ".TBL_ACTIVE_USERS." VALUES ('$username', '$time')";
          mysqli_query($this->connection, $q);
          $this->calcNumActiveUsers();
       }

       function addActiveGuest($ip, $time){
          if(!TRACK_VISITORS) return;
      
          mysqli_query($this->connection, "REPLACE INTO ".TBL_ACTIVE_GUESTS." VALUES ('$ip', '$time')");
          $this->calcNumActiveGuests();
       }

       function removeActiveUser($username){
          if(!TRACK_VISITORS) return;
          $q = "DELETE FROM ".TBL_ACTIVE_USERS." WHERE username = '$username'";
          mysqli_query($this->connection, $q);
          $this->calcNumActiveUsers();
       }

       function removeActiveGuest($ip){
          if(!TRACK_VISITORS) return;
          $q = "DELETE FROM ".TBL_ACTIVE_GUESTS." WHERE ip = '$ip'";
          mysqli_query($this->connection, $q);
          $this->calcNumActiveGuests();
       }

       function removeInactiveUsers(){
          if(!TRACK_VISITORS) return;
          $timeout = time()-USER_TIMEOUT*60;
 
          mysqli_query($this->connection, "DELETE FROM ".TBL_ACTIVE_USERS." WHERE timestamp < $timeout");
          $this->calcNumActiveUsers();
       }

       function removeInactiveGuests(){
          if(!TRACK_VISITORS) return;
          $timeout = time()-GUEST_TIMEOUT*60;

          mysqli_query($this->connection, "DELETE FROM ".TBL_ACTIVE_GUESTS." WHERE timestamp < $timeout");
          $this->calcNumActiveGuests();
       }

       function query($query){
          return mysqli_query($this->connection, $query);
       }
    };

    $database = new MySQLDB;

?>