<?php
//  Header.php einbinden (Navigation, Logout)
//include ('../header.php');

// Datenbankverbindung laden
require_once '../config/db.php';

// SQL-Abfrage: alle Sicherheitsstellen aus der Tabelle Security holen
$sql = "SELECT * FROM security";
// Abfrage ausführen
$result = mysqli_query($mysqli, $sql);

/* Daten holen und JSON speichern */
$sicherheitsstelle = mysqli_fetch_all($result, MYSQLI_ASSOC);

/* Ergebnis neu laden, damit es für die HTML-Tabelle genutzt werden kann */
$result = mysqli_query($mysqli, $sql);

?>

<!DOCTYPE html>
<html>
<head>
 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sicherheitsstelle Übersicht</title>
     <!-- Bootstrap lokal -->
     <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Eigene LESS-Datei für andere Styles -->
    <link rel="stylesheet/less" type="text/css" href="/styles-less/base/all.less">
    <!-- Lokaler LESS Compiler -->
	<script src="/js/less.min.js"></script>
</head>
<body>
  <?php include('../header.php'); ?>   
	<div class="container mt-4">
		<h2>Security Übersicht</h2>
		      <!-- Tabelle mit Bootstrap-Style -->
		<table class="table table-bordered table-striped">
			<thead class="table-dark">
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Max-Kapazität</th>
				</tr>
			</thead>
			<tbody>
                <?php
                // Prüfen, ob Ergebnisse vorhanden sind
                if (mysqli_num_rows($result) > 0) {
                    // Jede Zeile durchlaufen und in der Tabelle anzeigen
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr class='text-center'>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['max_kapazitaet']}</td>                              
                          </tr>";
                    }
                } else {
                    // Falls keine Daten vorhanden, Fehlermeldung anzeigen
                    echo "<tr><td colspan='4'>Keine Security gefunden.</td></tr>";
                }
                // mit free:  Ergebnis freigeben
                mysqli_free_result($result);
                //  Datenbankverbindung schließen
                mysqli_close($mysqli);
                ?>
   		 </tbody>
	</table>
  </div>
    <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
	
</body>
</html>