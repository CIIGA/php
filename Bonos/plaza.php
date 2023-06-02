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
    $exec = sqlsrv_query($cnx, $sql);
    $result = sqlsrv_fetch_array($exec);
    print_r(($result));
}
