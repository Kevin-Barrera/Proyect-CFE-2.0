<?php

echo '<script>console.log("popo1")</script>';
// Verificar si se ha proporcionado un nombre de archivo
if (isset($_GET['idArchivo'])) {
    echo '<script>console.log("popo3")</script>';
    // Obtener el nombre del archivo
    $idArchivo = $_GET['idArchivo'];

    // Ruta al archivo
    $ruta = '../Archivos/' . $idArchivo . '.xlsx';

    // Verificar si el archivo existe
    if (file_exists($ruta)) {
        // Configurar encabezados para la descarga
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $idArchivo . '.xlsx' . '"');
        header('Content-Length: ' . filesize($ruta));
        ob_clean();
        flush();
        
        // Leer el archivo y enviarlo al navegador
        readfile($ruta);
        exit();
    }
}
echo '<script>console.log("popo2")</script>';
// Si el archivo no existe, redireccionar a la página principal
header("Location: ../Interfaces/reportes.php?idTrabajador=14" . $idTrabajador);
exit();
?>
