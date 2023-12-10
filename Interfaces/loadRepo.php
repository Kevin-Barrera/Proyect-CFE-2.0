
<?php
session_start();

// Verifica si la variable de sesión 'idTrabajador' está configurada
if (!isset($_SESSION['idTrabajador'])) {
    // No se ha iniciado sesión, redirige a inicio.php
    header("Location: inicio.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "cfe";

$conexion2 = new mysqli($servername, $username, $password, $database);

if ($conexion2->connect_error) {
    die("Conexión fallida: " . $conexion2->connect_error);
}

// Obtiene el ID del trabajador desde la sesión
$idTrabajador = $_SESSION['idTrabajador'];

// Consulta para obtener los datos del trabajador con el ID de la sesión
$sql = "SELECT nombreTrab, apellidoTrab, telefono, puesto, usuario, imagen_perfil FROM trabajador WHERE idTrabajador = $idTrabajador";
$result = $conexion2->query($sql);

$trabajador = array();
if ($result->num_rows > 0) {
    // Obtén los datos del trabajador y almacénalos en el array $trabajador
    $trabajador = $result->fetch_assoc();
}

$conexion2->close();

require '../php/conexion.php';

/* Un arreglo de las columnas a mostrar en la tabla */
$columns = ['idProyecto', 'nomProyecto', 'rutaArc1', 'rutaArc2', 'idArchivo1', 'idArchivo2'];

/* Nombre de la tabla */
$table = "proyecto";
$id ='idProyecto';

$campo = isset($_POST['campo']) ? $conexion->real_escape_string($_POST['campo']) : null;

/* Filtrado */
$where = '';

if ($campo != null) {
    $where = "WHERE (";

    $cont = count($columns);
    for ($i = 0; $i < $cont; $i++) {
        $where .= $columns[$i] . " LIKE '%" . $campo . "%' OR ";
    }
    $where = substr_replace($where, "", -3);
    $where .= ")";
}

/* Limit */
$limit = isset($_POST['registros']) ? $conexion->real_escape_string($_POST['registros']) : 10;
$pagina = isset($_POST['pagina']) ? $conexion->real_escape_string($_POST['pagina']) : 0;

if (!$pagina) {
    $inicio = 0;
    $pagina = 1;
} else {
    $inicio = ($pagina - 1) * $limit;
}

$sLimit = "LIMIT $inicio, $limit";

/**
 * Ordenamiento
 */

 $sOrder = "";
 if(isset($_POST['orderCol'])){
    $orderCol = $_POST['orderCol'];
    $oderType = isset($_POST['orderType']) ? $_POST['orderType'] : 'asc';
    
    $sOrder = "ORDER BY ". $columns[intval($orderCol)] . ' ' . $oderType;
 }


/* Consulta */
$sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . "
FROM $table
$where
$sOrder 
$sLimit";
$resultado = $conexion->query($sql);
$num_rows = $resultado->num_rows;

/*consulta para el total de registros*/
$sqlFiltro = "SELECT FOUND_ROWS()";
$resFiltro = $conexion->query($sqlFiltro);
$row_Filtro = $resFiltro->fetch_array();
$totalFiltro = $row_Filtro[0];

/* Consulta para total de registro filtrados */
$sqlTotal = "SELECT count($id) FROM $table ";
$resTotal = $conexion->query($sqlTotal);
$row_total = $resTotal->fetch_array();
$totalRegistros = $row_total[0];

/* Mostrado resultados */
$output = [];
$output['totalRegistros'] = $totalRegistros;
$output['totalFiltro'] = $totalFiltro;
$output['data'] = '';
$output['paginacion'] = '';

if ($num_rows > 0) {
    foreach ($resultado->fetch_all(MYSQLI_ASSOC) as $row) {
        $output['data'] .= '<tr>';
        $output['data'] .= '<td>' . $row['idProyecto'] . '</td>';
        $output['data'] .= '<td>' . $row['nomProyecto'] . '</td>';

        if (!empty($row['rutaArc1'])) {
            $output['data'] .= '<td>' . $row['rutaArc1'];

            if (!empty($trabajador) && $trabajador['puesto'] == 'Jefe_Obra') {
                $output['data'] .= '<br><button class="btn btn-danger" onclick="confirmarEliminar(\'' . $row['idArchivo1'] . '\', 1)"><i class="fas fa-trash-alt"></i></button>';
            }

            $output['data'] .= '<a href="../php/descargar.php?idArchivo=' . $row['idArchivo1'] . '" class="btn btn-primary"><i class="fas fa-download"></i></a>';
            $output['data'] .= '</td>';
        } else {
            $output['data'] .= '<td></td>';
        }

        if (!empty($row['rutaArc2'])) {
            $output['data'] .= '<td>' . $row['rutaArc2'];

            if (!empty($trabajador) && $trabajador['puesto']== 'Jefe_Obra') {
                $output['data'] .= '<br><button class="btn btn-danger" onclick="confirmarEliminar(\'' . $row['idArchivo2'] . '\', 2)"><i class="fas fa-trash-alt"></i></button>';
            }

            $output['data'] .= '<a href="../php/descargar.php?idArchivo=' . $row['idArchivo2'] . '" class="btn btn-primary"><i class="fas fa-download"></i></a>';
            $output['data'] .= '</td>';
        } else {
            $output['data'] .= '<td></td>';
        }

        $output['data'] .= '</tr>';
    }
} else {
    $output['data'] .= '<tr>';
    $output['data'] .= '<td colspan="4">Sin resultados</td>';
    $output['data'] .= '</tr>';
}


if ($output['totalRegistros'] > 0) {
    $totalPaginas = ceil($output['totalRegistros'] / $limit);

    $output['paginacion'] .= '<nav>';
    $output['paginacion'] .= '<ul class="pagination">';

    $numeroInicio = 1;

    if(($pagina - 4) > 1){
        $numeroInicio = $pagina - 4;
    }
    $numeroFin = $numeroInicio + 9;

    if($numeroFin > $totalPaginas){
        $numeroFin = $totalPaginas;
    }

    for ($i = $numeroInicio; $i <= $numeroFin; $i++) {
        if ($pagina == $i){
            $output['paginacion'] .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
        } else {
            $output['paginacion'] .= '<li class="page-item"><a class="page-link" href="#" onclick="nextPage(' . $i . ')">' . $i . '</a></li>';
        }
    }

    $output['paginacion'] .= '</ul>';
    $output['paginacion'] .= '</nav>';
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
