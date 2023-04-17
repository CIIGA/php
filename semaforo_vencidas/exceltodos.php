<?php 
function getEstatusResolucionBasica($cuentaPredial, $cnx) {

    $sqlFechaQuery = "SELECT TOP 1 gestion.FechaCaptura 
        FROM [implementtaZapopanP].[dbo].[RegistroAbogado] AS gestion
        INNER JOIN [implementtaZapopanP].[dbo].[AspNetUsers] AS usuario ON usuario.Id=gestion.IdAspUser
        WHERE idTarea=138 AND cuenta='" . $cuentaPredial . "';";

    $fechasCaptura = ['FechaCaptura' => ''];
    $queryFechas = sqlsrv_query($cnx, $sqlFechaQuery);
    $hasRowsFechas = sqlsrv_has_rows($queryFechas);
    if($hasRowsFechas) {
        $fechasCaptura = sqlsrv_fetch_array($queryFechas);
    }

    $fechasNotif = ['PresentaDoc' => '', 'FechaEnvioM' => '', 'FechaEnvioR' => '', 'FechaRegreso' => ''];
    $sqlNotifquery = "SELECT TOP 1 * FROM resolucionexpedientes WHERE cuentaPredial='". $cuentaPredial ."';";
    $queryFechaNotif = sqlsrv_query($cnx, $sqlNotifquery);
    $hasRowsFechaNotif = sqlsrv_has_rows($queryFechaNotif);
    if($hasRowsFechaNotif) {
        $fechasNotif = sqlsrv_fetch_array($queryFechaNotif);
    }

    $fechasA = ['FechaAsignacion' => '', 'Nombre' => '', 'FechaCaptura' => ''];
    $sqlFechaAQuery = "SELECT TOP 1 gestion.cuenta,gestion.FechaAsignacion,usuario.Nombre,gestion.FechaCaptura 
        FROM [implementtaZapopanP].[dbo].[RegistroAbogado] AS gestion
        INNER JOIN [implementtaZapopanP].[dbo].[AspNetUsers] AS usuario ON usuario.Id=gestion.IdAspUser
        WHERE gestion.idTarea=140 AND cuenta='" . $cuentaPredial . "'";
    $queryFechasA = sqlsrv_query($cnx, $sqlFechaAQuery);
    $hasRowsFechasA = sqlsrv_has_rows($queryFechasA);
    if($hasRowsFechasA) {
        $fechasA = sqlsrv_fetch_array($queryFechasA);
    }

    $estatus = "";
    if($fechasA['FechaCaptura'] != '') {
        $estatus = "ACUERDO DE INICIO NOTIFICADO";
    } else if(strlen($fechasA['Nombre']) > 0) {
        $estatus = "EN CAMPO CON " . $fechasA['Nombre'];
    } else if(strlen($fechasNotif['FechaRegreso']) > 0) {
        $estatus = "EN ESPERA DE ASIGNACI&Oacute;N";
    } else if(strlen($fechasNotif['FechaEnvioR']) > 0) {
        $estatus = "EN FIRMA CON EL LIC. RODRIGO";
    } else if(strlen($fechasNotif['FechaEnvioM']) > 0) {
        $estatus = "EN FIRMA CON EL ARQ. LUIS E. MUNDO";
    } else if(($fechasCaptura['FechaCaptura'] != "") && strlen($fechasCaptura['FechaCaptura']->format('Y-m-d')) > 0) {
        $estatus = "PREPARANDO PARA INGRESAR A VALIDACION Y FIRMAS";
    } else if(strlen($fechasCaptura['FechaCaptura']) == 0) {
        $estatus = "SOLICITAR A ITZEL FICHAS Y LAYOUT PARA PREPARAR LOS DOCUMENTOS";
    }

    return $estatus;
}

