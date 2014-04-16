<?php

    include('include/session.php');

?>

<html>
<title>Registrierung!</title>
<body>
<?php

    if($session->logged_in)
        {
        echo "<h1>Registrierung-Fehler!</h1>";
        echo "<p>Leider ist er Username <b>$session->username</b> schon registriert. "
        ."<a href=\"main.php\">Zur Startseite</a>.</p>";
        }

    elseif(isset($_SESSION['regsuccess']))
        {
        if($_SESSION['regsuccess']){
        echo "<h1>Registrierung erfolgreich!</h1>";
        echo "<p>Danke <b>".$_SESSION['reguname']."</b>, Sie wurden erfolgreich eingetragen. "
        ."Sie können sich nun <a href=\"main.php\">anmelden</a>.</p>";
        }

        else

        {
        echo "<h1>Registrierung fehlgeschlagen!</h1>";
        echo "<p>Leider ist ein Fehler für die Registrierung des Usernamen <b>".$_SESSION['reguname']."</b> aufgetreten.<br> "
        ."Bitte versuchen Sie es nochmals.</p>";
        }

        unset($_SESSION['regsuccess']);
        unset($_SESSION['reguname']);
        }

        else

        {

?>
<h3>Registrierung!</h3>
<h1><img src="images/user_add.png" width="32" height="32" alt="Register">Registrieren</h1>
<?php

        if($form->num_errors > 0)
            {
            echo "<td><font size=\"2\" color=\"#ff0000\">".$form->num_errors." Fehler gefunden.</font></td>";
            }

?>
<form method="post" action="process.php">
    <table align="left" border="0" cellspacing="0" cellpadding="3">
        <tr><td><img src="images/user_info.png" width="32" height="32" alt="Username"></td><td>Username:</td><td><input type="text" name="user" maxlength="30" value="<?php echo $form->value("user"); ?>"></td><td><?php echo $form->error("user"); ?></td></tr>
        <tr><td><img src="images/key.png" width="32" height="32" alt="Password"></td><td>Passwort:</td><td><input type="password" name="pass" maxlength="30" value="<?php echo $form->value("pass"); ?>"></td><td><?php echo $form->error("pass"); ?></td></tr>
        <tr><td><img src="images/email.png" width="32" height="32" alt="Email"></td><td>Email Adresse:</td><td><input type="text" name="email" maxlength="50" value="<?php echo $form->value("email"); ?>"></td><td><?php echo $form->error("email"); ?></td>
        </tr>
        <tr><td colspan="2" align="right">
        <input type="hidden" name="subjoin" value="1">
        <input type="submit" value="Registrieren!"></td></tr>
        <tr>
        <td colspan="2" align="left"><a href="main.php">Zurück zur Login-Seite</a></td></tr>
    </table>
</form>
<?php

        }

?>
</body>
</html>