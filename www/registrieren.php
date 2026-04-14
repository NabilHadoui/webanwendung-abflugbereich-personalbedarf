<?php
// Lädt die Datei db.php aus config nur einmal
require_once 'config/db.php';
//Session starten
session_start();
// CSRF-Token erstellen, falls es noch nicht existiert
$token = isset($_SESSION['token']) ? $_SESSION['token'] : "";
if (!$token) {
    // erzeugt ein neues Token
    $token = bin2hex(random_bytes(32));  
    // Speichert das Token in der Session
    $_SESSION['token'] = $token;
}
// Bestätigung oder Fehlermeldung anzeigen
$message = "";
// Wenn das Formular abgesendet wurde
if ($_SERVER['REQUEST_METHOD'] == "POST") {    
    // Überprüfen, ob das Token gültig ist
    if (isset($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token'])) {
        $benutzername = $_POST['benutzername'];
        $passwort = $_POST['passwort'];
        $position = $_POST['position'];
        //Passwort hashen
        $hash = password_hash($passwort, PASSWORD_DEFAULT);
        
        // Daten in die Datenbank einfügen
        $stmt = $mysqli->prepare("INSERT INTO benutzer (benutzername, passwort, position)
                                  VALUES (?,?,?)");      
        $stmt->bind_param("sss", $benutzername, $hash, $position);
        $stmt->execute();       
       // echo "Benutzer erstellt!";
        // Erfolgsnachricht
        $message = "Benutzer wurde erfolgreich erstellt!";       
        // Nach der Ausführung des Formulars Token löschen
        unset($_SESSION['token']);
    } else {
       // echo("Ungültige Anfrage! Möglicher CSRF-Angriff.");
        $message = "Ungültige Anfrage! Möglicher CSRF-Angriff.";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrieren</title>
     <!-- Bootstrap für das Layout lokal -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Eigene LESS-Datei für andere Styles -->
    <link rel="stylesheet/less" type="text/css" href="styles-less/base/all.less">
</head>
<body>

    <div id="form_div">
        <form method="POST">
     	    <!-- Formularüberschrift -->
            <h2>Airport Departure Admin</h2>

            <!-- CSRF Token für Sicherheitscheck einfügen -->
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <!-- Eingabefelder für Benutzername, Passwort und Position -->
            <div>
                <label for="benutzername">Benutzername:</label>
                <input type="text" id="benutzername" name="benutzername" required>
            </div>
            <div>
                <label for="passwort">Passwort:</label>
                <input type="password" id="passwort" name="passwort" required>
            </div>
            <div>
                <label for="position">Position:</label>
                <select name="position">
                    <option value="admin">Admin</option>
                    <option value="mitarbeiter">Mitarbeiter</option>
                </select>
            </div>
            <!-- Formularabsendebutton und Login-Link -->
            <button type="submit">Benutzer erstellen</button>
            <a href="login.php">Login</a>
            
              <!-- Benachrichtigung unter dem Button als <div> -->
            <!-- Bestätigung oder ehlermeldung unterhalb des Buttons -->
            <span class="text-info fs-4"><?php echo $message; ?></span>
        
                  
        </form>
    </div>

    <!-- Lokaler LESS Compiler -->
    <script src="js/less.min.js"></script>
</body>
</html>