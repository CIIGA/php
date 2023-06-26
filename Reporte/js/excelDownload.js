function excelAdeudo(BD,fecha){
    window.open('excel/adeudo.php?base='+BD+'&fecha='+fecha)
}
function excelPagosNetos(BD,Pago){
    window.open('excel/pagosNetos.php?base='+BD+'&pago='+Pago);
}
function excelPagosBrutos(BD,Pago){
        window.open('excel/pagosBrutos.php?base='+BD+'&pago='+Pago);
    }