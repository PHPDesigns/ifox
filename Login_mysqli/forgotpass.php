<?php

    include('include/session.php');

?>
<html>
<title>Passwort vergessen?</title>
<body>
<?php

    // Passwort vergessen Funktion

    if(isset($_SESSION['forgotpass']))
        {
        if($_SESSION['forgotpass'])
            {
            echo "<h1>Neues Passwort wurde erstellt!</h1>";
            echo "<p>Ihr neues Passwort wurde eben erstellt "
            ."und an Ihre Email Adresse gesendet. "
            ."<a href=\"main.php\">Zur Startseite</a>.</p>";
            }

            else

            {
            echo "<h1>Ein Fehler ist aufgetreten!</h1>";
            echo "<p>Es konnte Ihnen leider kein neues Passwort "
            ."zugesendet werden, versuchen Sie es erneut. "
            ."<a href=\"main.php\">Zur Startseite</a>.</p>";
            }

        unset($_SESSION['forgotpass']);
        }

        else

        {

?>
<h1>Passwort vergessen?</h1>
<p>Sie können hier ein neues Passwort generieren, welches Ihnen dann per Email zugesendet wird.</p>
<p>Sie müssen nur Ihren Usernamen eingeben.</p>

<?php echo $form->error("user"); ?>

<form method="post" action="process.php">
    <strong>Username:</strong> <input type="text" name="user" maxlength="30" value="<?php echo $form->value("user"); ?>">
    <input type="hidden" name="subforgot" value="1">
    <input type="submit" value="Passwort anfordern">
</form>
<?php

        }

?>
</body>
</html>