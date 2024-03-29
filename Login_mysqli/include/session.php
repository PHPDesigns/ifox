<?php
    include("database.php");
    include("mailer.php");
    include("form.php");
    
    class Session
       {
       var $username;
       var $userid;
       var $userlevel;
       var $time;
       var $logged_in;
       var $userinfo = array();
       var $url;
       var $referrer;

       function Session()
          {
          $this->time = time();
          $this->startSession();
          }

       function startSession()
          {
          global $database;

          session_start();

          $this->logged_in = $this->checkLogin();

          if(!$this->logged_in){
             $this->username = $_SESSION['username'] = GUEST_NAME;
             $this->userlevel = GUEST_LEVEL;
             $database->addActiveGuest($_SERVER['REMOTE_ADDR'], $this->time);
          }

          else{
             $database->addActiveUser($this->username, $this->time);
          }
          
          $database->removeInactiveUsers();
          $database->removeInactiveGuests();

          if(isset($_SESSION['url'])){
             $this->referrer = $_SESSION['url'];
          }else{
             $this->referrer = "/";
          }

          $this->url = $_SESSION['url'] = $_SERVER['PHP_SELF'];
       }

       function checkLogin(){
          global $database;

          if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])){
             $this->username = $_SESSION['username'] = $_COOKIE['cookname'];
             $this->userid   = $_SESSION['userid']   = $_COOKIE['cookid'];
          }

          if(isset($_SESSION['username']) && isset($_SESSION['userid']) &&
             $_SESSION['username'] != GUEST_NAME){

             if($database->confirmUserID($_SESSION['username'], $_SESSION['userid']) != 0){

                unset($_SESSION['username']);
                unset($_SESSION['userid']);
                return false;
             }

             $this->userinfo  = $database->getUserInfo($_SESSION['username']);
             $this->username  = $this->userinfo['username'];
             $this->userid    = $this->userinfo['userid'];
             $this->userlevel = $this->userinfo['userlevel'];
             return true;
          }
          else{
             return false;
          }
       }

       function login($subuser, $subpass, $subremember){
          global $database, $form;

          $field = "user";
          if(!$subuser || strlen($subuser = trim($subuser)) == 0){
             $form->setError($field, "* Keinen Usernamen eingegeben.");
          }
          else{
             if(!preg_match("/^([0-9a-z])*$/i", $subuser)){
                $form->setError($field, "* Username nur alphanumerisch.");
             }
          }

          $field = "pass";
          if(!$subpass){
             $form->setError($field, "* Kein Passwort eingegeben.");
          }

          if($form->num_errors > 0){
             return false;
          }

          $subuser = stripslashes($subuser);
          $result = $database->confirmUserPass($subuser, md5($subpass));

          if($result == 1){
             $field = "user";
             $form->setError($field, "* Username nicht gefunden.");
          }
          else if($result == 2){
             $field = "pass";
             $form->setError($field, "* Falsches Passwort.");
          }

          if($form->num_errors > 0){
             return false;
          }

          $this->userinfo  = $database->getUserInfo($subuser);
          $this->username  = $_SESSION['username'] = $this->userinfo['username'];
          $this->userid    = $_SESSION['userid']   = $this->generateRandID();
          $this->userlevel = $this->userinfo['userlevel'];

          $database->updateUserField($this->username, "userid", $this->userid);
          $database->addActiveUser($this->username, $this->time);
          $database->removeActiveGuest($_SERVER['REMOTE_ADDR']);

          if($subremember){
             setcookie("cookname", $this->username, time()+COOKIE_EXPIRE, COOKIE_PATH);
             setcookie("cookid",   $this->userid,   time()+COOKIE_EXPIRE, COOKIE_PATH);
          }
          return true;
       }

       function logout(){
          global $database;

          if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])){
             setcookie("cookname", "", time()-COOKIE_EXPIRE, COOKIE_PATH);
             setcookie("cookid",   "", time()-COOKIE_EXPIRE, COOKIE_PATH);
          }

          unset($_SESSION['username']);
          unset($_SESSION['userid']);

          $this->logged_in = false;

          $database->removeActiveUser($this->username);
          $database->addActiveGuest($_SERVER['REMOTE_ADDR'], $this->time);

          $this->username  = GUEST_NAME;
          $this->userlevel = GUEST_LEVEL;
       }

       function register($subuser, $subpass, $subemail){
          global $database, $form, $mailer;

          $field = "user";
          if(!$subuser || strlen($subuser = trim($subuser)) == 0){
             $form->setError($field, "* Keinen Usernamen eingegeben.");
          }
          else{

             $subuser = stripslashes($subuser);
             if(strlen($subuser) < 5){
                $form->setError($field, "* Username weniger als 5 Zeichen.");
             }
             else if(strlen($subuser) > 30){
                $form->setError($field, "* Username mehr als 30 Zeichen.");
             }

             else if(!preg_match("/^([0-9a-z])+$/i", $subuser)){
                $form->setError($field, "* Username nicht alphanumerisch.");
             }

             else if(strcasecmp($subuser, GUEST_NAME) == 0){
                $form->setError($field, "* Username enth�lt reservierten Namen.");
             }

             else if($database->usernameTaken($subuser)){
                $form->setError($field, "* Username bereits vergeben.");
             }

             else if($database->usernameBanned($subuser)){
                $form->setError($field, "* Username verbannt.");
             }
          }

          $field = "pass";
          if(!$subpass){
             $form->setError($field, "* Kein Passwort eingegeben.");
          }
          else{
             $subpass = stripslashes($subpass);
             if(strlen($subpass) < 4){
                $form->setError($field, "* Passwort zu kurz.");
             }

             else if(!preg_match("/^([0-9a-z])+$/i", ($subpass = trim($subpass)))){
                $form->setError($field, "* Passwort nicht alphanumerisch.");
             }
          }

          $field = "email";
          if(!$subemail || strlen($subemail = trim($subemail)) == 0){
             $form->setError($field, "* Keine Email Adresse eingegeben.");
          }
          else{
             $regex = "/^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                     ."@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                     ."\.([a-z]{2,}){1}$/i";
             if(!preg_match($regex,$subemail)){
                $form->setError($field, "* Email Adresse ung�ltig.");
             }
             $subemail = stripslashes($subemail);
          }

          if($form->num_errors > 0){
             return 1;
          }
          else{
             if($database->addNewUser($subuser, md5($subpass), $subemail)){
                if(EMAIL_WELCOME){
                   $mailer->sendWelcome($subuser,$subemail,$subpass);
                }
                return 0;
             }else{
                return 2;
             }
          }
       }

       function editAccount($subcurpass, $subnewpass, $subemail){
          global $database, $form;

          if($subnewpass){
             $field = "curpass";
             if(!$subcurpass){
                $form->setError($field, "* Kein aktuelles Passwort eingegeben.");
             }
             else{
                $subcurpass = stripslashes($subcurpass);
                if(strlen($subcurpass) < 4 ||
                   !preg_match("/^([0-9a-z])+$/i", ($subcurpass = trim($subcurpass)))){
                   $form->setError($field, "* Aktuelles Passwort falsch.");
                }
                if($database->confirmUserPass($this->username,md5($subcurpass)) != 0){
                   $form->setError($field, "* Aktuelles Passwort falsch.");
                }
             }

             $field = "newpass";
             $subpass = stripslashes($subnewpass);
             if(strlen($subnewpass) < 4){
                $form->setError($field, "* Neues Passwort zu kurz.");
             }
             else if(!preg_match("/^([0-9a-z])+$/i", ($subnewpass = trim($subnewpass)))){
                $form->setError($field, "* Neues Passwort nicht alphanumerisch.");
             }
          }

          else if($subcurpass){
             $field = "newpass";
             $form->setError($field, "* Kein neues Passwort eingegeben.");
          }

          $field = "email";
          if($subemail && strlen($subemail = trim($subemail)) > 0){
             $regex = "/^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                     ."@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                     ."\.([a-z]{2,}){1}$/i";
             if(!preg_match($regex,$subemail)){
                $form->setError($field, "* Email Adresse ung�ltig.");
             }
             $subemail = stripslashes($subemail);
          }

          if($form->num_errors > 0){
             return false;
          }

          if($subcurpass && $subnewpass){
             $database->updateUserField($this->username,"password",md5($subnewpass));
          }

          if($subemail){
             $database->updateUserField($this->username,"email",$subemail);
          }

          return true;
       }

       function isAdmin(){
          return ($this->userlevel == ADMIN_LEVEL ||
                  $this->username  == ADMIN_NAME);
       }

       function generateRandID(){
          return md5($this->generateRandStr(16));
       }

       function generateRandStr($length){
          $randstr = "";
          for($i=0; $i<$length; $i++){
             $randnum = mt_rand(0,61);
             if($randnum < 10){
                $randstr .= chr($randnum+48);
             }else if($randnum < 36){
                $randstr .= chr($randnum+55);
             }else{
                $randstr .= chr($randnum+61);
             }
          }
          return $randstr;
       }
    };

    $session = new Session;

    $form = new Form;

?>