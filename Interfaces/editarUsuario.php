<?php
require_once './header.php';
include "../php/bd.php";
?>

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
        $usuario = $trabajador['usuario'];
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

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4">Usuario</h1>
            <center>
                <img src="login/img2/avatar.svg" alt="Perfil" style="width: 200px; height: 200px; border-radius: 50%;">
            </center>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Usuario:</label>
                <p><?php echo $usuario; ?></p>
            </div>
        </div>
    </div>
</div>

<script>
    function cancelar() {
        window.location.href = "./index.php";
    }
</script>

<?php require_once './footer.php'; ?>