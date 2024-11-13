<?php
error_reporting(E_ALL);  // Reporta todos los errores
ini_set('display_errors', 1);  // Muestra los errores en pantalla
session_start();  // Inicia la sesión

include('conexion.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="css/styles.css">  <!-- Asegúrate de que la ruta al CSS sea correcta -->
</head>
<body>

    <!-- Navbar -->
    <nav>
        <div class="navbar-container">
            <h1 class="logo">Tienda de Novelas Ligeras</h1>
            <div class="navbar-right">
                <?php if (isset($_SESSION['id_usuario'])): ?>
                    <a href="index.php">Novelas</a>
                    <a href="carrito.php">Mi Carrito</a>
                    <a href="logout.php">Cerrar Sesión</a>
                <?php else: ?>
                    <a href="login.php">Iniciar Sesión</a>
                    <a href="registro.php">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Formulario de Registro -->
    <h1>Registrarse</h1>
    <form action="procesar_registro.php" method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Registrar</button>
    </form>

</body>
</html>

