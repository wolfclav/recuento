<?php
session_start();

// Verificar si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit; // Asegura que el script se detenga aquí para que la redirección se procese correctamente
}

// Verificar el rol del usuario
if (isset($_SESSION['username'])) {
    // Establecer la conexión con la base de datos
    include 'db.php'; // Asegúrate de que este archivo contenga la conexión a tu base de datos

    $username = $_SESSION['username'];
    $sql = "SELECT role FROM usuarios WHERE username = ?";
    
    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener el resultado
    $stmt->bind_result($role);
    $stmt->fetch();
    
    // Cerrar la consulta y la conexión
    $stmt->close();
    $conn->close();
    
    // Verificar si el rol es de administrador
    $esAdmin = ($role === 'administrador');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" href="iconounidad.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
        }
        .container {
            max-width: 800px;
        }
        .text-center {
            color: white;
        }
        .list-group-item {
            background-color: transparent;
            border: none;
            text-align: center;
        }
        .list-group-item a {
            color: white;
            font-weight: bold;
            text-decoration: none;
        }
        .list-group-item a:hover {
            text-decoration: underline;
        }
        .encabezado {
            padding: 10px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .encabezado .logo-texto .unidad, 
        .encabezado .logo-texto .condenados, 
        .encabezado .encabezado-texto {
            color: white;
        }
        .btn-cerrar-sesion {
            margin-left: auto; /* Establece el margen izquierdo automático para mover el botón al extremo derecho */
        }
        .btn-registrar-usuario {
            margin-left: 10px;
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
        <h1 class="text-center">Sistema de Recuento de Internos</h1>
        
        <?php if (isset($_SESSION['username'])): ?>
        <div class="text-center">
            <p class="text-center">Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            <a href="logout.php" class="btn btn-danger btn-sm btn-cerrar-sesion">Cerrar Sesión</a>
            <?php if ($esAdmin): ?>
                <a href="registro_usuario.php" class="btn btn-primary btn-sm btn-registrar-usuario">Registro de Usuario</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['username'])): ?>
            <ul class="list-group">
                <li class="list-group-item"><a href="recuento.php">Ver Recuento de Internos por Pabellón</a></li>
                <li class="list-group-item"><a href="movimiento_interno.php">Registrar Movimiento de Internos a otras Áreas</a></li>
                <li class="list-group-item"><a href="cargar_interno.php">Cargar/Eliminar Interno</a></li>
                <li class="list-group-item"><a href="cargar_pabellon.php">Cargar/Eliminar Pabellón</a></li>
                <li class="list-group-item"><a href="cargar_area.php">Cargar/Eliminar Área</a></li>
                <li class="list-group-item"><a href="mover_interno.php">Mover Interno de Pabellón o Celda</a></li>
            </ul>
        <?php endif; ?>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
