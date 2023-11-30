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
            echo "<button class='btn btn-success mr-4 mb-3' onclick='guardarCambios()'>Guardar Cambios</button>";
            echo "</div>";

            // Obtener la ruta del archivo XLSX desde el campo idArchivo1
            $archivoXlsx = "../Archivos/" . $proyecto["idArchivo1"] . ".xlsx";
            // echo '<p>' . $archivoXlsx . '</p>';
            echo '<div id="tablaExcel" style="max-width: 100%; overflow-x: auto; overflow-y: auto; height: 1000px;"></div>';

            // Llamar a la función desde el script JavaScript
            echo '<script src="../Js/mostrarTablaExcel.js"></script>';
            echo '<script>mostrarTablaExcel("' . $archivoXlsx . '");</script>';
        } elseif ($tipo == 2) {
            echo "<p>Detalles del Reporte Final</p>";
            $archivoXlsx = "../Archivos/" . $proyecto["idArchivo2"] . ".xlsx";
            // echo '<p>' . $archivoXlsx . '</p>';
            echo '<div id="tablaExcel"></div>';

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

<!-- Agrega este bloque de script al final de tu HTML -->
<?php
require_once './footer.php';
?>