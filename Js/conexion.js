
console.log("popo")

const container = document.querySelector('#example');

const hot = new Handsontable(container, {
  data: [
    ['', 'Tesla', 'Volvo', 'Toyota', 'Ford'],
    ['2019', 10, 11, 12, 13],
    ['2020', 20, 11, 14, 13],
    ['2021', 30, 15, 12, 13]
  ],
  rowHeaders: true,
  colHeaders: true,
  contextMenu: true,
  manualColumnResize: true, // Permite redimensionar columnas manualmente
  manualRowResize: true, 
  cells: function (row, col, prop) {
    if (col === 0) {
      return { editor: 'text' }; // Habilita el editor de texto solo en la primera columna
    }
    return {}; // Las otras celdas no tienen editor personalizado
  },
  dropdownMenu: true,
  height: 'auto',
  licenseKey: 'non-commercial-and-evaluation' // for non-commercial use only
});