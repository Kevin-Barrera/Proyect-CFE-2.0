<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "cfe";

$conexion = new mysqli($servername, $username, $password, $database);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Verifica si se proporciona el ID del trabajador en la URL
if (isset($_GET['idTrabajador'])) {
    $idTrabajador = $_GET['idTrabajador'];

    // Consulta para obtener los detalles del trabajador específico
    $sql = "SELECT idTrabajador, nombreTrab, apellidoTrab, telefono, puesto, usuario FROM trabajador WHERE idTrabajador = $idTrabajador";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        $trabajador = $result->fetch_assoc();
        $nombre = $trabajador['nombreTrab'];
        $apellido = $trabajador['apellidoTrab'];
        $puesto = $trabajador['puesto'];
    } else {
        // Manejar el caso donde no se encuentra el trabajador con el ID proporcionado
        $usuario = "Trabajador no encontrado";
    }
} else {
    // Manejar el caso donde no se proporciona el ID del trabajador en la URL
    $usuario = "ID de trabajador no especificado";
}

$conexion->close();
?>

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
header("Location: ../Interfaces/reportes.php?idTrabajador=14" . $idTrabajador);
exit();
?>
