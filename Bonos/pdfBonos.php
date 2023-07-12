<?php
ini_set('max_execution_time', 0);
require 'PhpSpreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$anio = $_GET['anio'];
$BD = $_GET['base'];
$mes = $_GET['mes'];
$plaza = $_GET['plaza'];
$plz = $_GET['plz'];
$nombre = $_GET['nombre'];
$meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Nobiembre", "Diciembre"];

//Función para generar conexiones dinámicas
function conexion($BD)
{
    $serverName = "51.222.44.135";
    $connectionInfo = array('Database' => $BD, 'UID' => 'sa', 'PWD' => 'vrSxHH3TdC');
    $cnx = sqlsrv_connect($serverName, $connectionInfo);
    date_default_timezone_set('America/Mexico_City');
    if ($cnx) {
        return $cnx;
    } else {
        echo "Error de conexión";
        die(print_r(sqlsrv_errors(), true));
    }
}

function recaudado($BD, $anio, $mes)
{
    $cnx = conexion($BD);
    $sql = "sp_bono_gestor_monto $anio, $mes";
    $exec = sqlsrv_query($cnx,  $sql);
    $result = sqlsrv_fetch_array($exec);
    return $result['monto_facturado'];
}

// Generar el contenido HTML
ob_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $nombre ?></title>
    <style>
        table {
            margin-left: auto;
            margin-right: auto;
        }

        table,
        th,
        td {
            border: 1px solid #273746;
            border-collapse: collapse;
        }

        .table {
            width: 500px;
        }

        th {
            font-weight: normal;
            font-size: 12px;
        }

        td {
            min-width: 50px;
            max-width: 100px;
            font-weight: normal;
            font-size: 12px;
        }

        .text-center {
            text-align: center;
        }

        .title {
            color: #FF0000;
            font-size: 15px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <header></header>
    <footer></footer>

    <main>
        <h4 class="text-center">Calculo de Bonos <?php echo $anio ?></h4>
        <table class="table">
            <tr>
                <td>Plaza: <?php echo $plaza ?></td>
                <td></td>
            </tr>
            <tr>
                <td>Mes: <?php echo $meses[$mes - 1]; ?></td>
                <td>Recaudado: $<?php $recaudado = recaudado($BD, $anio, $mes);
                                    echo number_format($recaudado, 2); ?></td>
            </tr>
        </table>
        <br />

        <?php
        $cnn = conexion($BD);
        $sql = "sp_bono_gestor $anio , $mes";
        $exec1 = sqlsrv_query($cnn,  $sql);
        ?>

        <table>
            <thead>
                <tr>
                    <th colspan="12" class="title">Gestores</th>
                </tr>
                <tr>
                    <th>Gestor</th>
                    <th>Ingreso recaudado</th>
                    <th>Puesto</th>
                    <th>Número de pagos</th>
                    <th>Bono 0.6%</th>
                    <th>Gestiones promedio</th>
                    <th>Embargos</th>
                    <th>Mes calculado</th>
                    <th>Año calculado</th>
                    <th>Sube 0.8%</th>
                    <th>Bono adicional 0.8%</th>
                    <th>Bono efectivo</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($result = sqlsrv_fetch_array($exec1)) : ?>
                    <tr class="text-center">
                        <td><?php echo utf8_encode($result['gestor']) ?></td>
                        <td><?php echo utf8_encode($result['ingreso_recaudado']) ?></td>
                        <td><?php echo utf8_encode($result['puesto']) ?></td>
                        <td><?php echo utf8_encode($result['numero_de_pagos']) ?></td>
                        <td><?php echo utf8_encode($result['bono_1%']) ?></td>
                        <td><?php echo utf8_encode($result['gestiones_promedio']) ?></td>
                        <td><?php echo utf8_encode($result['embargos']) ?></td>
                        <td><?php echo utf8_encode($result['mes_calculado']) ?></td>
                        <td><?php echo utf8_encode($result[8]) ?></td>
                        <td><?php echo utf8_encode($result['sube_20%']) ?></td>
                        <td><?php echo utf8_encode($result['bono_adicional_20%']) ?></td>
                        <td><?php echo utf8_encode($result['bono_efectivo']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

</body>

</html>

<?php
$html = ob_get_clean();

// Generar el archivo PDF
require_once "dompdf/autoload.inc.php";
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$options = $dompdf->getOptions();
$options->set(array("isRemoteEnabled" => true));
$dompdf->setOptions($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'landscape');
$dompdf->render();
$nombreFile = $nombre . '.pdf';
$output = $dompdf->output();
file_put_contents("C:/wamp64/www/kpis/kpiestrategas/php/Bonos/" . $nombreFile, $output);

// Generar el archivo Excel
$spreadsheet = new Spreadsheet();
$spreadsheet->removeSheetByIndex(0);
$hoja1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "Resumen");
$hoja2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, "Detallado");
$spreadsheet->addSheet($hoja1, 0);
$spreadsheet->addSheet($hoja2, 1);
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(12);

