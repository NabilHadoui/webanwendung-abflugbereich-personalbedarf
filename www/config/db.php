<?php

// Aktiviert MySQLi-Fehlerberichte 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
  
    // Neue Verbindung zur MySQL-Datenbank herstellen
    // Parameter: Datenbank, Benutzername, Passwort, Datenbankname
    $mysqli = new mysqli('db', 'airport_user', 'rL[IfUkr1aXa[fyP', 'airport_database');
} catch (mysqli_sql_exception $e) {
    // Fehler beim Verbinden: Ausnahme  und Nachricht anzeigen
    throw new RuntimeException('Verbindung zur Datenbank fehlgeschlagen: ' . $e->getMessage());
}