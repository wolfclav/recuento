<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "recuento_internos";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_pabellon'])) {
        $nombre = $_POST['nombre_pabellon'];
        $sql = "INSERT INTO pabellones (nombre) VALUES ('$nombre')";
        if ($conn->query($sql) === TRUE) {
            echo "Pabellón agregado exitosamente.";
        } else {
            echo "Error al agregar pabellón: " . $conn->error;
        }
    } elseif (isset($_POST['add_interno'])) {
        $nombre = $_POST['nombre_interno'];
        $pabellon_id = $_POST['pabellon_id'];
        $ubicacion = $_POST['ubicacion_actual'];
        $sql = "INSERT INTO internos (nombre, pabellon_id, ubicacion_actual) VALUES ('$nombre', $pabellon_id, '$ubicacion')";
        if ($conn->query($sql) === TRUE) {
            echo "Interno agregado exitosamente.";
        } else {
            echo "Error al agregar interno: " . $conn->error;
        }
    } elseif (isset($_POST['move_interno'])) {
        $interno_id = $_POST['interno_id'];
        $nueva_ubicacion = $_POST['nueva_ubicacion'];
        $sql = "UPDATE internos SET ubicacion_actual = '$nueva_ubicacion' WHERE id = $interno_id";
        if ($conn->query($sql) === TRUE) {
            echo "Interno movido exitosamente.";
        } else {
            echo "Error al mover interno: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Recuento de Internos</title>
    <style>
        body { font-family: Arial, sans-serif; }
        form { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Administración de Recuento de Internos</h1>

    <h2>Agregar Pabellón</h2>
    <form method="post" action="">
        <label for="nombre_pabellon">Nombre del Pabellón:</label>
        <input type="text" id="nombre_pabellon" name="nombre_pabellon" required>
        <button type="submit" name="add_pabellon">Agregar Pabellón</button>
    </form>

    <h2>Agregar Interno</h2>
    <form method="post" action="">
        <label for="nombre_interno">Nombre del Interno:</label>
        <input type="text" id="nombre_interno" name="nombre_interno" required>
        <label for="pabellon_id">Pabellón:</label>
        <select name="pabellon_id" id="pabellon_id" required>
            <option value="">Seleccione</option>
            <?php
            $sql = "SELECT id, nombre FROM pabellones";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                echo "<option value=\"" . $row['id'] . "\">" . $row['nombre'] . "</option>";
            }
            ?>
        </select>
        <label for="ubicacion_actual">Ubicación Actual:</label>
        <input type="text" id="ubicacion_actual" name="ubicacion_actual" required>
        <button type="submit" name="add_interno">Agregar Interno</button>
    </form>

    <h2>Mover Interno</h2>
    <form method="post" action="">
        <label for="interno_id">Seleccione un Interno:</label>
        <select name="interno_id" id="interno_id" required>
            <option value="">Seleccione</option>
            <?php
            $sql = "SELECT id, nombre FROM internos";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                echo "<option value=\"" . $row['id'] . "\">" . $row['nombre'] . "</option>";
            }
            ?>
        </select>
        <label for="nueva_ubicacion">Nueva Ubicación:</label>
        <input type="text" id="nueva_ubicacion" name="nueva_ubicacion" required>
        <button type="submit" name="move_interno">Mover Interno</button>
    </form>

    <a href="index.php">Volver a la Consulta</a>

    <?php $conn->close(); ?>
</body>
</html>
