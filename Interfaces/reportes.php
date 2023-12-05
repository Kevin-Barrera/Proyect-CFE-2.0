<?php require_once './header.php'; ?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "cfe";

$conexion = new mysqli($servername, $username, $password, $database);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$sql = "SELECT idProyecto, nomProyecto, idArchivo1, rutaArc1, idArchivo2, rutaArc2 FROM proyecto";
$result = $conexion->query($sql);

$proyectos = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $proyectos[] = $row;
    }
}

$conexion->close();
?>

<div class="container my-5">
    <h1>Proyectos</h1>
    <div class="table-responsive">
        <table id="tabla" class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID del Proyecto</th>
                    <th>Nombre del Proyecto</th>
                    <th>Nombre del Reporte Inicial</th>
                    <th>Nombre del Reporte Final</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($proyectos as $proyecto) { ?>
                    <tr>
                        <td><?php echo $proyecto["idProyecto"]; ?></td>
                        <td><?php echo $proyecto["nomProyecto"]; ?></td>
                        <td>
                            <?php if (!empty($proyecto["rutaArc1"])) { ?>
                                <?php echo $proyecto["rutaArc1"]; ?>
                                <br>
                                <button class="btn btn-danger" onclick="confirmarEliminar('<?php echo $proyecto['idArchivo1']; ?>', 1)">Eliminar</button>
                                <a href="../php/descargar.php?archivo=<?php echo $proyecto["rutaArc1"]; ?>" class="btn btn-primary">Descargar</a>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if (!empty($proyecto["rutaArc2"])) { ?>
                                <?php echo $proyecto["rutaArc2"]; ?>
                                <br>
                                <button class="btn btn-danger" onclick="confirmarEliminar('<?php echo $proyecto['idArchivo2']; ?>', 2)">Eliminar</button>
                                <a href="../php/descargar.php?archivo=<?php echo $proyecto["rutaArc2"]; ?>" class="btn btn-primary">Descargar</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmarEliminar(idArchivo, tipo) {
    if (confirm("¿Seguro que quieres eliminar este archivo?")) {
        window.location.href = `../php/eliminar.php?idArchivo=${idArchivo}&tipo=${tipo}`;
    }
}
</script>

<?php require_once './footer.php'; ?>
