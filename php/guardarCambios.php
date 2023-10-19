<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conecta a la base de datos (ajusta las credenciales según tu configuración)
    $servername = "localhost";
    $username = "tu_usuario";
    $password = "tu_contraseña";
    $database = "tu_base_de_datos";

    $conexion = new mysqli($servername, $username, $password, $database);

    // Verifica la conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Recibe los datos editados enviados por JavaScript
    $datosEditados = $_POST;

    // Recorre los datos y realiza la actualización en la base de datos
    foreach ($datosEditados as $nombreCelda => $valorCelda) {
        // Extrae la fila y columna desde el nombre de la celda
        list($celda, $fila, $columna) = explode('_', $nombreCelda);

        // Realiza la actualización en la base de datos (ajusta esta consulta a tu estructura)
        $sql = "UPDATE tu_tabla SET valor = ? WHERE fila = ? AND columna = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sss", $valorCelda, $fila, $columna);
        $stmt->execute();
    }

    // Cierra la conexión a la base de datos
    $conexion->close();

    // Envía una respuesta de éxito a JavaScript
    $response = array('success' => true);
    echo json_encode($response);
} else {
    // Si la solicitud no es de tipo POST, envía una respuesta de error
    $response = array('success' => false, 'error' => 'Método no válido');
    echo json_encode($response);
}
?>