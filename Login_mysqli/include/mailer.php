<?php

    class Mailer
       {
       function sendWelcome($user, $email, $pass){
          $from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">";
          $subject = "Danke für Ihre Registrierung!";
          $body = $user.",\n\n"
                 ."Willkomen, "
                 ."hier sehen Sie Ihre Daten:\n\n"
                 ."Username: ".$user."\n"
                 ."Passwort: ".$pass."\n\n"
                 ."Bei Fragen wenden Sie sich an unseren Support.";

          return mail($email,$subject,$body,$from);
          }

       function sendNewPass($user, $email, $pass)
          {
          $from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">";
          $subject = "Ihr neues Passwort!";
          $body = $user.",\n\n"
                 ."Sie haben ein neues Passwort angefordert:\n\n"
                 ."Username: ".$user."\n"
                 ."Neues Passwort: ".$pass."\n\n"
                 ."Melden Sie sich an um Ihr Passwort zu ändern.";

          return mail($email,$subject,$body,$from);
         }
       };

    $mailer = new Mailer;

?>