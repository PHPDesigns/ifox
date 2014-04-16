<?php

    include('../include/session.php');

    function displayUsers()
        {
        global $database;

        $q = "SELECT username,userlevel,email,timestamp "
        ."FROM ".TBL_USERS." ORDER BY userlevel DESC,username";

        $result = $database->query($q);
        $num_rows = mysqli_num_rows($result);

        if(!$result || ($num_rows < 0))
            {
            echo 'Fehler!';
            return;
            }

        if($num_rows == 0)
            {
            echo 'Datenbank ist leider leer.';
            return;
            }

        echo "<table align=\"left\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">\n";
        echo "<tr><td><b>Username</b></td><td><b>Level</b></td><td><b>Email</b></td><td><b>Zuletzt Aktiv</b></td></tr>\n";

      //  for($i=0; $i<$num_rows; $i++)
	      while($rows=mysqli_fetch_array($result))
            {
        //    $uname  = mysql_result($result,$i,"username");
        //    $ulevel = mysql_result($result,$i,"userlevel");
        //    $email  = mysql_result($result,$i,"email");
        //    $time   = mysql_result($result,$i,"timestamp");

            echo "<tr><td>$rows[username]</td><td>$rows[userlevel]</td><td>$rows[email]</td><td>$rows[timestamp]</td></tr>\n";
            }
        echo "</table><br>\n";
        }

    function displayBannedUsers()
        {
        global $database;

        $q = "SELECT username,timestamp "
        ."FROM ".TBL_BANNED_USERS." ORDER BY username";

        $result = $database->query($q);
        $num_rows = mysqli_num_rows($result);

        if(!$result || ($num_rows < 0))
            {
            echo 'Fehler!';
            return;
            }

        if($num_rows == 0)
            {
            echo 'Datenbank ist leider leer.';
            return;
            }

        echo "<table align=\"left\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">\n";
        echo "<tr><td><b>Username</b></td><td><b>Verbannt Zeit</b></td></tr>\n";

       // for($i=0; $i<$num_rows; $i++)
	   	  while($rows=mysqli_fetch_array($result))
            {
           // $uname = mysql_result($result,$i,"username");
           // $time  = mysql_result($result,$i,"timestamp");

            echo "<tr><td>$rows[username]</td><td>$rows[timestamp]</td></tr>\n";
            }
        echo "</table><br>\n";
        }

    if(!$session->isAdmin())
        {
        header("Location: ../main.php");
        }

        else

        {

?>
<html>
<title>Admin Bereich</title>
<body>
<h1>Admin Bereich</h1>
<p><font size="5" color="#ff0000"><strong>Dies ist der Admin Bereich zur Verwaltung aller User.</strong></font></p>
<p><font size="4">Angemeldet als <b><?php echo $session->username; ?></b></font><br><br>
Zurück zur [<a href="../main.php">Startseite</a>]<br><br>
<?php

    if($form->num_errors > 0)
        {
        echo "<font size=\"4\" color=\"#ff0000\">"
        ."Ein Fehler ist aufgetreten!</font><br><br>";
        }

?>
</p>
<table align="left" border="0" cellspacing="5" cellpadding="5">
<tr>
    <td>
    <h3>User Liste:</h3>
<?php

    displayUsers();

?>
    </td>
</tr>
<tr>
    <td>
    <br>
    <h3>User Level verwalten:</h3>
    <?php echo $form->error("upduser"); ?>
    <table>
    <form method="post" action="adminprocess.php">
        <tr><td>
        Username:<br>
        <input type="text" name="upduser" maxlength="30" value="<?php echo $form->value("upduser"); ?>">
        </td>
        <td>
        Level:<br>
        <select name="updlevel">
        <option value="1">1
        <option value="9">9
        </select>
        </td>
        <td>
        <br>
        <input type="hidden" name="subupdlevel" value="1">
        <input type="submit" value="Update Level">
        </td></tr>
    </form>
    </table>
    </td>
</tr>
<tr>
    <td>
    <hr>
    </td>
</tr>
<tr>
    <td>
    <h3>User löschen:</h3>
    <?php echo $form->error("deluser"); ?>
    <form method="post" action="adminprocess.php">
        Username:<br>
        <input type="text" name="deluser" maxlength="30" value="<?php echo $form->value("deluser"); ?>">
        <input type="hidden" name="subdeluser" value="1">
        <input type="submit" value="User löschen">
    </form>
</td>
</tr>
<tr>
    <td>
    <hr>
    </td>
</tr>
<tr>
    <td>
    <h3>Inaktive User löschen:</h3>
    Alle User die in einer gewissen Zeit nicht eingelogged waren werden gelöscht, gild nicht für Admins.
    <br><br>
    <table>
    <form method="post" action="adminprocess.php">
        <tr><td>
        Tage:<br>
        <select name="inactdays">
        <option value="3">3
        <option value="7">7
        <option value="14">14
        <option value="30">30
        <option value="100">100
        <option value="365">365
        </select>
        </td>
        <td>
        <br>
        <input type="hidden" name="subdelinact" value="1">
        <input type="submit" value="Inaktive löschen">
        </td>
    </form>
    </table>
    </td>
</tr>
<tr>
    <td>
    <hr>
    </td>
</tr>
<tr>
    <td>
    <h3>User verbannen:</h3>
    <?php echo $form->error("banuser"); ?>
    <form method="post" action="adminprocess.php">
        Username:<br>
        <input type="text" name="banuser" maxlength="30" value="<?php echo $form->value("banuser"); ?>">
        <input type="hidden" name="subbanuser" value="1">
        <input type="submit" value="User verbannen">
    </form>
    </td>
</tr>
<tr>
    <td>
    <hr>
    </td>
</tr>
<tr>
    <td>
    <h3>Verbannte User:</h3>
<?php

    displayBannedUsers();

?>
    </td>
</tr>
<tr>
    <td>
    <hr>
    </td>
</tr>
<tr>
    <td>
    <h3>Verbannte User löschen:</h3>
    <?php echo $form->error("delbanuser"); ?>
    <form method="post" action="adminprocess.php">
        Username:<br>
        <input type="text" name="delbanuser" maxlength="30" value="<?php echo $form->value("delbanuser"); ?>">
        <input type="hidden" name="subdelbanned" value="1">
        <input type="submit" value="Verbannten löschen">
    </form>
    </td>
</tr>
</table>
</body>
</html>
<?php

    }

?>