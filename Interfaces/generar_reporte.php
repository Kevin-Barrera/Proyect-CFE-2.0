<?php
echo "Generar Reporte PHP: Inicio <br>";

if (isset($_GET['idProyecto'])) {
    $servername = "localhost"; // Nombre del servidor MySQL
    $username = "root";     // Nombre de usuario de la base de datos
    $password = "";        // Contraseña de la base de datos
    $database = "cfe"; // Nombre de la base de datos

    // Crear una conexión
    $conexion = new mysqli($servername, $username, $password, $database);
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }
    
    $idProyecto = $_GET['idProyecto'];

    // Obtener el nombre del archivo original del campo idArchivo2 del proyecto
    $sql = "SELECT idArchivo2, nomProyecto FROM proyecto WHERE idProyecto = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idProyecto);
    $stmt->execute();
    $stmt->bind_result($idArchivo2, $nomProyecto);
    $stmt->fetch();
    $stmt->close();

    if ($idArchivo2) {
        $nombreArchivoOriginal = "../Archivos/REPORTE FINAL.xlsx";  // Cambia esto según tu estructura de archivos
        $nombreNuevo = "Reporte Final - " . $nomProyecto;
        $nombreArchivoNuevo = "../Archivos/" . $nombreNuevo . ".xlsx";

        // Copiar el archivo
        if (copy($nombreArchivoOriginal, $nombreArchivoNuevo)) {
            echo "Éxito al copiar el archivo. <br>";

            // Actualizar el campo rutaArc2 en la base de datos
            $sqlUpdate = "UPDATE proyecto SET rutaArc2 = ? WHERE idProyecto = ?";
            $stmtUpdate = $conexion->prepare($sqlUpdate);
            $stmtUpdate->bind_param("si", $nombreNuevo, $idProyecto);

            if ($stmtUpdate->execute()) {
                echo "Campo rutaArc2 actualizado con éxito. <br>";
            } else {
                echo "Error al actualizar el campo rutaArc2: " . $stmtUpdate->error . "<br>";
            }

            $stmtUpdate->close();
        } else {
            echo "Error al copiar el archivo. <br>";
        }
    } else {
        echo "El proyecto no tiene asignado un idArchivo2. <br>";
    }
} else {
    echo "ID de proyecto no proporcionado. <br>";
}
?>
