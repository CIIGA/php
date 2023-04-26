<?php
session_start();
if(isset($_SESSION['user'])){
require "../../acnxerdm/cnx.php";
    
    $iduser=$_SESSION['user'];
    
    $usa="select * from usuarionuevo
    inner join usuario on usuario.id_usuarioNuevo=usuarionuevo.id_usuarioNuevo
    where usuarionuevo.id_usuarioNuevo='$iduser'";
    $usea=sqlsrv_query($cnx,$usa);
    $usera=sqlsrv_fetch_array($usea);
    
//****************************ACTUALIZAR DATOS DE USUARIO******************************************************
if(isset($_GET['update'])){
    $id_usuarioNuevo=$usera['id_usuarioNuevo'];
    $usr=$_GET['usr'];
    $actual=$_GET['actual'];
    $nueva=$_GET['nueva'];
    $confirmar=$_GET['confirmar'];
        $login = "SELECT * FROM usuarionuevo 
        inner join usuario on usuarionuevo.id_usuarioNuevo=usuario.id_usuarioNuevo
        WHERE usuarionuevo.usuario = '$usr' and usuario.clave='$actual'";
        $datos=sqlsrv_query($cnx,$login);
        $log=sqlsrv_fetch_array($datos);
    if(isset($log)){
        if($nueva==$confirmar){
            $clave="update usuario set clave='$nueva' where id_usuarioNuevo='$id_usuarioNuevo'";
            sqlsrv_query($cnx,$clave) or die ('No se ejecuto la consulta update datosart');
            echo "<script> alert('Contraseña actualizada correctamente.');</script>";
            echo '<meta http-equiv="refresh" content="0,url=acceso.php">';
        } else{
           echo "<script> alert('Confirmacion de nueva contraseña no coincide.');</script>"; 
           echo '<meta http-equiv="refresh" content="0,url=updateProfile.php">';
        }
    } else{
        echo "<script> alert('La contraseña actual no es correcta.');</script>";
        echo '<meta http-equiv="refresh" content="0,url=updateProfile.php">';
    }
}
//****************************FIN ACTUALIAR DATOS DE USUARIO***************************************************
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Perfil de Usuario | KPIs</title>
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
    <h3 style="text-shadow: 1px 1px 2px #717171;"><i class="fas fa-user-edit"></i> Actualizar perfil de usuario</h3>
    <h5 style="text-shadow: 1px 1px 2px #717171;">Usuario: <?php echo $usera['usuario'] ?></h5>
    <h5 style="text-shadow: 1px 1px 2px #717171;">Puesto: <?php echo $usera['puesto'] ?></h5>
<div class="jumbotron">
    <div class="md-form form-group">
        <label for="exampleInputEmail1">Contraseña actual: *</label>
        <input type="password" class="form-control" name="actual" placeholder="Contraseña actual" required autofocus>
        <input type="hidden" class="form-control" name="usr" value="<?php echo $usera['usuario'] ?>" required>
    </div>
<hr>
    <div class="form-row">
        <div class="col-md-6">
          <div class="md-form form-group">
              <label for="exampleInputEmail1">Nueva contraseña: *</label>
              <input type="text" class="form-control" name="nueva" placeholder="Nueva contraseña" minlength="6" maxlength="50" required>
          </div>
        </div>
        <div class="col-md-6">
            
      <div class="md-form form-group">
          <div class="md-form form-group">
              <label for="exampleInputEmail1">Confirmar nueva contraseña: *</label>
              <input type="text" class="form-control" name="confirmar" placeholder="Confirmar nueva contraseña" required>
          </div>
      </div>
            
        </div>
    </div>
<hr>
            <div style="text-align:center;">
                <button type="submit" class="btn btn-primary btn-sm" name="update" onclick="return Confirmara('¿Está seguro que desea actualizar sus datos de acceso?\nNingún otro usuario conoce sus datos de inicio de sesión')"><i class="fas fa-key"></i> Actualizar contraseña</button> 
                
                <a href="acceso.php" class="btn btn-dark btn-sm"><i class="fas fa-chevron-left"></i> Regresar</a>
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
<script>
    function Confirmara(Mensaje){
        return (confirm(Mensaje))?true:false;
    }
</script>
</html>