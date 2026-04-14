<?php
session_start();

// Verbindung einbinden
require_once 'config/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /*
     * $benutzername = $_POST['benutzername'] ?? '';
     * $passwort = $_POST['passwort'] ?? '';
     */
    $benutzername = trim($_POST['benutzername'] ?? '');
    $passwort = trim($_POST['passwort'] ?? '');

    $stmt = $mysqli->prepare("SELECT passwort FROM benutzer WHERE benutzername = ?");
    $stmt->bind_param("s", $benutzername);
    $stmt->execute();
    $stmt->bind_result($hash);

    if ($stmt->fetch() && password_verify($passwort, $hash)) {
        $_SESSION['benutzername'] = $benutzername;
        //Bei erfogreiche Anmeldung: Weiterleitung zum Dashboard.php
        header("Location: admin/dashboard.php");
        exit();
    } else {
        $message = "Benutzername oder Passwort ist falsch!";
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="de">
<head>
         <!-- Zeichencodierung festlegen -->
        <meta charset="UTF-8">
        <!-- Für mobile Optimierung -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Seitentitel -->
        <title>Login</title>   
        <!-- Bootstrap  für das Layout lokal -->
    	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Eigene LESS-Datei für andere Styles -->
        <link rel="stylesheet/less" type="text/css" href="styles-less/base/all.less">
        <!-- Lokaler LESS Compiler -->
	    <script src="../js/less.min.js"></script>
</head>
<body>
	<div id="form_div">
		<form method="POST">
		     <!-- Formularüberschrift -->
			<h2> Admin</h2>
			<!-- Eingabefeld für Benutzername -->
			<div>
				<label for="benutzername">Benutzername:</label> <input type="text" id="benutzername" name="benutzername" required>
			</div>
			<!-- Eingabefeld für Passwort -->
			<div>
				<label for="passwort">Passwort:</label> <input type="password" id="passwort" name="passwort" required>
			</div>
			 <!-- Button zum Bestätigung -->
			<button type="submit">Anmelden</button>	
			 <!-- Link zur Registrierung -->	
			<a href="registrieren.php">Registrieren</a>	
			<!-- Bei falscher Anmeldung: Ausgabe von Fehlermeldungen -->		
        	 <span class="text-info fs-4"><?php echo $message; ?></span>
       	
		</form>
	</div>
</body>
</html>