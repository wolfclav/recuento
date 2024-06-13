<?php
include 'db.php';

$pabellonId = intval($_GET['pabellon_id']);

// Internos presentes en el pabellón
$sql_presentes = "SELECT i.nombre AS interno, i.numero_celda AS celda
                  FROM internos i
                  WHERE i.pabellon_id = $pabellonId AND i.area_id IS NULL
                  ORDER BY i.numero_celda ASC";
$result_presentes = $conn->query($sql_presentes);

// Internos movidos a otra área
$sql_movidos = "SELECT i.nombre AS interno, a.nombre AS area
                FROM internos i
                JOIN areas a ON i.area_id = a.id
                WHERE i.pabellon_id = $pabellonId
                ORDER BY a.nombre ASC";
$result_movidos = $conn->query($sql_movidos);

echo "<h3>Detalles del Pabellón</h3>";

if ($result_presentes->num_rows > 0) {
    echo "<h4>Internos Presentes</h4>";
    echo "<table class='table table-bordered table-hover'><thead><tr><th>Interno</th><th>Celda</th></tr></thead><tbody>";
    while ($row = $result_presentes->fetch_assoc()) {
        echo "<tr><td>" . $row['interno'] . "</td><td>" . $row['celda'] . "</td></tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<div class='alert alert-info'>No hay internos presentes en este pabellón.</div>";
}

if ($result_movidos->num_rows > 0) {
    echo "<h4>Internos Movidos a Otras Áreas</h4>";
    echo "<table class='table table-bordered table-hover'><thead><tr><th>Interno</th><th>Área</th></tr></thead><tbody>";
    while ($row = $result_movidos->fetch_assoc()) {
        echo "<tr><td>" . $row['interno'] . "</td><td>" . $row['area'] . "</td></tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<div class='alert alert-info'>No hay internos movidos a otras áreas desde este pabellón.</div>";
}

$conn->close();
?>