$tableStyle = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

$hoja1->setCellValue("A1", 'Gestor')
    ->setCellValue("B1", "Ingreso recaudado")
    ->setCellValue("C1", "Puesto")
    ->setCellValue("D1", "Número de pagos")
    ->setCellValue("E1", "Bono 0.6%")
    ->setCellValue("F1", "Gestiones promedio")
    ->setCellValue("G1", "Embargos")
    ->setCellValue("H1", "Mes calculado")
    ->setCellValue("I1", "Año calculado")
    ->setCellValue("J1", "Sube 0.8%")
    ->setCellValue("K1", "Bono adicional 0.8%")
    ->setCellValue("L1", "Bono efectivo");

$hoja1->getStyle('A1:L1')->applyFromArray(['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFEFEF']]]);
$hoja1->fromArray([], null, 'A2');

$cnn = conexion($BD);
$sql = "sp_bono_gestor $anio , $mes";
$exec = sqlsrv_query($cnn,  $sql);
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
    $hoja1->getStyle("A{$i}:L{$i}")->applyFromArray($tableStyle);
    foreach ($hoja1->getColumnIterator() as $column) {
        $hoja1->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }
    $i++;
}

$hoja2->setCellValue("A1", 'Cuenta')
    ->setCellValue("B1", "Nombre")
    ->setCellValue("C1", "Fecha captura")
    ->setCellValue("D1", "Puesto")
    ->setCellValue("E1", "Fecha pago")
    ->setCellValue("F1", "Monto pagado")
    ->setCellValue("G1", "Monto bono 0.6%");

$hoja2->getStyle('A1:G1')->applyFromArray(['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFEFEF']]]);
$hoja2->fromArray([], null, 'A2');

if ($result = sqlsrv_next_result($exec)) {
    $j = 2;
    while ($result = sqlsrv_fetch_array($exec)) {
        $hoja2->setCellValue("A{$j}", utf8_encode($result['cuenta']))
            ->setCellValue("B{$j}", utf8_encode($result['nombre']))
            ->setCellValue("C{$j}", utf8_encode($result['fecha_captura']->format('d/m/Y')))
            ->setCellValue("D{$j}", utf8_encode($result['puesto']))
            ->setCellValue("E{$j}", utf8_encode($result['fecha_pago']->format('d/m/Y')))
            ->setCellValue("F{$j}", utf8_encode($result['monto_pagado']))
            ->setCellValue("G{$j}", utf8_encode($result['monto_bono1%']));
        $hoja2->getStyle("A{$j}:G{$j}")->applyFromArray($tableStyle);
        foreach ($hoja2->getColumnIterator() as $column) {
            $hoja2->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
        $j++;
    }
}

$writer = new Xlsx($spreadsheet);
$nombreFile = $nombre . '.xlsx';
$writer->save($nombreFile);

// Generar el archivo ZIP
$zip = new ZipArchive();
$zipname = $nombre . '.zip';
if ($zip->open($zipname, ZipArchive::CREATE) == true) {
    $zip->addFile($nombre . '.pdf');
    $zip->addFile($nombre . '.xlsx');
    $zip->close();
    echo 'Creando archivo...';
} else {
    echo "Error al generar el .zip";
}

// Descargar el archivo ZIP
header('Set-Cookie: fileDownload=true; path=/');
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $zipname . '"');
readfile($zipname);

// Eliminar los archivos generados
unlink($nombre . '.pdf');
unlink($nombre . '.xlsx');
unlink($nombre . '.zip');