<?php
require_once './header.php';
include "../php/bd.php";
?>

<style>
        /* Estilos para el botón */
        button {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 10px;
            margin: 0;
            background: linear-gradient(to right, #f39c12, #e67e22); /* Color naranja */
            color: #fff; /* Texto blanco */
            border: none;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s; /* Transición para el efecto hover */
        }

        /* Estilo adicional al pasar el ratón sobre el botón */
        button:hover {
            background: linear-gradient(to right, #e67e22, #d35400); /* Tono más oscuro de naranja */
        }

        /* Estilos para el botón "Subir Imagen" */
        input[type="submit"] {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 10px;
            margin: 0;
            background: linear-gradient(to right, #a9a9a9, #808080); /* Cambiado a tonos de gris */
            color: #fff; /* Texto blanco */
            border: none;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s; /* Transición para el efecto hover */
        }

        /* Estilo adicional al pasar el ratón sobre el botón "Subir Imagen" */
        input[type="submit"]:hover {
            background: linear-gradient(to right, #2ecc71, #27ae60); /* Cambiado a verde */
        }
    </style>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4">Usuario</h1>
            <center>
            <img src="<?php echo $imagen_perfil; ?>" alt="Perfil" style="width: 50px; height: 50px; border-radius: 50%;">
            </center>

            <form action="procesar_imagen.php?idTrabajador=<?php echo $idTrabajador; ?>" method="post" enctype="multipart/form-data">
            <br>
            <center>
                <input type="file" id="imagen" name="imagen" accept="image/*" style="display: none;">
                <button type="button" onclick="document.getElementById('imagen').click();">Seleccionar Imagen</button>
                <br>
                <br>
                <input type="submit" value="Subir Imagen">
            </center>
        </form>

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
            <br>
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