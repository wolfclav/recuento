<?php
// Incluir el archivo de conexión a la base de datos
include 'db.php';

// Obtener los datos del formulario
$interno = $_POST['interno'];
$pabellon_origen = $_POST['pabellon_origen'];
$celda_origen = $_POST['celda_origen'];
$pabellon_destino = $_POST['pabellon_destino'];
$celda_destino = $_POST['celda_destino'];

// Verificar si el pabellón de destino es diferente al pabellón de origen
if ($pabellon_destino == $pabellon_origen) {
    // Actualizar la ubicación del interno dentro del mismo pabellón
    $sql = "UPDATE internos SET numero_celda = '$celda_destino' WHERE id = $interno";
    if ($conn->query($sql) === TRUE) {
        echo "Interno movido exitosamente a la nueva celda.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    // Mover el interno a otro pabellón
    $sql = "UPDATE internos SET pabellon_id = '$pabellon_destino', numero_celda = '$celda_destino' WHERE id = $interno";
    if ($conn->query($sql) === TRUE) {
        echo "Interno movido exitosamente al nuevo pabellón.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
