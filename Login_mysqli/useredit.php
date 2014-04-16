<?php

    include('include/session.php');

?>
<html>
<title>Account bearbeiten</title>
<body>

<p>
<?php

    if(isset($_SESSION['useredit']))
        {
        unset($_SESSION['useredit']);
    
        echo "<h1>Account wurde überarbeitet!</h1>";
        echo "<p><b>$session->username</b>, Ihr Account wurde erfolgreich überarbeitet. "
        ."<a href=\"main.php\">Zur Startseite</a>.</p>";
        }

        else

        {
        if($session->logged_in)
            {

?>
</p>
<h3>Account bearbeiten</h3>
<h1><img src="images/user_edit.png" width="32" height="32" alt="Edit Account">Account bearbeiten: <?php echo $session->username; ?></h1>
<?php

            if($form->num_errors > 0)
                {
                echo "<td><font size=\"2\" color=\"#ff0000\">".$form->num_errors." Fehler gefunden.</font></td>";
                }

?>
<form method="post" action="process.php">
    <table align="left" border="0" cellspacing="0" cellpadding="3">
    <tr>
    <td><img src="images/key.png" width="32" height="32" alt="Aktuelles Passwort"></td>
    <td>Aktuelles Passwort:</td>
    <td><input type="password" name="curpass" maxlength="30" value="
    <?php echo $form->value("curpass"); ?>"></td>
    <td><?php echo $form->error("curpass"); ?></td>
    </tr>
    <tr>
    <td><img src="images/key_add.png" width="32" height="32" alt="Neues Passwort"></td>
    <td>Neues Passwort:</td>
    <td><input type="password" name="newpass" maxlength="30" value="
    <?php echo $form->value("newpass"); ?>"></td>
    <td><?php echo $form->error("newpass"); ?></td>
    </tr>
    <tr>
    <td><img src="images/email_add.png" width="32" height="32" alt="Neue Email Adresse"></td>
    <td>Neue Email Adresse:</td>
    <td><input type="text" name="email" maxlength="50" value="
    <?php
    if($form->value("email") == ""){
       echo $session->userinfo['email'];
    }else{
       echo $form->value("email");
    }
    ?>">
    </td>
    <td><?php echo $form->error("email"); ?></td>
    </tr>
    <tr><td colspan="2" align="right">
    <input type="hidden" name="subedit" value="1">
    <input type="submit" value="Account bearbeiten"></td></tr>
    <tr><td colspan="2" align="left"></td></tr>
    </table>
</form>
<?php

            }
        }

?>
</body>
</html>