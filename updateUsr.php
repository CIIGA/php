<?php
session_start();
if((isset($_SESSION['user'])) and (isset($_SESSION['tipousuario']))){
require "../../acnxerdm/cnx.php";
    
    $iduser=$_GET['usr'];
    
    $usa="select * from usuarionuevo
    inner join usuario on usuario.id_usuarioNuevo=usuarionuevo.id_usuarioNuevo
    left join plaza on plaza.id_plaza=usuarionuevo.id_plazaUsr
    where usuarionuevo.id_usuarioNuevo='$iduser'";
    $usea=sqlsrv_query($cnx,$usa);
    $usera=sqlsrv_fetch_array($usea);
    
    $pl="SELECT * FROM plaza";
    $plz=sqlsrv_query($cnx,$pl);
    $plaza=sqlsrv_fetch_array($plz);
    
//****************************ACTUALIZAR DATOS DE USUARIO******************************************************
if(isset($_GET['update'])){
    $id_usuarioNuevo=$_GET['id_usuarioNuevo'];
    $usuario=$_GET['usuario'];
    $correo=$_GET['correo'];
    $clave=$_GET['clave'];
    $idplzUsr=$_GET['plaza'];
    
 	    $datos="update usuarionuevo set id_plazaUsr='$idplzUsr',usuario='$usuario',puesto='$correo' where id_usuarioNuevo='$id_usuarioNuevo'";
		sqlsrv_query($cnx,$datos) or die ('No se ejecuto la consulta update datosart');
    
    $clave="update usuario set clave='$clave' where id_usuarioNuevo='$id_usuarioNuevo'";
		sqlsrv_query($cnx,$clave) or die ('No se ejecuto la consulta update datosart');
        echo '<script> alert("Registro Actulizado")</script>'; 
    
        echo '<meta http-equiv="refresh" content="0,url=updateUsr.php?usr='.$id_usuarioNuevo.'">';
}
//****************************FIN ACTUALIAR DATOS DE USUARIO***************************************************
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Actualizar Ususario | KPIs</title>
<link rel="icon" href="../icono/icon.png">
<!-- Bootstrap -->
<link rel="stylesheet" href="../css/bootstrap.css">
<link href="../fontawesome/css/all.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="../js/generatePass.js"></script>    
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
<form action="" method="GET">    
<div class="container">
    <h1 style="text-shadow: 1px 1px 2px #717171;">Plataforma de KPIs</h1>
    <h4 style="text-shadow: 1px 1px 2px #717171;"><i class="fas fa-user-edit"></i> Actualizar datos de usuario</h4>
    <h4 style="text-shadow: 1px 1px 2px #717171;">Usuario: <?php echo $usera['usuario'] ?></h4><hr>
<div class="jumbotron">
    <div class="md-form form-group">
        <label for="exampleInputEmail1">Usuario: *</label>
        <input type="text" class="form-control" name="usuario" value="<?php echo utf8_encode($usera['usuario']) ?>" maxlength="20" required>
    </div>
<hr>
    <div class="form-row">
        <div class="col-md-6">
          <div class="md-form form-group">
              <label for="exampleInputEmail1">Puesto: *</label>
              <input type="text" class="form-control" name="correo" value="<?php echo utf8_encode($usera['puesto']) ?>" required>
          </div>
        </div>
        <div class="col-md-6">
              <div class="form-group">
                <label for="exampleFormControlSelect1">Plaza: *</label>
                <select class="form-control" name="plaza" required>
                  <option value="<?php echo $usera['id_plazaUsr'] ?>"><?php echo utf8_encode($usera['nombreplaza']) ?></option>
                <?php do{ ?>    
                  <option value="<?php echo $plaza['id_plaza'] ?>"><?php echo utf8_encode($plaza['nombreplaza']) ?></option>
                <?php } while($plaza=sqlsrv_fetch_array($plz)); ?>
                </select>
              </div>
        </div>
    </div>
    
    
    
    
        <div class="form-row">
        <div class="col-md-6">
          <div class="md-form form-group">
            <label for="exampleInputEmail1">Contraseña: *</label>
            <div class="input-group col-md-15 justify-content-center">
                <div class="input-group-prepend">
                <button type="button" class="btn btn-primary btn-sm" onclick="return Confirmar('<?php echo $iduser ?>')"><i class="fas fa-key"></i></button>
                </div>
                <input id="tabla_resultado" type="text" class="form-control" name="clave" value="<?php echo $usera['clave'] ?>" minlength="6" maxlength="50" required>
                <input type="hidden" class="form-control" name="id_usuarioNuevo" value="<?php echo $usera['id_usuarioNuevo'] ?>" required>
            </div>
          </div>
        </div>
        <div class="col-md-6">
            
        </div>
    </div>
    
    
    
    
<hr>
            <div style="text-align:center;">
                <button type="submit" class="btn btn-primary btn-sm" name="update"><i class="fas fa-user-edit"></i> Actualizar usuario</button>
                <a href="config.php" class="btn btn-dark btn-sm"><i class="fas fa-chevron-left"></i> Regresar</a>
            </div>    
        </div>
    </div>
</form>    
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
</html>