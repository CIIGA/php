<?php 
require "include/conexion_d.php";
$id_plaza_servicioWeb=$_GET['id_plaza_servicioWeb'];
$nombreplaza=$_GET['nombre_plz'];
 //ejecuto mi otro store pasandole el id plaza
 $nombredb2='kpimplementta';
 

 //en una variable mando a llamar la conexion y le paso el nombre de la base de datos como parametro
 $cnxa2=conexion($nombredb2);
 // ejecuto mi store con la conexion que le corresponde
 $store2="execute [dbo].[sp_vencidaskpis] '$id_plaza_servicioWeb'";
 $datos=sqlsrv_query($cnxa2,$store2) or die ('Execute Stored Procedure Failed... Query vencidas.php [sp_vencidaskpis]');
 $hasRows=sqlsrv_has_rows($datos);

header('Set-Cookie: fileDownload=true; path=/');
header('Cache-Control: max-age=60, must-revalidate');
header("Pragma: public");
header("Expires: 0");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=semaforo_vencidas-$nombreplaza.xls");
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
    <?php if($id_plaza_servicioWeb!=24){ ?>
        <table class="table table-hover table-bordered" style="font-size: 11px;">
        <thead>
            <tr>
                <th >PLAZA</th>
                <th >NUMERO</th>
                <th >MES</th>
                <th >AÑO</th>
                <th >NUMERO DIA</th>
                <th >FECHA CALCULO</th>
                <th >FECHA CARGA</th>
                <th >MES 2</th>
                <th >SEMAFORO</th>
                <th >CUENTA</th>
                <th >PROPIETARIO</th>
                <th >FECHA CAPTURA</th>
                <th >TAREA</th>
                <th >GESTOR</th>
                <th >DEUDA TOTAL</th>
                <th >RANGO CORPORATIVO</th>
                <th >RANGO SISTEMA</th>
                <th >TIPO DE SERVICIO</th>
                <th >SEMAFORO 3</th>
                <th >COLOR SEMAFORO</th>
                <th >CARACTERISTICAS DEL PREDIO</th>
                <th >GIRO</th>
                <th >CALLE</th>
                <th >NUM. EXT.</th>
              
                
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
                <td><?=$cuenta['numero']?></td>
                <td><?=$cuenta['mes']?></td>
                <td><?=$cuenta['ano']?></td>
                <td><?=$cuenta['numero_dia']?></td>
                <td><?=($cuenta['fecha_calculo']) ? date('d-m-Y', strtotime($cuenta['fecha_calculo'])) : ""?></td>
                <td><?=($cuenta['fecha_carga']) ? date('d-m-Y', strtotime($cuenta['fecha_carga'])) : ""?></td>
                <td><?=$cuenta['Mes2']?></td>
                <td><?=$cuenta['semaforo']?></td>
                <td><?=$cuenta['cuenta']?></td>
                <td><?=$cuenta['propietario']?></td>
                <td><?=($cuenta['fecha_captura']) ? date('d-m-Y', strtotime($cuenta['fecha_captura'])) : ""?></td>
                <td><?=utf8_decode($cuenta['tarea'])?></td>
                <td><?=$cuenta['gestor']?></td>
                <td><?=$cuenta['deuda_total']?></td>
                <td><?=$cuenta['rango_corporativo']?></td>
                <td><?=$cuenta['rango_sistema']?></td>
                <td><?=$cuenta['tipo_servicio']?></td>
                <td><?=$cuenta['semaforo3']?></td>
                <td><?=$cuenta['color Semaforo']?></td>
                <td><?=$cuenta['caracteristica_predio']?></td>
                <td><?=$cuenta['giro']?></td>
                <td><?=$cuenta['calle']?></td>
                <td><?=$cuenta['num_ext']?></td>
            <?php } } else { ?>
            <tr>
                <td>No hay informacion</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php }else{ ?>
    <table class="table table-hover table-bordered" style="font-size: 11px;">
        <thead>
            <tr>
                <th >PLAZA</th>
                <th >NUMERO</th>
                <th >MES</th>
                <th >AÑO</th>
                <th >NUMERO DIA</th>
                <th >FECHA CALCULO</th>
                <th >FECHA CARGA</th>
                <th >MES 2</th>
                <th >SEMAFORO</th>
                <th >CUENTA</th>
                <th >PROPIETARIO</th>
                <th >FECHA CAPTURA</th>
                <th >TAREA</th>
                <th >GESTOR</th>
                <th >DEUDA TOTAL</th>
                <th >RANGO CORPORATIVO</th>
                <th >RANGO SISTEMA</th>
                <th >TIPO DE SERVICIO</th>
                <th >SEMAFORO 3</th>
                <th >COLOR SEMAFORO</th>
                <th >CARACTERISTICAS DEL PREDIO</th>
                <th >GIRO</th>
                <th >CALLE</th>
                <th >NUM. EXT.</th>
                <th >NUM. INT.</th>
                <th >COLONIA</th>
                <th >POBLACION</th>
                <th >CP</th>
                <th >ROL</th>
                <th >MESES ADEUDO</th>
                <th >FECHA INICIO</th>
                <th >FECHA FINAL</th>
                <th >DIAS VIGENCIA</th>
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
                <td><?=$cuenta['numero']?></td>
                <td><?=$cuenta['mes']?></td>
                <td><?=$cuenta['ano']?></td>
                <td><?=$cuenta['numero_dia']?></td>
                <td><?=($cuenta['fecha_calculo']) ? date('d-m-Y', strtotime($cuenta['fecha_calculo'])) : ""?></td>
                <td><?=($cuenta['fecha_carga']) ? date('d-m-Y', strtotime($cuenta['fecha_carga'])) : ""?></td>
                <td><?=$cuenta['Mes2']?></td>
                <td><?=$cuenta['semaforo']?></td>
                <td><?=$cuenta['cuenta']?></td>
                <td><?=$cuenta['propietario']?></td>
                <td><?=($cuenta['fecha_captura']) ? date('d-m-Y', strtotime($cuenta['fecha_captura'])) : ""?></td>
                <td><?=utf8_decode($cuenta['tarea'])?></td>
                <td><?=$cuenta['gestor']?></td>
                <td><?=$cuenta['deuda_total']?></td>
                <td><?=$cuenta['rango_corporativo']?></td>
                <td><?=$cuenta['rango_sistema']?></td>
                <td><?=$cuenta['tipo_servicio']?></td>
                <td><?=$cuenta['semaforo3']?></td>
                <td><?=$cuenta['color Semaforo']?></td>
                <td><?=$cuenta['caracteristica_predio']?></td>
                <td><?=$cuenta['giro']?></td>
                <td><?=$cuenta['calle']?></td>
                <td><?=$cuenta['num_ext']?></td>
                <td><?=$cuenta['num_int']?></td>
                <td><?=$cuenta['colonia']?></td>
                <td><?=$cuenta['poblacion']?></td>
                <td><?=$cuenta['cp']?></td>
                <td><?=$cuenta['rol']?></td>
                <td><?=$cuenta['mesesAdeudo']?></td>
                <td><?=($cuenta['FECHA INICIO']) ? date('d-m-Y', strtotime($cuenta['FECHA INICIO'])) : ""?></td>
                <td><?=($cuenta['FECHA FINAL']) ? date('d-m-Y', strtotime($cuenta['FECHA FINAL'])) : ""?></td>
                <td><?=$cuenta['DiasVigencia']?></td>
            <?php } } else { ?>
            <tr>
                <td>No hay informacion</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>
</body>

</html>
<?php sqlsrv_close($cnxa2); ?>
