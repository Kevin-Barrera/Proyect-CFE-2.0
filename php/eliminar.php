<?php
if (isset($_GET['archivo'])) {
    $archivo = $_GET['archivo'];
    $directorio = '../Archivos'; // Ruta a la carpeta donde se almacenan los archivos

    // Asegúrate de que el archivo existe antes de intentar eliminarlo
    if (file_exists("$directorio/$archivo")) {
        if (unlink("$directorio/$archivo")) {
            // Mensaje de eliminación exitosa
            echo "El archivo $archivo ha sido eliminado exitosamente.";

            // Redirige de vuelta a la página anterior
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit; // Asegura que el script termine después de la redirección
        } else {
            echo "Error al intentar eliminar el archivo.";
        }
    } else {
        echo "El archivo no existe en la ubicación especificada.";
    }
} else {
    echo "No se ha especificado un archivo a eliminar.";
}
?>
