<?php
require_once './header.php';
?>

<div class="container my-5">
    <h1>Detalles del Proyecto</h1>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "cfe";

    $conexion = new mysqli($servername, $username, $password, $database);

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    $idProyecto = $_GET['idProyecto'];
    $tipo = $_GET['tipo'];

    $sql_proyecto = "SELECT * FROM proyecto WHERE idProyecto = ?";
    $stmt_proyecto = $conexion->prepare($sql_proyecto);
    $stmt_proyecto->bind_param("i", $idProyecto);
    $stmt_proyecto->execute();
    $resultado_proyecto = $stmt_proyecto->get_result();

    if ($resultado_proyecto->num_rows > 0) {
        $proyecto = $resultado_proyecto->fetch_assoc();
        

        echo '<div id="carga" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); text-align: center; padding-top: 20%;">
                <p>Cargando...</p></div>';
        echo "<p><strong>ID del Proyecto:</strong> " . $proyecto["idProyecto"] . "</p>";
        echo "<p><strong>Nombre del Proyecto:</strong> " . $proyecto["nomProyecto"] . "</p>";
        echo "<p><strong>Descripción:</strong> " . $proyecto["descProyecto"] . "</p>";
        echo "<p><strong>Zona:</strong> " . $proyecto["zona"] . "</p>";
        echo "<p><strong>Obra:</strong> " . $proyecto["obra"] . "</p>";


        if ($tipo == 1) {
            echo "<div class='d-flex' style='justify-content: space-between;'>";
            echo "<p><strong>Detalles del Reporte Inicial</strong></p>";
            echo "<button class='btn btn-success mr-4 mb-3' onclick='guardarCambios($tipo)'>Guardar Cambios</button>";
            echo "</div>";

            // Obtener la ruta del archivo XLSX desde el campo idArchivo1
            $archivoXlsx = "../Archivos/" . $proyecto["idArchivo1"] . ".xlsx";
            // echo '<p>' . $archivoXlsx . '</p>';
            echo '<div id="tablaExcel" style="max-width: 100%; overflow-x: auto; overflow-y: auto; height: 1000px;"></div>';

            // Llamar a la función desde el script JavaScript
            echo '<script src="../Js/mostrarTablaExcel.js"></script>';
            echo '<script>mostrarTablaExcel("' . $archivoXlsx . '");</script>';
        } else if ($tipo == 2) {
            echo "<div class='d-flex' style='justify-content: space-between;'>";
            echo "<p><strong>Detalles del Reporte Inicial</strong></p>";
            echo "<button class='btn btn-success mr-4 mb-3' onclick='guardarCambios($tipo)'>Guardar Cambios</button>";
            echo "</div>";
            $archivoXlsx = "../Archivos/" . $proyecto["idArchivo2"] . ".xlsx";
            // echo '<p>' . $archivoXlsx . '</p>';
            echo '<div id="tablaExcel" style="max-width: 100%; overflow-x: auto; overflow-y: auto; height: 1000px;"></div>';

            // Llamar a la función desde el script JavaScript
            echo '<script src="../Js/mostrarTablaExcel.js"></script>';
            echo '<script>mostrarTablaExcel("' . $archivoXlsx . '");</script>';
        }
    } else {
        echo "No se encontró el proyecto con el ID proporcionado.";
    }

    $stmt_proyecto->close();
    $conexion->close();
    ?>

</div>
<script>
    function mostrarCarga() {
        Swal.fire({
            title: 'Cargando',
            html: 'Por favor, espera...',
            allowOutsideClick: false,
            showConfirmButton: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            },
        });
    }

    function ocultarCarga() {
        Swal.close();
    }

    function guardarCambios(tipo) {
        mostrarCarga();
        // Obtener la tabla actualizada
        var table = document.getElementById('excelTable');

        // Obtener el ID del proyecto
        var idProyecto = '<?php echo $idProyecto; ?>';

        // Crear un FormData para enviar los datos al servidor
        var formData = new FormData();
        formData.append('idProyecto', idProyecto);
        formData.append('tipo', tipo); // Agregar tipo también

        // Recorrer las filas de la tabla
        for (var i = 0, row; row = table.rows[i]; i++) {
            // Recorrer las celdas de cada fila
            for (var j = 0, col; col = row.cells[j]; j++) {
                // Obtener el valor de la celda
                var cellValue = col.innerText;

                // Agregar el valor y la posición de la celda al FormData
                formData.append('celda[' + i + '][' + j + ']', cellValue);
            }
        }

        // Enviar una solicitud al servidor
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'guardarCambios.php', true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                ocultarCarga();
                alert("Cambios guardados con éxito en el archivo y la base de datos.");
                // location.reload(true);
            } else {
                alert("Error al guardar cambios en el archivo o la base de datos.");
            }
        };

        xhr.send(formData);
    }

    function obtenerCeldasModificadas(table) {
        var celdasModificadas = [];

        // Recorrer las filas de la tabla
        for (var i = 0, row; row = table.rows[i]; i++) {
            // Recorrer las celdas de cada fila
            for (var j = 0, col; col = row.cells[j]; j++) {
                // Obtener el valor de la celda
                var cellValue = col.innerText;

                // Obtener el valor original de la celda
                var originalValue = col.getAttribute('data-original-value');

                // Agregar la información de la celda al array sin verificar cambios en este caso
                celdasModificadas.push({
                    fila: i,
                    columna: j,
                    valor: cellValue
                });

                // Actualizar el valor original de la celda
                col.setAttribute('data-original-value', cellValue);
            }
        }

        return celdasModificadas;
    }
</script>

<!-- Agrega este bloque de script al final de tu HTML -->
<?php
require_once './footer.php';
?>