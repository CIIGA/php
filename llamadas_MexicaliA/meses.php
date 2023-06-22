<?php 
$serverName = "51.222.44.135";
$connectionInfo = array( 'Database'=>'implementtaMexicaliA', 'UID'=>'sa', 'PWD'=>'vrSxHH3TdC');
$cnx = sqlsrv_connect($serverName, $connectionInfo);
date_default_timezone_set('America/Mexico_City');

$meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Nobiembre", "Diciembre"];

$cadena=" <label class='form-label mb-2'>Mes:*</label>
			<select id='lista_mes' name='lista_mes' class='form-select form-select-sm'>";

if(isset($_POST['anioselect'])){


$anio=$_POST['anioselect'];

	$sql="SELECT distinct(Mes) as mes FROM Duracionllamadas WHERE anio = $anio";
    $cnx_datos = sqlsrv_query($cnx, $sql);
	// $ver=sqlsrv_fetch_array($cnx_datos);
    // echo $cnx_datos;

	
       
	while ($ver=sqlsrv_fetch_array($cnx_datos)) {
		$cadena=$cadena.'<option value='.$ver['mes'].'>'.utf8_encode($meses[$ver['mes']-1]).'</option>';
	}
}
else{
	$cadena=$cadena.'<option value=0>Selecciona un año</option>';
}

	echo  $cadena."</select>";
	

?>