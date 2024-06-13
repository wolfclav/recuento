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
            max-width: 500px; /* Reducción del ancho */
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
        
        <!-- Formulario de Inicio de Sesión -->
        <h2 class="text-center">Iniciar Sesión</h2>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger" role="alert">
                Nombre de usuario o contraseña incorrectos.
            </div>
        <?php endif; ?>
        <form action="authenticate.php" method="post">
            <div class="form-group">
                <label for="login-username">Nombre de Usuario:</label>
                <input type="text" class="form-control" id="login-username" name="username" required>
            </div>
            <div class="form-group">
                <label for="login-password">Contraseña:</label>
                <input type="password" class="form-control" id="login-password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>
