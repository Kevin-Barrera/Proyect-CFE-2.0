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

if (isset($_POST['crearProyecto'])) {
    $nombreProyecto = $_POST['nombre'];
    $descripcionProyecto = $_POST['descripcion'];
    $nombreArchivo1 = $_FILES['archivo1']['name'];
    $archivoTemporal1 = $_FILES['archivo1']['tmp_name'];
    $nombreArchivo2 = $_FILES['archivo2']['name'];
    $archivoTemporal2 = $_FILES['archivo2']['tmp_name'];
    $nombreArchivo3 = $_FILES['archivo3']['name'];
    $archivoTemporal3 = $_FILES['archivo3']['tmp_name'];

    // Llamada a la función insertarProyecto con los datos capturados
    $respuesta = insertarProyecto($nombreProyecto, $descripcionProyecto, $nombreArchivo1, $archivoTemporal1, $nombreArchivo2, $archivoTemporal2, $nombreArchivo3, $archivoTemporal3);    

    // Mover el archivo a la carpeta de destino
    if ($respuesta) {
        echo "<script>alert('El archivo se ha guardado correctamente.');</script>";
        header("Location: ../Interfaces/index.php?success=true");
    } else {
        echo "Error al mover el archivo a la carpeta de destino.";
    }
}

// Función para insertar un proyecto en la base de datos
function insertarProyecto($nombreProyecto, $descripcionProyecto, $nombreArchivo1, $archivoTemporal1, $nombreArchivo2, $archivoTemporal2, $nombreArchivo3, $archivoTemporal3) {
    $conexion = conectar();
    $carpetaDestino = '../Archivos/';

    // Generar identificadores únicos para los archivos
    $idArchivo1 = generarIdUnico($conexion);
    $idArchivo2 = generarIdUnico($conexion);
    $idArchivo3 = generarIdUnico($conexion);

    // Preparar la consulta SQL para la inserción
    $sql = "INSERT INTO proyecto (nomProyecto, descProyecto, idArchivo1, rutaArc1, idArchivo2, rutaArc2, idArchivo3, rutaArc3) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    $stmt->bind_param("ssssssss", $nombreProyecto, $descripcionProyecto, $idArchivo1, $nombreArchivo1, $idArchivo2, $nombreArchivo2, $idArchivo3, $nombreArchivo3);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('Datos insertados con éxito.');</script>";
    } else {
        echo "Error al insertar datos: " . $stmt->error;
    }

    if (move_uploaded_file($archivoTemporal1, $carpetaDestino . $idArchivo1) &&
        move_uploaded_file($archivoTemporal2, $carpetaDestino . $idArchivo2) &&
        move_uploaded_file($archivoTemporal3, $carpetaDestino . $idArchivo3)) {
        $stmt->close();
        $conexion->close();
        return true;
    } else {
        $stmt->close();
        $conexion->close();
        return false;
    }

    return true;
}

// Función para generar un ID único y comprobar su existencia en la base de datos
function generarIdUnico($conexion) {
    $idUnico = uniqid('archivo_');

    // Consultar si el ID generado ya existe en la base de datos
    $sql = "SELECT COUNT(*) AS count FROM proyecto WHERE idArchivo1 = ? OR idArchivo2 = ? OR idArchivo3 = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $idUnico, $idUnico, $idUnico);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    // Verificar la existencia del ID en la base de datos y generar uno nuevo si es necesario
    if ($data['count'] > 0) {
        $idUnico = generarIdUnico($conexion); // Generar uno nuevo si ya existe
    }

    return $idUnico;
}

?>