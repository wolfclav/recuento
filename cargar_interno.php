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
    <title>Cargar Interno</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
        }
        .container {
            max-width: 600px;
        }
        /* Ajuste del ancho mínimo del campo de búsqueda */
        #buscar-interno {
            min-width: 150px;
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
    <h1 class="text-center">Cargar Interno</h1>
    <?php


    include 'db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre'])) {
        // Guardar nuevo interno
        $nombre = $_POST['nombre'];
        $pabellon_id = $_POST['pabellon'];
        $numero_celda = $_POST['numero_celda']; // Obtener el número de celda desde el formulario

        // Incluir el número de celda en la consulta SQL
        $sql = "INSERT INTO internos (nombre, pabellon_id, numero_celda) VALUES ('$nombre', $pabellon_id, $numero_celda)";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success'>Interno guardado exitosamente.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error al guardar el interno: " . $conn->error . "</div>";
        }
    }
    ?>
    <form action="" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="pabellon">Pabellón:</label>
            <select id="pabellon" name="pabellon" class="form-control" required>
                <?php
                $sql = "SELECT id, nombre FROM pabellones";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="numero_celda">Número de Celda:</label>
            <select id="numero_celda" name="numero_celda" class="form-control" required>
                <?php
                for ($i = 1; $i <= 12; $i++) {
                    echo "<option value='$i'>$i</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>

    <h2 class="text-center">Buscar y Eliminar Interno</h2>
    <form id="buscar-form" onsubmit="return false;">
        <div class="form-group">
            <label for="buscar">Buscar Interno:</label>
            <input type="text" id="buscar-interno" name="buscar" class="form-control">
        </div>
    </form>
    <div id="resultado-busqueda"></div>
    <button id="eliminar-interno" class="btn btn-danger mt-3" style="display: none;">Eliminar</button>

    <!-- Botón de Volver a Inicio -->
    <div class="text-center">
        <a href="index.php" class="btn btn-primary mt-3 no-print">Volver a Inicio</a>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $("#buscar-interno").on("keyup", function() {
            var query = $(this).val();
            if (query !== "") {
                $.ajax({
                    url: "buscar_interno.php",
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

        // Escuchar clics en nombres internos
        $(document).on("click", ".nombre-interno", function() {
            var id = $(this).data("id");
            $("#buscar-interno").val($(this).text()); // Establecer el nombre del interno en el campo de búsqueda
            $("#eliminar-interno").attr("data-id", id); // Establecer el ID del interno en el botón de eliminar
            $("#eliminar-interno").show(); // Mostrar el botón de eliminar
            $("#resultado-busqueda").empty(); // Vaciar los resultados de la búsqueda
        });

        // Escuchar clics en el botón de eliminar interno
        $("#eliminar-interno").on("click", function() {
            var id = $(this).attr("data-id");
            if (confirm("¿Estás seguro de que quieres eliminar este interno?")) {
                $.ajax({
                    url: "eliminar_interno.php",
                    method: "POST",
                    data: { id: id },
                    success: function(data) {
                        alert(data);
                        $("#buscar-interno").val(""); // Limpiar el campo de búsqueda después de eliminar
                        $("#eliminar-interno").hide(); // Ocultar el botón de eliminar
                    }
                });
            }
        });
    });
</script>
</body>
</html>
