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
    include './agregar.php';
    include './editar.php';
    require_once './footer.php'; 
?>

