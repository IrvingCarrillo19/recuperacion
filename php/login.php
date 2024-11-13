<?php
session_start();  // Inicia la sesión

include('conexion.php');

// Verifica si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Asegurarse de que los datos estén presentes
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        // Consulta para verificar el usuario y la contraseña
        $query = "SELECT * FROM usuarios WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Verifica si la contraseña es correcta
            if (password_verify($password, $user['password'])) {
                $_SESSION['id_usuario'] = $user['id'];  // Guarda el ID en la sesión
                header('Location: index.php');  // Redirige a la página principal
                exit();
            } else {
                $error_message = "Contraseña incorrecta.";
            }
        } else {
            $error_message = "El usuario no existe.";
        }
    } else {
        $error_message = "Todos los campos son requeridos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Vincula el archivo CSS -->
</head>
<body style="background-color: #222B31; color: white; font-family: Arial, sans-serif;">

    <div class="login-container" style="max-width: 400px; margin: 0 auto; padding: 40px; background-color: #440101; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);">
        <h1 style="text-align: center; color: #E22227;">Iniciar Sesión</h1>

        <!-- Mostrar mensajes de error si existen -->
        <?php if (isset($error_message)): ?>
            <p class="error-message" style="color: #E22227; text-align: center;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php" style="display: flex; flex-direction: column;">
            <label for="email" style="color: white;">Email:</label>
            <input type="email" id="email" name="email" required style="padding: 10px; margin-bottom: 15px; border: 1px solid #C7080C; border-radius: 5px; background-color: #222B31; color: white;">

            <label for="password" style="color: white;">Contraseña:</label>
            <input type="password" id="password" name="password" required style="padding: 10px; margin-bottom: 20px; border: 1px solid #C7080C; border-radius: 5px; background-color: #222B31; color: white;">

            <button type="submit" style="padding: 10px; background-color: #E22227; color: white; border: none; border-radius: 5px; cursor: pointer;">Iniciar sesión</button>
        </form>
    </div>

</body>
</html>
