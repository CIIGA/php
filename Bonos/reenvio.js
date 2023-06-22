function reenvio(){
    let ingreso1 = document.getElementById("id_plaza").value;
    let ingreso2 = document.getElementById("base").value
    let ingreso3 = document.getElementById("plaza").value
    let ingreso4 = document.getElementById("anio").value
    let ingreso5 = document.getElementById("mes").value
    const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Nobiembre", "Diciembre"];
    let nombre = 'ReporteBono_' + ingreso3 + '_' + meses[ingreso5 - 1] + '_' + ingreso4;
    // window.open("Bonos/descargas.php?plz="+ingreso1+"&base="+ingreso2+"&plaza="+ingreso3+"&anio="+ingreso4+"&mes="+ingreso5);
    window.open("Bonos/pdfBonos.php?plz="+ingreso1+"&base="+ingreso2+"&plaza="+ingreso3+"&anio="+ingreso4+"&mes="+ingreso5+"&nombre="+nombre);
    // window.open("Bonos/excelBonos.php?plz="+ingreso1+"&base="+ingreso2+"&plaza="+ingreso3+"&anio="+ingreso4+"&mes="+ingreso5);
}