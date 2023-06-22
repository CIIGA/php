<?php
function plaza($id_plaza)
{
    $serverName = "51.222.44.135";
    $connectionInfo = array('Database' => 'kpis', 'UID' => 'sa', 'PWD' => 'vrSxHH3TdC');
    $cnx = sqlsrv_connect($serverName, $connectionInfo);
    date_default_timezone_set('America/Mexico_City');
    $pl = "SELECT p.data as base, pl.nombreplaza as plaza FROM plaza as pl INNER JOIN proveniente as p ON pl.id_proveniente=p.id_proveniente where pl.id_plaza='$id_plaza'";
    $plz = sqlsrv_query($cnx, $pl);
    $result = sqlsrv_fetch_array($plz);
    return $result;
}
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
//La funcion que muestra los años de la plaza
function anio($BD)
{
    $cnx = conexion($BD);
    $sql = "select distinct datepart(year,fechaPago) as anio from PagosFactura where fechaPago is not null ";
    $exec = sqlsrv_query($cnx, $sql);
    $result = sqlsrv_fetch_array($exec);
    echo "<select class='custom-select' id='anio' name='anio' required>";
    echo "<option > Seleccione el año</option>";
    do {
        echo "<option value=" . $result['anio'] . " >" . $result['anio'] . " </option>";
    } while (($result = sqlsrv_fetch_array($exec)));
    echo "</select>";
}
//La funcion que guarda los años de la plaza
function anioArray($BD)
{
    $cnx = conexion($BD);
    $sql = "select distinct datepart(year,fechaPago) as anio from PagosFactura where fechaPago is not null ";
    $exec = sqlsrv_query($cnx, $sql);
    $result = sqlsrv_fetch_array($exec);
    $anios =  array();
    do {
        array_push($anios, $result['anio']);
    } while (($result = sqlsrv_fetch_array($exec)));
    return $anios;
}

//Se ejecuta el store para mostrar las consultas 
function storProcedure($BD, $anio, $mes)
{
    $cnx = conexion($BD);
    $sql = " sp_bono_gestor $anio , $mes";
    $exec = sqlsrv_query($cnx,  $sql);
    echo '<h4>Resumen</h4>';
    echo '<hr/>';
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
            echo '<h4>Detalle</h4>';
            echo '<hr/>';
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
}
function recaudado($BD, $anio, $mes){
    $cnx = conexion($BD);
    $sql = "sp_bono_gestor_monto $anio, $mes";
    $exec = sqlsrv_query($cnx,  $sql);
    $result = sqlsrv_fetch_array($exec);
    return $result['monto_facturado'];
}

