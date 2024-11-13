<?php
include('conexion.php');
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    die('Error: Usuario no ha iniciado sesión.');
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener el ID de la novela desde la URL
if (isset($_GET['id'])) {
    $id_novela = $_GET['id'];
    
    // Realizar la consulta para obtener los detalles de la novela
    $query = "SELECT * FROM novelas WHERE id = $id_novela";
    $result = mysqli_query($conn, $query);
    
    if (!$result || mysqli_num_rows($result) == 0) {
        die("Error: Producto no encontrado.");
    }

    // Obtener la novela
    $novela = mysqli_fetch_assoc($result);
} else {
    die("Error: No se proporcionó el ID de la novela.");
}

// Procesar el formulario de agregar al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'comprar') {
    $cantidad = intval($_POST['cantidad']);
    
    // Verificar si el producto ya existe en el carrito
    $query_check = "SELECT * FROM carrito WHERE id_usuario = $id_usuario AND id_novela = $id_novela";
    $result_check = mysqli_query($conn, $query_check);

    if (mysqli_num_rows($result_check) > 0) {
        // Si el producto ya está en el carrito, actualizar la cantidad
        $query_insert = "UPDATE carrito SET cantidad = cantidad + $cantidad WHERE id_usuario = $id_usuario AND id_novela = $id_novela";
    } else {
        // Si el producto no está en el carrito, insertarlo
        $query_insert = "INSERT INTO carrito (id_usuario, id_novela, cantidad) VALUES ($id_usuario, $id_novela, $cantidad)";
    }

    // Ejecutar la consulta de inserción o actualización
    if (!mysqli_query($conn, $query_insert)) {
        die("Error al agregar el producto al carrito: " . mysqli_error($conn));
    }

    // Redirigir al carrito
    header("Location: carrito.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($novela['titulo']); ?></title>
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

    <h1><?php echo htmlspecialchars($novela['titulo']); ?></h1>

    <div class="novela-detalle-container">
        <?php 
        // Ajustamos la imagen por defecto si no hay imagen
        $imagen = isset($novela['imagen']) && !empty($novela['imagen']) ? $novela['imagen'] : 'imagen_por_defecto.jpg';
        ?>
        <div class="novela-imagen">
            <img src="../imagen/<?php echo $imagen; ?>" alt="<?php echo htmlspecialchars($novela['titulo']); ?>">
        </div>

        <div class="novela-info">
            <!-- Mostramos el total de volúmenes en lugar de uno solo -->
            <p>Total de volúmenes: <?php echo htmlspecialchars($novela['volumen']); ?></p>
            <p>Precio: <?php echo htmlspecialchars($novela['precio']); ?> USD</p>
            
            <!-- Descripción de la novela -->
            <h3>Descripción:</h3>
            <p><?php echo nl2br(htmlspecialchars($novela['descripcion'])); ?></p>
        </div>
    </div>

    <!-- Barra para seleccionar volúmenes y cantidad -->
    <div class="barra-compra">
        <h3>Selecciona los volúmenes que deseas comprar:</h3>
        <form action="producto.php?id=<?php echo $novela['id']; ?>" method="POST">
            <label for="cantidad">Cantidad por volumen:</label>
            <input type="number" id="cantidad" name="cantidad" min="1" max="100" value="1">
            <input type="hidden" name="accion" value="comprar">
            <button type="submit" class="btn">Agregar al carrito</button>
        </form>
    </div>

</body>
</html>

