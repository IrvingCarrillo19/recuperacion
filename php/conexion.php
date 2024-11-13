<?php
$conn = mysqli_connect('localhost', 'root', '', 'tienda_novelas');
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
