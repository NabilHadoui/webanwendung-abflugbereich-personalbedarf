<?php
session_start();
//include ('../header.php');
// Startet die Session oder setzt eine bestehende fort.
// Sessions werden verwendet, um Daten (z. B. Benutzername) über mehrere Seiten hinweg zu speichern.


require_once '../config/db.php';

// SQL-Abfrage zur Zählung der Einträge in der Tabelle fluege

$sql_fluege = "SELECT COUNT(*) AS total FROM fluege WHERE abflugzeit > NOW()";

// SQL-Abfrage ausführen und Fehler prüfen
$result_fluege = mysqli_query($mysqli, $sql_fluege);

if ($result_fluege) {
    // Daten holen
    $fluege_daten = mysqli_fetch_assoc($result_fluege); // Holen eines einzelnen Ergebnisses

    $total_flights = $fluege_daten['total'];
} else {
    // Fehler bei der SQL-Abfrage
    echo json_encode([
        "error" => "Fehler bei der Abfrage: " . mysqli_error($mysqli)
    ]);
}

// Überprüft, ob der Benutzer angemeldet ist.
// Das wird gemacht, indem geprüft wird, ob die Session-Variable 'username' gesetzt ist.
/*
 * isset() überprüft, ob eine Variable existiert und einen Wert hat (nicht null).
 * Ergebnis: true oder false.
 * Hier prüft der Code: „Ist der Benutzername in der Session gespeichert?“
 * Wenn ja → Benutzer ist eingeloggt.
 * Wenn nein → Benutzer ist nicht eingeloggt → Weiterleitung zur Login-Seite.
 * >>>> isset() → „Existiert das Ding überhaupt?“
 */
