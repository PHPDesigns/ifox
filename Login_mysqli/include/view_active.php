<?php

    if(!defined('TBL_ACTIVE_USERS'))
        {
        die("Fehler!");
        }

    $q = "SELECT username FROM ".TBL_ACTIVE_USERS
    ." ORDER BY timestamp DESC,username";

    $result = $database->query($q);
    $num_rows = mysqli_num_rows($result);

    if(!$result || ($num_rows < 0))
        {
        echo 'Fehler!';
        }

    if($num_rows > 0)
        {
        echo "<table align=\"left\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">\n";
        echo "<tr><td><font size=\"2\">\n";

				while($rows=mysqli_fetch_array($result))
		{
            echo "<a href=\"userinfo.php?user=$rows[username]\">$rows[username]</a> / ";
            }
        echo "</font></td></tr></table><br>\n";
        }

?>