<?php 
require "include/conexion_d.php";
$id_plaza_servicioWeb=$_GET['id_plaza_servicioWeb'];

 //ejecuto mi otro store pasandole el id plaza
 $nombredb2='kpimplementta';
 

 //en una variable mando a llamar la conexion y le paso el nombre de la base de datos como parametro
 $cnxa2=conexion($nombredb2);
 // ejecuto mi store con la conexion que le corresponde
 $store2="execute [dbo].[sp_vencidaskpis] '$id_plaza_servicioWeb'";
 $datos=sqlsrv_query($cnxa2,$store2) or die ('Execute Stored Procedure Failed... Query store.php');
 $hasRows=sqlsrv_has_rows($datos);

header('Set-Cookie: fileDownload=true; path=/');
header('Cache-Control: max-age=60, must-revalidate');
header("Pragma: public");
header("Expires: 0");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=semaforo_vencidas.xls");
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
    <table class="table table-hover table-bordered" style="font-size: 11px;">
        <thead>
            <tr>
                <th class="p-1">PLAZA</th>
                <th class="p-1">CUENTA</th>
                
            </tr>
        </thead>
        <tbody>
            <?php 
            if($hasRows) {
                while($cuenta = sqlsrv_fetch_array($datos, SQLSRV_FETCH_ASSOC)) {
                    // Buscamos en SolicitarFolio 
            ?>
            <tr>
                <td><?=$cuenta['plaza']?></td>
                <td><?=$cuenta['cuenta']?></td>
            <?php } } else { ?>
            <tr>
                <td>No hay informacion</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>
<?php sqlsrv_close($cnxa2); ?>
