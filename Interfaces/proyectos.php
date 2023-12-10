<?php require_once './header.php'; ?>
<?php
// Conexión a la base de datos (reemplaza con tus propios datos)
$servername = "localhost";
$username = "root";
$password = "";
$database = "cfe";

$conexion = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Comprobar si se ha enviado una solicitud de eliminación
if (isset($_GET['eliminar_proyecto'])) {
    $id_proyecto_eliminar = $_GET['eliminar_proyecto'];

    // Consulta SQL para eliminar el proyecto por ID
    $sql_eliminar = "DELETE FROM proyecto WHERE idProyecto = ?";
    $stmt_eliminar = $conexion->prepare($sql_eliminar);
    $stmt_eliminar->bind_param("i", $id_proyecto_eliminar);

    // Ejecutar la consulta de eliminación
    if ($stmt_eliminar->execute()) {
        echo "<script>alert('El proyecto ha sido eliminado correctamente.');</script>";
    } else {
        echo "Error al eliminar el proyecto: " . $stmt_eliminar->error;
    }

    // Cerrar la consulta de eliminación
    $stmt_eliminar->close();
}
?>

<div class="container my-5">
    <h1>Proyectos registrados</h1>

    <div class="container py-4 text-center">
        <div class="row g-4">
            <div class="col-auto">
                <label for="num_registros" class="col-form-label">Mostrar:</label>
            </div>
            <div class="col-auto">
                <select name="num_registros" id="num_registros" class="form-select" style="width: 80px;  height: 35px; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
            <div class="col-auto">
                <label for="num_registros" class="col-form-label">registros </label>
            </div>

            <div class="col-4"></div>

            <div class="col-auto">
                <label for="campo" class="col-form-label">Buscar:</label>
            </div>
            <div class="col-auto">
                <input type="text" name="campo" id="campo" class="form-control" style="width: 100%;  height: 35px;  padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
            </div>
        </div>

        <div class="row py-4">
            <div class="col">
                <table id="tabla" class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <th class="sort asc">ID</th>
                        <th class="sort asc">Nombre del Proyecto</th>
                        <th class="sort asc">Descripción</th>
                        <th class="sort asc">Nombre del Reporte inicial</th>
                        <th class="sort asc">Nombre del Reporte Final</th>
                        <th>Opciones</th>
                    </thead>
                    <!-- El id del cuerpo de la tabla. -->
                    <tbody id="content">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <label id="lbl-total"></label>
            </div>

            <div class="col-6" id="nav-paginacion"></div>
            <input type="hidden" id="pagina" value="1">
            <input type="hidden" id="orderCol" value="0">
            <input type="hidden" id="orderType" value="asc">
        </div>

    </div>

    <script>
        getData()
        document.getElementById("campo").addEventListener("keyup", function() {
            getData()
        }, false)
        document.getElementById("num_registros").addEventListener("change", function() {
            getData()
        }, false)
        /* Peticion AJAX */
        function getData() {
            let input = document.getElementById("campo").value
            let num_registros = document.getElementById("num_registros").value
            let content = document.getElementById("content")
            let pagina = document.getElementById("pagina").value
            let orderCol = document.getElementById("orderCol").value
            let orderType = document.getElementById("orderType").value

            if (pagina == null) {
                pagina = 1
            }

            let url = "./loadProy.php"
            let formaData = new FormData()
            formaData.append('campo', input)
            formaData.append('registros', num_registros)
            formaData.append('pagina', pagina)
            formaData.append('orderCol', orderCol)
            formaData.append('orderType', orderType)

            fetch(url, {
                    method: "POST",
                    body: formaData
                }).then(response => response.json())
                .then(data => {
                    content.innerHTML = data.data
                    document.getElementById("lbl-total").innerHTML ='Mostrando ' +data.totalRegistros +
                    ' de '+ data.totalRegistros + ' registros'
                    document.getElementById("nav-paginacion").innerHTML = data.paginacion
                }).catch(err => console.log(err))
        }

        function nextPage(pagina){
            document.getElementById('pagina').value = pagina
            getData()
        }

        let columns = document.getElementsByClassName("sort")
        let tamanio = columns.length
        for(let i = 0; i < tamanio; i++){
            columns[i].addEventListener("click", ordenar)
        }

        function ordenar(e){
            let elemento = e.target
            document.getElementById('orderCol').value = elemento.cellIndex

            if(elemento.classList.contains("asc")){
                document.getElementById("orderType").value = "asc"
                elemento.classList.remove("asc")
                elemento.classList.add("desc")
            } else {
                document.getElementById("orderType").value = "desc"
                elemento.classList.remove("desc")
                elemento.classList.add("asc")
            }

            getData()
        }
    </script>

</div>


<script>
    function eliminarProyecto(idProyecto) {
        if (confirm("¿Estás seguro de que quieres eliminar este proyecto?")) {
            window.location.href = "./proyectos.php?eliminar_proyecto=" + idProyecto;
        }
        window.location.href = "./proyectos.php";
    }

    function mostrarCarga() {
        Swal.fire({
            title: 'Cargando',
            html: 'Por favor, espera...',
            allowOutsideClick: false,
            showConfirmButton: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            },
        });
    }

    function ocultarCarga() {
        Swal.close();
    }

    function generarReporte(idProyecto) {
        mostrarCarga();
        // Aquí haces la llamada AJAX al servidor para generar el reporte
        var xhr = new XMLHttpRequest();
        xhr.open('GET', './generar_reporte.php?idProyecto=' + idProyecto, true);
        console.log('');
        xhr.onload = function() {
            if (xhr.status === 200) {
                ocultarCarga();
                alert("Reporte generado con éxito.");
                window.location.href = "./proyectos.php";
            } else {
                alert("Error al generar el reporte.");
            }
        };

        xhr.send();
    }
</script>
<!-- Bootstrap core JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<?php require_once './footer.php'; ?>