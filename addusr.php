<?php
session_start();
if((isset($_SESSION['user'])) and (isset($_SESSION['tipousuario']))){
require "../../acnxerdm/cnx.php";
    $usr=$_SESSION['user'];
    
    $pl="SELECT * FROM plaza";
    $plz=sqlsrv_query($cnx,$pl);
    $plaza=sqlsrv_fetch_array($plz);
//*********************************** INICIO INSERT USR *******************************************************
if(isset($_POST['save'])){
    $usuario=$_POST['usuario'];
    $correo=$_POST['correo'];
    $clave=$_POST['clave'];
    $idplzUsr=$_POST['plaza'];
    $estado=2;
    
    $val="select * from usuarionuevo
    where usuario='$usuario'";
    $vali=sqlsrv_query($cnx,$val);
    $valida=sqlsrv_fetch_array($vali);
if($valida){
    echo '<script>alert("Ya existe un usuario con este nombre. \nVerifique registro")</script>';
    echo '<meta http-equiv="refresh" content="0,url=addusr.php">';
} else{
    $unidad="insert into usuarionuevo (id_plazaUsr,usuario,puesto,estado) values ('$idplzUsr','$usuario','$correo','$estado')";
		sqlsrv_query($cnx,$unidad) or die ('No se ejecuto la consulta isert usuarionuevo');
    
    $vala="select * from usuarionuevo
    where usuario='$usuario'";
    $valia=sqlsrv_query($cnx,$vala);
    $validaa=sqlsrv_fetch_array($valia);
    
    $idusr=$validaa['id_usuarioNuevo'];
    $clv="insert into usuario (id_usuarioNuevo, clave) values ('$idusr','$clave')";
		sqlsrv_query($cnx,$clv) or die ('No se ejecuto la consulta isert usuario');
        echo '<script>alert("Usuario agregado correctamente")</script>';
        echo '<meta http-equiv="refresh" content="0,url=config.php">';
    }
}
//************************ FIN INSERT USR ******************************************************************  
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Nuevo Ususario | FIDI</title>
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
<form action="" method="post">
<div class="container">
    <h1 style="text-shadow: 1px 1px 2px #717171;">Plataforma de KPIs</h1>
    <h4 style="text-shadow: 1px 1px 2px #717171;"><i class="fas fa-user-plus"></i> Agregar Nuevo Usuario</h4>
    <hr>
<div class="jumbotron">
    <div class="md-form form-group">
        <label for="exampleInputEmail1">Usuario: *</label>
        <input type="text" class="form-control" name="usuario" placeholder="Usuario (Maximo 20 carácteres)" maxlength="20" required autofocus>
    </div>
    
<hr>
    <div class="form-row">
        <div class="col-md-6">
          <div class="md-form form-group">
              <label for="exampleInputEmail1">Puesto: *</label>
              <input type="text" class="form-control" name="correo" placeholder="Puesto o cargo" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="exampleFormControlSelect1">Plaza: *</label>
            <select class="form-control" name="plaza" required>
              <option>Selecciona una opción</option>
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
                <button type="button" class="btn btn-primary btn-sm" onclick="return Confirmar('<?php echo $usr ?>')"><i class="fas fa-key"></i></button>
                </div>
                <input id="tabla_resultado" type="text" class="form-control" name="clave" placeholder="Contraseña (minimo 6 digitos)" minlength="6" maxlength="50" required>
            </div>
          </div> 
        </div>
        <div class="col-md-6">     

        </div>
    </div>
<hr>
            <div style="text-align:center;">
                <button type="submit" class="btn btn-primary btn-sm" name="save"><i class="fas fa-plus"></i> Agregar nuevo usuario</button>
                <a href="config.php" class="btn btn-dark btn-sm"><i class="fas fa-times"></i> Cancelar</a>
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