<?php

//JSON-Daten Laden

$json = file_get_contents('../json/flugdaten_update.json');
$fluege_update = json_decode($json, true);

// Verbindung zur Datenbank laden
require_once '../config/db.php';

foreach ($fluege_update as $flug) {
    $flug_id = $flug['flug_id'];
    $abflugzeit = $flug['abflugzeit'];
    $checkin_beginn = $flug['checkin_beginn'];
    $checkin_ende = $flug['checkin_ende'];
    
    // Abflugzeit in der Tabelle Fluege aktualisieren
    $sql_flug = "UPDATE fluege
                 SET abflugzeit ='$abflugzeit'
                 WHERE id = $flug_id";
    //checkin Daten in der Tabelle checkin aktualisieren
    
    $sql_checkin = "UPDATE checkin
                    SET checkin_beginn = '$checkin_beginn',
                        checkin_ende = '$checkin_ende'
                    WHERE flug_id = $flug_id";
    //SQL Abfragen durchführen
    if($mysqli->query($sql_flug) === TRUE) {
        echo "Abflugzeit erfolgreich aktualisiert <br>";
    }else{
        echo "  Abflugzeit: Fehler bei der Aktualisierung";
    }
    if($mysqli->query($sql_checkin) === TRUE) {
        echo "Checkin erfolgreich aktualisiert <br>";
    }else{
        echo " Checkin: Fehler bei der Aktualisierung ";
    }

}
//Verbindung schließen
$mysqli->close();
?>