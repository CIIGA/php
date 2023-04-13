<?php
session_start();
if((isset($_SESSION['user'])) and (isset($_SESSION['tipousuario']))){
require "../../acnxerdm/cnx.php";
    $id_usuarioNuevo=$_SESSION['user'];
    $us="SELECT * FROM usuarionuevo
    where id_usuarioNuevo='$id_usuarioNuevo'";
    $use=sqlsrv_query($cnx,$us);
    $user=sqlsrv_fetch_array($use);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Administrador | KPIs</title>
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
    <h4 style="text-shadow: 1px 1px 2px #717171;"><i class="fas fa-users-cog"></i> Administrador: <?php echo utf8_encode(ucwords(strtolower($user['usuario']))) ?></h4>
<hr>
    <div class="form-row">
        <div class="col-md-6">
            <div style="text-align:left;">
                <a href="addusr.php" class="btn btn-dark btn-sm"><i class="fas fa-user-plus"></i> Nuevo Usuario</a>
                <a href="addplz.php" class="btn btn-dark btn-sm"><i class="far fa-building"></i> Plazas ERDM</a>
            </div>
        </div>
        <div class="col-md-6">
        <div class="justify-content-center justify-content-md-center">
            <div >
              <div class="input-group col-md-15 justify-content-center">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroupPrepend2"><i class="fas fa-search"></i></span>
                </div>
                <input type="text" class="form-control border border-secondary" placeholder="Buscar nombre o usuario" name="alumnos" id="busqueda" required autofocus>
              </div>
            </div>
          </div>
        </div>
    </div>    
<br>
<section id="tabla_resultado" style="text-align:center;">
<!-- **********tabla resultado******** -->
</section>
    
    
    
    

    <br><br><br>
    </div>
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