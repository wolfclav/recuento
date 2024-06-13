<?php
include 'db.php';

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    $sql = "SELECT id, nombre FROM pabellones WHERE nombre LIKE '%$query%' LIMIT 10";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<a href='#' class='list-group-item list-group-item-action' data-id='" . $row['id'] . "'>" . $row['nombre'] . "</a>";
        }
    } else {
        echo "<p class='list-group-item list-group-item-action'>No se encontraron resultados</p>";
    }
}
?>
