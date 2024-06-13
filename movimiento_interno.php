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
    <title>Registrar Movimiento de Interno</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
        }
        .container {
            max-width: 800px;
        }
        /* Ajuste de ancho para la lista desplegable de búsqueda */
        .lista-desplegable {
            width: calc(100% - 12px);
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
        <h1 class="text-center">Registrar Movimiento de Interno</h1>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include 'db.php';

            $interno_id = $_POST['interno'];
            $area_id = $_POST['area'];
            $tipo_movimiento = $_POST['tipo_movimiento'];

            $sql = "INSERT INTO movimientos (interno_id, area_id, tipo_movimiento) VALUES ($interno_id, $area_id, '$tipo_movimiento')";

            if ($conn->query($sql) === TRUE) {
                if ($tipo_movimiento == 'entrada') {
                    $update_sql = "UPDATE internos SET area_id = $area_id WHERE id = $interno_id";
                } else {
                    $update_sql = "UPDATE internos SET area_id = NULL WHERE id = $interno_id";
                }
                if ($conn->query($update_sql) === TRUE) {
                    echo "<div class='alert alert-success'>Movimiento registrado exitosamente y estado del interno actualizado.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error al actualizar el estado del interno: " . $conn->error . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
            }

            $conn->close();
        }
        ?>
        <form action="movimiento_interno.php" method="POST">
            <div class="form-group">
                <label for="interno">Buscar Interno:</label>
                <input type="text" id="buscar-interno" name="buscar-interno" class="form-control" placeholder="Escribe el nombre del interno" required>
                <input type="hidden" id="interno" name="interno" required>
                <div id="resultado-interno" class="list-group"></div>
            </div>
            <div class="form-group">
                <label for="area">Área:</label>
                <select id="area" name="area" class="form-control" required>
                    <option value="">Selecciona el área</option> <!-- Agregamos una opción inicial vacía para forzar la selección -->
                    <?php
                    include 'db.php';
                    $sql = "SELECT id, nombre FROM areas ORDER BY nombre ASC";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tipo_movimiento">Tipo de Movimiento:</label>
                <select id="tipo_movimiento" name="tipo_movimiento" class="form-control" required>
                    <option value="">Selecciona el tipo de movimiento</option> <!-- Agregamos una opción inicial vacía para forzar la selección -->
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
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
                            $("#resultado-interno").html(data);
                        }
                    });
                } else {
                    $("#resultado-interno").html("");
                }
            });

            $(document).on("click", ".list-group-item", function() {
                var id = $(this).data("id");
                var nombre = $(this).text();
                $("#interno").val(id);
                $("#buscar-interno").val(nombre);
                $("#resultado-interno").html("");
            });
        });
    </script>
    <!-- Botón de Volver a Inicio -->
    <div class="text-center">
        <a href="index.php" class="btn btn-primary mt-3 no-print">Volver a Inicio</a>
    </div>
</body>
</html>
