var jsonData;  // Declarar la variable en un ámbito más amplio

    function mostrarTablaExcel(archivoXlsx) {
        // Leer el archivo Excel
        var xhr = new XMLHttpRequest();
        xhr.open('GET', archivoXlsx, true);
        xhr.responseType = 'arraybuffer';

        xhr.onload = function (e) {
            var arraybuffer = xhr.response;

            /* convertimos datos binarios a tipo de datos de trabajo */
            var data = new Uint8Array(arraybuffer);
            var arr = new Array();
            for (var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
            var bstr = arr.join("");

            // Llamar a XLSX para analizar el archivo
            var workbook = XLSX.read(bstr, {
                type: 'binary'
            });

            // Obtener la primera hoja de cálculo
            var firstSheet = workbook.SheetNames[0];
            var worksheet = workbook.Sheets[firstSheet];

            // Obtener la configuración de celdas combinadas
            var merges = worksheet['!merges'];

            // Convertir datos de la hoja de cálculo a formato JSON
            jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

            var headers = jsonData.length > 0 ? Object.keys(jsonData[0]) : [];

            // Crear la tabla HTML con clases de Bootstrap y el thead
            var table = '<table class="table table-striped table-bordered table-responsive" id="excelTable">';

            // Agregar thead con clase thead-dark
            table += '<thead class="thead-dark" style="text-align: center;">';
            for (var i = 0; i < 1; i++) {
                table += '<tr>';
                headers.forEach(header => {
                    var columnIndex = headers.indexOf(header);
                    var isMergedCell = merges && merges.some(function (range) {
                        return i >= range.s.r && i <= range.e.r && columnIndex >= range.s.c && columnIndex <= range.e.c;
                    });

                    var cellValue = jsonData[i][header] === undefined ? '' : jsonData[i][header];
                    var colspanAttr = isMergedCell ? ' colspan="' + (columnIndex - merges.find(range => columnIndex >= range.s.c && columnIndex <= range.e.c).s.c + 1) + '"' : '';

                    table += '<th' + colspanAttr + '>' + cellValue + '</th>';
                });
                table += '</tr>';
            }
            table += '</thead>';

            // Agregar tbody
            table += '<tbody>';

            for (var i = 1; i < jsonData.length; i++) {
                table += '<tr>';
                headers.forEach(header => {
                    var columnIndex = headers.indexOf(header);
                    var isMergedCell = merges && merges.some(function (range) {
                        return i >= range.s.r && i <= range.e.r && columnIndex >= range.s.c && columnIndex <= range.e.c;
                    });

                    var cellValue = jsonData[i][header] === undefined ? '' : jsonData[i][header];
                    var colspanAttr = isMergedCell ? ' colspan="' + (columnIndex - merges.find(range => columnIndex >= range.s.c && columnIndex <= range.e.c).s.c + 1) + '"' : '';

                    // Agregar atributo contenteditable para hacer la celda editable
                    table += '<td' + colspanAttr + ' contenteditable="true" oninput="updateCellValue(this)">' + cellValue + '</td>';
                });
                table += '</tr>';
            }

            // Cerrar tbody y table
            table += '</tbody>';
            table += '</table>';

            // Agregar la tabla al contenedor deseado
            document.getElementById('tablaExcel').innerHTML = table;
        }

        // Función para actualizar el valor de la celda en el JSON al editar
        function updateCellValue(cell) {
            var table = document.getElementById('excelTable');
            var rowIndex = cell.parentNode.rowIndex - 1; // Restar 1 para ajustarse al índice del JSON
            var colIndex = cell.cellIndex;

            jsonData[rowIndex][headers[colIndex]] = cell.textContent;
        }
        xhr.send();
    }

    // Nueva función para guardar los cambios en el archivo
    function guardarCambios() {
        // Obtener la tabla actualizada
        var table = document.getElementById('excelTable');
    
        // Convertir la tabla a una hoja de cálculo
        var updatedData = XLSX.utils.table_to_sheet(table);
    
        // Crear un libro y agregar la hoja de cálculo actualizada
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, updatedData, "Sheet1");
    
        // Guardar el archivo con un nuevo nombre o el mismo (depende de tus necesidades)
        var newFileName = "newFile.xlsx";
    
        XLSX.writeFile(wb, newFileName);
        alert("Cambios guardados con éxito en el archivo: " + newFileName);
    }