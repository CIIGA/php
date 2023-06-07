<?php
$anio = $_GET['anio'];
$BD = $_GET['base'];
$mes = $_GET['mes'];
$plaza = $_GET['plaza'];
$plz = $_GET['plz'];
$nombre = $_GET['nombre'];
$meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Nobiembre", "Diciembre"];
//Funcion para generar conexiones dinamicas
function conexion($BD)
{
    $serverName = "51.222.44.135";
    $connectionInfo = array('Database' => $BD, 'UID' => 'sa', 'PWD' => 'vrSxHH3TdC');
    $cnx = sqlsrv_connect($serverName, $connectionInfo);
    date_default_timezone_set('America/Mexico_City');
    if ($cnx) {
        return $cnx;
    } else {
        echo "error de conexion";
        die(print_r(sqlsrv_errors(), true));
    }
}

require 'PhpSpreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$spreadsheet->removeSheetByIndex(0);
$hoja1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "Resumen");
$hoja2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "Detallado");
$spreadsheet->addSheet($hoja1, 0);
$spreadsheet->addSheet($hoja2, 1);
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(12);
$hoja1->setCellValue("A1", 'Gestor')
    ->setCellValue("B1", "Ingreso recaudado")
    ->setCellValue("C1", "Puesto")
    ->setCellValue("D1", "Numero de pagos")
    ->setCellValue("E1", "Bono 0.6%")
    ->setCellValue("F1", "Gestiones promedio")
    ->setCellValue("G1", "Embargos")
    ->setCellValue("H1", "Mes calculado")
    ->setCellValue("I1", "Año calculado")
    ->setCellValue("J1", "Sube 0.8")
    ->setCellValue("K1", "Bono adicional 0.8%")
    ->setCellValue("L1", "Bono efectivo");
$cnx = conexion($BD);
$sql = "sp_bono_gestor $anio , $mes";
$exec = sqlsrv_query($cnx,  $sql);
$i = 2;
while ($result = sqlsrv_fetch_array($exec)) {
    $hoja1->setCellValue("A{$i}", utf8_encode($result['gestor']))
        ->setCellValue("B{$i}", utf8_encode($result['ingreso_recaudado']))
        ->setCellValue("C{$i}", utf8_encode($result['puesto']))
        ->setCellValue("D{$i}", utf8_encode($result['numero_de_pagos']))
        ->setCellValue("E{$i}", utf8_encode($result['bono_1%']))
        ->setCellValue("F{$i}", utf8_encode($result['gestiones_promedio']))
        ->setCellValue("G{$i}", utf8_encode($result['embargos']))
        ->setCellValue("H{$i}", utf8_encode($result['mes_calculado']))
        ->setCellValue("I{$i}", utf8_encode($result[8]))
        ->setCellValue("J{$i}", utf8_encode($result['sube_20%']))
        ->setCellValue("K{$i}", utf8_encode($result['bono_adicional_20%']))
        ->setCellValue("L{$i}", utf8_encode($result['bono_efectivo']));
    $i++;
    foreach ($hoja1->getColumnIterator() as $column) {
        $hoja1->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }
}
if ($result = sqlsrv_next_result($exec)) {
    $hoja2->setCellValue("A1", 'Cuenta')
        ->setCellValue("B1", "Nombre")
        ->setCellValue("C1", "Fecha captura")
        ->setCellValue("D1", "Puesto")
        ->setCellValue("E1", "Fecha pago")
        ->setCellValue("F1", "Monto pagado")
        ->setCellValue("G1", "Monto bono 0.6%");
    $j = 2;
    while ($result = sqlsrv_fetch_array($exec)) {
        $hoja2->setCellValue("A{$j}", utf8_encode($result['cuenta']))
            ->setCellValue("B{$j}", utf8_encode($result['nombre']))
            ->setCellValue("C{$j}", utf8_encode($result['fecha_captura']->format('d/m/Y')))
            ->setCellValue("D{$j}", utf8_encode($result['puesto']))
            ->setCellValue("E{$j}",    utf8_encode($result['fecha_pago']->format('d/m/Y')))
            ->setCellValue("F{$j}",   utf8_encode($result['monto_pagado']))
            ->setCellValue("G{$j}",   utf8_encode($result['monto_bono1%']));
        $j++;
        foreach ($hoja2->getColumnIterator() as $column) {
            $hoja2->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
    }
}
$writer = new Xlsx($spreadsheet);
$nombreFile = $nombre . '.xlsx';
$writer->save($nombreFile);
?>
<script languaje='javascript' type='text/javascript'>
    window.close();
</script>