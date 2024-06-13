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
    <title>Cambio de Interno</title>
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
        label {
            font-weight: bold;
        }
        select, input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        #sugerencias {
            position: absolute;
            width: 100%;
            background-color: #fff;
            border: 1px solid #ddd;
            border-top: none;
            display: none;
        }
        .sugerencia {
            padding: 10px;
            cursor: pointer;
        }
        .sugerencia:hover {
            background-color: #f0f0f0;
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
        <h2 class="text-center">Seleccionar Interno y Nuevo Pabellón</h2>
        <form id="form-cambio" action="procesar_cambio.php" method="POST">
            <label for="interno">Interno:</label>
            <input type="text" name="interno" id="interno" placeholder="Escribe el nombre del interno">
            <div id="sugerencias"></div>
            <label for="pabellon">Nuevo Pabellón:</label>
            <select name="pabellon" id="pabellon">
                <?php
                // Conexión a la base de datos
                include 'db.php';

                // Consulta SQL para obtener los pabellones
                $sql = "SELECT id, nombre FROM pabellones";
                $result = $conn->query($sql);

                // Generar opciones para cada pabellón
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
                    }
                }

                // Cerrar la conexión a la base de datos
                $conn->close();
                ?>
            </select>
            <input type="submit" value="Cambiar Interno">
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#interno').keyup(function(){
                var query = $(this).val();
                console.log("Query: " + query); // Agregamos esta línea para depurar
                if(query != ''){
                    $.ajax({
                        url:"buscar_internos.php",
                        method:"POST",
                        data:{query:query},
                        success:function(data){
                            console.log(data); // Agregamos esta línea para depurar
                            $('#sugerencias').fadeIn();
                            $('#sugerencias').html(data);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
            $(document).on('click', '.sugerencia', function(){
                $('#interno').val($(this).text());
                $('#sugerencias').fadeOut();
            });
            $('#form-cambio').submit(function(e){
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    success: function(data){
                        // Hacer algo con la respuesta del servidor si es necesario
                    }
                });
            });
        });
    </script>
</body>
</html>
