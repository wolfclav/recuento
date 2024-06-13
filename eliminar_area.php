<?php
include 'db.php';

$id = $_POST['id'];

// Eliminar primero los registros relacionados en la tabla 'movimientos'
$sql_delete_movimientos = "DELETE FROM movimientos WHERE area_id = $id";
if ($conn->query($sql_delete_movimientos) === TRUE) {
    // Luego, eliminar el área de la tabla 'areas'
    $sql_delete_areas = "DELETE FROM areas WHERE id = $id";
    if ($conn->query($sql_delete_areas) === TRUE) {
        echo "Área eliminada exitosamente.";
    } else {
        echo "Error al eliminar el área: " . $conn->error;
    }
} else {
    echo "Error al eliminar los registros relacionados en la tabla 'movimientos': " . $conn->error;
}

$conn->close();
?>
