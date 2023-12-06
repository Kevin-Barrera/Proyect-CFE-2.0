<?php require_once './header.php';
include "../php/bd.php";

$id_proyecto = $_GET['id'];

if (isset($_POST['guardarCambios'])) {
    $nombreProyecto = $_POST['nombre'];
    $descripcionProyecto = $_POST['descripcion'];
    $zonaProyecto = $_POST['zona'];
    $obraProyecto = $_POST['obra'];

    // Verifica si se ha subido un nuevo archivo Excel
    for ($i = 1; $i <= 2; $i++) {
        $campo_archivo = "archivo" . $i;

        if ($_FILES[$campo_archivo]['name']) {
            // Procesa y guarda el nuevo archivo Excel
            $archivoRuta = $_FILES[$campo_archivo]['name'];
            $archivoTemporal = $_FILES[$campo_archivo]['tmp_name'];

            // Ruta de la carpeta donde se guardarán los archivos subidos
            $carpetaDestino = '../Archivos/';

            // Obtiene la ruta del archivo existente
            $archivoExistente = obtenerRutaArchivoProyecto($id_proyecto, $i);
            $idArchivoExistente = obtenerIdArchivoProyecto($id_proyecto, $i);
            $idArchivoExistente = $idArchivoExistente . '.xlsx';

            // Elimina el archivo existente si hay uno y es diferente al nuevo
            if (!empty($idArchivoExistente) && file_exists($carpetaDestino . $idArchivoExistente)) {
                unlink($carpetaDestino . $idArchivoExistente);
            }

            // Mover el nuevo archivo a la carpeta de destino
            if (move_uploaded_file($archivoTemporal, $carpetaDestino . $idArchivoExistente)) {
                // Actualiza la ruta del archivo en la base de datos
                actualizarRutaArchivoProyecto($id_proyecto, $i, $archivoRuta);
            } else {
                echo "Error al mover el archivo a la carpeta de destino.";
            }
        }
    }

    // Realiza la llamada a la función para guardar los cambios
    guardarCambiosProyecto($id_proyecto, $nombreProyecto, $descripcionProyecto, $zonaProyecto, $obraProyecto);

    // Redirige a la página de éxito o a donde desees después de guardar los cambios.
    echo "<script>
    alert('Los datos se han actualizado correctamente.');
    var idTrabajador = $idTrabajador;
    window.location.href = './inicio.php?&idTrabajador=' + idTrabajador;
    </script>";
    //header("Location: inicio.php?success=true");
}

// Obtén los datos del proyecto
$datos_proyecto = obtenerDatosDelProyecto($id_proyecto);
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4">Editar archivo</h1>
            <!-- Formulario para editar el proyecto con los datos obtenidos -->
            <form action="editar_proyecto.php?id=<?php echo $id_proyecto; ?>&idTrabajador=<?php echo $idTrabajador;?>" method="post" enctype="multipart/form-data">
                <!-- Campos para mostrar y editar los datos del proyecto --> 
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Proyecto:</label>
                    <input type="text" name="nombre" value="<?php echo $datos_proyecto['nomProyecto']; ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <input type="text" name="descripcion" value="<?php echo $datos_proyecto['descProyecto']; ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="zona" class="form-label">Zona:</label>
                    <input type="text" name="zona" value="<?php echo $datos_proyecto['zona']; ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="obra" class="form-label">Obra:</label>
                    <input type="text" name="obra" value="<?php echo $datos_proyecto['obra']; ?>" class="form-control">
                </div>
                <!-- Campo para el archivo Excel -->
                <div class="mb-3">
                    <label for="archivo1" class="form-label">Selecciona un archivo Excel (Si desea actualizarlo):</label>
                    <input type="file" class="form-control" name="archivo1" accept=".xlsx">
                </div>

                <!-- Campo para el archivo Excel 2 -->
                <div class="mb-3">
                    <label for="archivo2" class="form-label">Selecciona un archivo Excel (Si desea actualizarlo):</label>
                    <input type="file" class="form-control" name="archivo2" accept=".xlsx">
                </div>
                <!-- Otros campos del formulario según tus necesidades -->
                <!-- Botón para guardar los cambios -->
                <button type="submit" class="btn btn-primary" name="guardarCambios">Guardar Cambios</button>
                <button type="button" class="btn btn-danger" onclick="cancelar()">Cancelar</button>
            </form>
        </div>
    </div>
</div>

<script>

var idTrabajador = <?php echo $idTrabajador; ?>;

    function cancelar() {
        window.location.href = "./inicio.php?idTrabajador=" + idTrabajador;
    }
</script>

<?php
require_once './footer.php';
?>