<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "cfe";

$conexion = new mysqli($servername, $username, $password, $database);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if (isset($_FILES['imagen'])) {
    $imagen_nombre = $_FILES['imagen']['name'];
    $imagen_temporal = $_FILES['imagen']['tmp_name'];

    $ruta_destino = "login/img2/" . $imagen_nombre;

    // Mueve la imagen al directorio deseado
    move_uploaded_file($imagen_temporal, $ruta_destino);

    // Actualiza la base de datos con la ruta de la imagen
    if (isset($_GET['idTrabajador'])) {
        $idTrabajador = $_GET['idTrabajador'];
        $sql_update = "UPDATE trabajador SET imagen_perfil = '$ruta_destino' WHERE idTrabajador = $idTrabajador";
        $conexion->query($sql_update);
        // Redirige a la página deseada
        header("Location: http://localhost/Proyect-CFE-2.0/Interfaces/editarUsuario.php?idTrabajador=" . $idTrabajador);
        exit(); // Asegura que no se ejecuten más instrucciones después de la redirección
    }
}

$conexion->close();
?>