<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Archivo</title>
    <style>
        table {
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        .celda-pequena {
            width: 125px; /* Establece el ancho para las celdas pequeñas */
            height: 20px; /* Establece la altura para las celdas pequeñas */
        }
    </style>
    <!-- Importa jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Archivo: </h1>

    <?php
    if (isset($datosProyecto)) {
        // Si tienes datos del proyecto, puedes construir la tabla de edición con los datos
        echo '<table>';
        for ($i = 0; $i < 10; $i++) {
            echo '<tr>';
            for ($j = 0; $j < 5; $j++) {
                // Agrega un identificador único a cada celda
                echo '<td class="celda-pequena" contenteditable="true" data-fila="' . $i . '" data-columna="' . $j . '"></td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    } else {
        // Si no tienes datos del proyecto, muestra una tabla vacía 10x5
        echo '<table>';
        for ($i = 0; $i < 10; $i++) {
            echo '<tr>';
            for ($j = 0; $j < 5; $j++) {
                // Agrega un identificador único a cada celda
                echo '<td class="celda-pequena" contenteditable="true" data-fila="' . $i . '" data-columna="' . $j . '"></td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }
    ?>

    <!-- Agrega botones para guardar los cambios y cancelar -->
    <button type="button" id="guardarCambios">Guardar Cambios</button>
    <button type="button" id="cancelarEdicion">Cancelar</button>

    <!-- Scripts de JavaScript -->
    <script>
    $(document).ready(function() {
        // Agrega un evento clic al botón "Guardar Cambios"
        $("#guardarCambios").on("click", function () {
            // Crear un objeto para almacenar los datos editados
            var datosEditados = {};

            // Recorrer las celdas editables
            $(".celda-pequena").each(function() {
                var fila = $(this).data("fila");
                var columna = $(this).data("columna");
                var valor = $(this).text();
                datosEditados["celda_" + fila + "_" + columna] = valor;
            });

            // Realizar una solicitud AJAX para enviar los datos al servidor
            $.ajax({
                url: 'guardarCambios.php', // Nombre del archivo PHP que procesará los cambios
                type: 'POST',
                data: datosEditados,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        alert("Cambios guardados con éxito.");
                        // Redirige al usuario a la página de inicio (index.php)
                        window.location.href = "../Interfaces/index.php";
                    } else {
                        alert("Error al guardar los cambios.");
                    }
                },
                error: function (error) {
                    console.error("Error al enviar los datos al servidor:", error);
                }
            });
        });

        // Agrega un evento clic al botón "Cancelar"
        $("#cancelarEdicion").on("click", function () {
            // Redirige al usuario a la página de inicio (en este caso, index.php)
            window.location.href = "../Interfaces/index.php";
        });
    });
</script>
</body>
</html>