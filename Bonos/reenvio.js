function reenvio(){
    let ingreso1 = document.getElementById("id_plaza").value;
    let ingreso2 = document.getElementById("base").value
    let ingreso3 = document.getElementById("plaza").value
    let ingreso4 = document.getElementById("anio").value
    let ingreso5 = document.getElementById("mes").value

    window.open("Bonos/Descargas.php?plz="+ingreso1+"&base="+ingreso2+"&plaza="+ingreso3+"&anio="+ingreso4+"&mes="+ingreso5);
    // window.open("Bonos/pdfBonos.php?plz="+ingreso1+"&base="+ingreso2+"&plaza="+ingreso3+"&anio="+ingreso4+"&mes="+ingreso5);
    // window.open("Bonos/excelBonos.php?plz="+ingreso1+"&base="+ingreso2+"&plaza="+ingreso3+"&anio="+ingreso4+"&mes="+ingreso5);
}