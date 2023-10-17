<?php require_once './header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-4">Subir archivo Excel</h1>
            <form class="formulario" action="../php/procesar.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="archivo" class="form-label">Selecciona un archivo Excel:</label>
                    <input type="file" class="form-control" id="archivo" name="archivo" accept=".xlsx">
                </div>
                <button type="submit" class="btn btn-primary">Subir archivo</button>
            </form>
        </div>
    </div>
</div>



<?php
$directorio = '../Archivos'; // Ruta a la carpeta donde se almacenan los archivos
$archivos = scandir($directorio);
$archivos_excel = array();

foreach ($archivos as $archivo) {
    if (pathinfo($archivo, PATHINFO_EXTENSION) === 'xlsx') {
        $archivos_excel[] = $archivo;
    }
}
?>

<div class="container my-5">
    <h1>Archivos Excel Almacenados</h1>
    <div class="table-responsive">
        <table id="tabla" class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre del Archivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($archivos_excel as $archivo) { ?>
                    <tr>
                        <td><a href="Archivos/<?php echo $archivo; ?>"><?php echo $archivo; ?></a></td>
                        <td>
                            <a href="../php/eliminar.php?archivo=<?php echo $archivo; ?>" class="btn btn-danger">Eliminar</a>
                            <a href="convertir.php?archivo=<?php echo $archivo; ?>" class="btn btn-primary">Convertir</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- script para exportar a excel -->
<script>
    const $btnExportar = document.querySelector("#btnExportar"),
        $tabla = document.querySelector("#tabla");

    $btnExportar.addEventListener("click", function() {
        let tableExport = new TableExport($tabla, {
            exportButtons: false, // No queremos botones
            filename: "Reporte de prueba", //Nombre del archivo de Excel
            sheetname: "Reporte de prueba", //Título de la hoja
        });
        let datos = tableExport.getExportData();
        let preferenciasDocumento = datos.tabla.xlsx;
        tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
    });
</script>

<?php require_once './footer.php'; ?>
