<?php
require "../../acnxerdm/cnx.php";
?>
<?php if(isset($_GET['alumnos'])) {
    
    $busqueda=$_GET['alumnos'];
    $usa="SELECT top 10 * FROM usuarionuevo
    inner join usuario on usuario.id_usuarioNuevo=usuarionuevo.id_usuarioNuevo
    left join plaza on plaza.id_plaza=usuarionuevo.id_plazaUsr
    where usuarionuevo.usuario LIKE '%$busqueda%'
    or usuarionuevo.puesto LIKE '%$busqueda%'";
    $usea=sqlsrv_query($cnx,$usa);
    $usera=sqlsrv_fetch_array($usea);
?>
<?php  if(isset($usera)){ ?>
<table class="table table-sm table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col" style="text-align:left;">Usuario</th>
      <th scope="col" style="text-align:left;">Puesto</th>
      <th scope="col" style="text-align:left;">Acceso</th>
      <th scope="col">Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php do{ ?>  
    <tr>
      <td style="text-align:left;"><?php echo utf8_encode($usera['usuario']) ?><?php if($usera['estado']==1){ ?> <i class="fas fa-user-shield"></i> <?php } ?>
        <br><small>Plaza: <?php echo utf8_encode($usera['nombreplaza']) ?></small></td>
      <td style="text-align:left;"><?php echo utf8_encode($usera['puesto']) ?></td>
<?php if($usera['acceso'] == NULL){ ?>
      <td style="text-align:left;"><i class="far fa-window-minimize"></i></td>
<?php } else{ ?>        
      <td style="text-align:left;"><?php echo $usera['acceso'] ?></td>
<?php } ?>        
      <td><a href="updateUsr.php?usr=<?php echo $usera['id_usuarioNuevo'] ?>" class="btn btn-primary btn-sm"><i class="fas fa-user"></i></a>
          
    <a href="permisoPlz.php?usr=<?php echo $usera['id_usuarioNuevo'].'&plz=65&crhm=950721&idus=659898895' ?>" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="right" title="Asignar plaza" ><i class="fas fa-cubes"></i></a>
          
    <a href="delete.php?poneUser=1&usr=<?php echo $usera['id_usuarioNuevo'] ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar usuario" onclick="return Confirmara('¿Esta sguro que decea eliminar al este usuario <?php echo $usera['usuario'] ?>?')"><i class="far fa-trash-alt"></i></a>
        </td>
    </tr>
  </tbody>
    <?php } while($usera=sqlsrv_fetch_array($usea)); ?>
</table>
<small><i class="fas fa-info-circle"></i> Mostrando el top diez de la búsqueda </small>



<?php } else{ ?>
    <div class="alert alert-info" role="alert">
      <i class="fas fa-info-circle"></i> No hay resultados para <?php echo '"'.$busqueda.'"' ?>
    </div>
<?php } ?>














<?php } else{ 
    $usa="SELECT * FROM usuarionuevo
    inner join usuario on usuario.id_usuarioNuevo=usuarionuevo.id_usuarioNuevo
    left join plaza on plaza.id_plaza=usuarionuevo.id_plazaUsr";
    $usea=sqlsrv_query($cnx,$usa);
    $usera=sqlsrv_fetch_array($usea);
?>
<table class="table table-sm table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col" style="text-align:left;">Usuario</th>
      <th scope="col" style="text-align:left;">Puesto</th>
      <th scope="col" style="text-align:left;">Acceso</th>
      <th scope="col">Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php do{ ?>  
    <tr>
      <td style="text-align:left;"><?php echo utf8_encode($usera['usuario']) ?><?php if($usera['estado']==1){ ?> <i class="fas fa-user-shield"></i> <?php } ?>
        <br><small>Plaza: <?php echo utf8_encode($usera['nombreplaza']) ?></small></td>
      <td style="text-align:left;"><?php echo utf8_encode($usera['puesto']) ?></td>
<?php if($usera['acceso'] == NULL){ ?>
      <td style="text-align:left;"><i class="far fa-window-minimize"></i></td>
<?php } else{ ?>        
      <td style="text-align:left;"><?php echo $usera['acceso'] ?></td>
<?php } ?>        
      <td><a href="updateUsr.php?usr=<?php echo $usera['id_usuarioNuevo'] ?>" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="Datos del colaborador"><i class="fas fa-user"></i></a>
          
    <a href="permisoPlz.php?usr=<?php echo $usera['id_usuarioNuevo'].'&plz=65&crhm=950721&idus=659898895' ?>" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Asignar plaza" ><i class="fas fa-cubes"></i></a>
          
    <a href="delete.php?poneUser=1&usr=<?php echo $usera['id_usuarioNuevo'] ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Eliminar usuario" onclick="return Confirmara('¿Esta sguro que decea eliminar al este usuario <?php echo $usera['usuario'] ?>?')"><i class="far fa-trash-alt"></i></a>
        </td>
    </tr>
  </tbody>       
    <?php } while($usera=sqlsrv_fetch_array($usea)); ?>    
</table>

<?php } ?>


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


