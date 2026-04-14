<?php
//include ('../header.php');
// Verbindung
require_once '../config/db.php';

// Prüfen ob Formular abgeschickt,Da flug_id, gate_id, passagieranzahl Zahlen sind, solltest du sie als Integer casten:
if (isset($_POST["submit"])) {
    $id = (int) $_POST["id"];
    $gate_id = (int) $_POST["gate_id"];
    $schalter_name = $_POST["schalter_name"];

    $stmt = mysqli_prepare($mysqli, "UPDATE checkin SET gate_id=?, schalter_name=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "isi", $gate_id, $schalter_name, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<div class='alert alert-success'>checkin erfolgreich aktualisiert.</div>";
    } else {
        echo "<div class='alert alert-danger'>Fehler: " . mysqli_stmt_error($stmt) . "</div>";
    }

    mysqli_stmt_close($stmt);
}

// ID aus URL
$id = (int) $_GET["id"];
$result = mysqli_query($mysqli, "SELECT * FROM checkin WHERE id=$id");

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);

    $gate_id = $row["gate_id"];
    $schalter_name = $row["schalter_nr"];
} else {
    die("Checkin nicht gefunden.");
}

mysqli_free_result($result);
?>

<!DOCTYPE html>
<html>
<head>
<title>Lehrer bearbeiten</title>
    <!-- Bootstrap lokal -->
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Eigene LESS-Datei für andere Styles -->
    <link rel="stylesheet/less" type="text/css" href="../styles-less/base/all.less">
     <!-- LESS Compiler -->
    <script src="/js/less.min.js"></script>
</head>
<body>
  <?php include('../header.php'); ?>   
	<div class="container mt-4">
		<h2>Checkin bearbeiten</h2>	
		<form class="form_edit" method="post">
			<input type="hidden" name="id" value="<?php echo $id; ?>">

			<div class="mb-3">
				<label for="gate_id" class="form-label text-white fs-4">Gate ID</label>
				<input type="number" id="gate_id" name="gate_id"
					class="form-control"
					value="<?php echo htmlspecialchars($gate_id); ?>" readonly>
			</div>

			<div class="mb-3">
				<label for="schalter_name" class="form-label text-white fs-4">Schaltername</label>
				<select id="schalter_name" name="schalter_name" class="form-control">
					 <option value="A1">A1</option>
                     <option value="A2">A2</option>
                     <option value="A3">A3</option>
                     <option value="B1">B1</option>
                     <option value="B2">B2</option>
                     <option value="B3">B3</option>
                     <option value="C1">C1</option>
                     <option value="C2">C2</option>
                     <option value="C3">C3</option>
				</select>
			</div>
			<button type="submit" name="submit" class="btn btn-primary mb-4">Checkin
				aktualisieren</button>
		</form>
	</div>
 
    <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>