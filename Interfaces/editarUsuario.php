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
            <style>
    .mb-3 {
        text-align: center;
    }

    .form-label {
        display: block;
    }

    p {
        display: inline-block;
        background-color: #e0e0e0; /* Puedes ajustar el color de fondo según tus preferencias */
        padding: 10px;
        border-radius: 10px; /* Ajusta el valor según tus preferencias */
    }
</style>

<div class="mb-3">
    <label for="nombre" class="form-label">Nombre del Usuario:</label>
    <p><?php echo $nombreTrab .' '. $apellidoTrab ?></p>
</div>

<div class="mb-3">
    <label for="nombre" class="form-label">Telefono:</label>
    <p><?php echo $telefono ?></p>
</div>

<div class="mb-3">
    <label for="nombre" class="form-label">Puesto:</label>
    <p><?php echo $puesto ?></p>
</div>
        </div>
    </div>
</div>

<style>
    .cerrar-sesion-btn {
        background-color: #ff0000; /* Color rojo, puedes ajustar según tus preferencias */
        color: #fff; /* Color del texto */
        padding: 10px 20px; /* Ajusta el padding según tus preferencias */
        border: none;
        border-radius: 5px; /* Ajusta el radio de las esquinas según tus preferencias */
        cursor: pointer;
    }
</style>

<center><a href="index.php" class="cerrar-sesion-btn">Cerrar Sesión</a></center>
<br>

<?php require_once './footer.php'; ?>