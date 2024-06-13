<?php
include 'db.php';

$id = $_POST['id'];

$sql = "DELETE FROM pabellones WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "Pabellón eliminado exitosamente.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
