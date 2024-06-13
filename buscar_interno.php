<?php
include 'db.php';

$query = $_POST['query'];

$sql = "SELECT internos.id, internos.nombre, pabellones.nombre AS nombre_pabellon, internos.numero_celda 
        FROM internos 
        INNER JOIN pabellones ON internos.pabellon_id = pabellones.id 
        WHERE internos.nombre LIKE '%$query%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<ul class='list-group'>";
    while ($row = $result->fetch_assoc()) {
        echo "<li class='list-group-item' data-id='" . $row['id'] . "'>";
        echo "<span class='nombre-interno' data-id='" . $row['id'] . "' style='cursor: pointer;'>" . $row['nombre'] . "</span>";
        echo " - Pabellón: " . $row['nombre_pabellon'];
        echo " - Celda: " . $row['numero_celda'];
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<div class='alert alert-warning'>No se encontraron internos.</div>";
}

$conn->close();
?>
