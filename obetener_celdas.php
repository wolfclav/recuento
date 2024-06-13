<?php
// Incluir el archivo de conexión a la base de datos
include 'db.php';

if(isset($_POST["pabellon_id"])){
    $pabellon_id = $_POST["pabellon_id"];
    $sql = "SELECT id, numero FROM celdas WHERE pabellon_id = $pabellon_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['numero'] . "</option>";
        }
    }
}
?>
