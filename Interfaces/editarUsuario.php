<?php
require_once './header.php';
include "../php/bd.php";
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4">Usuario</h1>
            <center>
                <img src="login/img2/avatar.svg" alt="Perfil" style="width: 200px; height: 200px; border-radius: 50%;">
            </center>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Usuario:</label>
                <p><?php echo $usuario; ?></p>
            </div>
        </div>
    </div>
</div>

<script>
    function cancelar() {
        window.location.href = "./index.php"; .W
    }
</script>

<?php require_once './footer.php'; ?>