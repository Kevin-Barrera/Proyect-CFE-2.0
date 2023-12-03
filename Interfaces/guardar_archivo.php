<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idArchivo = $_FILES['file']['name'];
    $fileContent = file_get_contents($_FILES['file']['tmp_name']);
    file_put_contents("../Archivos/{$idArchivo}", $fileContent);

    echo 'OK';
} else {
    echo 'Error: Método no permitido';
}
?>
