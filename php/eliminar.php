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
if (isset($_GET['idArchivo']) && isset($_GET['tipo'])) {
    $idArchivo = $_GET['idArchivo'];
    $tipo = $_GET['tipo'];

    $archivoPath = '../Archivos/' . $idArchivo . '.xlsx';

    // Verificar si el archivo existe antes de intentar eliminarlo
    if (file_exists($archivoPath)) {
        unlink($archivoPath);

        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "cfe";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        if ($tipo == 1) {
            $sql = "UPDATE proyecto SET rutaArc1 = '' WHERE idArchivo1 = '$idArchivo'";
        } elseif ($tipo == 2) {
            $sql = "UPDATE proyecto SET rutaArc2 = '' WHERE idArchivo2 = '$idArchivo'";
        }

        $conn->query($sql);
        $conn->close();
    } else {
        echo "El archivo no existe o ya ha sido eliminado.";
    }
}

header("Location: ../Interfaces/reportes.php?idTrabajador=14" . $idTrabajador);
exit();
?>