function getEstatusDeterminacion($cuentaPredial, $folio, $notificaciones, $cnx) {
    $estatusFinal = "";
    if($folio['fechaNotif'] != "") {
        $estatusFinal = "TR&Aacute;MITE CONCLUIDO";
    } else if ($notificaciones['fechaAsignacion'] != "") {
        $estatusFinal = "EN CAMPO CON " . $notificaciones['notificador'];
    } else if($notificaciones['fechaRegresosello'] != "") {
        $estatusFinal = "EN OFICINA EN ESPERA DE ASIGNACI&Oacute;N";
    } else if($notificaciones['fechaIngresoTesoreria'] != "") {
        $estatusFinal = "EN FIRMA CON TESORER&Iacute;A O EN DIRECCI&Oacute;N DE INGRESO";
    } else if($notificaciones['fechaEnvio'] != "") {
        $estatusFinal = "EN REVISI&Oacute;N CON APREMIOS";
    } else {
        $estatusFinal = getEstatusResolucionBasica($cuentaPredial, $cnx);
    }

    return $estatusFinal;
}

$serverName = "implementta.mx";
$connectionInfo = array('Database' => 'cartomaps', 'UID' => 'sa', 'PWD' => 'vrSxHH3TdC');
$cnx = sqlsrv_connect($serverName, $connectionInfo);

$query = "SELECT * FROM determinacion $extraquery ORDER BY id_determinacion DESC";
$datos = sqlsrv_query($cnx, $query) or die(print_r(sqlsrv_errors()));
$hasRows = sqlsrv_has_rows($datos);

