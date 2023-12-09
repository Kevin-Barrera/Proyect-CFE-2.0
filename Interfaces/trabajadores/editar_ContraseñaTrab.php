<?php 
print_r($_POST);
 
$conexion = conectarBD();
$idTrabajador = $_POST['id_trab'];
$contra = $_POST["contraE"];


$sentencia = $conexion->prepare("UPDATE trabajador SET contra = ? where idTrabajador = ?");
$resultado = $sentencia->execute([$contra,$idTrabajador]);

if ($resultado == TRUE) {
    header('Location: ../editarUsuario.php?idTrabajador=' . $idTrabajador .'&mensajeP=contrasena cambiada');
} else {
    header('Location: ../editarUsuario.php?idTrabajador=' . $idTrabajador . '&mensajeP=error');
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