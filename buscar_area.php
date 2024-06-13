<?php
include 'db.php';

$query = $_POST['query'];

$sql = "SELECT id, nombre FROM areas WHERE nombre LIKE '%$query%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<ul class='list-group'>";
    while ($row = $result->fetch_assoc()) {
        echo "<li class='list-group-item' data-id='" . $row['id'] . "'>";
        echo "<span class='nombre-area' data-id='" . $row['id'] . "' style='cursor: pointer;'>" . $row['nombre'] . "</span>";
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<div class='alert alert-warning'>No se encontraron áreas.</div>";
}

$conn->close();
?>
