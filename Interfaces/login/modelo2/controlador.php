<?php
if (!empty($_POST["btningresar"])) {
    if (empty($_POST["usuario"]) || empty($_POST["password"])) {
        echo '<div class="alert alert-danger">LOS CAMPOS ESTAN VACIOS</div>';
    } else {
        $usuario = $_POST["usuario"];
        $clave = $_POST["password"];
        
        // Conexión a la base de datos (asegúrate de tener esta parte en tu código)
        $conexion = new mysqli("localhost", "root", "", "cfe", 3306);

        // Verifica la conexión
        if ($conexion->connect_error) {
            die("La conexión falló: " . $conexion->connect_error);
        }

        $sql = "SELECT idTrabajador FROM trabajador WHERE usuario='$usuario' AND contra='$clave'";
        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {
            // Obtiene el ID del trabajador
            $fila = $resultado->fetch_assoc();
            $idTrabajador = $fila['idTrabajador'];

            // Redirige a inicio.php con la ID del trabajador en la URL
            header("location: inicio.php?idTrabajador=$idTrabajador");
        } else {
            echo '<div class="alert alert-danger">ACCESO DENEGADO</div>';
        }

        // Cierra la conexión
        $conexion->close();
    }
}
?>