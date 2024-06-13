<?php
// detalles_area.php

// Verificar si se recibió correctamente el parámetro "area_id" desde la solicitud AJAX
if (isset($_GET['area_id'])) {
    // Conectarse a la base de datos (reemplaza estos valores con los tuyos)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "recuento";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Escapar el ID del área para prevenir inyección de SQL
    $area_id = $conn->real_escape_string($_GET['area_id']);

    // Consulta SQL para obtener los nombres de los internos en el área especificada
    $sql = "SELECT nombre FROM internos WHERE area_id = '$area_id'";

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        // Mostrar los nombres de los internos en el área
        echo "<h2>Internos en el Área</h2>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row['nombre'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No se encontraron internos en esta área.";
    }

    // Cerrar conexión
    $conn->close();
} else {
    // Si no se recibió el parámetro "area_id", mostrar un mensaje de error
    echo "Error: No se proporcionó el ID del área.";
}
?>

