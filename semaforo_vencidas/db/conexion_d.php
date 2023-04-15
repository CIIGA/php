<?php 
function conexion($db){

    $serverName = "51.222.44.135";
        $connectionInfo = array( 'Database'=>$db, 'UID'=>'sa', 'PWD'=>'vrSxHH3TdC');
        $cnx = sqlsrv_connect($serverName, $connectionInfo);
        date_default_timezone_set('America/Mexico_City');
        return $cnx;
}
    ?>