<?php
require_once './header.php';
include "../php/bd.php";
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4">Usuario</h1>
            <center>
            <img class="imgPerfil" style="max-width: 10%;" src="login/img2/avatar.svg" alt="Perfil">
            </center>

            <style>
                .container {
                    background-color: #fff;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                .imgPerfil {
                    max-width: 100%;
                    height: auto;
                    border-radius: 50%;
                    margin-bottom: 20px;
                }

                .mb-3 {
                    text-align: center;
                }

                .form-label {
                    display: block;
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                .parrafo {
                    display: inline-block;
                    padding: 10px 200px;
                    border-radius: 10px;
                    margin: 0;
                    background: linear-gradient(to right, #3498db, #2980b9); /* Cambia estos colores según tus preferencias */
                    color: #fff; /* Texto blanco */
                    cursor: default;
                }

                .cerrar-sesion-btn {
                    background-color: #ff0000;
                    color: #fff;
                    padding: 10px 20px;
                    margin-top: 15px;
                }

                .cerrar-sesion-btn:hover {
                    color: #fff;
                    background-color: #e02d1b;
                    border-color: #d52a1a;
                }
            </style>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Usuario:</label>
                <p class="parrafo"><?php echo $nombreTrab .' '. $apellidoTrab ?></p>
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Telefono:</label>
                <p class="parrafo"><?php echo $telefono ?></p>
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Puesto:</label>
                <p class="parrafo"><?php echo $puesto ?></p>
            </div>
        </div>
    </div>
    <center><a href="inicio.php" class="btn cerrar-sesion-btn">Cerrar Sesión</a></center>
</div>



<?php require_once './footer.php'; ?>