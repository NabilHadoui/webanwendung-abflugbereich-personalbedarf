
<?php
// Header einbinden
//include ('../header.php');
// Datenbankverbindung herstellen
require_once '../config/db.php';

// Löschen-Funktion: Löscht Flug basierend auf der ID
if (isset($_POST['delete'])) {
    // ID des zu löschenden Fluges
    $delete_id = (int) $_POST['delete'];
    // Flug löschen
    mysqli_query($mysqli, "DELETE FROM fluege WHERE id=$delete_id");
    // Seite neu laden
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
// SQL-Abfrage: Alle Flüge holen
$sql = "SELECT * FROM fluege";
// Abfrage ausführen
$result = mysqli_query($mysqli, $sql);
// Wiederholung der SQL-Abfrage
$sql_fluege = "SELECT * FROM fluege";
$result_fluege = mysqli_query($mysqli, $sql_fluege);
// $result_fluege = $mysqli->query($sql_fluege);

/* Flugdaten holen */
$fluege_daten = mysqli_fetch_all($result_fluege, MYSQLI_ASSOC);

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
		<h2>Flüge Übersicht</h2>
		<!-- Hinweis für das Löschen -->
		<div class="alert alert-info">
			Hinweis: Vor dem Löschen eines Fluges müssen die zugehörigen
			Checkin-Zeilen in der Tabelle <strong>Checkin</strong> zuerst
			gelöscht werden.
		</div>
         <!-- Tabelle für Flüge -->
		<table class="table table-bordered text-center table-striped">
			<thead class="table-dark">
				<tr>
					<th>ID</th>
					<th>Flugnummer</th>
					<th>Ziel</th>
					<th>Abflugzeit</th>
					<th>Flugzeugtyp</th>
					<th>Max Passagiere Kapazität</th>
					<th>Menü</th>
				</tr>
			</thead>
              <tbody>
                <?php
                // Zeilen für alle Flüge aus der Abfrage ausgeben
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['flug_nr']}</td>               
                            <td>{$row['ziel']}</td>
                            <td>{$row['abflugzeit']}</td> 
                            <td>{$row['flugzeug_typ']}</td>
                            <td>{$row['max_passagiere_kapazitaet']}</td>               
                            <td>                 
                              <form method='post' action='' style='display:inline-block'>
                                <button type='submit' name='delete' value='{$row['id']}' class='btn btn-danger btn-sm'>Löschen</button>
                              </form>
                            </td>
                          </tr>";
                    }
                } else {
                    // keine Flüge gefunden
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