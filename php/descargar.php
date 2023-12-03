<?php
// Verificar si se ha proporcionado un nombre de archivo
if (isset($_GET['archivo'])) {
    // Obtener el nombre del archivo
    $archivo = $_GET['archivo'];

    // Ruta al archivo
    $ruta = '../Archivos/' . $archivo;

    // Verificar si el archivo existe
    if (file_exists($ruta)) {
        // Configurar encabezados para la descarga
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $archivo . '"');
        header('Content-Length: ' . filesize($ruta));

        // Leer el archivo y enviarlo al navegador
        readfile($ruta);
        exit();
    }
}

// Si el archivo no existe, redireccionar a la página principal
header("Location: ../Interfaces/reportes.php");
exit();
?>
