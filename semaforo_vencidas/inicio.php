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

$pagina = 1;
$registros = 1;
$numRegistros = 50;
$hasRows = false;
$tpaginas = 0;
$search = "";
$extraquery = "";
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    if($search != "") {
        $extraquery = " WHERE cuentaPredial='$search' ";
    }
}

if(isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
    $registros = $_GET['pagina'];
}

$registros = ($registros - 1) * $numRegistros;
$query = "SELECT * FROM determinacion $extraquery ORDER BY id_determinacion DESC OFFSET $registros ROWS FETCH NEXT $numRegistros ROWS ONLY";
$datos = sqlsrv_query($cnx, $query) or die(print_r(sqlsrv_errors()));
$hasRows = sqlsrv_has_rows($datos);

$query = "SELECT count(id_determinacion) as total FROM determinacion $extraquery;";
$paginas = sqlsrv_query($cnx, $query) or die(print_r(sqlsrv_errors()));
$hasRowsPaginas = sqlsrv_has_rows($paginas);
if($hasRowsPaginas) {
    $paginas = sqlsrv_fetch_array($paginas);
    $tpaginas = $paginas['total']/$numRegistros;
    $sobrante = $paginas['total']%$numRegistros;
    if($sobrante > 0) $tpaginas = $tpaginas + 1;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Expedientes Determinacion</title>
    <link rel="icon" href="../../icono/implementtaIcon.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../css/bootstrap.css">
    <link href="../../fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.css"
        id="theme-styles">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.css" id="theme-styles">
    <link href="../../fontawesome/css/all.css" rel="stylesheet">
    <style>
    body {
        background-image: url(../../img/back.jpg);
        background-repeat: repeat;
        background-size: 100%;
        background-attachment: fixed;
        overflow-x: hidden;
        /* ocultar scrolBar horizontal*/
    }

    body {
        font-family: sans-serif;
        font-style: normal;
        font-weight: normal;
        width: 100%;
        height: 100%;
        margin-top: -2%;
        padding-top: 0px;
    }

    .jumbotron {
        margin-top: 0%;
        margin-bottom: 0%;
        padding-top: 4%;
        padding-bottom: 1%;
    }

    .padding {
        padding-right: 15%;
        padding-left: 15%;
    }
    </style>
</head>

<body>
    <br>
    <!--********************************INICIO NAVBAR***************************************************************--> 
<br> 
 <nav class="navbar navbar-expand-lg navbar-light">
   <a href="../../Administrador/selectSistem.php"><img src="../../img/logoImplementtaHorizontal.png" width="250" height="82" alt=""></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a class="nav-item nav-link" href="inicio.php"> Inicio </a>

        <a class="nav-item nav-link" href="#"> <i class="fas fa-users-cog"></i> Administrador </a>
        <a class="nav-item nav-link" href="../../Administrador/logout.php"> Salir <i class="fas fa-sign-out-alt"></i></a>

    </ul>

  </div>
</nav>
<!--*************************************NAVBAR*************************************************************-->
    <div class="p-4">
        <div class="row">
            <div class="col-sm-12">
                <h1 style="text-shadow: 0px 0px 2px #717171;">
                    <img width="50px" src="https://gallant-driscoll.198-71-62-113.plesk.page/Implementta/modulos/img/reportes.png" /> Expedientes Determinacion
                </h1>
            </div>
        </div>
        <hr>
        <div class="card">
            <div class="card-body">
                <?php
                if (isset($_GET['error'])) {
                    $alert = ($_GET['error'] == 1) ? 'alert-danger' : 'alert-success';
                    $msg = $_GET['msg']; ?>
                    <div class="alert <?= $alert ?>"><strong><?=$msg?></strong></div>
                <?php } ?>
                <form method="GET" onsubmit="javascript:loadInfo();" autocomplete="off">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label for="search" class="form-label">Buscar por cuenta:</label>
                                <input type="text" value="<?=$search?>" placeholder="Buscar por cuenta" name="search"
                                    id="search" class="form-control" autofocus>
                            </div>
                        </div>
                        <div class="col-sm-5 mt-2">
                            <br>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Buscar</button>
                            <a target="_blank" class="btn btn-info btn-sm toDownload" href="./excel.php?search=<?=$search?>&pagina=<?=$pagina?>"><i class="fa fa-download"></i> Descargar Pagina</a>
                            <a target="_blank" class="btn btn-warning btn-sm toDownload" href="./exceltodos.php"><i class="fa fa-download"></i> Descargar Todo</a>
                            <a class="btn btn-dark btn-sm" data-toggle="modal" data-target="#modal-upload-file-reporte" href="#"><i class="fa fa-upload"></i> Subir Datos</a>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
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

                                    $datosEE = ['fechaResolucion'=> '', 'fechaNotificacionResolucion' => '', 'elaboro' => '', 'fechaElaboracion' => '', 'quienFirma' => '', 'fechaEnvio' => '', 'fechaIngresoTesoreria' => '', 
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
                                
                                
                                <td class="p-1"><?=$datosEE['quienFirma']?></td>
                                <td class="p-1"><?=($datosEE['fechaEnvio'] != "") ? date('d-m-Y', strtotime($datosEE['fechaEnvio'])): ""?></td>
                                <td class="p-1"><?=($datosEE['fechaIngresoTesoreria']) ? date('d-m-Y', strtotime($datosEE['fechaIngresoTesoreria'])) : ""?></td>
                                <td class="p-1"><?=($datosEE['fechaRegresosello'])? date('d-m-Y', strtotime($datosEE['fechaRegresosello'])) : ""?></td>
                                <td class="p-1"><?=($datosEE['fechaAsignacion']) ? date('d-m-Y', strtotime($datosEE['fechaAsignacion'])) : ""?></td>
                                <td class="p-1"><?=$datosEE['notificador']?></td>
<!--                                <td class="p-1"><? //=$folio['fechaEnvioNotif']?></td>-->
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
                        <tfoot>
                        <?php if($hasRows && $tpaginas > 0) { ?>
                            <tr>
                                <td class="p-1" colspan="19">
                                    <nav aria-label="...">
                                        <ul class="pagination">
                                            <?php for($i = 1; $i<=$tpaginas; $i++) { ?>
                                                <li class="page-item <?=($i == $pagina) ? 'active' : "" ?>">
                                                    <a onclick="loadInfojavascript:loadInfo();" class="page-link" href="?search=<?=$search?>&pagina=<?=$i?>">
                                                        <?=$i?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </nav>
                                </td>
                            </tr>
                        <?php } ?>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-upload-file-reporte" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <form action="./upload.php" method="POST" autocomplete="off" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Plantilla Estatus de Expedientes</h5>
                        </div>
                        <div class="modal-body">
                            <label for="file_upload">Selecciona archivo</label>
                            <input type="file" name="file_upload" id="file_upload" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-sm">Subir Archivo</button>
                            <a href="./datos_reporte.xlsx" download="" class="btn btn-info btn-sm"><i class="fa fa-download"></i> Descargar Plantilla</a>
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <br><br>
    <div style="text-align:center;">
        <a href="../../determinaciones/inicio.php" class="btn btn-dark btn-sm"><i class="fas fa-chevron-left"></i> Regresar</a>
    </div>
    <br>
<!--*************************INICIO FOOTER***********************************************************************-->
<nav class="navbar sticky-bottom navbar-expand-lg">
    <span class="navbar-text" style="font-size:12px;font-weigth:normal;color: #7a7a7a;">
        Implementta ©<br>
        Estrategas de México <i class="far fa-registered"></i><br>
        Centro de Inteligencia Informática y Geografía Aplicada CIIGA
        <hr style="width:105%;border-color:#7a7a7a;">
        Created and designed by <i class="far fa-copyright"></i> <?php echo date('Y') ?> Estrategas de México<br>
    </span><hr>
    <span class="navbar-text" style="font-size:12px;font-weigth:normal;color: #7a7a7a;">
        Contacto:<br>
        <i class="fas fa-phone-alt"></i> Red: 187<br>
        <i class="fas fa-phone-alt"></i> 66 4120 1451<br>
        <i class="fas fa-envelope"></i> sistemas@estrategas.mx<br>
    </span>
    <ul class="navbar-nav mr-auto">
        <br><br><br><br><br><br><br><br>
    </ul>
    <form class="form-inline my-2 my-lg-0">
        <a href="../../index.php"><img src="../../img/logoImplementta.png" width="155" height="150" alt=""></a>
        <a href="http://estrategas.mx/" target="_blank"><img src="../../img/logoTop.png" width="200" height="85" alt=""></a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </form>
</nav>
<!--***********************************FIN FOOTER****************************************************************-->
    <script src="../../js/jquery-3.4.1.min.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.fileDownload/1.4.2/jquery.fileDownload.min.js"></script>
    <script>
        var loadInfo = function () {
            Swal.fire({
                title: 'Obteniendo Datos',
                html: 'Espere un momento porfavor...',
                timer: 0,
                timerProgressBar: true,
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                willClose: () => {
                    return false;
                }
                }).then((result) => {}
            );
        }
        $('.toDownload').on('click', function () {
            toDownload($(this).attr('href'));
            return false;
        });

        var toDownload = function (url) {
            $.fileDownload(url, {
                successCallback: function (url) {
                    Swal.fire('Archivo Descargado', '', 'success' );
                },
                failCallback: function () {
                    Swal.fire('No se pudo descargar el archivo', '', 'error' );
                },
                prepareCallback: function () {
                    Swal.fire({
                        title: 'Generando Archivo Excel',
                        html: 'Espere un momento porfavor.',
                        timer: 0,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    }).then((result) => { })
                }
            });
        }
    </script>
</body>

</html>
<?php sqlsrv_close($cnx); ?>