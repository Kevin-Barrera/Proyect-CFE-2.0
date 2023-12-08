<?php

require '../../php/conexion.php';

/* Un arreglo de las columnas a mostrar en la tabla */
$columns = ['idTrabajador', 'nombreTrab', 'apellidoTrab', 'telefono', 'puesto', 'usuario'];

/* Nombre de la tabla */
$table = "trabajador";
$id = 'idTrabajador';

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
/*$pagina = isset($_POST['pagina']) ? $conexion->real_escape_string($_POST['pagina']) : 0;

if (!$pagina) {
    $inicio = 0;
    $pagina = 1;
} else {
    $inicio = ($pagina - 1) * $limit;
}*/

$sLimit = "LIMIT $limit";


/* Consulta */
$sql = "SELECT " . implode(", ", $columns) . "
FROM $table
$where 
$sLimit";
$resultado = $conexion->query($sql);
$num_rows = $resultado->num_rows;

$html =''; 

if ($num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['idTrabajador'] . '</td>';
        $html .= '<td>' . $row['nombreTrab'] . '</td>';
        $html .= '<td>' . $row['apellidoTrab'] . '</td>';
        $html .= '<td>' . $row['telefono'] . '</td>';
        $html .= '<td>' . $row['puesto'] . '</td>';
        $html.= '<td>' . $row['usuario'] . '</td>';
        $html .= '<td><a class="btn btn-danger" href="./trabajadores/eliminarTrabajador.php?idTrabajador=' . $row['idTrabajador'] . '" onclick="return confirm(\'¿Estás seguro de eliminar este trabajador?\')">Eliminar</a>
        <a class="btn btn-primary" href="./editar_Trabajador.php?idTrabajador=' . $row['idTrabajador'] . '">Modificar</a>
        </td>';
        $html .= '</tr>';
        
    }
} else {
    $html .= '<tr>';
    $html.= '<td colspan="7">Sin resultados</td>';
    $html.= '</tr>';
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);
