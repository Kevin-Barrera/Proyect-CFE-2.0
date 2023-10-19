<?php require_once './header.php'; ?>
<?php
if (isset($_GET['success']) && $_GET['success'] == "true") {
    echo "<div id='successAlert' class='alert alert-success'>Los datos se han insertado con éxito.</div>";
}
?>
<script>
    // Espera 5 segundos y luego oculta la alerta
    setTimeout(function () {
        var successAlert = document.getElementById('successAlert');
        if (successAlert) {
            successAlert.style.display = 'none';
        }
    }, 5000); // 3000 milisegundos (3 segundos)
</script>


<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4">Panel de administración</h1>
            <form class="formulario text-center">
                <div class="mb-3">
                    <label for="archivo" class="form-label" style="font-size: 1.5rem;">Opciones de proyecto:</label>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#agregar">Crear proyecto</button>
                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#editar">Editar proyecto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
    include './agregar.php';
    include './editar.php';
    require_once './footer.php'; 
?>