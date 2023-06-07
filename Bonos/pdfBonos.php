<?php
//inicializamos el bufer para despues guardarlo en una variable
ob_start();
ini_set('max_execution_time', 0);
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

function recaudado($BD, $anio, $mes)
{
    $cnx = conexion($BD);
    $sql = "sp_bono_gestor_monto $anio, $mes";
    $exec = sqlsrv_query($cnx,  $sql);
    $result = sqlsrv_fetch_array($exec);
    return $result['monto_facturado'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Determinación</title>
    <!-- <link href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/deterTolucaA/public/estilos/pdf.css" rel="stylesheet"> -->

</head>
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

<body>
    <header>

    </header>
    <footer>

    </footer>

    <main>
        <h4 class="text-center">Calculo de Bonos <?php echo $anio ?></h4>
        <table class="table">
            <tr>
                <td>
                    Plaza: <?php echo $plaza ?>
                </td>
                <td>

                </td>
            </tr>
            <tr>
                <td>
                    Mes: <?php echo $meses[$mes - 1]; ?>
                </td>
                <td>
                    Recaudado : $<?php $recaudado = recaudado($BD, $anio, $mes);
                                    echo number_format($recaudado, 2); ?>
                </td>
            </tr>
        </table>
        <br />
        <?php
        $cnn = conexion($BD);
        $sql = " sp_bono_gestor $anio , $mes";
        $exec1 = sqlsrv_query($cnn,  $sql);
        echo "<table>
            <thead class=''>
                    <tr>
                    <th colspan='12' class='title'>Gestores</th>
                    </tr>
                    <tr>
                    <th class=''>Gestor</th>
                    <th class=''>Ingreso recaudado</th>
                    <th class=''>Puesto</th>
                    <th class=''>Numero de pagos</th>
                    <th class=''>Bono 0.6%</th>
                    <th class=''>Gestiones promedio</th>
                    <th class=''>Embargos</th>
                    <th class=''>Mes calculado</th>
                    <th class=''>Año calculado</th>
                    <th class=''>Sube 0.8%</th>
                    <th class=''>Bono adicional 0.8%</th>
                    <th class=''>Bono efectivo</th>
                    </tr>
                </thead>
                <tbody>";
        while ($result = sqlsrv_fetch_array($exec1)) {
            echo "<tr class='text-center'>
                    <td class=''>" . utf8_encode($result['gestor']) . "</td>
                    <td class=''>" . utf8_encode($result['ingreso_recaudado']) . "</td>
                    <td class=''>" . utf8_encode($result['puesto']) . "</td>
                    <td class=''>" . utf8_encode($result['numero_de_pagos']) . "</td>
                    <td class=''>" . utf8_encode($result['bono_1%']) . "</td>
                    <td class=''>" . utf8_encode($result['gestiones_promedio']) . "</td>
                    <td class=''>" . utf8_encode($result['embargos']) . "</td>
                    <td class=''>" . utf8_encode($result['mes_calculado']) . "</td>
                    <td class=''>" . utf8_encode($result[8]) . "</td>
                    <td class=''>" . utf8_encode($result['sube_20%']) . "</td>
                    <td class=''>" . utf8_encode($result['bono_adicional_20%']) . "</td>
                    <td class=''>" . utf8_encode($result['bono_efectivo']) . "</td>
                    </tr>";
        }
        echo " </tbody>
                </table>";

        ?>
    </main>

</body>

</html>
<?php
//guardar tod0 el buher en una variable
$html = ob_get_clean();
require_once "dompdf/autoload.inc.php";

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array("isRemoteEnabled" => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);
// $dompdf->setPaper('letter');
// horizontal
$dompdf->setPaper('letter', 'landscape');
$dompdf->render();
$nombreFile = $nombre.'.pdf';
// true para que habra el pdf
// false para que se descargue
// $dompdf->stream("determinacion.pdf", array("Attachment" => false));
// $rutaGuardado = url($nombreFile);
$output = $dompdf->output();

file_put_contents("C:/wamp64/www/kpis/kpiestrategas/php/Bonos/".$nombreFile, $output);
?>
<script languaje='javascript' type='text/javascript'>window.close();</script>