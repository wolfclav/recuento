<?php
include 'db.php';

$nombre = $_POST['nombre'];
$pabellon_id = $_POST['pabellon'];

$sql = "INSERT INTO internos (nombre, pabellon_id) VALUES ('$nombre', $pabellon_id)";

if ($conn->query($sql) === TRUE) {
    echo "Interno guardado exitosamente.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
<a href="index.php">Volver a Inicio</a>
