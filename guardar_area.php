<?php
include 'db.php';

$nombre = $_POST['nombre'];

$sql = "INSERT INTO areas (nombre) VALUES ('$nombre')";

if ($conn->query($sql) === TRUE) {
    echo "Área guardada exitosamente.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
<a href="index.php">Volver a Inicio</a>
