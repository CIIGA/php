<?php
session_start();
if((isset($_SESSION['user'])) and (isset($_SESSION['tipousuario']))){
//require "../../acnxerdm/cnx.php";
require "conect.php";    
    $plz=$_GET['idpl'];
    
    $pro="SELECT * FROM plaza
    inner join proveniente on proveniente.id_proveniente=plaza.id_proveniente
    where plaza.id_plaza='$plz' and plaza.id_proveniente is not NULL";
    $prov=sqlsrv_query($cnx,$pro);
    $proveniente=sqlsrv_fetch_array($prov);
    
    $st="SELECT * FROM sync
    inner join plaza on plaza.id_plaza=sync.id_plaza
    where plaza.id_plaza='$plz'";
    $sto=sqlsrv_query($cnx,$st);
    $sync=sqlsrv_fetch_array($sto);
    
    $pro="SELECT * FROM plaza
    where id_plaza='$plz'";
    $prov=sqlsrv_query($cnx,$pro);
    $prove=sqlsrv_fetch_array($prov);
    
//*********************************** INICIO INSERT SYNC *******************************************************
if(isset($_POST['save'])){
    $nombre=$_POST['nombre'];
    $stored=$_POST['stored'];
    $plzid=$_GET['idpl'];
    
    $val="select * from sync
    where stored='$stored' AND id_plaza='$plzid'";
    $vali=sqlsrv_query($cnx,$val);
    $valida=sqlsrv_fetch_array($vali);
if($valida){
    echo '<script>alert("El Stored procedure ya esta agregado. \nVerifique registro")</script>';
    echo '<meta http-equiv="refresh" content="0,url=store.php?idpl='.$plzid.'">';
} else{
    $unidad="insert into sync (id_plaza,nombreSync,stored) values ('$plzid','$nombre','$stored')";
		sqlsrv_query($cnx,$unidad) or die ('No se ejecuto la consulta isert nueva plz');
        echo '<script>alert("Proceso de sincronización agregado correctamente")</script>';
        echo '<meta http-equiv="refresh" content="0,url=store.php?idpl='.$plzid.'">';
    }
}
//************************ FIN INSERT SYNC ******************************************************************    
//****************************ACTUALIZAR DATOS DE USUARIO******************************************************
if(isset($_POST['update'])){
    $idsync=$_POST['idsync'];
    $nombreSync=$_POST['nombre'];
    $stor=$_POST['stored'];
    $plzid=$_GET['idpl'];
    
    $udtStr="update sync set nombreSync='$nombreSync', stored='$stor'
    where id_sync='$idsync'";
    sqlsrv_query($cnx,$udtStr) or die ('No se ejecuto la consulta update datosart');
    echo '<script> alert("Registro actualizado correctamente.")</script>';
    echo '<meta http-equiv="refresh" content="0,url=store.php?idpl='.$plzid.'">';
}
//****************************FIN ACTUALIAR DATOS DE USUARIO***************************************************
//****************************CONSULTA POR FECHA REGISTRO**************************************************
if(isset($_GET['execute'])){
    $plzid=$_GET['idpl'];
    $syncid=$_GET['syncid'];
    $ini=$_GET['inicial'];
    $fin=$_GET['final'];
    $executedDate=date('Y-m-d_H:i:s');
    
    $val="select * from sync
    where id_sync='$syncid'";
    $vali=sqlsrv_query($cnx,$val);
    $valida=sqlsrv_fetch_array($vali);
    
    $nombreStore='['.$valida['stored'].']';
    
//    $store="execute [dbo].$nombreStore '$ini','$fin',0";
//    echo $store;

    $store="execute [dbo].$nombreStore'$ini','$fin',0";
    $st=sqlsrv_query($cnxa,$store) or die ('Execute Stored Procedure Failed... Query store.php');
    $resultSt=sqlsrv_fetch_array($st);
    
    if(isset($resultSt)){
        if($resultSt['resultado']==1){
            $udtExecute="update sync set executed='$executedDate'
            where id_sync='$syncid'";
            sqlsrv_query($cnx,$udtExecute) or die ('No se ejecuto la consulta update execute sync');
            echo '<script> alert("Stored ejecutado correctamente.")</script>';
            echo '<meta http-equiv="refresh" content="0,url=store.php?idpl='.$plzid.'">';
        } else{
            echo '<script> alert("Error en la ejecución de Stored Procedure, informe al área de sistemas.")</script>';
            echo '<meta http-equiv="refresh" content="0,url=store.php?idpl='.$plzid.'">';
        }
    } else{
        echo '<script> alert("Execute Stored Procedure Failed. Informe al área de sistemas.")</script>';
        echo '<meta http-equiv="refresh" content="0,url=store.php?idpl='.$plzid.'">';
    }
}
//****************************FIN CONSULTA POR FECHA REGISTRO**********************************************
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sincronización | KPIs</title>
<link rel="icon" href="../icono/icon.png">
<!-- Bootstrap -->
<link rel="stylesheet" href="../css/bootstrap.css">
<link href="../fontawesome/css/all.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="../js/peticionAjax.js"></script>
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
    margin-top:-1%;
    padding-top:0px;
}
    .jumbotron {
        margin-top:0%;
        margin-bottom:0%;
        padding-top:3%;
        padding-bottom:2%;
}
    .padding {
        padding-right:35%;
        padding-left:35%;
    }
    </style>
