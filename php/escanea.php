<?php
$directorio = 'Archivos'; // Ruta a la carpeta donde se almacenan los archivos
$archivos = scandir($directorio);
$archivos_excel = array();

foreach ($archivos as $archivo) {
    if (pathinfo($archivo, PATHINFO_EXTENSION) === 'xlsx') {
        $archivos_excel[] = $archivo;
    }
}
?>