
   
    // window.open("Bonos/descargas.php?plz="+ingreso1+"&base="+ingreso2+"&plaza="+ingreso3+"&anio="+ingreso4+"&mes="+ingreso5);
//    var url= window.open("Bonos/pdfBonos.php?plz="+ingreso1+"&base="+ingreso2+"&plaza="+ingreso3+"&anio="+ingreso4+"&mes="+ingreso5+"&nombre="+nombre);
    // window.open("Bonos/excelBonos.php?plz="+ingreso1+"&base="+ingreso2+"&plaza="+ingreso3+"&anio="+ingreso4+"&mes="+ingreso5);
   
      var loadInfo = function() {
        Swal.fire({
          title: 'Obteniendo Datos',
          html: 'Espere un momento porfavor...',
          timer: 0,
          timerProgressBar: true,
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
          willClose: () => {
            return false;
          }
        }).then((result) => {});
      }
      $('.toDownload').on('click', function() {
        let ingreso1 = document.getElementById("id_plaza").value;
        let ingreso2 = document.getElementById("base").value
        let ingreso3 = document.getElementById("plaza").value
        let ingreso4 = document.getElementById("anio").value
        let ingreso5 = document.getElementById("mes").value
        const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Nobiembre", "Diciembre"];
        let nombre = 'ReporteBono_' + ingreso3 + '_' + meses[ingreso5 - 1] + '_' + ingreso4;
        toDownload("Bonos/pdfBonos.php?plz="+ingreso1+"&base="+ingreso2+"&plaza="+ingreso3+"&anio="+ingreso4+"&mes="+ingreso5+"&nombre="+nombre);
        return false;
      });

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
              title: 'Generando Archivo Zip',
              html: 'Espere un momento porfavor.',
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
    
