<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function conectarBaseDeDatos($servername, $username, $password, $database)
{
    $conexion = new mysqli($servername, $username, $password, $database);
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }
    return $conexion;
}

function obtenerRutaArchivo($tipo, $idArchivo1, $idArchivo2)
{
    $idArchivo = ($tipo == 1) ? $idArchivo1 : $idArchivo2;
    return "../Archivos/" . $idArchivo . ".xlsx";
}

function cargarArchivo($rutaArchivo)
{
    require '../vendor/autoload.php';
    return \PhpOffice\PhpSpreadsheet\IOFactory::load($rutaArchivo);
}

function convertirCoordenadas($coordenadas)
{
    // Si es un rango, extraer las coordenadas de inicio
    $coordenadasInicio = explode(':', $coordenadas)[0];

    list($columna, $fila) = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::coordinateFromString($coordenadasInicio);
    return [
        'columna' => \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($columna),
        'fila' => $fila,
    ];
}

function ordenarCeldasCombinadas($celdasCombinadas)
{
    // Dividir las coordenadas en columnas y filas para facilitar la comparación
    $coordenadasDivididas = array_map('convertirCoordenadas', $celdasCombinadas);

    // Ordenar las coordenadas combinadas por filas y columnas
    usort($coordenadasDivididas, function ($celda1, $celda2) {
        // Ordenar por filas
        $compareFilas = $celda2['fila'] - $celda1['fila'];

        if ($compareFilas === 0) {
            // Si las filas son iguales, ordenar por columnas
            return $celda1['columna'] - $celda2['columna'];
        }

        return $compareFilas;
    });

    // Reconstruir el array de celdas combinadas ordenadas
    $celdasCombinadasOrdenadas = array_map(function ($coordenadas) {
        return convertirCoordenadasInversa($coordenadas['columna'], $coordenadas['fila']);
    }, $coordenadasDivididas);

    return $celdasCombinadasOrdenadas;
}

function convertirCoordenadasInversa($columna, $fila)
{
    $letraColumna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columna);
    return $letraColumna . $fila;
}

function procesarCeldasCombinadas($celdasCombinadas)
{
    $celdasProcesadas = [];

    foreach ($celdasCombinadas as $coordenadasCombinadas) {
        // No es necesario usar explode para celdas individuales
        $celdasProcesadas[] = $coordenadasCombinadas;
    }

    // Quitar duplicados
    $celdasProcesadas = array_unique($celdasProcesadas);

    echo "Celdas procesadas: ";
    print_r($celdasProcesadas);

    return $celdasProcesadas;
}



function obtenerCeldasEntreCoordenadas($coordenadasInicio, $coordenadasFin)
{
    $columnaInicio = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($coordenadasInicio[0]);
    $filaInicio = $coordenadasInicio[1];
    $columnaFin = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($coordenadasFin[0]);
    $filaFin = $coordenadasFin[1];

    $celdas = [];

    for ($fila = $filaInicio; $fila <= $filaFin; $fila++) {
        for ($columna = $columnaInicio; $columna <= $columnaFin; $columna++) {
            $celdas[] = convertirCoordenadasInversa($columna, $fila);
        }
    }

    return $celdas;
}



function aplicarCambios($worksheet, $celdas, $celdasCombinadas)
{
    $celdasCombinadasProcesadas = procesarCeldasCombinadas($celdasCombinadas);

    foreach ($celdas as $fila => $columnas) {
        if (!is_array($columnas)) {
            continue; // Saltar si no es un array
        }

        foreach ($columnas as $columna => $valor) {
            if (!is_numeric($columna)) {
                continue; // Saltar si la clave no es numérica
            }

            $columnaIndex = $columna + 1;
            $filaIndex = $fila + 1;

            $cellAddress = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnaIndex) . $filaIndex;

            if (!in_array($cellAddress, $celdasCombinadasProcesadas)) {
                $worksheet->setCellValue($cellAddress, $valor);
                echo "Columna: ";
                if (isset($columnaIndex)) {
                    echo $columnaIndex;
                } else {
                    echo "No definida";
                }
            
                echo ", fila: ";
            
                if (isset($filaIndex)) {
                    echo $filaIndex;
                } else {
                    echo "No definida";
                }
            
                echo ", valor: ";
            
                if (isset($valor)) {
                    echo $valor;
                } else {
                    echo "No definido";
                }
            
                echo "<br>";
            }
            
        }
    }
}




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "cfe";

    $conexion = conectarBaseDeDatos($servername, $username, $password, $database);

    $idProyecto = $_POST["idProyecto"];
    $tipo = $_POST["tipo"];
    $celdas = isset($_POST["celda"]) && is_array($_POST["celda"]) ? $_POST["celda"] : [];

    echo "ID del Proyecto: " . $idProyecto . "<br>";
    echo "Tipo: " . $tipo . "<br>";

    echo "Datos de las celdas: <pre>";
    // print_r($celdas);
    echo "</pre>";

    $sql = "SELECT idArchivo1, idArchivo2 FROM proyecto WHERE idProyecto = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idProyecto);
    $stmt->execute();
    $stmt->bind_result($idArchivo1, $idArchivo2);
    $stmt->fetch();
    $stmt->close();

    $rutaArchivo = obtenerRutaArchivo($tipo, $idArchivo1, $idArchivo2);

    $spreadsheet = cargarArchivo($rutaArchivo);
    $worksheet = $spreadsheet->getActiveSheet();

    $celdasCombinadas = $worksheet->getMergeCells();
    $celdasCombinadasOrdenadas = ordenarCeldasCombinadas($celdasCombinadas);

    echo "Celdas combinadas ordenadas:<br>";
    print_r($celdasCombinadasOrdenadas);

    aplicarCambios($worksheet, $celdas, $celdasCombinadasOrdenadas);

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

    try {
        $writer->save('../Archivos/prueba.xlsx');
        echo "Cambios aplicados y archivo guardado con éxito.";
    } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
        echo "Error al guardar el archivo: " . $e->getMessage();
    }
} else {
    echo "Solicitud no válida.";
}
