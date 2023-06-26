<?php 
ini_set('max_execution_time', 0);
require '../modules/pagos.php';
require '../PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//Se condiciona si se reciben como paarametro lo siguiente
if ((isset($_GET['base'])) && (isset($_GET['pago']))) {
    //Lo convertimos a variables
$spreadsheet = new Spreadsheet();
$spreadsheet->removeSheetByIndex(0);
$hoja1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "Pagos Netos");
$spreadsheet->addSheet($hoja1, 0);
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(12);
$hoja1->setCellValue("A1", 'Cuenta')
    ->setCellValue("B1", "Referencia")
    ->setCellValue("C1", "Recibo")
    ->setCellValue("D1", "Descripcion")
    ->setCellValue("E1", "Total")
    ->setCellValue("F1", "FechaPago")
    ->setCellValue("G1", "Propietario")
    ->setCellValue("H1", "Subsidio")
    ->setCellValue("I1", "Convenio")
    ->setCellValue("J1", "MensualidadPago")
    ->setCellValue("K1", "MesesConvenio");
    $BD = $_GET['base'];
    $Fecha_pago = $_GET['pago'];
   
    //Contenido del archivo
    $cnx = conexionPagos($BD);
    $procedure = "exec sp_pagosNetoDetalle '$Fecha_pago'";
    $exec = sqlsrv_query($cnx, $procedure);
    $result = sqlsrv_fetch_array($exec);
    $i = 2;
while ($result = sqlsrv_fetch_array($exec)) {
    $hoja1->setCellValue("A{$i}", utf8_encode($result['Cuenta']))
        ->setCellValue("B{$i}", utf8_encode($result['Referencia']))
        ->setCellValue("C{$i}", utf8_encode($result['Recibo']))
        ->setCellValue("D{$i}", utf8_encode($result['Descripcion']))
        ->setCellValue("E{$i}", utf8_encode($result['Total']))
        ->setCellValue("F{$i}", utf8_encode($result['FechaPago']->format('d/m/Y')))
        ->setCellValue("G{$i}", utf8_encode($result['Propietario']))
        ->setCellValue("H{$i}", utf8_encode($result['Subsidio']))
        ->setCellValue("I{$i}", utf8_encode($result['Convenio']))
        ->setCellValue("J{$i}", utf8_encode($result['MensualidadPago']))
        ->setCellValue("K{$i}", utf8_encode($result['MesesConvenio']));
    $i++;
    foreach ($hoja1->getColumnIterator() as $column) {
        $hoja1->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }
}


$writer = new Xlsx($spreadsheet);
$nombreFile ="PagosNetos_".$BD."_".$Fecha_pago.".csv";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $nombreFile . '"');
header('Cache-Control: max-age=0');

// Guardar el archivo en la salida del navegador
$writer->save('php://output');
}

?>
