<?php

    include('include/session.php');

?>
<html>
<title>Hauptseite</title>
<body>

<table>
<tr>
    <td>
<?php

    // Der Inhalt für angemeldete User

    if($session->logged_in)
        {
        echo '<h3>Mitglieder Inhalte</h3>';
        echo '<h1><img src="images/lock_unlocked.png" width="32" height="32">Eingelogged</h1>';

        echo "Willkommen <b>$session->username</b>, Sie sind eingelogged. <br><br>"
        ."[<a href=\"userinfo.php?user=$session->username\">Mein Account</a>] &nbsp;&nbsp;"
        ."[<a href=\"useredit.php\">Account bearbeiten</a>] &nbsp;&nbsp;";

        if($session->isAdmin())
            {
            echo "[<a href=\"admin/admin.php\">Zum Adminbereich</a>] &nbsp;&nbsp;";
            }
        echo "[<a href=\"process.php\">Logout</a>]";
        }

        else

        {

?>
<h3>Hauptseite</h3>
<h1><img src="images/lock_locked.png" width="32" height="32" alt="Login">Login</h1>
<?php

        if($form->num_errors > 0)
            {
            echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." Fehler gefunden.</font>";
            }

?>
<form method="post" action="process.php">
    <table align="left" border="0" cellspacing="0" cellpadding="3">
        <tr><td>Username:</td><td><input type="text" name="user" maxlength="30"></td><td><?php echo $form->error("user"); ?></td></tr>
        <tr><td>Passwort:</td><td><input type="password" name="pass" maxlength="30"></td><td><?php echo $form->error("pass"); ?></td></tr>
        <tr><td colspan="2" align="left"><input type="checkbox" name="remember" <?php if($form->value("remember") != ""){ echo "checked"; } ?>>
        <font size="2">Angemeldet bleiben? &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="hidden" name="sublogin" value="1">
        <input type="submit" value="Login"></td></tr>
        <tr><td colspan="2" align="left"><br><font size="2">[<a href="forgotpass.php">Passwort vergessen?</a>]</font></td><td align="right"></td></tr>
        <tr><td colspan="2" align="left"><br>Nicht registriert? <a href="register.php">Jetzt beitreten!</a></td></tr>
    </table>
</form>
<?php

        }

    echo "</td></tr><tr><td align=\"center\"><br><br>";
    echo "<strong>Mitgliederzahl:</strong> ".$database->getNumMembers()."<br>";
    echo "Es sind $database->num_active_users registrierte User und ";
    echo "$database->num_active_guests Gäste online.<br><br>";

    include('include/view_active.php');

?>
    </td>
</tr>
</table>

</body>
</html>