<?php
session_start();

// Verificar si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit; // Asegura que el script se detenga aquí para que la redirección se procese correctamente
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'config.php'; // Archivo que contiene la conexión a la base de datos

    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $registration_error = "Las contraseñas no coinciden.";
    } else {
        // Crear un hash seguro de la contraseña
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Crear una conexión a la base de datos
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Definir el rol por defecto
        $default_role = 'usuario';

        // Insertar el nuevo usuario en la base de datos con el rol por defecto
        $sql = "INSERT INTO usuarios (username, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $hashed_password, $default_role);

        if ($stmt->execute()) {
            $registration_success = true;
        } else {
            $registration_error = $conn->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" href="iconounidad.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
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
        <h1 class="text-center">Registro de Usuario</h1>

        <?php if (isset($registration_success) && $registration_success): ?>
            <div class="alert alert-success" role="alert">
                Registro exitoso! El usuario <?php echo htmlspecialchars($username); ?> ha sido registrado.
            </div>
        <?php elseif (isset($registration_error)): ?>
            <div class="alert alert-danger" role="alert">
                Error al registrar el usuario: <?php echo htmlspecialchars($registration_error); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="register-username">Nombre de Usuario:</label>
                <input type="text" class="form-control" id="register-username" name="username" required>
            </div>
            <div class="form-group">
                <label for="register-password">Contraseña:</label>
                <input type="password" class="form-control" id="register-password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirmar Contraseña:</label>
                <input type="password" class="form-control" id="confirm-password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>

        <div class="text-center mt-3">
            <a href="index.php" class="btn btn-primary">Volver a Inicio</a>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
