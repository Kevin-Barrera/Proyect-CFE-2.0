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
                <form action="../php/procesar.php" method="post" enctype="multipart/form-data" onsubmit="return validarSeleccion()">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="proyecto">Seleccionar Proyecto:</label>
                                <select name="proyecto" id="proyecto" class="form-control">
                                    <option value="" disabled selected>Seleccione uno:</option>
                                    <?php
                                    $servername = "localhost"; // Nombre del servidor MySQL
                                    $username = "root";     // Nombre de usuario de la base de datos
                                    $password = "";        // Contraseña de la base de datos
                                    $database = "cfe"; // Nombre de la base de datos

                                    // Crear una conexión
                                    $conexion = new mysqli($servername, $username, $password, $database);

                                    // Verificar la conexión
                                    if ($conexion->connect_error) {
                                        die("Conexión fallida: " . $conexion->connect_error);
                                    }

                                    // Consulta SQL para obtener los proyectos
                                    $sql = "SELECT nomProyecto FROM proyecto";
                                    $result = $conexion->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $nombreProyecto = $row['nomProyecto'];
                                            echo "<option value=\"$nombreProyecto\">$nombreProyecto</option>";
                                        }
                                    }
                                    // Cierra la conexión a la base de datos
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
    function validarSeleccion() {
        var proyectoSelect = document.getElementById("proyecto");
        var selectedValue = proyectoSelect.options[proyectoSelect.selectedIndex].value;

        if (selectedValue === "") {
            alert("Por favor, seleccione un proyecto válido antes de editar.");
            return false; // Detiene el envío del formulario
        }

        return true; // Permite el envío del formulario si se ha seleccionado un proyecto válido
    }

    function editarProyecto() {
        var proyectoSelect = document.getElementById("proyecto");
        var proyectoSeleccionado = proyectoSelect.value;

        if (!proyectoSeleccionado) {
            alert("Por favor, seleccione un proyecto válido antes de editar.");
        } else {
            // Redirige al usuario a la página de edición con el proyecto seleccionado
            window.location.href = '../php/editar_proyecto.php?proyecto=' + proyectoSeleccionado;
        }
    }
</script>