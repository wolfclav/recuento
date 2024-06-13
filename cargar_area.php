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
    <link rel="icon" href="iconounidad.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <meta charset="UTF-8">
    <title>Cargar Área</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
        }
        .container {
            max-width: 600px;
        }
    </style>
</head>
<body>
<div class="encabezado">
  <div class="logo-texto">
    <div class="unidad">Unidad I</div>
    <div class="condenados">Condenados Mayores</div>
    <div class="condenados">Servicio Penitenciario Provincial</div>
    <div class="condenados">Provincia de San Luis</div>
  </div>

</div>
    <div class="container">
        <h1 class="text-center">Cargar Área</h1>
        <?php
        include 'db.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre'])) {
            // Guardar nueva área
            $nombre = $_POST['nombre'];

            $sql = "INSERT INTO areas (nombre) VALUES ('$nombre')";

            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success'>Área guardada exitosamente.</div>";
            } else {
                echo "<div class='alert alert-danger'>Error al guardar el área: " . $conn->error . "</div>";
            }
        }
        ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>

        <h2 class="text-center">Buscar y Eliminar Área</h2>
        <form id="buscar-form" onsubmit="return false;">
            <div class="form-group">
                <label for="buscar">Buscar Área:</label>
                <input type="text" id="buscar-area" name="buscar" class="form-control">
            </div>
        </form>
        <div id="resultado-busqueda"></div>
        <button id="eliminar-area" class="btn btn-danger mt-3" style="display: none;">Eliminar</button>
            <!-- Botón de Volver a Inicio -->
    <div class="text-center">
        <a href="index.php" class="btn btn-primary mt-3 no-print">Volver a Inicio</a>
    </div>
    </div>
    <!-- Scripts JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#buscar-area").on("keyup", function() {
                var query = $(this).val();
                if (query !== "") {
                    $.ajax({
                        url: "buscar_area.php",
                        method: "POST",
                        data: { query: query },
                        success: function(data) {
                            $("#resultado-busqueda").html(data);
                        }
                    });
                } else {
                    $("#resultado-busqueda").html("");
                }
            });

            $(document).on("click", ".nombre-area", function() {
                var id = $(this).data("id");
                $("#buscar-area").val($(this).text());
                $("#eliminar-area").attr("data-id", id);
                $("#eliminar-area").show();
                $("#resultado-busqueda").empty();
            });

            $("#eliminar-area").on("click", function() {
                var id = $(this).attr("data-id");
                if (confirm("¿Estás seguro de que quieres eliminar esta área?")) {
                    $.ajax({
                        url: "eliminar_area.php",
                        method: "POST",
                        data: { id: id },
                        success: function(data) {
                            alert(data);
                            $("#buscar-area").val("");
                            $("#eliminar-area").hide();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
