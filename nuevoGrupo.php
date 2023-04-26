<?php
session_start();
if((isset($_SESSION['user'])) and (isset($_SESSION['tipousuario']))){
require "../../acnxerdm/cnx.php";
    $idplz=$_GET['plz'];
    $pl="SELECT * FROM subregistro
    inner join plaza on plaza.id_plaza=subregistro.id_plaza
    where subregistro.id_plaza='$idplz'";
    $plz=sqlsrv_query($cnx,$pl);
    $plaza=sqlsrv_fetch_array($plz);
    
    $pla="SELECT * FROM plaza
    where id_plaza='$idplz'";
    $plza=sqlsrv_query($cnx,$pla);
    $plazaa=sqlsrv_fetch_array($plza);
//*********************************** INICIO INSERT PLZ *******************************************************
if(isset($_GET['save'])){
    $idplaza=$_GET['plz'];
    $nombre=$_GET['nombre'];
    $val="select * from subregistro
    where nombreSub='$nombre' and id_plaza='$idplaza'";
    $vali=sqlsrv_query($cnx,$val);
    $valida=sqlsrv_fetch_array($vali);
if($valida){
    echo '<script>alert("El nombre de subregistro ya esta agregado. \nVerifique registro")</script>';
    echo '<meta http-equiv="refresh" content="0,url=nuevoGrupo.php?plz='.$idplaza.'">';
} else{
    $unidad="insert into subregistro values ('$idplaza','$nombre')";
		sqlsrv_query($cnx,$unidad) or die ('No se ejecuto la consulta isert nuevo subgrupo');
        echo '<script>alert("Plaza agregada correctamente")</script>';
        echo '<meta http-equiv="refresh" content="0,url=nuevoGrupo.php?plz='.$idplaza.'">';
    }
}
//************************ FIN INSERT PLZ ******************************************************************
//****************************ACTUALIZAR DATOS DE USUARIO******************************************************
if(isset($_GET['update'])){
    $idreg=$_GET['reg'];
    $nameSub=$_GET['nombreSub'];
    
    $datos="update subregistro set nombreSub='$nameSub'
    where id_subregistro='$idreg'";
    sqlsrv_query($cnx,$datos) or die ('No se ejecuto la consulta update datosart');
    echo '<script> alert("Registro actulizado correctamente.")</script>'; 
    echo '<meta http-equiv="refresh" content="0,url=nuevoGrupo.php?plz='.$idplz.'">';
}
//****************************FIN ACTUALIAR DATOS DE USUARIO***************************************************    
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Nuevo Subgrupo | KPIs</title>
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
    <h1 style="text-shadow: 1px 1px 2px #717171;">Plataforma de KPIs</h1>
    <h4 style="text-shadow: 1px 1px 2px #717171;"><i class="fas fa-object-ungroup"></i> Agregar nuevo subgrupo de KPIs</h4>
    <h4 style="text-shadow: 1px 1px 2px #717171;">Plaza <?php echo utf8_encode($plazaa['nombreplaza']) ?></h4>
<form action="" method="GET">
<div class="jumbotron">
    <div class="form-group" style="text-align:center;">
    <label for="exampleInputEmail1">Nombre del subgrupo: *</label>
    <input style="text-align:center;" type="text" class="form-control" name="nombre" placeholder="Nombre del nuevo subgrupo" required autofocus>
  </div> 
    <small id="e" class="form-text text-muted" style="font-size:14px;">*Todos los campos son requeridos.</small>
<div style="text-align:right;">
        <button type="submit" class="btn btn-primary btn-sm" name="save"><i class="fas fa-plus"></i> Agregar nuevo subgrupo</button>
</div>
        </div>
</form> 
<hr>
    <h3 style="text-shadow: 1px 1px 2px #717171;"><i class="fas fa-object-group"></i> Editar Subgrupos</h3>
    <hr>
</div>
<div class="container">
<?php if(isset($plaza)){ ?>    
<table class="table table-sm table-hover">
  <thead>
    <tr align="center">
      <th scope="col">Nombre Subgrupo</th>
      <th scope="col">Plaza</th>
      <th scope="col">Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php do{ ?>  
    <tr align="center">
      <td><?php echo utf8_encode($plaza['nombreSub']) ?></td>
      <td><?php echo utf8_encode($plaza['nombreplaza']) ?></td>
      <td>         
          
    <a href="" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#datos<?php echo $plaza['id_subregistro'] ?>"><span aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Datos de plaza"><i class="far fa-edit"></i></span></a>
          
    <a href="delete.php?ponesub=1&reg=<?php echo $plaza['id_subregistro'].'&plz='.$plaza['id_plaza'] ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar plaza" onclick="return Confirmar('¿Esta seguro que desea eliminar el subgrupo <?php echo $plaza['nombreSub'] ?>?')"><i class="far fa-trash-alt"></i></a>
        </td>
    </tr>
  </tbody>
<!-- *********************************MODAL PARA ACTUALIZAR UDATOS *************************************************** -->
<form action="" method="GET">
<div class="modal fade" id="datos<?php echo $plaza['id_subregistro'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" style="text-shadow: 0px 0px 2px #717171;">Editar nombre de subgrupo</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group" style="text-align:center;">  
            <label for="exampleInputEmail1">Editar nombre de subgrupo: </label>
            <input type="text" class="form-control" name="nombreSub" value="<?php echo utf8_encode($plaza['nombreSub']) ?>" required>
        </div>
      </div>
      <div class="modal-footer">
          <input type="hidden" class="form-control" name="reg" value="<?php echo $plaza['id_subregistro'] ?>" placeholder="Agregar marca">
          <button type="submit" class="btn btn-primary" name="update">Actualizar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
      </div>
    </div>
  </div>
</div>
</form>
<!-- ***********************************FIN MODAL ACTUALIZAR DATOS *************************************************** -->   
<?php } while($plaza=sqlsrv_fetch_array($plz)); ?>
    </table>
<br>
<div style="text-align:center;">
    <a href="addplz.php" class="btn btn-dark btn-sm"><i class="fas fa-chevron-left"></i> Regresar</a>
</div>
<?php } else{ ?>
    <div class="alert alert-info" role="alert">
        <i class="fas fa-terminal"></i> Todavía no has agregado ningun subgrupo.
    </div>
<br>
<div style="text-align:center;">
    <a href="addplz.php" class="btn btn-dark btn-sm"><i class="fas fa-chevron-left"></i> Regresar</a>
</div>    
<?php } ?>    
    </div>    
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