<?php require_once './header.php';

if (!isset($_GET['idTrabajador'])) {
    header('Location: .editarUsuario.php?idTrabajador=<?php echo $idTrabajador;?>&mensajeP=error');
    exit();
}
$conexion = conectarBD();
$idTrabajador = $_GET['idTrabajador'];

$sentencia = $conexion->prepare("select * from trabajador where idTrabajador = ?;");
$sentencia->execute([$idTrabajador]);
$persona = $sentencia->fetch(PDO::FETCH_OBJ);
$Tipo_puesto = $persona->puesto;
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

<div class="container mt-5">
    <center><h1>Cambiar Contraseña</h1></center>
    <form class="contact-form" method="POST" action="./trabajadores/editar_ContraseñaTrab.php">
        <input type="hidden" name="id_trab" value="<?php echo $idTrabajador; ?>">

        <div>
            <center>
                <label for="contra">Contraseña: </label>
                <div class="password-container">
                    <input type="text" id="contraText" style="display: none;">
                    <input type="text" id="contraE" name="contraE" placeholder="Contraseña" required value="<?php echo $persona->contra; ?>">
                </div>
            </center>
        </div>
        <center>
            <div style="grid-column: span 2;">
                <input type="hidden" name="oculto" value="1">
                <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
                <a href="editarUsuario.php?idTrabajador=<?php echo $idTrabajador;?>" class="btn btn-primary">Regresar</a>
            </div>
        </center>
    </form>
</div>
</center>

<?php require_once './footer.php'; ?>