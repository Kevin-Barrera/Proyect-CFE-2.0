<?php

    function validar($texto) {
        $texto = trim($texto);
        $texto = stripslashes($texto);
        $texto = htmlspecialchars($texto);
        return $texto;
    }

    function conectar() {
        DEFINE('SERVIDOR', 'localhost');
        DEFINE('USUARIO', 'root');
        DEFINE('PASSWORD', '');
        DEFINE('BD', 'cfe');

        $resultado = mysqli_connect(SERVIDOR, USUARIO, PASSWORD, BD);

        return $resultado;
    }

    if(empty($_POST)){
        //redireccionar('Prohibido', 'index.php');
        return;
    }

    $postre = validar($_POST['postre']);
    $descripcion = validar($_POST['descripcion']);
    $tipo = validar($_POST['tipo']);
    $precio = validar($_POST['precio']);

    if($postre == '' || $descripcion == '' || $tipo == '' || $precio == '') {
        //redireccionar('Información no válida.', 'agregar.php');
        return;
    }

    $conexion = conectar();

    if(!$conexion) {
        //redireccionar('Error en la conexión.', 'agregar.php');
        return;
    }

    //$imagen = subir_imagen($_FILES['imagen']);

    $sql = "insert into postre(postre, descripcion, precio, tipo, imagen) values ('$postre','$descripcion','$precio','$tipo','$imagen')";

    $resultado = mysqli_query($conexion, $sql);

    if($resultado) {
        //redireccionar('Datos guardados exitosamente.', 'agregar.php');
    } else {
        //redireccionar('Error: ' . mysql_error($conexion), 'agregar.php');
    }

    mysqli_close($conexion);

?>