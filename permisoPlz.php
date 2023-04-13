<?php
session_start();
if((isset($_SESSION['user'])) and (isset($_SESSION['tipousuario']))){
require "../../acnxerdm/cnx.php";
    
    $id_usuarioNuevo=$_GET['usr'];
    
    $us="SELECT * FROM usuarionuevo
    where id_usuarioNuevo='$id_usuarioNuevo'";
    $use=sqlsrv_query($cnx,$us);
    $usera=sqlsrv_fetch_array($use);
    
    $ac="SELECT * FROM acceso
    inner join plaza on plaza.id_plaza=acceso.id_plaza
    where acceso.id_usuarioNuevo='$id_usuarioNuevo'";
    $acces=sqlsrv_query($cnx,$ac);
    $acceso=sqlsrv_fetch_array($acces);  
    
//*********************************** INICIO INSERT PLZ *******************************************************
if(isset($_POST['add'])){
    $idplz=$_POST['plz'];
    $idusuario=$_GET['usr'];
    
    $val="select id_plaza from acceso where id_plaza='$idplz' AND id_usuarioNUevo='$idusuario'";
    $vali=sqlsrv_query($cnx,$val);
    $valida=sqlsrv_fetch_array($vali);
if($valida){
    echo '<script>alert("El usuario ya tiene registrada esta plaza. \nVerifique registro")</script>';
    echo '<meta http-equiv="refresh" content="0,url=permisoPlz.php?usr='.$_GET['usr'].'&plz=65&crhm=950721&idus=659898895">';
} else{
    $unidad="insert into acceso values ('$idusuario','$idplz')";
		sqlsrv_query($cnx,$unidad) or die ('No se ejecuto la consulta isert nuevo colaborador');
//        echo '<script>alert("Acceso agregado correctamente")</script>';
        echo '<meta http-equiv="refresh" content="0,url=permisoPlz.php?usr='.$_GET['usr'].'&plz=65&crhm=950721&idus=659898895">';
    }
}
//************************ FIN INSERT PLZ ******************************************************************
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Acceso | KPIs</title>
<link rel="icon" href="../icono/icon.png">
<!-- Bootstrap -->
<link rel="stylesheet" href="../css/bootstrap.css">
<link href="../fontawesome/css/all.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="../js/busquedaAjax.js"></script>
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
        padding-top:2%;
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
    <h1 style="text-shadow: 0px 0px 2px #717171;">Plataforma de KPIs</h1>
    <h3 style="text-shadow: 0px 0px 2px #717171;"><img src="https://img.icons8.com/fluency/30/000000/employee-card.png"/> Acceso a colaboradores</h3>
    <h5 style="text-shadow: 0px 0px 2px #717171;">Nombre: <?php echo utf8_encode($usera['usuario']) ?></h5>
    <h5 style="text-shadow: 0px 0px 2px #717171;">Usuario: <?php echo utf8_encode($usera['puesto']) ?></h5>
<hr>
<?php if($usera['estado']==1){ ?>
    
    <div class="alert alert-info" role="alert">
      <i class="fas fa-info-circle"></i> El colaborador es usuario Administrador. Solamente el área de sistemas puede realizar cambios.
    </div>
    <meta http-equiv="refresh" content="4,url=config.php">
    
<?php } else{ ?>   
<div class="jumbotron">
    <form action="" method="post">
        <?php   
          $pl="SELECT * FROM plaza";
            $pla=sqlsrv_query($cnx,$pl);
            $plz=sqlsrv_fetch_array($pla); 
        ?>         
        <div class="md-form form-group">
            <label for="exampleInputEmail1">Seleccione una plaza: *</label>
                <select name="plz" class="form-control" required>
                    <option value="">Selecciona una plaza</option>
            <?php do{ ?>
                    <option value="<?php echo $plz['id_plaza'] ?>"><?php echo utf8_encode(ucwords(strtolower($plz['nombreplaza']))) ?></option>
            <?php } while($plz=sqlsrv_fetch_array($pla)); ?>
                </select>
        </div>

        <div style="text-align:right;">
            <a href="config.php" class="btn btn-dark btn-sm"><i class="fas fa-chevron-left"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary btn-sm" name="add"><i class="fas fa-user-edit"></i> Agregar acceso a plaza</button>  
        </div>
    </form>
</div>
<hr>
    <h3 style="text-shadow: 0px 0px 2px #717171;"><img src="https://img.icons8.com/fluency/35/000000/user-credentials.png"/> Accesos</h3>
    <h5 style="text-shadow: 0px 0px 2px #717171;">Usuario: <?php echo utf8_encode($usera['usuario']) ?></h5>
<br>
<?php if(isset($acceso)){ ?>    
    
    
    
<table class="table table-hover table-sm">
  <thead class="thead-dark">
    <tr>
      <th scope="col" style="text-align:center;">#</th>
      <th scope="col">Plaza de acceso</th>
      <th scope="col" style="text-align:center;">Opciones</th>
    </tr>
  </thead>
  <tbody>
<?php $numreg=0; 
     do{ 
      $numreg++; ?>      
    <tr>
      <th scope="row" style="text-align:center;"><?php echo $numreg ?></th>
      <td><?php echo utf8_encode($acceso['nombreplaza']) ?></td>
      <td style="text-align:center;">
        <a href="delete.php?poneacces=1&acces=<?php echo $acceso['id_acceso'].'&usr='.$_GET['usr'] ?>" data-toggle="tooltip" data-placement="top" title="Eliminar usuario" onclick="return Confirmar('¿Esta sguro que decea eliminar este acceso?')" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></a>
        </td>
    </tr>
<?php }while($acceso=sqlsrv_fetch_array($acces)); ?>      
  </tbody>
</table>
    
<?php } else{ ?>
    
    <div class="alert alert-info" role="alert">
      <i class="fas fa-info-circle"></i> Este usuario aún no tiene ningún acceso permitido.
    </div>
    
<?php } ?>
<?php } ?>
<br>
    <div style="text-align:center;">
        <a href="config.php" class="btn btn-dark btn-sm"><i class="fas fa-chevron-left"></i> Regresar</a>
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