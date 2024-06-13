<?php
require_once 'dompdf/autoload.inc.php';

function convertirImagenABase64($ruta) {
    $imagen = file_get_contents($ruta);
    return base64_encode($imagen);
}

function generarPDF() {
    // Configurar la zona horaria
    date_default_timezone_set('America/Argentina/Buenos_Aires'); // Cambiar según tu zona horaria

    // Conexión a la base de datos
    include 'db.php';

    // Crea una instancia de Dompdf
    $dompdf = new Dompdf\Dompdf();

    // Obtener la fecha y hora actual
    $fechaHoraActual = date("d/m/Y H:i:s");

    // Convertir la imagen del logo a base64
    $logoBase64 = convertirImagenABase64('assets/logo-unidad1.png'); // Cambia 'ruta/a/tu/logo.png' por la ruta correcta

    // Generar contenido HTML dinámicamente
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <title>Recuento de Internos</title>
        <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' rel='stylesheet'>
        <style>
            body {
                padding-top: 20px;
                color: black; /* Cambiar a negro para el PDF */
                background-color: white; /* Cambiar a blanco para el PDF */
                font-size: 12px; /* Tamaño de fuente general */
            }
            .container {
                max-width: 800px; /* Reducción del ancho máximo de la tabla */
            }
            a {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
            .encabezado {
                display: flex;
                align-items: center;
                justify-content: center; /* Alineación central de los elementos */
                flex-direction: column; /* Dirección de los elementos (columna para vertical) */
                margin-bottom: 20px;
                padding-bottom: 10px;
                text-align: center; /* Alineación central del texto */
            }
            .logo {
                max-width: 80px; /* Reducción del tamaño del logo */
                height: auto;
            }
            .logo-texto {
                margin-top: 10px; /* Espacio adicional arriba del texto */
                font-size: 10px; /* Tamaño de fuente para el texto del encabezado */
                line-height: 1.2;
            }
            .unidad {
                font-size: 12px; /* Tamaño de fuente para la unidad */
                font-weight: bold;
            }
            .condenados {
                font-size: 10px; /* Tamaño de fuente para las líneas de texto */
            }
            .fecha-hora {
                font-size: 10px; /* Tamaño de fuente para la fecha y hora */
            }
            .table-internos {
                margin-bottom: 20px;
                width: 100%; /* Ancho completo de la tabla */
            }
            .table-internos th,
            .table-internos td {
                text-align: center;
                padding: 6px; /* Espaciado interno reducido */
            }
            .table-internos tbody tr:nth-child(even) {
                background-color: #f2f2f2; /* Sombreado de filas alternadas */
            }
        </style>
    </head>
    <body>
    <div class='encabezado'>
        <div>
            <img src='data:image/png;base64,<?php echo $logoBase64; ?>' class='logo' alt='Logo'>
        </div>
        <div class='logo-texto'>
            <div class='unidad'>Unidad I</div>
            <div class='condenados'>Condenados Mayores</div>
            <div class='condenados'>Servicio Penitenciario Provincial</div>
            <div class='condenados'>Provincia de San Luis</div>
            <div class='fecha-hora'><?php echo $fechaHoraActual; ?></div>
        </div>
    </div>
    <div class='container'>
        <h1 style='font-size: 16px; text-align: center;'>Recuento de Internos</h1>
        <h2 style='font-size: 14px;'>Internos en Pabellones</h2>
        <table class='table table-hover table-internos'>
            <thead>
                <tr>
                    <th style='width: 50%;'>Pabellón</th>
                    <th style='width: 50%;'>Total Internos</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Código PHP para generar la tabla de pabellones
            $sql = "SELECT p.id, p.nombre AS pabellon, COUNT(i.id) AS total_internos
                    FROM pabellones p
                    LEFT JOIN internos i ON p.id = i.pabellon_id
                    WHERE i.area_id IS NULL
                    GROUP BY p.id";
            $result = $conn->query($sql);
            $total_internos_pabellones = 0;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row['pabellon'] . "</td><td>" . $row['total_internos'] . "</td></tr>";
                    $total_internos_pabellones += $row['total_internos'];
                }
            }
            ?>
            </tbody>
        </table>
        <h2 style='font-size: 14px;'>Internos en Áreas</h2>
        <table class='table table-hover table-internos'>
            <thead>
                <tr>
                    <th style='width: 33.33%;'>Área</th>
                    <th style='width: 33.33%;'>Internos</th>
                    <th style='width: 33.33%;'>Total Internos</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Código PHP para generar la tabla de áreas
            $sql = "SELECT a.id, a.nombre AS area, COUNT(i.id) AS total_internos
                    FROM areas a
                    LEFT JOIN internos i ON a.id = i.area_id
                    GROUP BY a.id
                    ORDER BY a.nombre ASC";
            $result = $conn->query($sql);
            $total_internos_areas = 0;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row['area'] . "</td>";
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
                    echo "</td><td>" . $row['total_internos'] . "</td></tr>";
                    $total_internos_areas += $row['total_internos'];
                }
            }
            ?>
            </tbody>
        </table>
        <h2 style='font-size: 14px;'>Total de Internos en la Unidad</h2>
        <p style='font-size: 12px;'>Total de internos en pabellones: <?php echo $total_internos_pabellones; ?></p>
        <p style='font-size: 12px;'>Total de internos en áreas: <?php echo $total_internos_areas; ?></p>
        <p style='font-size: 12px;'>Total de internos en la unidad: <?php echo $total_internos_pabellones + $total_internos_areas; ?></p>
    </div>
    </body>
    </html>
    <?php
    $html = ob_get_clean();
    $conn->close();

    // Carga el contenido HTML en Dompdf
    $dompdf->loadHtml($html);

    // Renderiza el PDF
    $dompdf->render();

    // Envía el PDF al navegador para que se pueda descargar
    $dompdf->stream("recuento_internos.pdf");
    exit(); // Detiene la ejecución del script después de enviar el PDF
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    generarPDF();
}
?>
