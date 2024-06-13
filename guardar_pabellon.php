<?php
include 'db.php';

$nombre = $_POST['nombre'];

$sql = "INSERT INTO pabellones (nombre) VALUES ('$nombre')";

if ($conn->query($sql) === TRUE) {
    echo "Pabellón guardado exitosamente.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
<a href="index.php">Volver a Inicio</a>
