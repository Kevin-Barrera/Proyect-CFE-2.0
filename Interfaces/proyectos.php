<?php require_once './header.php'; ?>
<script src="../Js/axeljoto.js" defer></script>

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

// Consulta SQL para seleccionar todos los proyectos
$sql = "SELECT * FROM proyecto";
$resultado = $conexion->query($sql);

// Comprobar si se encontraron resultados
if ($resultado->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nombre del Proyecto</th><th>Descripción</th><th>Ruta del Archivo</th></tr>";

    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $fila["idProyecto"] . "</td>";
        echo "<td>" . $fila["nomProyecto"] . "</td>";
        echo "<td>" . $fila["descProyecto"] . "</td>";
        echo "<td>" . $fila["rutaArc1"] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No se encontraron proyectos en la base de datos.";
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>

<div id="example"></div>

<?php require_once './footer.php'; ?>