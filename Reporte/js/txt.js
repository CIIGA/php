function txtPregrabadas(id_plaza,BD,fechaI,fechaF,pagina){
    window.open('txt/pregrabadas.php?plz='+id_plaza+'&base='+BD+'&fecha_inicial='+fechaI+'&fecha_final='+fechaF+'&page='+pagina)
}
function txtPagosBrutos(BD,Pago){
    window.open('txt/pagosBrutosNetos.php?base='+BD+'&pago='+Pago);
}
function txtPagosNetos(BD,Pago){
    window.open('txt/pagosNetosDetalle.php?base='+BD+'&pago='+Pago);
}