<?php
include 'db.php';

$area_id = $_GET['area_id'];

$sql = "SELECT i.id, i.nombre AS interno, p.nombre AS pabellon
        FROM internos i
        INNER JOIN pabellones p ON i.pabellon_id = p.id
        WHERE i.area_id = $area_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Internos en el Área</h2>";
    echo "<table class='table table-bordered table-hover'><thead><tr><th style='text-align: center;'>Interno</th><th style='text-align: center;'>Pabellón</th></tr></thead><tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row['interno'] . "</td><td>" . $row['pabellon'] . "</td></tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<div class='alert alert-info'>No hay internos en esta área.</div>";
}

$conn->close();
?>
