<?php require_once './header.php'; ?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "cfe";

$conexion = new mysqli($servername, $username, $password, $database);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$sql = "SELECT idTrabajador, nombreTrab, apellidoTrab, telefono, puesto, usuario FROM trabajador";
$result = $conexion->query($sql);

$trabajadores = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $trabajadores[] = $row;
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Css/styles-trab.css">
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

</head>

<body>
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
        <div class="table-responsive">
            <table id="tabla" class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Teléfono</th>
                        <th>Puesto</th>
                        <th>Usuario</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trabajadores as $trabajador) { ?>
                        <tr>
                            <td><?php echo $trabajador["idTrabajador"]; ?></td>
                            <td><?php echo $trabajador["nombreTrab"]; ?></td>
                            <td><?php echo $trabajador["apellidoTrab"]; ?></td>
                            <td><?php echo $trabajador["telefono"]; ?></td>
                            <td><?php echo $trabajador["puesto"]; ?></td>
                            <td><?php echo $trabajador["usuario"]; ?></td>
                            <td>
                                <a href="./eliminarTrabajador.php?idTrabajador=<?php echo $trabajador["idTrabajador"]; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este trabajador?')">Eliminar</a>
                                <a href="./editar_Trabajador.php?idTrabajador=<?php echo $trabajador["idTrabajador"]; ?>" class="btn btn-primary">Modificar</a>
                                <?php  ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <br><br>
        <h1>Agregar Trabajador</h1>
        <form class="contact-form" method="POST" action="./registrarTrabajador.php">
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
</body>

</html>

<?php require_once './footer.php'; ?>