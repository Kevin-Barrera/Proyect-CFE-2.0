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
    $sql = "SELECT idProyecto, nomProyecto, descProyecto, rutaArc1, rutaArc2 FROM proyecto WHERE idProyecto = ?";
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

    // Llamada a la función insertarProyecto con los datos capturados
    $respuesta = insertarProyecto($nombreProyecto, $descripcionProyecto, $nombreArchivo1, $archivoTemporal1, $nombreArchivo2, $archivoTemporal2);    

    // Mover el archivo a la carpeta de destino
    if ($respuesta) {
        echo "<script>alert('El archivo se ha guardado correctamente.');</script>";
        header("Location: ../Interfaces/index.php?success=true");
    } else {
        echo "Error al mover el archivo a la carpeta de destino.";
    }
}

// Función para insertar un proyecto en la base de datos
function insertarProyecto($nombreProyecto, $descripcionProyecto, $nombreArchivo1, $archivoTemporal1, $nombreArchivo2, $archivoTemporal2) {
    $conexion = conectar();
    $carpetaDestino = '../Archivos/';

    // Generar identificadores únicos para los archivos
    $idArchivo1 = generarIdUnico($conexion);
    $idArchivo2 = generarIdUnico($conexion);

    // Obtener extensiones de los archivos originales
    $extensionArchivo1 = pathinfo($nombreArchivo1, PATHINFO_EXTENSION);
    $extensionArchivo2 = pathinfo($nombreArchivo2, PATHINFO_EXTENSION);

    // Preparar la consulta SQL para la inserción
    $sql = "INSERT INTO proyecto (nomProyecto, descProyecto, idArchivo1, rutaArc1, idArchivo2, rutaArc2) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    $stmt->bind_param("ssssss", $nombreProyecto, $descripcionProyecto, $idArchivo1, $nombreArchivo1, $idArchivo2, $nombreArchivo2);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Datos insertados con éxito, ahora movemos los archivos

        // Mover el archivo 1 a la carpeta de destino con el nombre único y la extensión original
        $rutaArchivo1 = $carpetaDestino . $idArchivo1 . '.' . $extensionArchivo1;
        if (move_uploaded_file($archivoTemporal1, $rutaArchivo1)) {
            echo "Archivo 1 movido con éxito.<br>";
        } else {
            echo "Error al mover el archivo 1 a la carpeta de destino.<br>";
        }

        // Mover el archivo 2 a la carpeta de destino con el nombre único y la extensión original
        $rutaArchivo2 = $carpetaDestino . $idArchivo2 . '.' . $extensionArchivo2;
        if (move_uploaded_file($archivoTemporal2, $rutaArchivo2)) {
            echo "Archivo 2 movido con éxito.<br>";
        } else {
            echo "Error al mover el archivo 2 a la carpeta de destino.<br>";
        }

        $stmt->close();
        $conexion->close();
        return true;
    } else {
        echo "Error al insertar datos: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
    return false;
}

// Función para generar un ID único y comprobar su existencia en la base de datos
function generarIdUnico($conexion) {
    $idUnico = uniqid('archivo_');

    // Consultar si el ID generado ya existe en la base de datos
    $sql = "SELECT COUNT(*) AS count FROM proyecto WHERE idArchivo1 = ? OR idArchivo2 = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $idUnico, $idUnico);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    // Verificar la existencia del ID en la base de datos y generar uno nuevo si es necesario
    if ($data['count'] > 0) {
        $idUnico = generarIdUnico($conexion); // Generar uno nuevo si ya existe
    }

    return $idUnico;
}

// Función para obtener la ruta del archivo asociado a un proyecto
function obtenerRutaArchivoProyecto($id_proyecto, $numero_archivo) {
    $conexion = conectar();

    // Determinar el nombre del campo de rutaArc según el número de archivo
    $campo_ruta = "rutaArc" . $numero_archivo;

    // Preparar y ejecutar una consulta SQL para obtener la ruta del archivo
    $sql = "SELECT $campo_ruta FROM proyecto WHERE idProyecto = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_proyecto);
    $stmt->execute();

    // Obtener los resultados
    $result = $stmt->get_result();
    $datos_proyecto = $result->fetch_assoc();

    // Cerrar la conexión y retornar la ruta del archivo
    $stmt->close();
    $conexion->close();
    return $datos_proyecto[$campo_ruta];
}

function obtenerIdArchivoProyecto($id_proyecto, $numero_archivo) {
    $conexion = conectar();

    // Determinar el nombre del campo de rutaArc según el número de archivo
    $campo_ruta = "idArchivo" . $numero_archivo;

    // Preparar y ejecutar una consulta SQL para obtener la ruta del archivo
    $sql = "SELECT $campo_ruta FROM proyecto WHERE idProyecto = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_proyecto);
    $stmt->execute();

    // Obtener los resultados
    $result = $stmt->get_result();
    $datos_proyecto = $result->fetch_assoc();

    // Cerrar la conexión y retornar la ruta del archivo
    $stmt->close();
    $conexion->close();
    return $datos_proyecto[$campo_ruta];
}

// Función para actualizar la ruta del archivo asociado a un proyecto
function actualizarRutaArchivoProyecto($id_proyecto, $numero_archivo, $nueva_ruta_archivo) {
    $conexion = conectar();

    // Determinar el nombre del campo de rutaArc según el número de archivo
    $campo_ruta = "rutaArc" . $numero_archivo;

    // Preparar la consulta SQL para actualizar la ruta del archivo
    $sql = "UPDATE proyecto SET $campo_ruta = ? WHERE idProyecto = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $nueva_ruta_archivo, $id_proyecto);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // La ruta del archivo se ha actualizado con éxito en la base de datos
    } else {
        echo "Error al actualizar la ruta del archivo del proyecto: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conexion->close();
}

?>