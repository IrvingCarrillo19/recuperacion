<?php
session_start();
include('../php/conexion.php');  // Asegúrate de incluir correctamente la conexión a la base de datos

// Consultar solo las novelas, agrupando por título para mostrar solo un volumen por novela
$query = "SELECT titulo, MIN(volumen) as volumen, precio, imagen, id FROM novelas GROUP BY titulo ORDER BY titulo"; 
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error en la consulta: ' . mysqli_error($conn));  // Si hay un error, lo muestra
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Novelas Ligeras</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="navbar-container">
            <h1 class="logo">Tienda de Novelas Ligeras</h1>
            <div class="navbar-right">
                <?php if (isset($_SESSION['id_usuario'])): ?>
                    <a href="index.php">Novelas</a>  <!-- Redirige al index.php -->
                    <a href="carrito.php">Mi Carrito</a>
                    <form action="cerrar_sesion.php" method="POST" style="display:inline;">
                        <button type="submit" name="cerrar_sesion">Cerrar Sesión</button>
                    </form>
                <?php else: ?>
                    <a href="login.php">Iniciar Sesión</a>
                    <a href="registro.php">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Contenido principal de la página -->
    <h2>Novelas Disponibles</h2>
    
    <div class="novelas-container">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="novela-card">
                    <!-- Verifica si la columna 'imagen' tiene un valor y ajusta la ruta -->
                    <?php 
                    $imagen = isset($row['imagen']) && !empty($row['imagen']) ? $row['imagen'] : 'imagen_por_defecto.jpg'; 
                    ?>
                    <img src="../imagen/<?php echo $imagen; ?>" alt="<?php echo htmlspecialchars($row['titulo']); ?>" class="novela-img">
                    <div class="novela-info">
                        <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                        <p>Volumen: <?php echo htmlspecialchars($row['volumen']); ?></p>
                        <p>Precio: <?php echo htmlspecialchars($row['precio']); ?> USD</p>
                        <a href="producto.php?id=<?php echo $row['id']; ?>" class="btn">Ver más</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay novelas disponibles.</p>
        <?php endif; ?>
    </div>
</body>
</html>




