<?php
// Verbindung zur Datenbank laden
require_once '../config/db.php';

// Holt Flugdaten, Airline, Check-in-Zeiten, Schalter, Gate, Security und Passagieranzahl
// GROUP_CONCAT fasst mehrere Schalternummern in einen String zusammen
// Die LEFT JOINs verbinden die Tabellen Airline, Check-in, Gate und Security mit den Fluege Tabelle
// GROUP BY fluege.id: gruppiert die Ergebnisse pro Flug 
// ORDER BY fluege.abflugzeit ASC: sortiert die Flüge nach Abflugzeit aufsteigend
/*
-- Schalternummern aller Check-in-Schalter für den Flug als kommagetrennte Liste zusammenfassen
-- DISTINCT entfernt doppelte Werte
-- ORDER BY sortiert die Schalternummern aufsteigend
-- SEPARATOR ', ' setzt ein Komma mit Leerzeichen zwischen den Einträgen
-- Quelle/Referenz: MySQL Dokumentation zu GROUP_CONCAT: https://dev.mysql.com/doc/refman/8.0/en/group-by-functions.html#function_group-concat
GROUP_CONCAT(DISTINCT checkin.schalter_nr
    ORDER BY checkin.schalter_nr ASC
    SEPARATOR ', ') AS schalter_nr,
    https://www.linkedin.com/posts/anushkasawant_sql-dataanalytics-groupabrconcat-share-7232415870210236416-2VYq/?utm_source=share&utm_medium=member_desktop&rcm=ACoAAFeADGMBPZWoHbKV2uvFu0KbiGBRWctiMRc

*/

$sql_dashboard = "
SELECT
    fluege.id AS flug_id,
    fluege.flug_nr,
    airline.name AS airline_name,
    fluege.ziel,
    fluege.abflugzeit,
    checkin.checkin_beginn,
    checkin.checkin_ende,
    GROUP_CONCAT(DISTINCT checkin.schalter_nr ORDER BY checkin.schalter_nr ASC SEPARATOR ', ') AS schalter_nr,
    gate.gate_name AS gate,
    security.name AS security_name,
    fluege.max_passagiere_kapazitaet AS passagier_anzahl
FROM fluege
LEFT JOIN airline ON fluege.airline_id = airline.id
LEFT JOIN checkin ON fluege.id = checkin.flug_id
LEFT JOIN gate ON checkin.gate_id = gate.id
LEFT JOIN security ON gate.security_id = security.id
GROUP BY fluege.id
ORDER BY fluege.abflugzeit ASC;
";

// SQL-Abfrage ausführen
$result_dashboard = mysqli_query($mysqli, $sql_dashboard);

if ($result_dashboard) {
    // Daten aus der Abfrage holen, wenn die Abfrage erfolgreich ist. 
    $dashboard_daten = mysqli_fetch_all($result_dashboard, MYSQLI_ASSOC);
    
    // Wenn keine Daten gefunden wurden, eine Fehlermeldung zurückgeben
    if (empty($dashboard_daten)) {
        echo json_encode(["error" => "Keine Daten gefunden."]);
        exit();
    }
    
    // json_encode: Konvertieren der Daten in JSON
    $json = json_encode($dashboard_daten, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    // JSON in einer Datei speichern
    if (file_put_contents('../json/dashboard_daten.json', $json)) {
        echo json_encode(["message" => "Daten erfolgreich gespeichert."]);
    } else {
        echo json_encode(["error" => "Fehler beim Speichern der Datei."]);
    }
} else {
    // error: Fehler bei der SQL-Abfrage
    echo json_encode([
        "error" => "Fehler bei der Abfrage: " . mysqli_error($mysqli)
    ]);
 
}

?>