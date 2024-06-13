<?php
session_start();

// Verificar si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit; // Asegura que el script se detenga aquí para que la redirección se procese correctamente
}
?>

<?php
// Incluir el archivo de conexión a la base de datos
include 'db.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" href="iconounidad.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <meta charset="UTF-8">
    <title>Mover Interno</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
        }
        .container {
            max-width: 600px;
        }
        #mensaje-confirmacion {
            display: none;
            margin-top: 20px;
        }

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
$(document).ready(function(){
    $('#buscar_interno').keyup(function(){
        var query = $(this).val();
        if(query != '') {
            $.ajax({
                url: "buscar_interno.php",
                method: "POST",
                data: {query: query},
                success: function(data) {
                    $('#resultado-interno').fadeIn();
                    $('#resultado-interno').html(data);
                    // Ajustar el ancho de la lista desplegable al ancho del campo de búsqueda
                    var inputWidth = $('#buscar_interno').outerWidth();
                    $('#resultado-interno').css('width', inputWidth);
                }
            });
        } else {
            $('#resultado-interno').fadeOut();
        }
    });

    $(document).on('click', '#resultado-interno li', function(){
        var id = $(this).data('id');
        var nombre = $(this).text();
        $('#interno').val(id);
        $('#buscar_interno').val(nombre);
        $('#resultado-interno').fadeOut();
    });

    // Manejar el envío del formulario con AJAX
    $('#form-mover').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(data){
                $('#mensaje-confirmacion').html(data).fadeIn();
            }
        });
    });
});
</script>

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
    <h1 class="text-center">Mover Interno a otra celda o pabellón</h1>
    <form id="form-mover" action="procesar_movimiento.php" method="POST">
        <div class="form-group">
            <label for="buscar_interno">Buscar Interno:</label>
            <input type="text" id="buscar_interno" class="form-control" placeholder="Escribe el nombre del interno" required>
            <input type="hidden" name="interno" id="interno">
            <div id="resultado-interno" class="list-group"></div>
        </div>
        <!-- Resto del formulario -->
        <div class="form-group">
            <label for="pabellon_origen">Pabellón de Origen:</label>
            <select name="pabellon_origen" id="pabellon_origen" class="form-control" required>
                <?php
                // Consulta SQL para obtener los pabellones
                $sql = "SELECT id, nombre FROM pabellones";
                $result = $conn->query($sql);

                // Generar opciones para cada pabellón
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <!-- Resto del formulario -->
        <div class="form-group">
            <label for="celda_origen">Celda de Origen:</label>
            <select name="celda_origen" id="celda_origen" class="form-control" required>
                <?php
                // Generar opciones para cada celda de origen (del 1 al 12)
                for ($i = 1; $i <= 12; $i++) {
                    echo "<option value='" . $i . "'>" . $i . "</option>";
                }
                ?>
            </select>
        </div>
        <!-- Resto del formulario -->
        <div class="form-group">
            <label for="pabellon_destino">Pabellón de Destino:</label>
            <select name="pabellon_destino" id="pabellon_destino" class="form-control" required>
                <?php
                // Consulta SQL para obtener los pabellones
                $sql = "SELECT id, nombre FROM pabellones";
                $result = $conn->query($sql);

                // Generar opciones para cada pabellón
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <!-- Resto del formulario -->
        <div class="form-group">
            <label for="celda_destino">Celda de Destino:</label>
            <select name="celda_destino" id="celda_destino" class="form-control" required>
<?php
             // Generar opciones para las celdas de destino (del 1 al 12)
             for ($i = 1; $i <= 12; $i++) {
                 echo "<option value='" . $i . "'>" . $i . "</option>";
             }
             ?>
</select>
</div>
<!-- Resto del formulario -->
<button type="submit" class="btn btn-primary">Mover Interno</button>
</form>
<!-- Mensaje de confirmación -->
<div id="mensaje-confirmacion" class="alert alert-success" role="alert">
<!-- El mensaje de confirmación se mostrará aquí -->
</div>

</div>
<!-- Botón de Volver a Inicio -->
<div class="text-center">
    <a href="index.php" class="btn btn-primary mt-3 no-print">Volver a Inicio</a>
</div>
</body>
</html>
