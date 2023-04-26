<?php
session_start();
if((isset($_SESSION['user'])) and (isset($_SESSION['tipousuario']))){
require "../../acnxerdm/cnx.php";
    $pl="SELECT * FROM proveniente";
    $plz=sqlsrv_query($cnx,$pl);
    $plaza=sqlsrv_fetch_array($plz);
    
    $da="SELECT name FROM sys.databases
    where name like '%implementta%'";
    $base=sqlsrv_query($cnx,$da);
    $datBase=sqlsrv_fetch_array($base);
    
//*********************************** INICIO INSERT PLZ *******************************************************
if(isset($_GET['save'])){
    $nombre=$_GET['nombreOrigen'];
    $base=$_GET['base'];
    $val="select * from proveniente
    where nombreProveniente='$nombre' or data='$base'";
    $vali=sqlsrv_query($cnx,$val);
    $valida=sqlsrv_fetch_array($vali);
if($valida){
    echo '<script>alert("El origen de datos ya esta agregado. \nVerifique registro")</script>';
    echo '<meta http-equiv="refresh" content="0,url=origen.php">';
} else{
    $unidad="insert into proveniente values ('$nombre','$base')";
		sqlsrv_query($cnx,$unidad) or die ('No se ejecuto la consulta isert nuevo origen de datos');
        echo '<script>alert("Origen de datos agregado correctamente")</script>';
        echo '<meta http-equiv="refresh" content="0,url=origen.php">';
    }
}
//************************ FIN INSERT PLZ ******************************************************************
//****************************ACTUALIZAR DATOS DE USUARIO******************************************************
if(isset($_GET['update'])){
    $idprov=$_GET['idprov'];
    $name=$_GET['nombre'];
    $data=$_GET['datos'];
    
    $datos="update proveniente set nombreProveniente='$name',data='$data'
    where id_proveniente='$idprov'";
    sqlsrv_query($cnx,$datos) or die ('No se ejecuto la consulta update datosart');
    echo '<script> alert("Registro cctulizado correctamente.")</script>'; 
    echo '<meta http-equiv="refresh" content="0,url=origen.php">';
}
//****************************FIN ACTUALIAR DATOS DE USUARIO***************************************************    
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Nuevo Origen de Datos</title>
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
        background-attachment: fixed;
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
    <h4 style="text-shadow: 1px 1px 2px #717171;"><i class="fas fa-database"></i> Agregar nuevo origen de datos</h4>
    <div class="alert alert-info" role="alert">
      <i class="fas fa-code"></i> Para asegurar el correcto funcionamiento de la plataforma, confirme datos con el área de sistemas.
    </div>
<form action="" method="GET">
<div class="jumbotron">
    
    <div class="form-row">
    <div class="col-md-6">
    <div class="form-group" style="text-align:center;">
    <label for="exampleInputEmail1">Nombre: *</label>
    <input style="text-align:center;" type="text" class="form-control" name="nombreOrigen" placeholder="Nombre del recurso o plaza" required>
  </div>
    </div>  
    <div class="col-md-6"> 
    <div class="form-group" style="text-align:center;">
        <label for="exampleFormControlSelect1">Base de datos: *</label>
        <select class="form-control" name="base" required>
          <option value="">Selecciona una opcion</option>
        <?php do{ ?>
            <option value="<?php echo $datBase['name'] ?>"><?php echo $datBase['name'] ?></option>
        <?php } while($datBase=sqlsrv_fetch_array($base)); ?>
        </select>
    </div>
    </div>
    </div>
    <small id="e" class="form-text text-muted" style="font-size:14px;">*Todos los campos son requeridos.<br>
    El origen de la base de datos es proveniente del SQLserver Implementta.<br>
    Si no conoce el nombre correcto del recurso db, contacte al área de sistemas.</small>
<div style="text-align:right;">
    <button type="submit" class="btn btn-primary btn-sm" name="save"><i class="fas fa-plus"></i> Agregar nuevo origen de datos</button>
</div>
    </div>
</form>
    
    
    
    
    <br><hr><br>
    <h3 style="text-shadow: 1px 1px 2px #717171;"><i class="fas fa-server"></i> Editar Origen de datos</h3>
    <br>
</div>
<div class="container">
<?php if(isset($plaza)){ ?>    
<table class="table table-sm table-hover">
  <thead>
    <tr align="left">
      <th scope="col"></th>
      <th scope="col">Nombre</th>
      <th scope="col">DB implementta SQLserver</th>
      <th scope="col">Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php do{ ?>  
    <tr align="left">
      <td><span class="badge badge-pill badge-success"><i class="fas fa-check"></i></span></td>
      <td><?php echo utf8_encode($plaza['nombreProveniente']) ?></td>
      <td><?php echo utf8_encode($plaza['data']) ?></td>
      <td>
          
    <a href="" class="btn btn-dark btn-sm" data-toggle="modal" data-target="#datos<?php echo $plaza['id_proveniente'] ?>"><span aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Datos de origen"><i class="far fa-edit"></i></span></a>
          
    <a href="delete.php?poneorigen=1&origen=<?php echo $plaza['id_proveniente'] ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar Origen de datos" onclick="return Confirmar('¿Esta seguro que decea eliminar el origen de datos <?php echo utf8_encode($plaza['nombreProveniente']) ?>?')"><i class="far fa-trash-alt"></i></a>
        </td>
    </tr>
  </tbody>
<!-- *********************************MODAL PARA ACTUALIZAR UDATOS *************************************************** -->
<form action="" method="GET">
<div class="modal fade" id="datos<?php echo $plaza['id_proveniente'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" style="text-shadow: 0px 0px 2px #717171;">Editar origen de datos</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label for="exampleInputEmail1">Nombre: *</label>
        <input type="text" class="form-control" name="nombre" value="<?php echo utf8_encode($plaza['nombreProveniente']) ?>" required>
          <br>
        <label for="exampleInputEmail1">Base de datos: *</label>
        <input type="text" class="form-control" name="datos" value="<?php echo utf8_encode($plaza['data']) ?>" required>
            <small id="e" class="form-text text-muted" style="font-size:14px;">*Todos los campos son requeridos.<br> 
            El origen de la base de datos es proveniente del SQLserver Implementta.<br> 
            Si no conoce el nombre correcto del recurso db, contacte al área de sistemas.</small>
      </div>
      <div class="modal-footer">
        <input type="hidden" class="form-control" name="idprov" value="<?php echo $plaza['id_proveniente'] ?>">
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
<?php } else{ ?>
    <div class="alert alert-info" role="alert">
        <i class="fas fa-database"></i> No hay origenes de datos para mostrar.
    </div>
    <br><br>
<?php } ?>
    <div style="text-align:center;">
        <a href="addplz.php" class="btn btn-dark btn-sm"><i class="fas fa-chevron-left"></i> Regresar</a>
    </div>
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