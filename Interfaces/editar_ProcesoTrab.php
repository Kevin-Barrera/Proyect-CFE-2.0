<?php 
print_r($_POST);
 
$conexion = conectarBD();
$idTrabajador = $_POST['id_trab'];
$nombreTrab = $_POST["nombreE"];
$apellidoTrab = $_POST["apellidoE"];
$telefono = $_POST["telefonoE"];
$puesto = $_POST["puestoE"];
$usuario = $_POST["usuarioE"];
$contra = $_POST["contraE"];


$sentencia = $conexion->prepare("UPDATE trabajador SET nombreTrab = ?, apellidoTrab = ?, telefono = ?, puesto = ?, usuario = ?, contra = ? where idTrabajador = ?");
$resultado = $sentencia->execute([$nombreTrab,$apellidoTrab,$telefono,$puesto,$usuario,$contra,$idTrabajador]);

if ($resultado == TRUE) {
    header('Location: ./trabajadores.php?mensajeP=trabajador registrado');
} else {
    header('Location: ./trabajadores.php?mensajeP=error');
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