<!-- procesar_registro.php -->
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    echo "Datos recibidos correctamente."; // Verifica si los datos están llegando

    $query = "INSERT INTO usuarios (nombre, email, password) VALUES ('$nombre', '$email', '$password')";

    if (mysqli_query($conn, $query)) {
        echo "Registro exitoso. <a href='login.php'>Inicia sesión aquí</a>";
    } else {
        echo "Error al registrar: " . mysqli_error($conn); // Mostrar error en la consulta
    }
} else {
    echo "No se ha enviado ningún formulario.";
}

?>
