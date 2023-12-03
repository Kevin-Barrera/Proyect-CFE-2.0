<?php
if (isset($_GET['idArchivo']) && isset($_GET['tipo'])) {
    $idArchivo = $_GET['idArchivo'];
    $tipo = $_GET['tipo'];

    $archivoPath = '../Archivos/' . $idArchivo . '.xlsx';

    // Verificar si el archivo existe antes de intentar eliminarlo
    if (file_exists($archivoPath)) {
        unlink($archivoPath);

        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "cfe";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        if ($tipo == 1) {
            $sql = "UPDATE proyecto SET rutaArc1 = '' WHERE idArchivo1 = '$idArchivo'";
        } elseif ($tipo == 2) {
            $sql = "UPDATE proyecto SET rutaArc2 = '' WHERE idArchivo2 = '$idArchivo'";
        }

        $conn->query($sql);
        $conn->close();
    } else {
        echo "El archivo no existe o ya ha sido eliminado.";
    }
}

header("Location: ../Interfaces/reportes.php");
exit();
?>
