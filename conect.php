
<?php
$serverName = "51.222.44.135";
    $connectionInfoa = array( 'Database'=>'kpis', 'UID'=>'sa', 'PWD'=>'vrSxHH3TdC');
    $cnx = sqlsrv_connect($serverName, $connectionInfoa);
    date_default_timezone_set('America/Mexico_City');

    $plz=$_GET['idpl'];
    
    $pro="SELECT * FROM plaza
    inner join proveniente on proveniente.id_proveniente=plaza.id_proveniente
    where plaza.id_plaza='$plz'";
    $prov=sqlsrv_query($cnx,$pro);
    $proveniente=sqlsrv_fetch_array($prov);

if(isset($proveniente)){
    $connectionInfo = array( 'Database'=>$proveniente['data'], 'UID'=>'sa', 'PWD'=>'vrSxHH3TdC');
    $cnxa = sqlsrv_connect($serverName, $connectionInfo);
}




?>