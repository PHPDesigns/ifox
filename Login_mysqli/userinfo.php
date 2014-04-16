<?php

    include('include/session.php');

?>

<html>
<title>User Info</title>
<body>
<?php

    $req_user = trim($_GET['user']);

    if(!$req_user || strlen($req_user) == 0 ||
    !preg_match("/^([0-9a-z])+$/i", $req_user) ||
    !$database->usernameTaken($req_user))
        {
        die("User existiert nicht!");
        }

    if(strcmp($session->username,$req_user) == 0)
        {
        echo "<h1><img src=\"images/user_info.png\" width=\"32\" height=\"32\">Mein Account</h1>";
        }

        else

        {
        echo "<h1>User Info</h1>";
        }

    $req_user_info = $database->getUserInfo($req_user);

    echo "<b>Username: ".$req_user_info['username']."</b><br>";
    echo "<b>Email Adresse:</b> ".$req_user_info['email']."<br>";

    if(strcmp($session->username,$req_user) == 0)
        {
        echo "<br><a href=\"useredit.php\">Account bearbeiten</a><br>";
        }

    echo "<br>Back To [<a href=\"main.php\">Zur Startseite</a>]<br>";

?>
</body>
</html>