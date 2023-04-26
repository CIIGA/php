<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION['user'])){
require "include/lib.php";
require "conect.php";
    $cta=$_GET['ncnt'];
    $plz=$_GET['plz'];
    
    $us="SELECT * FROM implementta
    where Cuenta='$cta'";
    $use=sqlsrv_query($cnx,$us);
    $usuario=sqlsrv_fetch_array($use);
    
    $fo="SELECT * FROM registrofotomovil
    where cuenta='$cta'";
    $fot=sqlsrv_query($cnx,$fo);
    $foto=sqlsrv_fetch_array($fot);
    
    $insw="select COUNT(idRegistroFoto) regFoto from registrofotomovil
    where cuenta='$cta'";
    $inserw=sqlsrv_query($cnx,$insw);
    $inse=sqlsrv_fetch_array($inserw);

    $maNL="SELECT * FROM plaza
    where id_plaza='$plz'";
    $mapNL=sqlsrv_query($cnxa,$maNL);
    $mapaNL=sqlsrv_fetch_array($mapNL);
    
    $ul="select top 1 * from cuenta_vencida_detalle_actual
    where cuenta='$cta'";
    $ult=sqlsrv_query($cnxkpi,$ul);
    $ultima=sqlsrv_fetch_array($ult);
    
if(isset($_GET['update'])){
$cuenta=$_GET['cuenta'];
$propietario=$_GET['propietario'];
$clave=$_GET['clave'];
$calle=$_GET['calle'];
$numext=$_GET['numext'];
$numint=$_GET['numint'];
$colonia=$_GET['colonia'];
$cp=$_GET['cp'];
$seriemedidor=$_GET['seriemedidor'];
$deudatotal=$_GET['deudatotal'];
$rango=$_GET['rango'];
$fechaultimopago=$_GET['fechaultimopago'];
$fechaasignacion=$_GET['fechaasignacion'];
$fechavencimiento=$_GET['fechavencimiento'];
$fechaactualizacion=$_GET['fechaactualizacion'];
$totalpagado=$_GET['totalpagado'];
    
$datos="update implementta set
 Clave='$clave',Propietario='$propietario',Calle='$calle',NumExt='$numext',NumInt='$numint',Colonia='$colonia',CP='$cp',SerieMedidor='$seriemedidor',DeudaTotal='$deudatotal',Rango='$rango',TotalPagado='$totalpagado' where Cuenta='$cuenta'";
    sqlsrv_query($cnx,$datos) or die ('No se ejecuto la consulta update datos ciudadano');
echo '<script> alert("Resgistro Actulizado Correctamente.")</script>';
echo '<meta http-equiv="refresh" content="0,url=profile.php?ncnt='.$cuenta.'&plz='.$_GET['plz'].'&upd=2222">';
    }
?>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
<title>Perfil del ciudadano | ERDM</title>
<link rel="icon" href="../icono/icon.png">
<link href="../css/styles.css" rel="stylesheet" />
<link href="../css/slide.css" rel="stylesheet" />
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>        
<style>
  body {
        background-image: url(../img/back.jpg);
        background-repeat: repeat;
        background-size: 100%;
/*        background-attachment: fixed;*/
        overflow-x: hidden; /* ocultar scrolBar horizontal*/
    }
        body{
    font-family: sans-serif;
    font-style: normal;
    font-weight:bold;
    width: 100%;
    height: 100%;
    margin-top:0%;
}
    .jumbotron {
        margin-top:2%;
        margin-bottom:2%;
        padding-top:5%;
        padding-bottom:2%;
        padding-left:3%;
        padding-right:3%;
}
    .btn-whatsapp {
       display:block;
       width:70px;
       height:70px;
       color:#fff;
       position: fixed;
       left:20px;
       bottom:20px;
       border-radius:50%;
       line-height:80px;
       text-align:center;
       z-index:999;
}
.carousel-control-prev-icon {
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='000000' viewBox='0 0 8 8'%3E%3Cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3E%3C/svg%3E");
}

.carousel-control-next-icon {
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='000000' viewBox='0 0 8 8'%3E%3Cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3E%3C/svg%3E");
}
.carousel-indicators .active{
    background-color: #000000;
}    
.carousel-indicators li {
    background-color: #7a7a7a;
}    
</style>
    <?php// require "include/nav.php"; ?>    
</head>
<body>
        <div class="d-flex" id="wrapper">
            <!-- Sidebar-->
            <div class="border-end bg-white" id="sidebar-wrapper">
                        <div class="sidebar-heading border-bottom bg-light"><a href="acceso.php"><img src="../img/logoFIDI.png" height="50" alt=""></a></div><br>
                <div class="list-group list-group-flush">
                <h5 style="text-shadow: 1px 1px 2px #717171;text-align:center;">Plaza: <?php echo ucwords(strtolower($mapaNL['nombreplaza'])) ?></h5>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="map.php?plz=<?php echo $_GET['plz'].'&mp='.$_GET['mp'].'&src='.$usuario['Cuenta'].'&clv=1' ?>"><i class="fas fa-chevron-left"></i> Regresar</a><br>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="acceso.php"><i class="fas fa-home"></i> Inicio</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="map.php?plz=<?php echo $_GET['plz'] ?>"><i class="fas fa-map-marked-alt"></i> Mapas <?php echo ucwords(strtolower($mapaNL['nombreplaza'])) ?></a><br>
                </div>
            </div>
            <!-- Page content wrapper-->
            <div id="page-content-wrapper">
                <!-- Top navigation-->
<div class="btn-whatsapp">
    <button class="btn btn-link" id="sidebarToggle"><i class="fas fa-bars"></i></button>
</div>
<!-- Page content-->
<div class="container-fluid">
    <br>
<form action="" method="GET">    
<div class="container" style="padding-left:0%;padding-right:0%;">
    <h2 style="text-shadow: 1px 1px 2px #717171;">Plataforma Fidi</h2>
    <h5 style="text-shadow: 1px 1px 2px #717171;"><i class="fas fa-users"></i> Perfil del ciudadano</h5>   
    <h5 style="text-shadow: 1px 1px 2px #717171;">C. <?php echo ucwords(strtolower($usuario['Propietario'])) ?></h5>
    <div class="row">
          <div class="row">
            <div class="col-md-6" style="box-shadow: 0px 0px 1px #717171;text-align:center;">
              
    <div class="jumbotron">
        <div class="md-form form-group">
              <label for="exampleInputEmail1">Propietario: *</label>
    <input type="text" class="form-control" name="propietario" placeholder="Nombre completo" value="<?php echo $usuario['Propietario'] ?>" required>
          </div>
        <div class="form-row">
        <div class="col-md-6">
          <div class="md-form form-group">
            <label for="exampleInputEmail1">Clave: *</label>
    <input type="text" class="form-control" name="clave" placeholder="Clave" value="<?php echo $usuario['Clave'] ?>" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="md-form form-group">
            <label for="exampleInputEmail1">Calle: *</label>
    <input type="text" class="form-control" name="calle" placeholder="Calle" value="<?php echo $usuario['Calle'] ?>" required>
          </div>
        </div>
      </div>
        <div class="form-row">
        <div class="col-md-6">
          <div class="md-form form-group">
            <label for="exampleInputEmail1">Num. Ext: *</label>
    <input type="text" class="form-control" name="numext" placeholder="Numero Exterior" value="<?php echo $usuario['NumExt'] ?>">
          </div>
        </div>
        <div class="col-md-6">
          <div class="md-form form-group">
            <label for="exampleInputEmail1">Num. Int: *</label>
    <input type="text" class="form-control" name="numint" placeholder="Numero Interior" value="<?php echo $usuario['NumInt'] ?>">
          </div>
        </div>
      </div>
    <div class="form-row">
        <div class="col-md-6">
          <div class="md-form form-group">
            <label for="exampleInputEmail1">Colonia: *</label>
    <input type="text" class="form-control" name="colonia" placeholder="Colonia" value="<?php echo $usuario['Colonia'] ?>">
          </div>
        </div>
        <div class="col-md-6">
          <div class="md-form form-group">
            <label for="exampleInputEmail1">Codigo GETal: *</label>
    <input type="text" class="form-control" name="cp" placeholder="Codigo GETal" value="<?php echo $usuario['CP'] ?>">
          </div>
        </div>
      </div>
    <div class="form-row">
        <div class="col-md-6">
          <div class="md-form form-group">
            <label for="exampleInputEmail1">Serie de Medidor: *</label>
    <input type="text" class="form-control" name="seriemedidor" placeholder="Serie del medidor" value="<?php echo $usuario['SerieMedidor'] ?>" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="md-form form-group">
            <label for="exampleInputEmail1">Deuda Total: *</label>
    <input type="text" class="form-control" name="deudatotal" placeholder="Numero Interior" value="<?php echo '$'.$usuario['DeudaTotal'] ?>">
          </div>
        </div>
      </div>
    <div class="form-row">
        <div class="col-md-6">
          <div class="md-form form-group">
            <label for="exampleInputEmail1">Rango: *</label>
    <input type="text" class="form-control" name="rango" placeholder="Rango" value="<?php echo $usuario['Rango'] ?>">
          </div>
        </div>
        <div class="col-md-6">
          <div class="md-form form-group">
            <label for="exampleInputEmail1">Fecha de ultimo pago: *</label>
        <?php
        if($usuario['FechaUltimoPago']<>NULL){
        $fechaultimo = $usuario['FechaUltimoPago']->format('Y-m-d H:i:s'); ?>
        <input type="text" class="form-control" name="fechaultimopago" placeholder="Fecha de ultimo pago" value="<?php echo $fechaultimo ?>">    
    <?php } else{ ?>
        <input type="text" class="form-control" name="fechaultimopago" placeholder="Fecha de ultimo pago">
    <?php } ?>
          </div>
        </div>
      </div>
        <div class="form-row">
        <div class="col-md-6">
          <div class="md-form form-group">
            <label for="exampleInputEmail1">Fecha de Asignacion: *</label>
        <?php
        if($usuario['FechaAsignacion']<>NULL){
        $fechaAsigna = $usuario['FechaAsignacion']->format('Y-m-d H:i:s'); ?>
    <input type="text" class="form-control" name="fechaasignacion" placeholder="Fecha de Asignacion" value="<?php echo $fechaAsigna ?>">    
        <?php } else{ ?>
            <input type="text" class="form-control" name="fechaasignacion" placeholder="Fecha de Asignacion">   
        <?php } ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="md-form form-group">
            <label for="exampleInputEmail1">Fecha de Vencimiento: *</label>
    <?php
        if($usuario['FechaVencimiento']<>NULL){
        $fechaVenci = $usuario['FechaVencimiento']->format('Y-m-d H:i:s'); ?>
<input type="text" class="form-control" name="fechavencimiento" placeholder="Fecha de Vencimiento" value="<?php echo $fechaVenci ?>">    
        <?php } else{ ?>
            <input type="text" class="form-control" name="fechavencimiento" placeholder="Fecha de Vencimiento">   
        <?php } ?>
          </div>
        </div>
      </div>
        <div class="form-row">
        <div class="col-md-6">
          <div class="md-form form-group">
            <label for="exampleInputEmail1">Fecha de Actualizacion: *</label>
    <?php
        if($usuario['FechaActualizacion']<>NULL){
        $fechaActual = $usuario['FechaActualizacion']->format('Y-m-d H:i:s'); ?>
<input type="text" class="form-control" name="fechaactualizacion" placeholder="Fecha de Vencimiento" value="<?php echo $fechaActual ?>">    
        <?php } else{ ?>
            <input type="text" class="form-control" name="fechaactualizacion" placeholder="Fecha de Actualizacion">   
        <?php } ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="md-form form-group">
            <label for="exampleInputEmail1">Total Pagado: *</label>
    <input type="text" class="form-control" name="totalpagado" placeholder="Total Pagado" value="<?php echo '$'.$usuario['TotalPagado'] ?>">
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-6">
          <div class="md-form form-group">
            <label for="exampleInputEmail1">Fecha de ultima gestion: *</label>
    <?php
        if($ultima['fecha_captura']<>NULL){
        $fechaActual = $ultima['fecha_captura']->format('Y-m-d H:i:s'); ?>
<input type="text" class="form-control" name="fechacaptura" placeholder="Fecha de Vencimiento" value="<?php echo $fechaActual ?>">    
        <?php } else{ ?>
            <input type="text" class="form-control" name="fechacaptura" placeholder="Fecha de Actualizacion">   
        <?php } ?>
          </div>
        </div>
        <div class="col-md-6">
            
            
            
        </div>
      </div>   

    <small id="e" class="form-text text-muted" style="font-size:14px;">* Campos son requeridos.</small>
     <br>
<input type="hidden" class="form-control" name="cuenta" value="<?php echo $usuario['Cuenta'] ?>" required>

</div>
              
    </div>
<div class="col-md-6" style="box-shadow: 0px 0px 1px #717171;"><br>
    <h4 style="text-shadow: 1px 1px 2px #717171;"><i class="fas fa-camera-retro"></i> Fotografias</h4>
    <br>   
<?php// do{ ?>
<!--    <a class="btn btn-link btn-sm" data-toggle="modal" data-target="#foto<?php// echo $foto['idRegistroFoto'] ?>"><img src="<?php// echo $foto['urlImagen'] ?>" alt="Responsive image" width="20%" style="box-shadow: 2px 2px 4px #717171;" class="rounded"></a>-->
<!--**********************************MODAL FOTO******************************************************************************-->
<!--
<div class="modal fade" id="foto<?php// echo $foto['idRegistroFoto'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <img src="<?php// echo $foto['urlImagen'] ?>" alt="Responsive image" width="100%" style="box-shadow: 2px 2px 4px #717171;">
          <?php// $fechaCaptura = $foto['fechaCaptura']->format('Y-m-d H:i:s'); ?>
          <br><br>
          <h6 style="text-shadow: 0px 0px 2px #717171;text-align:center;">Fecha de Captura: <?php// echo $fechaCaptura ?></h6>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Salir</button>
      </div>
    </div>
  </div>
</div>
-->
<!--*************************************** FIN MODAL FOTO*********************************************************************-->
<?php// } while($foto=sqlsrv_fetch_array($fot)); ?>
    
    
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>  
  </ol>    
  <div class="carousel-inner" style="text-align:center;">
    <div class="carousel-item active">
      <img class="img-fluid" src="https://erdm.mx/asdasd.png" alt="Responsive image">
    </div>
    <?php do{ ?>
    <div class="carousel-item">
        <a class="btn btn-link btn-sm" data-toggle="modal" data-target="#foto<?php echo $foto['idRegistroFoto'] ?>"><img class="img-fluid" src="<?php echo $foto['urlImagen'] ?>" alt="Responsive image" style="height:380px"></a>
        <div class="carousel-caption d-none d-md-block">
            <div class="alert alert-dark" role="alert" style="color:#000000; padding-top:1%;padding-bottom:0%;background:rgba(218,218,218, 0.7);">
                <?php $fechaCaptura = $foto['fechaCaptura']->format('Y-m-d H:i:s'); ?>
              <h6 class="small" style="text-shadow: 0px 0px 2px #717171;text-align:center;">Fecha de Captura: <?php echo $fechaCaptura ?></h6>
            </div>
        </div>
    </div>
<!--**********************************MODAL FOTO******************************************************************************-->
<div class="modal fade" id="foto<?php echo $foto['idRegistroFoto'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <img src="<?php echo $foto['urlImagen'] ?>" alt="Responsive image" width="100%" style="box-shadow: 2px 2px 4px #717171;">
          <?php $fechaCaptura = $foto['fechaCaptura']->format('Y-m-d H:i:s'); ?>
          <br><br>
          <h6 style="text-shadow: 0px 0px 2px #717171;text-align:center;">Fecha de Captura: <?php echo $fechaCaptura ?></h6>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Salir</button>
      </div>
    </div>
  </div>
</div>
<!--*************************************** FIN MODAL FOTO*********************************************************************-->      
    <?php } while($foto=sqlsrv_fetch_array($fot)); ?>  
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
    <h6 class="small" style="text-shadow: 0px 0px 2px #717171;text-align:center;">Fotos registradas en esta cuenta: <?php echo $inse['regFoto']; ?></h6>
              </div>
            </div>
        </div>
    </div>
</form>
            </div>
        </div>
    </div>
<br><br><br>
</body>
<?php 
    } else{
    header('location:../../login.php');
}
require "include/footer.php"; ?>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script src="../js/scripts.js"></script>    
</html>