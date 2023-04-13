<?php
session_start();
if(isset($_SESSION['user'])){
require "../../acnxerdm/cnx.php";
    $id_usuarioNuevo=$_SESSION['user'];
    $us="SELECT * FROM usuarionuevo
    where id_usuarioNuevo='$id_usuarioNuevo'";
    $use=sqlsrv_query($cnx,$us);
    $user=sqlsrv_fetch_array($use);
    
if((isset($_SESSION['tipousuario'])) or($user['id_usuarioNuevo']==57)) {
    $ac="SELECT * FROM plaza";
    $acces=sqlsrv_query($cnx,$ac);
    $acceso=sqlsrv_fetch_array($acces);
} else{
  $ac="SELECT * FROM usuarionuevo
    inner join acceso on acceso.id_usuarionuevo=usuarionuevo.id_usuarionuevo
    inner join plaza on plaza.id_plaza=acceso.id_plaza
    where acceso.id_usuarioNuevo='$id_usuarioNuevo'";
    $acces=sqlsrv_query($cnx,$ac);
    $acceso=sqlsrv_fetch_array($acces);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>KPIs | Estrategas</title>
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
}
    .padding {
        padding-right:35%;
        padding-left:35%;
    }
	.caption-style-4{
		list-style-type: none;
		margin: 0px;
		padding: 0px;
		
	}

	.caption-style-4 li{
		float: left;
		padding: 0px;
		position: relative;
		overflow: hidden;
	}

	.caption-style-4 li:hover .caption{
		opacity: 1;
	}

	.caption-style-4 li:hover img{
		opacity: 1;
		transform: scale(1.15,1.15);
		-webkit-transform:scale(1.15,1.15);
		-moz-transform:scale(1.15,1.15);
		-ms-transform:scale(1.15,1.15);
		-o-transform:scale(1.15,1.15);
	}


	.caption-style-4 img{
		margin: 0px;
		padding: 0px;
		float: left;
		z-index: 0;
	}


	.caption-style-4 .caption{
		cursor: pointer;
		position: absolute;
		opacity: 0;
		-webkit-transition:all 0.45s ease-in-out;
		-moz-transition:all 0.45s ease-in-out;
		-o-transition:all 0.45s ease-in-out;
		-ms-transition:all 0.45s ease-in-out;
		transition:all 0.45s ease-in-out;
	}

	.caption-style-4 img{
		-webkit-transition:all 0.25s ease-in-out;
		-moz-transition:all 0.25s ease-in-out;
		-o-transition:all 0.25s ease-in-out;
		-ms-transition:all 0.25s ease-in-out;
		transition:all 0.25s ease-in-out;
	}
	.caption-style-4 .blur{
		background-color: rgba(0,0,0,0.65);
		height: 300px;
		width: 400px;
		z-index: 5;
		position: absolute;
	}

	.caption-style-4 .caption-text h1{
		text-transform: uppercase;
		font-size: 24px;
	}
	.caption-style-4 .caption-text{
		z-index: 10;
		color: #fff;
		position: absolute;
		width: 400px;
		height: 300px;
		text-align: center;
		top:100px;
	}
</style>
<?php require "include/nav.php"; ?>
</head>
<body>
<div class="container">
    <h1 style="text-shadow: 1px 1px 2px #717171;">Sistema de KPIs</h1>
<?php if(isset($_SESSION['tipousuario'])){ ?>
  <h4 style="text-shadow: 0px 0px 2px #717171;">Administrador KPIs</h4>
<?php } ?>    
    <h4 style="text-shadow: 1px 1px 2px #717171;">Bienvenido(a) <?php echo utf8_encode(ucwords(strtolower($user['usuario']))) ?></h4>
    
<br>
<?php if(isset($acceso)){ ?> 
    
    
<div class="card-columns">
<?php do{ ?>
  <div class="card">
    <?php     
        $id_plz=$acceso['id_plaza'];
        $ma="SELECT * FROM kpi
        inner join subregistro on subregistro.id_subregistro=kpi.id_subregistro
        where subregistro.id_plaza='$id_plz'";
        $map=sqlsrv_query($cnx,$ma);
        $mapa=sqlsrv_fetch_array($map);
          if(isset($mapa)){ ?>
      
      
      <div class="container-a4">
		<div class="caption-style-4">
			<li>
      <img class="card-img-top" src="<?php echo $acceso['img'] ?>" alt="Card image cap" height="200px">
        <div class="caption">
            <a href="map.php?plz=<?php echo $acceso['id_plaza'] ?>"> <div class="blur"></div></a>
        </div>        
            </li>
        </div>
      </div>
                
                
                
        <?php } else{ ?>
      <span class="badge badge-warning">No hay KPIs disponibles</span>
        <?php } ?>
    <div class="card-body">
      <h5 style="text-shadow: 0px 0px 2px #717171;" class="card-text"><?php echo utf8_encode($acceso['nombreplaza']) ?></h5>
    </div>
  </div>
<?php } while($acceso=sqlsrv_fetch_array($acces)); ?>
</div>
    
    
<?php } else{ ?>
    <br><br>
    <div class="alert alert-primary" role="alert"><i class="far fa-building"></i> Aún no tienes acceso a ninguna plaza, agrega una nueva o contacta al departamento de sistemas.</div>
    <br><br>
<?php } ?>
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
    function Confirmar(Mensaje){
        return (confirm(Mensaje))?true:false;
    }
</script>      
</html>