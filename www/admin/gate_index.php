
<?php
// Header einbinden
//include ('../header.php');
// Datenbankverbindung herstellen
require_once '../config/db.php';
// SQL-Abfrage: Alle Gate-Daten aus der gate Tabelle holen
$sql = "SELECT * FROM gate";
// Abfrage ausführen
$result = mysqli_query($mysqli, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkin Übersicht</title>
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
		<h2>Gate Übersicht</h2>
		<table class="table table-bordered text-center table-striped">
			<thead class="table-dark">
				<tr>
					<th>ID</th>
					<th>Gate Nummer</th>
					<th>Max-Kapazität</th>
					<th>Security ID</th>

				</tr>
			</thead>
			<tbody>
                <?php
                // Wenn Ergebnisse vorhanden
                if (mysqli_num_rows($result) > 0) {
                    // Jede Zeile durchlaufen
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['gate_name']}</td>
                            <td>{$row['max_kapazitaet']}</td>
                            <td>{$row['security_id']}</td>
                            
                          </tr>";
                    }
                } else {
                    // Wenn keine Daten
                    echo "<tr><td colspan='5'>Keine checkin gefunden.</td></tr>";
                }
                // Ergebnisse freigeben
                mysqli_free_result($result);
                // Verbindung schließen
                mysqli_close($mysqli);
                ?>
    		</tbody>
		</table>
	</div>
	<script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
	
</body>
</html>