if (isset($_SESSION['benutzername'])) {
    // Wenn ja, speichern wir den Benutzernamen in einer lokalen Variable für die Anzeige
    $benutzername = $_SESSION['benutzername'];
} else {
    // Wenn nein (Benutzer nicht eingeloggt), wird er auf die Login-Seite weitergeleitet
    /*
     * Wenn der Benutzer nicht eingeloggt ist, wird er automatisch auf die Login-Seite geschickt.
     * exit() sorgt dafür, dass die aktuelle Seite nicht mehr weitergeladen wird.
     * >>>header('Location: …') → „Schick mich irgendwohin!“
     */
    header('Location: ../login.php'); // Browser geht sofort auf login.php
                                      // Beendet das Skript sofort, damit kein HTML ausgegeben wird
    exit(); // Stoppt das Script, damit kein weiterer Code ausgeführt wird
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <!-- Bootstrap lokal -->
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Eigene LESS-Datei für andere Styles -->
    <link rel="stylesheet/less" type="text/css" href="/styles-less/base/all.less">
    <!-- LESS Compiler -->
    <script src="/js/less.min.js"></script>
</head>
<body>
    <?php include('../header.php'); ?>  
	<div class="container mt-4 ">
		<div class="user_div">
			<h1>Willkommen, <?php echo $benutzername; ?>!</h1>
		</div>
		<div class="row g-3"> 
              <div class="col-md-3">
                <div class="card h-100 text-center text-white bg-primary">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Eingecheckte Passagiere</h5>
                    <p class="card-text display-4" id="checkincount">0</p>
                    <a href="#engpass" class="btn btn-warning mt-auto">Tabelle anzeigen</a>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card h-100 text-center text-white bg-success">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Flüge</h5>
                    <p class="card-text display-4"><?php echo $total_flights; ?></p>
                    <a href="fluege_index.php" class="btn btn-warning mt-auto">Tabelle anzeigen</a>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card h-100 text-center text-white bg-secondary">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Überkapazität Security</h5>
                    <p class="card-text display-4" id="summesecurity">0</p>
                    <a href="#engpass" class="btn btn-warning mt-auto">Tabelle anzeigen</a>
                  </div>
                </div>
              </div>
            
              <div class="col-md-3">
                <div class="card h-100 text-center text-white bg-danger">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Engpass Security</h5>
                    <p class="card-text display-4" id="engpass-security-value">0</p>
                    <a href="#engpass" class="btn btn-warning mt-auto">Tabelle Engpass</a>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card h-100 text-center text-white bg-primary">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Anzahl der Passagiere</h5>
                    <p class="card-text display-4" id="anzahl_passagiere">0</p>
                    <a href="checkin_index.php" class="btn btn-warning mt-auto">Tabelle anzeigen</a>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card h-100 text-center text-white bg-success">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Abflüge</h5>
                    <p class="card-text display-4" id="abflugcount">0</p>
                    <a href="fluege_index.php" class="btn btn-warning mt-auto">Tabelle anzeigen</a>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card h-100 text-center text-white bg-secondary">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Überkapazität Beamte</h5>
                    <p class="card-text display-4" id="summebeamte">0</p>
                    <a href="#engpass" class="btn btn-warning mt-auto">Tabelle anzeigen</a>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card h-100 text-center text-white bg-danger">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Engpass Beamte</h5>
                    <p class="card-text display-4" id="engpass-beamte-value">0</p>
                    <a href="#engpass" class="btn btn-warning mt-auto">Tabelle Engpass</a>
                  </div>
                </div>
              </div>
            </div>
	</div>
	  <div class="container mt-4 ">
   	<nav class="nav nav-pills flex-column flex-lg-row  align-items-center">
   		<a class="flex-sm-fill text-sm-center nav-link fs-3 fw-bolder " href="/api/dashboard_api.php">Dashboard API</a>
   		<a class="flex-sm-fill text-sm-center nav-link fs-3 fw-bolder " href="/api/flugdaten_update.php">Flugdaten API</a>       
        <a class="flex-sm-fill text-sm-center nav-link fs-3 fw-bolder " href="/json/dashboard_daten.json">Dashboard JSON</a>
        <a class="flex-sm-fill text-sm-center nav-link fs-3 fw-bolder " href="/json/flugdaten_update.json">Flugdaten JSON</a>
        
   	</nav>
   	</div>

	<div class="container mt-4">
            <!--
             <div class="table-responsive">
                  <table class="table">
                    ...
                  </table>
                </div> 
             -->
             
		<h2>Flüge Info</h2>
		<div class="table-responsive">
		<table class="table table-bordered table-striped text-center  ">
			<thead class="table-dark">
				<tr>
					<th>Flugnummer</th>
					<th>Airline</th>
					<th>Ziel</th>
					<th>Abflugzeit</th>
					<th>Schalter</th>
					<th>security_name</th>
					<th>checkinStatus</th>
					<th>statusSecurity</th>
					<th>boardingStatus</th>
					<th>flugStatus</th>
				</tr>
			</thead>
			<tbody id="fluege_plan">
				<!-- Die Zeilen werden an dieser Stelle hinzugefügt -->
			</tbody>
		</table>
        </div>
		<h2>Engpass und Überkapazität Tabelle !</h2>
        <div class="table-responsive">
		<table class="table table-bordered text-center table-striped">
			<thead class="table-dark">
				<tr>
					<th>Flugnummer</th>
					<th>Geplante Passagiere</th>
					<th>eingescheckte Passagiere</th>
					<th>Passagiere Differenz</th>
					<th>Geplante Checkin Mitarbeiter</th>
					<th>Geplante Security</th>
					<th>Benoetigte Security</th>
					<th>Geplante Beamte</th>
					<th>Benoetigte Beamte</th>
					<th>summe Plan Passagiere</th>
					<th>summe eingechekte Passagiere</th>
					<th>Summe Differenz</th>
				</tr>
			</thead>
			<tbody id="engpass">
				<!-- Die Zeilen werden an dieser Stelle hinzugefügt -->
			</tbody>
		</table>
		</div>
	</div>
	
	<br>
 
	<!-- <script src="../js/checkin.js"></script> -->
		<!-- Lokaler LESS Compiler -->
	
	<script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="/js/dashboard.js"></script>

</body>
</html>
