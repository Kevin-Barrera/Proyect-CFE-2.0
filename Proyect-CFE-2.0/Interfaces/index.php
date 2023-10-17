
<?php 
require_once './header.php';
?>

<div class="formulario-container">
        <h1>Subir archivo Excel</h1>
        <form class="formulario" action="../php/procesar.php" method="post" enctype="multipart/form-data">
            <label for="archivo">Selecciona un archivo Excel:</label>
            <input type="file" name="archivo" id="archivo" accept=".xlsx">
            <input type="submit" value="Subir archivo">
        </form>
    </div>

    <?php 
require_once './footer.php';
?>

<style>
    .formulario-container {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .formulario-container h1 {
            color: #333;
        }
        .formulario {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .formulario label {
            display: block;
            margin-bottom: 10px;
        }
        .formulario input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .formulario input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }
        .formulario input[type="submit"]:hover {
            background-color: #0056b3;
        }
</style>