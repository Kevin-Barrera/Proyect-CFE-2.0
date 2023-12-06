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
if (!isset($_GET['idTrabajador'])) {
    header('Location: ./trabajadores.php?mensajeP=error');
    exit();
}

$conexion = conectarBD();
$idTrabajador = $_GET['idTrabajador'];

$sentencia = $conexion->prepare("DELETE FROM trabajador where idTrabajador = ?;");
$resultado = $sentencia->execute([$idTrabajador]);

if ($resultado === TRUE) {
    header('Location: ./trabajadores.php?mensajeP=eliminado&idTrabajador=14');
} else {
    header('Location: ./trabajadores.php?mensajeP=error&idTrabajador=14');
}

?>

<?php
    function conectarBD()
    {
        $contrasena = "";
        $usuario = "root";
        $nombre_bd = "cfe";

        try {
            $bd = new PDO(
                'mysql:host=localhost;dbname=' . $nombre_bd,
                $usuario,
                $contrasena,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
            return $bd;
        } catch (Exception $e) {
            echo "No fue posible conectar la base de datos: " . $e->getMessage();
            return null; // Manejar el error según tus necesidades
        }
    }
?>
    