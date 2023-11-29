<?php require_once './header.php'; ?>
<script src="../Js/conexion.js" defer></script>

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
        echo "<td>" . $fila["rutaArc1"] . "</td>";
        
        // Mostrar el botón "Generar" solo si no hay valor en idArchivo1
        if(empty($fila["idArchivo1"])){
            echo "<td></td>";
        }else if (empty($fila["idArchivo2"])) {
            echo "<td style='text-align: center;'>" . "<button class='btn btn-primary' onclick='generarReporte(" . $fila["idProyecto"] . ")'>Generar</button>" . "</td>";
        } else {
            // Mostrar el valor de rutaArc2 en caso contrario
            echo "<td>" . $fila["rutaArc2"] . "</td>";
        }

        echo "<td style='text-align: center;'>" . "<button class='btn btn-danger' onclick='eliminarProyecto(" . $fila["idProyecto"] . ")'>Eliminar</button>" . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No se encontraron proyectos en la base de datos.";
}

    echo "</div></div>";

// No cierres la conexión aquí, ya que aún necesitas los resultados
// $conexion->close();
?>

<script>
function eliminarProyecto(idProyecto) {
    if (confirm("¿Estás seguro de que quieres eliminar este proyecto?")) {
        window.location.href = "./proyectos.php?eliminar_proyecto=" + idProyecto;
    }
}
</script>

<?php require_once './footer.php'; ?>
