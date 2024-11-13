<?php
session_start();  // Iniciar la sesión para acceder a las variables de sesión

// Destruir la sesión
session_unset();  // Elimina todas las variables de sesión
session_destroy();  // Destruye la sesión

// Redirigir a la página de inicio (index.php)
header("Location: ../php/index.php");
exit();  // Asegurarse de que no se ejecute más código
?>
