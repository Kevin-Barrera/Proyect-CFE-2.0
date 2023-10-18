<?php

if (isset($_POST['subirArchivo'])) {
    $nombreProyecto = $_POST['nombre'];
    $descripcionProyecto = $_POST['descripcion'];
    $archivoRuta = $_FILES['archivo']['name'];
    $archivoTemporal = $_FILES['archivo']['tmp_name'];

    // Llamada a la función insertarProyecto con los datos capturados
    insertarProyecto($nombreProyecto, $descripcionProyecto, $archivoRuta);

    // Ruta de la carpeta donde se guardarán los archivos subidos
    $carpetaDestino = '../Archivos/';

    // Mover el archivo a la carpeta de destino
    if (move_uploaded_file($archivoTemporal, $carpetaDestino . $archivoRuta)) {
        echo "<script>alert('El archivo se ha guardado correctamente.');</script>";
    } else {
        echo "Error al mover el archivo a la carpeta de destino.";
    }
}

// Función para insertar un proyecto en la base de datos
function insertarProyecto($nombreProyecto, $descripcionProyecto, $archivoRuta) {
    $servername = "localhost"; // Nombre del servidor MySQL
    $username = "root";     // Nombre de usuario de la base de datos
    $password = "";        // Contraseña de la base de datos
    $database = "cfe"; // Nombre de la base de datos

    // Crear una conexión
    $conexion = new mysqli($servername, $username, $password, $database);

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Preparar la consulta SQL para la inserción
    $sql = "INSERT INTO proyecto (nomProyecto, descProyecto, rutaArc1, rutaArc2, rutaArc3) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $rutaArc2 = "";
    $rutaArc3 = "";
    $stmt->bind_param("sssss", $nombreProyecto, $descripcionProyecto, $archivoRuta, $rutaArc2, $rutaArc3);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('Datos insertados con éxito.');</script>";
    } else {
        echo "Error al insertar datos: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conexion->close();
}
?>
