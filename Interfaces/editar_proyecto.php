<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Panel Admin</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-i3ziGFCpwpQf4WIg6AqO1HbOkDMM8fdM12lpFckNXy6lW3CzIYbPm7G2Y3KdhZzI" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../Css/sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css" />
    <link rel="stylesheet" href="../Css/estilos.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous"> -->
    <script src="../Js/jquery.min.js"></script>
    <script src="../Js/bootstrap.min.js"></script>
</head>

<style>
    body {
        background-color: #f8f9fc;
    }
</style>

<?//php require_once './header.php'; ?>
<?php
include "../php/bd.php";

$id_proyecto = $_GET['id'];

if (isset($_POST['guardarCambios'])) {
    $nombreProyecto = $_POST['nombre'];
    $descripcionProyecto = $_POST['descripcion'];

    // Verifica si se ha subido un nuevo archivo Excel
    if ($_FILES['archivo']['name']) {
        // Procesa y guarda el nuevo archivo Excel
        $archivoRuta = $_FILES['archivo']['name'];
        $archivoTemporal = $_FILES['archivo']['tmp_name'];

        // Ruta de la carpeta donde se guardarán los archivos subidos
        $carpetaDestino = '../Archivos/';

        // Verifica si hay un archivo existente
        $datos_proyecto = obtenerDatosDelProyecto($id_proyecto);
        $archivoExistente = $datos_proyecto['rutaArc1'];
        if (!empty($archivoExistente) && file_exists($carpetaDestino . $archivoExistente)) {
            // Elimina el archivo existente
            unlink($carpetaDestino . $archivoExistente);
        }

        // Mover el nuevo archivo a la carpeta de destino
        if (move_uploaded_file($archivoTemporal, $carpetaDestino . $archivoRuta)) {
            // Actualiza el nombre del archivo en la base de datos
            actualizarNombreArchivoProyecto($id_proyecto, $archivoRuta);
        } else {
            echo "Error al mover el archivo a la carpeta de destino.";
        }
    }

    // Realiza la llamada a la función para guardar los cambios
    guardarCambiosProyecto($id_proyecto, $nombreProyecto, $descripcionProyecto);

    // Redirige a la página de éxito o a donde desees después de guardar los cambios.
    header("Location: ../Interfaces/index.php?success=true");
}

// Obtén los datos del proyecto
$datos_proyecto = obtenerDatosDelProyecto($id_proyecto);
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4">Editar archivo</h1>
            <!-- Formulario para editar el proyecto con los datos obtenidos -->
            <form action="../Interfaces/editar_proyecto.php?id=<?php echo $id_proyecto; ?>" method="post" enctype="multipart/form-data">
            <!-- Campos para mostrar y editar los datos del proyecto -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Proyecto:</label>
                <input type="text" name="nombre" value="<?php echo $datos_proyecto['nomProyecto']; ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <input type="text" name="descripcion" value="<?php echo $datos_proyecto['descProyecto']; ?>" class="form-control">
            </div>
            <!-- Campo para el archivo Excel -->
            <div class="mb-3">
                <label for="archivo" class="form-label">Selecciona un archivo Excel (Si desea actualizarlo):</label>
                <input type="file" class="form-control" name="archivo" accept=".xlsx">
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
function cancelar() {
    window.location.href = "../Interfaces/index.php";
}
</script>

<?php 
    require_once './footer.php'; 
?>