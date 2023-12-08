
<?php
if (!isset($_GET['idTrabajador'])) {
    header('Location: ../trabajadores.php?mensajeP=error');
    exit();
}

$conexion = conectarBD();
$idTrabajador = $_GET['idTrabajador'];

$sentencia = $conexion->prepare("DELETE FROM trabajador where idTrabajador = ?;");
$resultado = $sentencia->execute([$idTrabajador]);

if ($resultado === TRUE) {
    header('Location: ../trabajadores.php?mensajeP=eliminado');
    exit();
} else {
    header('Location: ../trabajadores.php?mensajeP=error');
    exit();
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
    