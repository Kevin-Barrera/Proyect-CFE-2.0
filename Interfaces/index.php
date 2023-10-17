
<?php 
require_once './header.php';
?>

<div class="formulario-container">
        <h1>Subir archivo Excel</h1>
        <form class="formulario" action="procesar.php" method="post" enctype="multipart/form-data">
            <label for="archivo">Selecciona un archivo Excel:</label>
            <input type="file" name="archivo" id="archivo" accept=".xlsx">
            <input type="submit" value="Subir archivo">
        </form>
    </div>

    <?php 
require_once './footer.php';
?>