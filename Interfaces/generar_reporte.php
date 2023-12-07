<?php
echo "Generar Reporte PHP: Inicio <br>";
echo '<script>console.log("popo")</script>';
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
        // Ruta del archivo original
        $nombreArchivoOriginal = "../Archivos/REPORTE FINAL.xlsx";
    
        // Nombre del nuevo archivo
        $nombreNuevo = "Reporte Final - " . $nomProyecto;
    
        // Id del archivo
        $idArc = $idArchivo2;
    
        // Ruta del nuevo archivo
        $nombreArchivoNuevo = "../Archivos/" . $idArc . ".xlsx";
    
        // Copiar el archivo original al nuevo
        if (copy($nombreArchivoOriginal, $nombreArchivoNuevo)) {
            echo "Éxito al copiar el archivo. <br>";
    
            // Utilizar PhpSpreadsheet para cargar y modificar el archivo
            require '../vendor/autoload.php'; // Asegúrate de incluir el archivo de autoload de Composer
    
            // Cargar el archivo con PhpSpreadsheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($nombreArchivoNuevo);
    
            // Obtener la hoja activa
            $worksheet = $spreadsheet->getActiveSheet();
    
            // Obtener la fecha actual en el formato dd/mm/aaaa
            $fechaActual = date('d/m/Y');

            $sql = "SELECT idArchivo1, zona, obra FROM proyecto WHERE idProyecto = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $idProyecto);
            $stmt->execute();
            $stmt->bind_result($idArchivo1, $zona, $obra);
            $stmt->fetch();
            $stmt->close();
    
            // Establecer la fecha actual en la celda F5
            $worksheet->setCellValue('F5', $fechaActual);
            $worksheet->setCellValue('C5', $zona);
            $worksheet->setCellValue('C6', $obra);
            $worksheet->setCellValue('H3', "'[" . $idArchivo1 . ".xlsx]");
    
            // Guardar el archivo actualizado
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($nombreArchivoNuevo);
    
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
