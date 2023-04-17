<?php
function plaza($id_plaza)
{
    require "db/conexion.php";
    $pl = "SELECT p.data as base, pl.nombreplaza as plaza FROM plaza as pl INNER JOIN proveniente as p ON pl.id_proveniente=p.id_proveniente where pl.id_plaza='$id_plaza'";
    $plz = sqlsrv_query($cnx, $pl);
    $plaza = sqlsrv_fetch_array($plz);
    return $plaza;
}
function sp_RGCallCenter(
    $sector,
    $base,
    $fechaI,
    $fechaF,
    $BD) {
        if($sector==1){
            $store = 'sp_RGCallCenter';
        }
        else if ($sector==2){
            $store = 'sp_ReportePregrabadas';
        }
    $cnx = conexion($BD);
    $procedure = "exec " . $store . " '$fechaI', '$fechaF'";
    $exec = sqlsrv_query($cnx, $procedure);
     // if ($result) {
    //     while ($result = sqlsrv_fetch_array($exec)) {

    //         echo utf8_encode($result['Observaciones']);
    //         echo utf8_encode($result['FechaPromesaPago']->format('d/m/Y'));
    //         echo utf8_encode($result['PersonaAtendio']);
    //         echo utf8_encode($result['TareaAnterior']);
    //         echo utf8_encode($result['TareaActual']);
    //         echo utf8_encode($result['Fecha']->format('d/m/Y'));
    //         echo utf8_encode($result['Cuenta']);
    //         echo utf8_encode($result['Clave']);
    //         echo utf8_encode($result['CallCenter']);
    //         echo utf8_encode($result['Clasificacion']);
    //         echo utf8_encode($result['Telefono']);
    //         echo utf8_encode($result['TelRadio']);
    //         echo utf8_encode($result['TelRadio']);
    //         echo utf8_encode($result['TelefonoUsuario']);
    //         echo utf8_encode($result['CelularUsuario']);
    //         echo utf8_encode($result['TelRadioUsuario']);
    //         echo utf8_encode($result['TelefonoUsuario']);
    //         echo utf8_encode($result['CelularUsuario']);
    //         echo utf8_encode($result['TelRadioUsuario']);
    //         echo utf8_encode($result['Usuario']);
    //         echo utf8_encode($result['Direccion']);
    //         echo utf8_encode($result['Colonia']);
    //         echo utf8_encode($result['Distrito']);
    //         echo utf8_encode($result['Clave Catastral']);
    //         echo utf8_encode($result['Serie Medidor']);
    //         echo utf8_encode($result['Tipo Servicio']);
    //         echo utf8_encode($result['Giro']);
    //         echo utf8_encode($result['Razon Social']);
    //         echo utf8_encode($result['Deuda Total']);
    //         echo utf8_encode($result['Abogado']);
    //         echo utf8_encode($result['Gestor']);
    //     }
    // }

}
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
