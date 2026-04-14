<?php
// Startet die Session
session_start();
// Löscht alle Daten der aktuellen Session
session_destroy();
// Leitet den Benutzer zur Login-Seite weiter, nachdem er erfolgreich angemeldet ist.
header("Location: login.php");
// Stoppt hier
exit();
?>