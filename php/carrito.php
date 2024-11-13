<?php
// Configurar la duración de la sesión
ini_set('session.gc_maxlifetime', 3600);
ini_set('session.cookie_lifetime', 3600);

include('conexion.php');

// Iniciar la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    die('Error: Usuario no ha iniciado sesión.');
}

$id_usuario = $_SESSION['id_usuario'];

// Lógica para eliminar productos del carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $id_novela = intval($_POST['id_novela']);
    
    $query_eliminar = "DELETE FROM carrito WHERE id_usuario = $id_usuario AND id_novela = $id_novela";
    
    if (!mysqli_query($conn, $query_eliminar)) {
        die("Error al eliminar el producto del carrito: " . mysqli_error($conn));
    }
}

// Consultar los productos en el carrito
$query = "SELECT n.titulo, c.cantidad, n.precio, c.id_novela 
          FROM carrito c 
          INNER JOIN novelas n ON c.id_novela = n.id 
          WHERE c.id_usuario = $id_usuario";

$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error en la consulta: ' . mysqli_error($conn));
}

$carrito_vacio = mysqli_num_rows($result) == 0; // Verificar si el carrito está vacío

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <!-- Vincular el archivo CSS -->
    <link rel="stylesheet" href="../css/styles.css">
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

    <!-- Contenido del carrito -->
    <h1>Tu Carrito</h1>

    <!-- Mostrar productos en el carrito si no está vacío -->
    <?php if (!$carrito_vacio): ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="item">
                <h2><?php echo htmlspecialchars($row['titulo']); ?></h2>
                <p>Cantidad: <?php echo htmlspecialchars($row['cantidad']); ?></p>
                <p>Precio: <?php echo htmlspecialchars($row['precio']); ?> USD</p>

                <!-- Formulario para eliminar el producto del carrito -->
                <form action="carrito.php" method="POST">
                    <input type="hidden" name="id_novela" value="<?php echo htmlspecialchars($row['id_novela']); ?>">
                    <input type="hidden" name="accion" value="eliminar">
                    <button type="submit">Eliminar</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Tu carrito está vacío.</p>
    <?php endif; ?>
</body>
</html>





