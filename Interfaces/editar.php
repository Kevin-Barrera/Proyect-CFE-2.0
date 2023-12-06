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

<div class="modal fade" id="editar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h3 class="modal-title" id="exampleModalLabel">Editar Proyecto</h3>
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="../php/procesar.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="proyecto">Seleccionar Proyecto:</label>
                                <select name="proyecto" id="proyecto" class="form-control">
                                    <option value="" disabled selected>Seleccione uno:</option>
                                    <?php
                                    $servername = "localhost";
                                    $username = "root";
                                    $password = "";
                                    $database = "cfe";

                                    $conexion = new mysqli($servername, $username, $password, $database);

                                    if ($conexion->connect_error) {
                                        die("Conexión fallida: " . $conexion->connect_error);
                                    }

                                    $sql = "SELECT idProyecto, nomProyecto FROM proyecto";
                                    $result = $conexion->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $idProyecto = $row['idProyecto'];
                                            $nombreProyecto = $row['nomProyecto'];
                                            echo "<option value=\"$idProyecto\">$nombreProyecto</option>";
                                        }
                                    }

                                    $conexion->close();
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="editarProyecto()">Editar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function editarProyecto() {
    var proyectoSelect = document.getElementById("proyecto");
    var proyectoSeleccionado = proyectoSelect.value;

    var idTrabajador = <?php echo json_encode($idTrabajador); ?>;

    if (!proyectoSeleccionado) {
        alert("Por favor, seleccione un proyecto válido antes de editar.");
    } else {
        // Redirige al usuario a la página de edición con la ID del proyecto seleccionado
        window.location.href = './editar_proyecto.php?id=' + proyectoSeleccionado + '&idTrabajador=' + idTrabajador;
    }
}
</script>