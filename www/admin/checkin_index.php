<?php
//include ('../header.php');
require_once '../config/db.php';

// 🔥 DELETE nach oben verschoben
if (isset($_POST['delete'])) {
    $delete_id = (int) $_POST['delete'];
    mysqli_query($mysqli, "DELETE FROM checkin WHERE id=$delete_id");
    // Einfache Nachricht
    echo "<p style='color: green;'>Datensatz erfolgreich gelöscht!</p>";
    header("Location: " . $_SERVER['PHP_SELF']); // Seite neu laden
    exit();
}

$sql = "SELECT * from checkin";
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Checkin Übersicht</title>
    <!-- Bootstrap lokal -->
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Eigene LESS-Datei für andere Styles -->
    <link rel="stylesheet/less" type="text/css"href="/styles-less/base/all.less">
    <!-- Lokaler LESS Compiler -->
	<script src="/js/less.min.js"></script>
</head>
<body>
  <?php include('../header.php'); ?>   
	<div class="container mt-4">
		<h2>Checkin Übersicht</h2>
		<table class="table table-bordered text-center table-striped">
			<thead class="table-dark">
				<tr>
					<th>Flug ID</th>
					<th>Gate ID</th>
					<th>Schaltername</th>
					<th>Checkin Beginn</th>
					<th>Checkin Ende</th>
					<th>Menü</th>
				</tr>
			</thead>
			<tbody>
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>   
                <td>{$row['flug_id']}</td>                                   
                <td>{$row['gate_id']}</td>
                <td>{$row['schalter_nr']}</td>                        
                <td>{$row['checkin_beginn']}</td>
                <td>{$row['checkin_ende']}</td>
                <td>
                  <a href='checkin_edit.php?id={$row['id']}' class='btn btn-primary btn-sm'>Bearbeiten</a>
                  <form method='post' action='' style='display:inline-block'>
                    <button type='submit' name='delete' value='{$row['id']}' class='btn btn-danger btn-sm'>Löschen</button>
                  </form>

                </td>
              </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Keine checkin gefunden.</td></tr>";
    }

    mysqli_free_result($result);
    mysqli_close($mysqli);
    ?>
    </tbody>
		</table>
	</div>
	<script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
	
</body>
</html>