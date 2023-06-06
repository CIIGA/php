<?php
$anio = $_GET['anio'];
$BD = $_GET['base'];
$mes = $_GET['mes'];
$plaza = $_GET['plaza'];
$plz = $_GET['plz'];
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

ini_set('max_execution_time', 0);
header('Cache-Control: max-age=60, must-revalidate');
header("Pragma: public");
header("Expires: 0");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=bonos.xls");
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style>
        th,
        td {

            text-align: left;
            vertical-align: top;
            border: 1px solid #707070;
            border-spacing: 0;
        }

        /* color al titulo de las columnas */
        .bg_th {
            background-color: #B8B8B8 !important;
            text-align: center !important;
        }

        .saltopagina {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <?php
    $cnx = conexion($BD);
    $sql = "sp_bono_gestor $anio , $mes";
    $exec = sqlsrv_query($cnx,  $sql);
    echo '<h4>Resumen</h4>';
    echo '<div class="div-tabla">';
        echo "<table class='table table-responsive table-condensed'>
        <thead class='thead-dark'>
                <tr>
                <th class='text-xs'>Gestor</th>
                <th class='text-xs'>Ingreso recaudado</th>
                <th class='text-xs'>Puesto</th>
                <th class='text-xs'>Numero de pagos</th>
                <th class='text-xs'>Bono 0.6%</th>
                <th class='text-xs'>Gestiones promedio</th>
                <th class='text-xs'>Embargos</th>
                <th class='text-xs'>Mes calculado</th>
                <th class='text-xs'>Año calculado</th>
                <th class='text-xs'>Sube 0.8%</th>
                <th class='text-xs'>Bono adicional 0.8%</th>
                <th class='text-xs'>Bono efectivo</th>
                </tr>
            </thead>
            <tbody>";
        while ($result = sqlsrv_fetch_array($exec)) {
            // print_r($result);
            echo "<tr class='text-center'>
                <td class='text-xs'>" . utf8_encode($result['gestor']) . "</td>
                <td class='text-xs'>" . utf8_encode($result['ingreso_recaudado']) . "</td>
                <td class='text-xs'>" . utf8_encode($result['puesto']) . "</td>
                <td class='text-xs'>" . utf8_encode($result['numero_de_pagos']) . "</td>
                <td class='text-xs'>" . utf8_encode($result['bono_1%']) . "</td>
                <td class='text-xs'>" . utf8_encode($result['gestiones_promedio']) . "</td>
                <td class='text-xs'>" . utf8_encode($result['embargos']) . "</td>
                <td class='text-xs'>" . utf8_encode($result['mes_calculado']) . "</td>
                <td class='text-xs'>" . utf8_encode($result[8]) . "</td>
                <td class='text-xs'>" . utf8_encode($result['sube_20%']) . "</td>
                <td class='text-xs'>" . utf8_encode($result['bono_adicional_20%']) . "</td>
                <td class='text-xs'>" . utf8_encode($result['bono_efectivo']) . "</td>
                </tr>";
        }
        echo " </tbody>
            </table>";
            echo '</div>';
            echo '<div class="saltopagina"></div>';
            echo '<h4>Detalle</h4>';
            echo '<div class="div-tabla">';
        if ($result = sqlsrv_next_result($exec)) {
            echo "<table class='table text-center'>
            <thead class='thead-dark'>
                <tr>
                <th class='text-xs'>Cuenta</th>
                <th class='text-xs'>Nombre</th>
                <th class='text-xs'>Fecha_captura</th>
                <th class='text-xs'>Puesto</th>
                <th class='text-xs'>Fecha pago</th>
                <th class='text-xs'>Monto pagado</th>
                <th class='text-xs'>Monto bono 0.6%</th>
                </tr>
            </thead>
            <tbody>";
            while ($result = sqlsrv_fetch_array($exec)) {
                echo "<tr>
                <td class='text-xs'>" . utf8_encode($result['cuenta']) . "</td>
                <td class='text-xs'>" . utf8_encode($result['nombre']) . "</td>
                <td class='text-xs'>" . utf8_encode($result['fecha_captura']->format('d/m/Y')) . "</td>
                <td class='text-xs'>" . utf8_encode($result['puesto']) . "</td>
                <td class='text-xs'>" . utf8_encode($result['fecha_pago']->format('d/m/Y')) . "</td>
                <td class='text-xs'>" . utf8_encode($result['monto_pagado']) . "</td>
                <td class='text-xs'>" . utf8_encode($result['monto_bono1%']) . "</td>
                </tr>";
            }
            echo " </tbody>
            </table>";
        }
    echo '</div>';
    ?>
</body>

</html>