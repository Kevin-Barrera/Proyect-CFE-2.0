<?php
if (isset($_FILES["archivo"])) {
    $archivo = $_FILES["archivo"];

    // Verifica si el archivo es una hoja de cálculo Excel
    $fileType = pathinfo($archivo["name"], PATHINFO_EXTENSION);
    if ($fileType !== "xlsx") {
        die("Error: El archivo debe ser un archivo Excel en formato .xlsx");
    }

    // Directorio de destino (asegúrate de que exista)
    $directorio_destino = "Archivos/"; // Ruta relativa, puedes ajustarla según tu estructura de archivos
    if (!file_exists($directorio_destino)) {
        mkdir($directorio_destino, 0777, true);
    }

    // Ruta completa de destino
    $ruta_destino = $directorio_destino . $archivo["name"];
    
    if (move_uploaded_file($archivo["tmp_name"], $ruta_destino)) {
        echo "El archivo se ha subido correctamente.";
    } else {
        echo "Error al subir el archivo.";
    }
}
?>
