<?php require_once './header.php'; ?>
<?php require_once '../php/conexion.php' ?>

<title>Trabajadores</title>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var nombreInput = document.getElementById('nombre');
        var apellidoInput = document.getElementById('apellido');
        var usuarioInput = document.getElementById('usuario');
        var contraInput = document.getElementById('contra');
        var contraText = document.getElementById('contraText');

        nombreInput.addEventListener('input', actualizarCredenciales);
        apellidoInput.addEventListener('input', actualizarCredenciales);

        function actualizarCredenciales() {
            var nombre = nombreInput.value.substring(0, 3).toLowerCase();
            var apellido = apellidoInput.value.split(' ')[0].toLowerCase();
            var usuario = nombre + apellido;
            var contrasena = apellido + '123';

            usuarioInput.value = usuario;

            // Actualizamos ambos campos (tipo password y tipo text)
            contraInput.value = contrasena;
            contraText.value = contrasena;

            // Mostramos el campo de tipo contraseña y ocultamos el campo de tipo texto
            contraInput.style.display = 'inline-block';
            contraText.style.display = 'none';
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var alerts = document.querySelectorAll('.alert');

        function ocultarAlertas() {
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }

        // Manejar el clic en el botón de registrar
        var registrarBtn = document.querySelector('.btn-primary');
        if (registrarBtn) {
            registrarBtn.addEventListener('click', function() {
                // Mostrar la alerta al hacer clic en el botón de registrar
                alerts.forEach(function(alert) {
                    alert.style.display = 'block';
                });

                // Ocultar la alerta después de 5 segundos
                setTimeout(ocultarAlertas, 5000);
            });
        }

        // Ocultar las alertas después de 5 segundos incluso si no se hace clic en el botón
        setTimeout(ocultarAlertas, 5000);
    });
</script>

<div class="container my-5">
    <!----- alertas----->
    <?php
    if (isset($_GET['mensajeP']) and $_GET['mensajeP']  == 'falta algun dato') {
    ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Rellena todos los campos.
        </div>
    <?php
    }
    ?>
    <?php
    if (isset($_GET['mensajeP']) and $_GET['mensajeP']  == 'trabajador registrado') {
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Trabajador registado: </strong> Trabajador registrado correctamente
        </div>
    <?php
    }
    ?>
    <?php
    if (isset($_GET['mensajeP']) and $_GET['mensajeP']  == 'error') {
    ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error al actualizar!</strong> Intenta de nuevo
        </div>
    <?php
    }
    ?>
    <?php
    if (isset($_GET['mensajeP']) and $_GET['mensajeP']  == 'eliminado') {
    ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Eliminado!</strong> El trabajador se elimino correctamente.
        </div>
    <?php
    }
    ?>
    <!----- Fin alertas----->

    <h1>Trabajadores</h1>

    <div class="container py-4 text-center">
        <div class="row g-4">
            <div class="col-auto">
                <label for="num_registros" class="col-form-label">Mostrar:</label>
            </div>
            <div class="col-auto">
                <select name="num_registros" id="num_registros" class="form-select trabajadores-input" style="width: 80px;  height: 35px; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
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
                <input type="text" name="campo" id="campo" class="form-control trabajadores-input" style="width: 100%;  height: 35px;  padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
            </div>
        </div>

        <div class="row py-4">
            <div class="col">
                <table id="tabla" class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <th class="sort asc">ID</th>
                        <th class="sort asc">Nombre</th>
                        <th class="sort asc">Apellidos</th>
                        <th class="sort asc">Teléfono</th>
                        <th class="sort asc">Puesto</th>
                        <th class="sort asc">Usuario</th>
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

            let url = "./trabajadores/loadTrab.php"
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


    <h1>Agregar Trabajador</h1>
    <form class="contact-form" method="POST" action="./trabajadores/registrarTrabajador.php">
        <div>
            <label for="nombre">Nombre: </label>
            <input type="text" id="nombre" name="nombre" placeholder="Ingresa el nombre" required>
            <label for="apellido">Apellidos: </label>
            <input type="text" id="apellido" name="apellido" placeholder="Ingresa el apellido" required>
            <label for="telefono">Teléfono: </label>
            <input type="tel" id="telefono" name="telefono" placeholder="Ingresa el Telefono" maxlength="10" required>
        </div>
        <div>

            <label for="puesto">Puesto:</label>
            <select id="puesto" name="puesto" required>
                <option value="" disabled selected>Selecciona un puesto</option>
                <option value="Jefe_Obra">Jefe de Obra</option>
                <option value="Trabajador">Trabajador</option>
            </select>
            <label for="usuario">Usuario: </label>
            <input type="text" id="usuario" name="usuario" placeholder="Ingresa el usuario" required readonly>
            <div>
                <label for="password">Contraseña:</label>
                <div class="password-container">
                    <input type="text" id="contraText" style="display: none;" readonly>
                    <input type="password" id="contra" name="contra" placeholder="Ingresa la contraseña" required readonly>
                </div>
            </div>
        </div>
        <div style="grid-column: span 2;">
            <input type="hidden" name="oculto" value="1">
            <button type="submit" class="btn btn-primary full-width">Registrar</button>
        </div>

    </form>
</div>
<!-- Bootstrap core JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<?php require_once './footer.php'; ?>