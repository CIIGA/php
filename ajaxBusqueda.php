<?php
if(isset($_POST['alumnos'])) {
require "conect.php";
    
    $buscara=$_POST['alumnos'];
    $plazaa=$_POST['num2'];
    $ura="SELECT top 4 * FROM implementta
    where Propietario LIKE '%$buscara%'";
    $urla=sqlsrv_query($cnx,$ura);
    $direcciona=sqlsrv_fetch_array($urla);
    
if(isset($direcciona)){
    do{
?>
<a href="map.php?plz=<?php echo $plazaa.'&mp='.$_GET['mp'].'&src='.$direcciona['Cuenta'].'&clv=1' ?>" style="color:#545454;text-decoration:none;font-style:italic;font-size:70%; text-align:left;" class="btn btn-outline-light btn-sm">* <?php echo $direcciona['Propietario'] ?></a>

<?php } while($direcciona=sqlsrv_fetch_array($urla)); 

} else{
?>
<div class="alert alert-info" role="alert">
  No hay resultados para "<?php echo $buscara ?>"
</div>
<?php } ?>
<br><br>
<?php } else { ?>
    
    <br>
    
<?php     
} ?>