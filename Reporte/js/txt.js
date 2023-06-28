function txtPregrabadas(id_plaza,BD,fechaI,fechaF,pagina){
        toDownload('txt/pregrabadas2.php?plz='+id_plaza+'&base='+BD+'&fecha_inicial='+fechaI+'&fecha_final='+fechaF+'&page='+pagina);
        return false;
        // window.open('txt/pregrabadas2.php?plz='+id_plaza+'&base='+BD+'&fecha_inicial='+fechaI+'&fecha_final='+fechaF+'&page='+pagina);
}

var toDownload = function(url) {
    $.fileDownload(url, {
      successCallback: function(url) {
        Swal.fire('Listo comenzara la descarga de su archivo', '', 'success');
      },
      failCallback: function() {
        Swal.fire('No se pudo descargar el archivo', '', 'error');
      },
      prepareCallback: function() {
        Swal.fire({
          title: 'Generando Archivo txt',
          html: 'Esto puede tomar varios minutos.',
          timer: 0,
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
          willClose: () => {
            clearInterval(timerInterval)
          }
        }).then((result) => {})
      }
    });
  }

// function txtPagosBrutos(BD,Pago){
//     window.open('txt/pagosBrutosNetos.php?base='+BD+'&pago='+Pago);
// }
// function txtPagosNetos(BD,Pago){
//     window.open('txt/pagosNetosDetalle.php?base='+BD+'&pago='+Pago);
// }
