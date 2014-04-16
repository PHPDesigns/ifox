<?php

    //Datenbankverbindung
    define("DB_SERVER", "localhost");
    define("DB_USER", "login");
    define("DB_PASS", "werner85");
    define("DB_NAME", "login");

    // Tabellennamen (nicht ändern)
    define("TBL_USERS", "blog_users");
    define("TBL_ACTIVE_USERS",  "blog_active_users");
    define("TBL_ACTIVE_GUESTS", "blog_active_guests");
    define("TBL_BANNED_USERS",  "blog_banned_users");

    // User Level
    define("ADMIN_NAME", "Admin");
    define("GUEST_NAME", "Gast");
    define("ADMIN_LEVEL", 9);
    define("USER_LEVEL",  1);
    define("GUEST_LEVEL", 0);

    // Besucher Statistik
    define("TRACK_VISITORS", true);         // (true = ja / false = nein)

    // Zeitangabe - Timeout
    define("USER_TIMEOUT", 10);             // Für Mitglieder
    define("GUEST_TIMEOUT", 5);             // Für Gäste

    // Cookie Informationen
    define("COOKIE_EXPIRE", 60*60*24*100);  // 100 Tage - Standard
    define("COOKIE_PATH", "/");             // Auf ganzer Domain erlauben

    // Email Informationen
    define("EMAIL_FROM_NAME", "Your Name"); // Ihr Name
    define("EMAIL_FROM_ADDR", "yourname@domain.com");   // Ihre Email Adresse
    define("EMAIL_WELCOME", true);          // Willkomen-Nachricht senden (true = ja / false = nein)

    // Nur Kleinbuchstaben erlauben?
    define("ALL_LOWERCASE", false);         // Nur kleine Buchstaben (true = ja / false = nein)

?>