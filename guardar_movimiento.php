<?php
include 'db.php';

$interno_id = $_POST['interno'];
$pabellon_id = $_POST['pabellon'];
$area_id = $_POST['area'];
$tipo_movimiento = $_POST['tipo_movimiento'];

$sql = "INSERT INTO movimientos (interno_id, pabellon_id, area_id, tipo_movimiento) VALUES ($interno_id, $pabellon_id, $area_id, '$tipo_movimiento')";

if ($conn->query($sql) === TRUE) {
    if ($tipo_movimiento == 'entrada') {
        $update_sql = "UPDATE internos SET area_id = $area_id WHERE id = $interno_id";
    } else {
        $update_sql = "UPDATE internos SET area_id = NULL WHERE id = $interno_id";
    }
    if ($conn->query($update_sql) === TRUE) {
        echo "Movimiento registrado exitosamente y estado del interno actualizado.";
    } else {
        echo "Error al actualizar el estado del interno: " . $conn->error;
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
<a href="index.php">Volver a Inicio</a>
