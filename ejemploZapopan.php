<?php


$serverName = "implementta.mx";
    $connectionInfo = array( 'Database'=>'implementtaZapopanP', 'UID'=>'sa', 'PWD'=>'vrSxHH3TdC');
    $cnx = sqlsrv_connect($serverName, $connectionInfo);
    date_default_timezone_set('America/Mexico_City');


    $clave='1401212001000103811500036000037';

    $re="select * from Padron
    where CURT='$clave'";
    $res=sqlsrv_query($cnx,$re);
    $resultado=sqlsrv_fetch_array($res);



do{
    
    
echo $resultado['Propietario'];
    
    
}while($resulado=sqlsrv_fetch_array($res));



?>