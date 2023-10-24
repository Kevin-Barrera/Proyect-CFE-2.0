<?php

function conectar(){
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
    return $conexion;
}

function obtenerDatosDelProyecto($id_proyecto) {
    $conexion = conectar();

    // Preparar y ejecutar una consulta SQL para obtener los datos del proyecto
    $sql = "SELECT idProyecto, nomProyecto, descProyecto, rutaArc1, rutaArc2, rutaArc3 FROM proyecto WHERE idProyecto = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_proyecto);
    $stmt->execute();

    // Obtener los resultados
    $result = $stmt->get_result();
    $datos_proyecto = $result->fetch_assoc();

    // Cerrar la conexión y retornar los datos del proyecto
    $stmt->close();
    $conexion->close();
    return $datos_proyecto;
}

// Función para actualizar el nombre del archivo asociado a un proyecto
function actualizarNombreArchivoProyecto($id_proyecto, $nuevoNombreArchivo) {
    $conexion = conectar();

    // Preparar la consulta SQL para actualizar el nombre del archivo
    $sql = "UPDATE proyecto SET rutaArc1 = ? WHERE idProyecto = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $nuevoNombreArchivo, $id_proyecto);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // El nombre del archivo se ha actualizado con éxito en la base de datos
    } else {
        echo "Error al actualizar el nombre del archivo del proyecto: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conexion->close();
}

function guardarCambiosProyecto($id_proyecto, $nombreProyecto, $descripcionProyecto) {
    $conexion = conectar();

    // Preparar la consulta SQL para actualizar los datos del proyecto
    $sql = "UPDATE proyecto SET nomProyecto = ?, descProyecto = ? WHERE idProyecto = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssi", $nombreProyecto, $descripcionProyecto, $id_proyecto);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Los datos se han actualizado con éxito
    } else {
        echo "Error al actualizar datos del proyecto: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conexion->close();
}

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
        header("Location: ../Interfaces/index.php?success=true");
    } else {
        echo "Error al mover el archivo a la carpeta de destino.";
    }
}

// Función para insertar un proyecto en la base de datos
function insertarProyecto($nombreProyecto, $descripcionProyecto, $archivoRuta) {
    $conexion = conectar();

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