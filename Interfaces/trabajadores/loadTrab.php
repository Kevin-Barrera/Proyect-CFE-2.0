<?php

require '../../php/conexion.php';

/* Un arreglo de las columnas a mostrar en la tabla */
$columns = ['idTrabajador', 'nombreTrab', 'apellidoTrab', 'telefono', 'puesto', 'usuario'];

/* Nombre de la tabla */
$table = "trabajador";
$id ='idTrabajador';

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
    while ($row = $resultado->fetch_assoc()) {
        $output['data'] .= '<tr>';
        $output['data'] .= '<td>' . $row['idTrabajador'] . '</td>';
        $output['data'] .= '<td>' . $row['nombreTrab'] . '</td>';
        $output['data'] .= '<td>' . $row['apellidoTrab'] . '</td>';
        $output['data'] .= '<td>' . $row['telefono'] . '</td>';
        $output['data'] .= '<td>' . $row['puesto'] . '</td>';
        $output['data'] .= '<td>' . $row['usuario'] . '</td>';
        $output['data'] .= '<td><a class="btn btn-danger" href="./trabajadores/eliminarTrabajador.php?idTrabajador=' . $row['idTrabajador'] . '" onclick="return confirm(\'¿Estás seguro de eliminar este trabajador?\')">Eliminar</a>
        <a class="btn btn-primary" href="./editar_Trabajador.php?idTrabajador=' . $row['idTrabajador'] . '">Modificar</a>
        </td>';
        $output['data'] .= '</tr>';
        
    }
} else {
    $output['data'] .= '<tr>';
    $output['data'] .= '<td colspan="7">Sin resultados</td>';
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
