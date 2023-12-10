<?php
if (empty($_POST["oculto"]) || empty($_POST["nombre"]) || empty($_POST["telefono"]) || empty($_POST["usuario"]) || empty($_POST["apellido"]) || empty($_POST["puesto"]) || empty($_POST["contra"])) {
    echo "faltan datos";
    header('Location: ../trabajadores.php?mensajeP=falta algun dato');
    exit();
}
// Uso de la función
$conexion = conectarBD();

$nombreTrab = $_POST["nombre"];
$apellidoTrab = $_POST["apellido"];
$telefono = $_POST["telefono"];
$puesto = $_POST["puesto"];
$usuario = $_POST["usuario"];
$contra = $_POST["contra"];

$sentencia = $conexion->prepare("INSERT INTO trabajador(nombreTrab,apellidoTrab,telefono,puesto,usuario,contra) VALUES (?,?,?,?,?,?);");
$resultado = $sentencia->execute([$nombreTrab, $apellidoTrab, $telefono, $puesto,$usuario,$contra]);

if ($resultado == TRUE) {
    header('Location: ../trabajadores.php?mensajeP=trabajador registrado');
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