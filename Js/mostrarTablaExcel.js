// mostrarTablaExcel.js

function mostrarTablaExcel(archivoXlsx) {
    // Cargar el archivo Excel utilizando SheetJS
    var xhr = new XMLHttpRequest();
    xhr.open('GET', archivoXlsx, true);
    xhr.responseType = 'arraybuffer';

    xhr.onload = function (e) {
        var arraybuffer = xhr.response;

        // Convertir el arraybuffer a un libro de trabajo (workbook)
        var data = new Uint8Array(arraybuffer);
        var arr = [];
        for (var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
        var bstr = arr.join("");
        var workbook = XLSX.read(bstr, { type: 'binary' });

        // Obtener la primera hoja de cálculo
        var firstSheetName = workbook.SheetNames[0];
        var worksheet = workbook.Sheets[firstSheetName];

        // Convertir la hoja de cálculo a una tabla HTML
        var htmlTable = XLSX.utils.sheet_to_html(worksheet);

        // Modificar la tabla para agregar clases de Bootstrap a los encabezados
        var modifiedTable = applyBootstrapStyles(htmlTable);

        // Mostrar la tabla en el contenedor con id 'tablaExcel'
        document.getElementById('tablaExcel').innerHTML = modifiedTable;

        var tablaExcelContainer = document.getElementById('tablaExcel');
        if (tablaExcelContainer) {
            // Asignar el id 'tablaExcel' a la tabla antes de agregarla al contenedor
            modifiedTable = modifiedTable.replace('<table', '<table id="excelTable"');

            tablaExcelContainer.innerHTML = modifiedTable;
        }
    };

    xhr.send();
}

// Función para aplicar estilos de Bootstrap a la tabla
function applyBootstrapStyles(htmlTable) {

    // Crear un elemento div para contener la tabla y facilitar la manipulación del DOM
    var wrapperDiv = document.createElement('div');
    wrapperDiv.innerHTML = htmlTable;

    // Obtener la tabla
    var table = wrapperDiv.querySelector('table');

    // Aplicar clases de Bootstrap a la tabla
    if (table) {
        table.classList.add('table', 'table-striped', 'table-bordered', 'table-responsive');

        // Obtener todas las celdas de la tabla
        var cells = table.querySelectorAll('td, th');

        // Hacer que cada celda sea editable
        cells.forEach(function (cell) {
            cell.setAttribute('contenteditable', true);
        });
    }

    // Obtener la primera fila (encabezados) de la tabla
    var headersRow = wrapperDiv.querySelector('thead tr');

    // Aplicar clases de Bootstrap al thead
    var thead = wrapperDiv.querySelector('thead');
    if (thead) {
        thead.classList.add('thead-dark');
    }

    // Aplicar clases de Bootstrap a los encabezados
    if (headersRow) {
        var headers = headersRow.getElementsByTagName('th');
        for (var i = 0; i < headers.length; i++) {
            // Agregar clases de Bootstrap (puedes ajustarlas según tu preferencia)
            headers[i].classList.add('table-header', 'bg-primary', 'text-white', 'text-center');
        }
    }

    // Devolver la tabla HTML con los estilos aplicados
    return wrapperDiv.innerHTML;
}

function s2ab(s) {
    var buf = new ArrayBuffer(s.length);
    var view = new Uint8Array(buf);
    for (var i = 0; i != s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
    return buf;
}
