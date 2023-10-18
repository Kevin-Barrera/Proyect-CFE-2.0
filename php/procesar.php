<?php

function importarArchivo(){
    $archivo = $_FILES["archivo"];

    // Verifica si el archivo es una hoja de cálculo Excel
    $fileType = pathinfo($archivo["name"], PATHINFO_EXTENSION);
    if ($fileType !== "xlsx") {
        die("Error: El archivo debe ser un archivo Excel en formato .xlsx");
    }

    // Directorio de destino (asegúrate de que exista)
    $directorio_destino = "../Archivos/"; // Ruta relativa, esta es la carpeta fuera de "php"

    if (!file_exists($directorio_destino)) {
        mkdir($directorio_destino, 0777, true);
    }

    // Ruta completa de destino
    $ruta_destino = $directorio_destino . $archivo["name"];

    if (move_uploaded_file($archivo["tmp_name"], $ruta_destino)) {
        // Redirige de vuelta a la página anterior
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit; // Asegura que el script termine después de la redirección
    } else {
        echo "Error al subir el archivo.";
    }
}

if (isset($_FILES["archivo"])) {
    importarArchivo();
}
?>