<?php require "include/nav.php"; ?>
</head>
<body>
<div class="container">
    <h1 style="text-shadow: 1px 1px 2px #717171;">Sincronización de KPIs</h1>
    <h3 style="text-shadow: 1px 1px 2px #717171;">Plaza <?php echo utf8_encode($prove['nombreplaza']) ?></h3>
    <h5 style="text-shadow: 1px 1px 2px #717171;"><i class="fas fa-server"></i> Agregar nuevo proceso de sincronización</h5>
</div>    
<?php if(isset($proveniente)){ ?>
<div class="container">    
<form action="" method="post">
<div class="jumbotron">   
    
<div class="form-row">
    <div class="col-md-6">
        <div class="md-form form-group">
            <label for="exampleInputEmail1">Nombre de Sincronización: *</label>
            <input style="text-align:center;" type="text" class="form-control" name="nombre" placeholder="Nombre del proceso" required>
        </div>
    </div>
    <div class="col-md-6">
        <label for="exampleInputEmail1">Stored Procedure: *</label>
        <input style="text-align:center;" type="text" class="form-control" name="stored" placeholder="Nombre del Stored Procedure" required>
    </div>
</div>
    
    <small id="e" class="form-text text-muted" style="font-size:14px;">*Todos los campos son requeridos.<br>
    El origen de datos es proveniente del SQLserver Implementta.<br>
    Si no conoce el nombre correcto del recurso Stored, contacte al área de sistemas.</small>
    <span class="badge badge-warning">Agregar nuevo proceso a plaza <?php echo utf8_encode($prove['nombreplaza']) ?></span> 
<div style="text-align:right;">
        <button type="submit" class="btn btn-primary btn-sm" name="save"><i class="fas fa-plus"></i> Agregar nuevo proceso de sincronización</button>
</div>
        </div>
</form>
<hr>
    <h3 style="text-shadow: 1px 1px 2px #717171;"><i class="fas fa-sync"></i> Procesos de sincronización</h3>
<hr>
</div>
<div class="container">
<?php if(isset($sync)){ ?>
<table class="table table-sm table-hover">
  <thead>
    <tr align="center">
      <th scope="col"></th>
      <th scope="col">Nombre</th>
      <th scope="col">Stored Procedure</th>
      <th scope="col">Last update</th>
      <th scope="col">Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php do{ ?>  
    <tr>
      <td style="text-align:center;"><span class="badge badge-pill badge-success"><i class="fas fa-check"></i> <small><?php echo $sync['id_sync'] ?></small></span></td>    
      <td><?php echo utf8_encode($sync['nombreSync']) ?></td>
      <td style="text-align:center;"><?php echo utf8_encode($sync['stored']) ?></td>
      <td style="text-align:center;"><?php echo $sync['executed'] ?></td>
        
    <td style="text-align:center;">
        
    <a href="" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#execute<?php echo $sync['id_sync'] ?>"><span aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Datos de plaza"><i class="fas fa-sync"></i> Execute</span></a>
        
    <a href="" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#datos<?php echo $sync['id_sync'] ?>"><span aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Datos de plaza"><i class="far fa-edit"></i></span></a>
          
    <a href="delete.php?ponesync=1&syc=<?php echo $sync['id_sync'].'&idpl='.$_GET['idpl'] ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar proceso" onclick="return Confirmar('¿Esta seguro que decea eliminar este proceso de sincronización?')"><i class="far fa-trash-alt"></i></a>
        </td>
    </tr>
  </tbody>
<!-- *********************************MODAL PARA ACTUALIZAR UDATOS *************************************************** -->
<form action="" method="post">
<div class="modal fade" id="datos<?php echo $sync['id_sync'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" style="text-shadow: 0px 0px 2px #717171;">Editar Procesos de sincronización</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group" style="text-align:center;">
            <label for="exampleInputEmail1">Nombre de proceso: </label>
            <input type="text" class="form-control" name="nombre" value="<?php echo utf8_encode($sync['nombreSync']) ?>" required>
        </div>
          
        <div class="form-group" style="text-align:center;">    
            <label for="exampleInputEmail1">Stored Procedure: </label>
            <input type="text" class="form-control" name="stored" value="<?php echo utf8_encode($sync['stored']) ?>" required>  
        </div>
            <br>
      </div>
      <div class="modal-footer">
          <input type="hidden" class="form-control" name="idsync" value="<?php echo $sync['id_sync'] ?>">
          <button type="submit" class="btn btn-primary btn-sm" name="update"><i class="fas fa-pen-alt"></i> Actualizar</button>
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Salir</button>
      </div>
    </div>
  </div>
</div>
</form>    
<!-- ***********************************FIN MODAL ACTUALIZAR DATOS *************************************************** -->
<!-- *********************************MODAL PARA ACTUALIZAR UDATOS *************************************************** -->
<form action="" method="get">    
<div class="modal fade" id="execute<?php echo $sync['id_sync'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" style="text-shadow: 0px 0px 2px #717171;"><img src="../img/powerByLogo.png" class="img-fluid" alt="Responsive image" width="15%"> Ejecutar Sincronización</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <span class="badge badge-danger">Plaza <?php echo utf8_encode($prove['nombreplaza']) ?></span>
        <h6 class="modal-title" style="text-shadow: 0px 0px 1px #717171;">Proceso: <?php echo utf8_encode($sync['nombreSync']) ?></h6>
        <h6 class="modal-title" style="text-shadow: 0px 0px 1px #717171;">Stored Procedure: <?php echo utf8_encode($sync['stored']) ?></h6>
        <h6 class="modal-title" style="text-shadow: 0px 0px 1px #717171;">Origen de datos: <?php echo utf8_encode($proveniente['data']) ?></h6>
          <hr>
          <label>Selecciona un intervalo de fechas: *</label>
        <div class="form-row">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <div class="col-md-2.5">
          <div class="md-form form-group">
            <input type="date" name="inicial" class="form-control" required style="width:auto;">
          </div>
        </div>
        <div class="col-md-2.5">
          <div class="md-form form-group">
              <i class="fas fa-arrows-alt-h"></i>
          </div>
        </div>
        <div class="col-md-2.5">
          <div class="md-form form-group">
            <input type="date" name="final" class="form-control" required style="width:auto;">
          </div>
        </div>
      </div>
        <input type="hidden" class="form-control" name="idpl" value="<?php echo $_GET['idpl'] ?>" required>
        <input type="hidden" class="form-control" name="syncid" value="<?php echo $sync['id_sync'] ?>" required>
    <div style="text-align:center;">
        <button type="submit" class="btn btn-warning btn-sm" name="execute" onclick="return Confirmar('¿Esta seguro que decea ejecutar este proceso de sincronización?')"><img src="../img/powerByLogo.png" class="img-fluid" alt="Responsive image" width="30%"> 
        <h6 style="text-shadow: 0px 0px 2px #717171;">Ejecutar proceso</h6></button>
    </div> 
          <br>
    <small id="e" class="form-text text-muted" style="font-size:14px;text-align:center;">*Si no conoce el nombre correcto del recurso Stored Procedure, contacte al área de sistemas.</small>
          
          
          
            <br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Salir</button>
      </div>
    </div>
  </div>
</div>
</form>
<!-- ***********************************FIN MODAL ACTUALIZAR DATOS *************************************************** --> 
<?php } while($sync=sqlsrv_fetch_array($sto)); ?>
    </table>
<?php } else{ ?>
    <div class="alert alert-info" role="alert">
        <i class="fas fa-terminal"></i> No hay ningún proceso de sincronización.
    </div>
<?php } ?>
    <br><br>
<div style="text-align:center;">    
    <a href="addplz.php" class="btn btn-dark btn-sm"><i class="fas fa-chevron-left"></i> Regresar</a>
</div>
    </div>
<?php } else{ ?>
<div class="container">
    <br><hr><br>
    <div class="alert alert-danger" role="alert" style="text-align:center;">
        <i class="fas fa-database"></i> Esta plaza no tiene asignado ningun origen de datos <br>
                                        Agregue un origen para guardar procesos de sincronización<br><hr>
            <a href="origen.php" class="btn btn-primary btn-sm"><i class="fas fa-database"></i> Nuevo origen de datos</a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="addplz.php" class="btn btn-dark btn-sm"><i class="fas fa-chevron-left"></i> Regresar</a>
    </div>
    <br><hr><br>
</div>
<?php } ?>    
<br><br>
<?php } else{
    header('location:../../login.php');
}
require "include/footer.php";
    ?>
</body>
<script src="../js/jquery-3.4.1.min.js"></script>
<script src="../js/popper.min.js"></script>    
<script src="../js/bootstrap.js"></script>  
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
<script>
    function Confirmar(Mensaje){
        return (confirm(Mensaje))?true:false;
    }
</script>      
</html>