header('Set-Cookie: fileDownload=true; path=/');
header('Cache-Control: max-age=60, must-revalidate');
header("Pragma: public");
header("Expires: 0");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=estatus_expedientes_todos.xls");
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
    <table class="table table-hover table-bordered" style="font-size: 11px;">
        <thead>
            <tr>
                <th class="p-1">NO. EXPEDIENTE</th>
                <th class="p-1">CUENTA PREDIAL</th>
                <th class="p-1">FECHA DE RESOLUCI&Oacute;N</th>
                <th class="p-1">FECHA DE NOTIFICACI&Oacute;N DE RESOLUCI&Oacute;N </th>
                <th class="p-1">ELABOR&Oacute;</th>
                <th class="p-1">FECHA DE ELABORACI&Oacute;N</th>
                <th class="p-1">NO. DE FOLIO</th>
                <th class="p-1">FECHA FOLIO</th>
                <th class="p-1">MONTO</th>
                <th class="p-1">QUIEN FIRMA Y SELLA</th>
                <th class="p-1">FECHA DE ENVIO CON GUSTAVO</th>
                <th class="p-1">FECHA DE INGRESO CON TESORER&Iacute;A O DIR. INGRESOS</th>
                <th class="p-1">FECHA DE REGRESO CON SELLO Y FIRMA</th>
                <th class="p-1">FECHA DE ASIGNACI&Oacute;N</th>
                <th class="p-1">NOTIFICADOR A QUIEN SE LE ASIGN&Oacute;</th>
                <th class="p-1">FECHA DE NOTIFICACI&Oacute;N</th>
                <th class="p-1">FECHA DE TIMBRADO</th>
                <th class="p-1">ETAPA EN QUE SE ENCUENTRA</th>
                <th class="p-1">ESTATUS FINAL</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if($hasRows) {
                while($cuenta = sqlsrv_fetch_array($datos, SQLSRV_FETCH_ASSOC)) {
                    // Buscamos en SolicitarFolio
                    $folio = ['folio' => '', 'fechaPeticion' => '', 'fechaEnvioNotif' => '', 'fechaNotif' => ''];
                    $qfolio = "SELECT TOP 1 folio, fechaPeticion, fechaEnvioNotif, fechaNotif FROM SolicitarFolio WHERE cPredial='" . $cuenta['cuentaPredial'] . "';";
                    $queryfolio = sqlsrv_query($cnx, $qfolio);
                    $hasfolio = sqlsrv_has_rows($queryfolio);
                    if($hasfolio) {
                        $folio = sqlsrv_fetch_array($queryfolio);
                    }

                    $datosEE = ['fechaResolucion' => '', 'fechaNotificacionResolucion' => '', 'elaboro' => '', 'fechaElaboracion' => '', 'quienFirma' => '', 'fechaEnvio' => '', 'fechaIngresoTesoreria' => '', 
                        'fechaRegresosello' => '', 'fechaAsignacion' => '', 'notificador' => '', 'fechaNotificacion' => '', 'etapa' => '', 'estatusFinal' => ''];
                    $qexpediente = "SELECT TOP 1 * FROM estatusexpedientes WHERE cuentaPredial='" . $cuenta['cuentaPredial'] . "';";
                    $queryexpediente = sqlsrv_query($cnx, $qexpediente);
                    $hasfolio = sqlsrv_has_rows($queryexpediente);
                    if($hasfolio) {
                        $datosEE = sqlsrv_fetch_array($queryexpediente);
                    }
            ?>
            <tr>
                <td class="p-1"><?=$cuenta['expediente']?></td>
                <td class="p-1"><?=$cuenta['cuentaPredial']?></td>
                <td class="p-1"><?=($datosEE['fechaResolucion']) ? date('d-m-Y', strtotime($datosEE['fechaResolucion'])) : ""?></td>
                <td class="p-1"><?=($datosEE['fechaNotificacionResolucion']) ? date('d-m-Y', strtotime($datosEE['fechaNotificacionResolucion'])) : ""?></td>
                <td class="p-1"><?=$datosEE['elaboro']?></td>
                <td class="p-1"><?=($datosEE['fechaElaboracion'] != "") ? date('d-m-Y', strtotime($datosEE['fechaElaboracion'])) : ""?></td>
                <td class="p-1"><?=$folio['folio']?></td>
                <td class="p-1"><?=$folio['fechaPeticion']?></td>
                
        <?php  
            //**********obtener total del monto **************************
                $cPredial=$cuenta['cuentaPredial'];
                $suma="select SUM(convert(float,REPLACE(REPLACE(credito.total,',',''),'$',''))) as MontoTotal from determinacion 
                inner join creditoFisDet as credito on credito.id_determinacion=determinacion.id_determinacion
                where determinacion.cuentaPredial='$cPredial'";
                $sumaT=sqlsrv_query($cnx,$suma);
                $montoTotal=sqlsrv_fetch_array($sumaT);
            //************************************************************
            $estatusFinal = getEstatusDeterminacion($cuenta['cuentaPredial'], $folio, $datosEE, $cnx);
                ?>
                <td class="p-1"><?php echo '$'.number_format($montoTotal['MontoTotal'],3) ?></td>            
<!--                <td class="p-1">$<?//=number_format(0, 2)?></td>-->
                
                
                <td class="p-1"><?=$datosEE['quienFirma']?></td>
                <td class="p-1"><?=($datosEE['fechaEnvio'] != "") ? date('d-m-Y', strtotime($datosEE['fechaEnvio'])): ""?></td>
                <td class="p-1"><?=($datosEE['fechaIngresoTesoreria']) ? date('d-m-Y', strtotime($datosEE['fechaIngresoTesoreria'])) : ""?></td>
                <td class="p-1"><?=($datosEE['fechaRegresosello'])? date('d-m-Y', strtotime($datosEE['fechaRegresosello'])) : ""?></td>
                <td class="p-1"><?=($datosEE['fechaAsignacion']) ? date('d-m-Y', strtotime($datosEE['fechaAsignacion'])) : ""?></td>
                <td class="p-1"><?=$datosEE['notificador']?></td>
<!--                <td class="p-1"><?//=$folio['fechaEnvioNotif']?></td>-->
                <td class="p-1"><?=($datosEE['fechaNotificacion'] != "") ? date('d-m-Y', strtotime($datosEE['fechaNotificacion'])) : ""?></td>
                <td class="p-1"><?=$folio['fechaNotif']?></td>
                <td class="p-1"><?=$datosEE['etapa']?></td>
                <td class="p-1"><?=$estatusFinal?></td>
            </tr>
            <?php } } else { ?>
            <tr>
                <td class="p-1" colspan="19">No hay informaci&oacute;n</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>
<?php sqlsrv_close($cnx); ?>