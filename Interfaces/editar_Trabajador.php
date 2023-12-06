<?php require_once './header.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Css/styles-trab.css">
    <title>Editar Trabajador</title>
</head>
<?php
if (!isset($_GET['idTrabajador'])) {
    header('Location: ./trabajadores.php?mensajeP=error&idTrabajador=14');
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

<body class="sb-nav-fixed">
    <div class="container mt-5">

        <h1>Editar</h1>
        <form class="contact-form" method="POST" action="editar_ProcesoTrab.php">
            <input type="hidden" name="id_trab" value="<?php echo $idTrabajador; ?>">

            <div>
                <label for="nombre">Nombre: </label>
                <input type="text" id="nombreE" placeholder="Nombre" name="nombreE" required value="<?php echo $persona->nombreTrab; ?>">
                <label for="apellido">Apellidos: </label>
                <input type="text" id="apellidoE" placeholder="Apellido" name="apellidoE" required value="<?php echo $persona->apellidoTrab; ?>">
                <label for="telefono">Teléfono: </label>
                <input type="tel" id="telefonoE" name="telefonoE" placeholder="Telefono" maxlength="10" required value="<?php echo $persona->telefono; ?>">
            </div>
            <div>
                <label for="puesto">Puesto: </label>
                <select name="puestoE" id="puestoE" required>
                    <option value="" disabled selected>Selecciona un puesto</option>
                    <option value="Jefe_Obra" <?php echo ($Tipo_puesto == 'Jefe_Obra' ? 'selected' : '') ?>>Jefe de Obra</option>
                    <option value="Trabajador" <?php echo ($Tipo_puesto == 'Trabajador' ? 'selected' : '') ?>>Trabajador</option>
                </select>
                <label for="usuario">Usuario: </label>
                <input type="text" id="usuarioE" name="usuarioE" placeholder="Usuario" required value="<?php echo $persona->usuario; ?>">
                <div>
                    <label for="contra">Contraseña: </label>
                    <div class="password-container">
                        <input type="text" id="contraText" style="display: none;">
                        <input type="text" id="contraE" name="contraE" placeholder="Contraseña" required value="<?php echo $persona->contra; ?>">
                    </div>
                </div>
            </div>
            <div style="grid-column: span 2;">
                <input type="hidden" name="oculto" value="1">
                <button type="submit" class="btn btn-primary">Actualizar Registro</button>
                <a href="./trabajadores.php" class="btn btn-primary">Regresar</a>
            </div>
        </form>
    </div>

</body>
<?php require_once './footer.php'; ?>