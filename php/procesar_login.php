<!-- procesar_login.php -->
<?php
$_SESSION['id_usuario'] = $user['id'];
error_reporting(E_ALL);  
ini_set('display_errors', 1);  

include('conexion.php');
session_start(); // Inicia la sesión al principio

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        // Consulta para buscar el usuario por email
        $query = "SELECT * FROM usuarios WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Verifica la contraseña
            if (password_verify($password, $user['password'])) {
                echo "Inicio de sesión exitoso.";
                
                // Almacena el ID del usuario en la sesión
                $_SESSION['id_usuario'] = $user['id'];  // Usa 'id_usuario' en lugar de 'user_id' para consistencia

                // Redirige al usuario a la página principal
                header('Location: index.php');  // Ajusta la ruta si es necesario
                exit();
            } else {
                echo "Contraseña incorrecta.";
            }
        } else {
            echo "El usuario no existe.";
        }
    } else {
        echo "Todos los campos son requeridos.";
    }
} else {
    echo "No se ha enviado ningún formulario.";
}

mysqli_close($conn); // Cierra la conexión a la base de datos
?>


