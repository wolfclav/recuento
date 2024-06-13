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
    <title>Cargar Pabellón</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
        }
        .container {
            max-width: 600px;
        }
        .list-group-item {
            cursor: pointer;
        }
        .position-relative {
            position: relative;
        }
        #resultado-busqueda {
            position: absolute;
            z-index: 1000;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
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
  <div class="encabezado-texto">"Reeducar para reinsertar."</div>
</div>
    <div class="container">
        <h1 class="text-center">Cargar Pabellón</h1>
        <?php
        include 'db.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre'])) {
            // Guardar nuevo pabellón
            $nombre = $_POST['nombre'];

            $sql = "INSERT INTO pabellones (nombre) VALUES ('$nombre')";

            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success'>Pabellón guardado exitosamente.</div>";
            } else {
                echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
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

        <h2 class="text-center">Buscar y Eliminar Pabellón</h2>
        <form id="buscar-form" onsubmit="return false;" class="position-relative">
            <div class="form-group">
                <label for="buscar">Buscar Pabellón:</label>
                <input type="text" id="buscar-pabellon" name="buscar" class="form-control">
                <input type="hidden" id="pabellon-id" name="pabellon-id">
                <div id="resultado-busqueda" class="list-group"></div>
            </div>
        </form>
        <button id="eliminar-pabellon" class="btn btn-danger mt-3" style="display: none;">Eliminar</button>
        <!-- Botón de Volver a Inicio -->
        <div class="text-center">
            <a href="index.php" class="btn btn-primary mt-3 no-print">Volver a Inicio</a>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#buscar-pabellon").on("keyup", function() {
                var query = $(this).val();
                if (query !== "") {
                    $.ajax({
                        url: "buscar_pabellon.php",
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

            $(document).on("click", ".list-group-item", function() {
                var id = $(this).data("id");
                var nombre = $(this).text();
                $("#pabellon-id").val(id);
                $("#buscar-pabellon").val(nombre);
                $("#resultado-busqueda").html("");
                $("#eliminar-pabellon").attr("data-id", id).show();
            });

            $("#eliminar-pabellon").on("click", function() {
                var id = $(this).attr("data-id");
                if (confirm("¿Estás seguro de que quieres eliminar este pabellón?")) {
                    $.ajax({
                        url: "eliminar_pabellon.php",
                        method: "POST",
                        data: { id: id },
                        success: function(data) {
                            alert(data);
                            $("#buscar-pabellon").val("");
                            $("#eliminar-pabellon").hide();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
