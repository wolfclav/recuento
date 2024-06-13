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
    <title>Recuento de Internos</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
            color: white;
            background-color: #333;
        }
        .container {
            max-width: 1000px;
        }
        a {
            color: white;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <script>
        function mostrarDetallesPabellon(pabellonId) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("detalles").innerHTML = this.responseText;
                    scrollToDetails();
                }
            };
            xhttp.open("GET", "detalles_pabellon.php?pabellon_id=" + pabellonId, true);
            xhttp.send();
        }

        function mostrarDetallesArea(areaId) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("detalles").innerHTML = this.responseText;
                    scrollToDetails();
                }
            };
            xhttp.open("GET", "detalles_area.php?area_id=" + areaId, true);
            xhttp.send();
        }

        function scrollToDetails() {
            var detailsDiv = document.getElementById("detalles");
            detailsDiv.scrollIntoView({ behavior: "smooth" });
        }
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
    <h1 class="text-center">Recuento de Internos</h1>
    <?php
    include 'db.php';

    // Recuento por pabellón
    $sql = "SELECT p.id, p.nombre AS pabellon, COUNT(i.id) AS total_internos
            FROM pabellones p
            LEFT JOIN internos i ON p.id = i.pabellon_id
            WHERE i.area_id IS NULL
            GROUP BY p.id";
    $result = $conn->query($sql);
    $total_internos_pabellones = 0;

    if ($result->num_rows > 0) {
        echo "<h2>Internos en Pabellones</h2>";
        echo "<table class='table table-bordered table-hover'><thead><tr><th style='width: 50%; text-align: center;'>Pabellón</th><th style='width: 50%; text-align: center;'>Total Internos</th></tr></thead><tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td><a href='#' onclick='mostrarDetallesPabellon(" . $row['id'] . ")'>" . $row['pabellon'] . "</a></td><td>" . $row['total_internos'] . "</td></tr>";
            $total_internos_pabellones += $row['total_internos'];
        }
        echo "</tbody></table>";
    } else {
        echo "<div class='alert alert-info'>No hay datos disponibles.</div>";
    }

    // Recuento por área
    $sql = "SELECT a.id, a.nombre AS area, COUNT(i.id) AS total_internos
            FROM areas a
            LEFT JOIN internos i ON a.id = i.area_id
            GROUP BY a.id
            ORDER BY a.nombre ASC";

    $result = $conn->query($sql);
    $total_internos_areas = 0;

    if ($result->num_rows > 0) {
        echo "<h2>Internos en Áreas</h2>";
        echo "<table class='table table-bordered table-hover'><thead><tr><th style='width: 33.33%; text-align: center;'>Área</th><th style='width: 33.33%; text-align: center;'>Internos</<th style='width: 33.33%; text-align: center;'>Pabellón</th><th style='width: 33.33%; text-align: center;'>Total Internos</th></tr></thead><tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td><a href='#' onclick='mostrarDetallesArea(" . $row['id'] . ")'>" . $row['area'] . "</a></td>";

            // Obtener el nombre del pabellón al que pertenece cada interno en esta área
            $internos_sql = "SELECT i.nombre AS interno_nombre, p.nombre AS pabellon_nombre
                             FROM internos i
                             INNER JOIN pabellones p ON i.pabellon_id = p.id
                             WHERE i.area_id = " . $row['id'];
            $internos_result = $conn->query($internos_sql);

            echo "<td>";
            if ($internos_result->num_rows > 0) {
                while ($interno_row = $internos_result->fetch_assoc()) {
                    echo $interno_row['interno_nombre'] . " (" . $interno_row['pabellon_nombre'] . ")<br>";
                }
            } else {
                echo "No hay internos en esta área.";
            }
            echo "</td>";
            echo "<td>" . $row['total_internos'] . "</td></tr>";
            $total_internos_areas += $row['total_internos'];
        }
        echo "</tbody></table>";
    } else {
        echo "<div class='alert alert-info'>No hay datos disponibles.</div>";
    }
    $conn->close();
    ?>
    <h2>Total de Internos en la Unidad</h2>
    <p>Total de internos en pabellones: <?php echo $total_internos_pabellones; ?></p>
    <p>Total de internos en áreas: <?php echo $total_internos_areas; ?></p>
    <p>Total de internos en la unidad: <?php echo $total_internos_pabellones + $total_internos_areas; ?></p>
    <div id="detalles">
        <h2>Detalles</h2>
        <p>Selecciona un pabellón o un área para ver los detalles.</p>
    </div>
    <a href="index.php" class="btn btn-primary mt-3 no-print">Volver a Inicio</a>
    <a href="generar_pdf.php" class="btn btn-secondary mt-3 no-print">Generar PDF</a>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
