<?php require_once './header.php'; ?>

<?php
// Conexión a la base de datos (reemplaza con tus propios datos)
$servername = "localhost";
$username = "root";
$password = "";
$database = "cfe";

$conexion = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Comprobar si se ha enviado una solicitud de eliminación
if (isset($_GET['eliminar_proyecto'])) {
    $id_proyecto_eliminar = $_GET['eliminar_proyecto'];

    // Consulta SQL para eliminar el proyecto por ID
    $sql_eliminar = "DELETE FROM proyecto WHERE idProyecto = ?";
    $stmt_eliminar = $conexion->prepare($sql_eliminar);
    $stmt_eliminar->bind_param("i", $id_proyecto_eliminar);

    // Ejecutar la consulta de eliminación
    if ($stmt_eliminar->execute()) {
        echo "<script>alert('El proyecto ha sido eliminado correctamente.');</script>";
    } else {
        echo "Error al eliminar el proyecto: " . $stmt_eliminar->error;
    }

    // Cerrar la consulta de eliminación
    $stmt_eliminar->close();
}


// Consulta SQL para seleccionar todos los proyectos
$sql = "SELECT * FROM proyecto";
$resultado = $conexion->query($sql);
echo '<div id="carga" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); text-align: center; padding-top: 20%;">
                <p>Cargando...</p></div>';
echo "<div class='container my-5'><div class='table-responsive'>";
echo "<h1>Proyectos registrados</h1>";

// Comprobar si se encontraron resultados
if ($resultado->num_rows > 0) {
    echo "<table border='1' class='table table-striped table-bordered'>";
    echo "<thead class='thead-dark' style='text-align: center;'>
            <tr>
                <th style='width: 10px;'>ID</th>
                <th style='width: 120px;'>Nombre del Proyecto</th>
                <th style='width: 180px;'>Descripción</th>
                <th style='width: 150px;'>Nombre del Reporte inicial</th>
                <th style='width: 150px;'>Nombre del Reporte Final</th>
                <th style='width: 80px;'>Acciones</th>
            </tr>
        </thead>";

    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $fila["idProyecto"] . "</td>";
        echo "<td>" . $fila["nomProyecto"] . "</td>";
        echo "<td>" . $fila["descProyecto"] . "</td>";
        echo "<td><a href='./detalle_proyecto.php?idTrabajador=14&idProyecto=" . $fila["idProyecto"] . "&tipo=1'>" . $fila["rutaArc1"] . "</a></td>";

        // Mostrar el botón "Generar" solo si no hay valor en idArchivo1
        if (empty($fila["rutaArc1"]) && empty($fila["rutaArc2"])) {
            echo "<td></td>";
        } else if (empty($fila["rutaArc2"])) {
            echo "<td style='text-align: center;'>" . "<button class='btn btn-primary' onclick='generarReporte(" . $fila["idProyecto"] . ")'>Generar</button>" . "</td>";
        } else {
            // Mostrar el valor de rutaArc2 en caso contrario
            echo "<td><a href='./detalle_proyecto.php?idProyecto=" . $fila["idProyecto"] . "&tipo=2'>" . $fila["rutaArc2"] . "</a></td>";
        }

        echo "<td style='text-align: center;'>" . "<button class='btn btn-danger' onclick='eliminarProyecto(" . $fila["idProyecto"] . ")'>Eliminar</button>" . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No se encontraron proyectos en la base de datos.";
}

echo "</div></div>";
?>

<script>
    var idTrabajador = <?php echo $idTrabajador; ?>;

    function eliminarProyecto(idProyecto) {
        if (confirm("¿Estás seguro de que quieres eliminar este proyecto?")) {
            window.location.href = "./proyectos.php?eliminar_proyecto=" + idProyecto;
        }
        window.location.href = "./proyectos.php?idTrabajador=" + idTrabajador;
    }

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

    function generarReporte(idProyecto) {
        mostrarCarga();
        // Aquí haces la llamada AJAX al servidor para generar el reporte
        var xhr = new XMLHttpRequest();
        xhr.open('GET', './generar_reporte.php?idProyecto=' + idProyecto, true);
        console.log('');
        xhr.onload = function() {
            if (xhr.status === 200) {
                ocultarCarga();
                alert("Reporte generado con éxito.");
                window.location.href = "./proyectos.php?idTrabajador=" + idTrabajador;
            } else {
                alert("Error al generar el reporte.");
            }
        };

        xhr.send();
    }
</script>

<?php require_once './footer.php'; ?>