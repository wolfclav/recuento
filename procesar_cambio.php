<?php
session_start();

// Verificar si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit; // Asegura que el script se detenga aquí para que la redirección se procese correctamente
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Procesar Cambio de Interno</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        h2 {
            margin-bottom: 20px;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Procesar Cambio de Interno</h2>
        <?php
        // Conexión a la base de datos
        include 'db.php';

        // Obtener los datos del formulario
        $interno_id = $_POST['interno'];
        $nuevo_pabellon_id = $_POST['pabellon'];

        // Consulta SQL para actualizar el pabellón del interno
        $sql = "UPDATE internos SET pabellon_id = $nuevo_pabellon_id WHERE id = $interno_id";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success'>El interno ha sido cambiado de pabellón exitosamente.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error al cambiar el interno de pabellón: " . $conn->error . "</div>";
        }

        // Cerrar la conexión a la base de datos
        $conn->close();
        ?>
        <a href="cambio_interno.php" class="btn btn-primary mt-3">Volver</a>
    </div>
</body>
</html>
