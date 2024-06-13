<?php
include 'db.php';

$id = $_POST['id'];

// Eliminar primero los registros relacionados en la tabla 'movimientos'
$sql_delete_movimientos = "DELETE FROM movimientos WHERE interno_id = $id";
if ($conn->query($sql_delete_movimientos) === TRUE) {
    // Luego, eliminar el interno de la tabla 'internos'
    $sql_delete_interno = "DELETE FROM internos WHERE id = $id";
    if ($conn->query($sql_delete_interno) === TRUE) {
        echo "Interno eliminado exitosamente.";
    } else {
        echo "Error al eliminar el interno: " . $conn->error;
    }
} else {
    echo "Error al eliminar los registros relacionados en la tabla 'movimientos': " . $conn->error;
}

$conn->close();
?>
