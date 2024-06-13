<?php
include 'db.php';

$tipo = $_GET['tipo'];
$tabla = $tipo == 'pabellon' ? 'pabellones' : 'areas';
$sql = "SELECT id, nombre FROM $tabla";

$result = $conn->query($sql);
$destinos = [];

while ($row = $result->fetch_assoc()) {
    $destinos[] = $row;
}

echo json_encode($destinos);

$conn->close();
?>
