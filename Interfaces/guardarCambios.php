<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost"; // Nombre del servidor MySQL
    $username = "root";     // Nombre de usuario de la base de datos
    $password = "";        // Contraseña de la base de datos
    $database = "cfe"; // Nombre de la base de datos

    // Crear una conexión
    $conexion = new mysqli($servername, $username, $password, $database);
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    $idProyecto = $_POST["idProyecto"];
    $tipo = $_POST["tipo"];
    $celdas = $_POST["celda"];

    echo "ID del Proyecto: " . $idProyecto . "<br>";
    echo "Tipo: " . $tipo . "<br>";
    
    echo "Datos de las celdas: <pre>";
    print_r($celdas);
    echo "</pre>";
    
    // Verificar si la clave 'celda' está presente y es un array
    // $celdas = isset($_POST["celda"]) && is_array($_POST["celda"]) ? $_POST["celda"] : [];

    $sql = "SELECT idArchivo1, idArchivo2 FROM proyecto WHERE idProyecto = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idProyecto);
    $stmt->execute();
    $stmt->bind_result($idArchivo1, $idArchivo2);
    $stmt->fetch();
    $stmt->close();

    // Obtén la ruta del archivo XLSX desde el campo idArchivo
    $idArchivo = ($tipo == 1) ? $idArchivo1 : $idArchivo2;
    $rutaArchivo = "../Archivos/" . $idArchivo . ".xlsx";

    // Cargar el archivo Excel existente
    require '../vendor/autoload.php';
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($rutaArchivo);

    // Obtener la hoja activa
    $worksheet = $spreadsheet->getActiveSheet();

    // Aplicar los cambios a las celdas
    foreach ($celdas as $fila => $columnas) {
        foreach ($columnas as $columna => $valor) {
            $cellAddress = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columna + 1) . $fila + 1;
            $worksheet->setCellValue($cellAddress, $valor);
        }
    }

    // Guardar el archivo actualizado
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

    // Intentar guardar el archivo
    try {
        $writer->save($rutaArchivo);
        echo "Cambios aplicados y archivo guardado con éxito.";
    } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
        echo "Error al guardar el archivo: " . $e->getMessage();
    }
} else {
    echo "Solicitud no válida.";
}
?>
