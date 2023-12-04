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

        echo "<p><strong>ID del Proyecto:</strong> " . $proyecto["idProyecto"] . "</p>";
        echo "<p><strong>Nombre del Proyecto:</strong> " . $proyecto["nomProyecto"] . "</p>";
        echo "<p><strong>Descripción:</strong> " . $proyecto["descProyecto"] . "</p>";


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
    function guardarCambios($tipo) {
        // Obtener la tabla actualizada
        var table = document.getElementById('excelTable');

        // Convertir la tabla a una hoja de cálculo
        var updatedData = XLSX.utils.table_to_sheet(table);

        // Crear un libro y agregar la hoja de cálculo actualizada
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, updatedData, "Sheet1");

        // Obtener el nombre del archivo original del campo idArchivo del proyecto
        if ($tipo == 1) {
            var idArchivo = '<?php echo $proyecto["idArchivo1"]; ?>'; // Asegúrate de imprimir correctamente el valor en PHP
        } else if ($tipo == 2) {
            var idArchivo = '<?php echo $proyecto["idArchivo2"]; ?>';
        }
        // console.log("ID Archivo:", idArchivo); // Agrega esta línea para verificar en la consola

        // Convertir el libro a una cadena binaria
        var wbout = XLSX.write(wb, {
            bookType: 'xlsx',
            bookSST: true,
            type: 'binary'
        });

        // Convertir la cadena binaria a un Blob
        var blob = new Blob([s2ab(wbout)], {
            type: 'application/octet-stream'
        });

        // Crear un FormData para enviar el blob al servidor
        var formData = new FormData();
        formData.append('file', blob, idArchivo + '.xlsx');

        // Enviar una solicitud al servidor
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'guardar_archivo.php', true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                alert("Cambios guardados con éxito en el archivo.");
            } else {
                alert("Error al guardar cambios en el archivo.");
            }
        };

        xhr.send(formData);
    }
</script>
<!-- Agrega este bloque de script al final de tu HTML -->
<?php
require_once './footer.php';
?>