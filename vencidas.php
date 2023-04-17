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
header("Content-Disposition: attachment; filename=semaforo_vencidas_$nombreplaza.xls");
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style>
        th, td {

   text-align: left;
   vertical-align: top;
   border: 1px solid #707070;
   border-spacing: 0;
}

/* color al titulo de las columnas */
.bg_th{
    background-color: #B8B8B8 !important;
    text-align: center !important;
}
    </style>
</head>
<body>
    <?php if($id_plaza_servicioWeb!=24){ ?>
        <table class="" style="font-size: 11px; ">
        <thead>
            <tr>
                <th class="bg_th">PLAZA</th>
                <th class="bg_th">NUMERO</th>
                <th class="bg_th">MES</th>
                <th class="bg_th">AÑO</th>
                <th class="bg_th">NUMERO DIA</th>
                <th class="bg_th">FECHA CALCULO</th>
                <th class="bg_th">FECHA CARGA</th>
                <th class="bg_th">MES 2</th>
                <th class="bg_th">SEMAFORO</th>
                <th class="bg_th">CUENTA</th>
                <th class="bg_th">PROPIETARIO</th>
                <th class="bg_th">FECHA CAPTURA</th>
                <th class="bg_th">TAREA</th>
                <th class="bg_th">GESTOR</th>
                <th class="bg_th">DEUDA TOTAL</th>
                <th class="bg_th">RANGO CORPORATIVO</th>
                <th class="bg_th">RANGO SISTEMA</th>
                <th class="bg_th">TIPO DE SERVICIO</th>
                <th class="bg_th">SEMAFORO 3</th>
                <th class="bg_th">COLOR SEMAFORO</th>
                <th class="bg_th">CARACTERISTICAS DEL PREDIO</th>
                <th class="bg_th">GIRO</th>
                <th class="bg_th">CALLE</th>
                <th class="bg_th">NUM. EXT.</th>
              
                
            </tr>
        </thead>
        <tbody>
            <?php 
            if($hasRows) {
                while($cuenta = sqlsrv_fetch_array($datos, SQLSRV_FETCH_ASSOC)) {
                    // Buscamos en SolicitarFolio 
            ?>
            <tr>
                <td><?=utf8_encode($cuenta['plaza'])?></td>
                <td><?=utf8_encode($cuenta['numero'])?></td>
                <td><?=utf8_encode($cuenta['mes'])?></td>
                <td><?=utf8_encode($cuenta['ano'])?></td>
                <td><?=utf8_encode($cuenta['numero_dia'])?></td>
                <td><?=($cuenta['fecha_calculo']) ? date('d-m-Y', strtotime($cuenta['fecha_calculo'])) : ""?></td>
                <td><?=($cuenta['fecha_carga']) ? date('d-m-Y', strtotime($cuenta['fecha_carga'])) : ""?></td>
                <td><?=utf8_encode($cuenta['Mes2'])?></td>
                <td><?=utf8_encode($cuenta['semaforo'])?></td>
                <td><?=utf8_encode($cuenta['cuenta'])?></td>
                <td><?=utf8_encode($cuenta['propietario'])?></td>
                <td><?=($cuenta['fecha_captura']) ? date('d-m-Y', strtotime($cuenta['fecha_captura'])) : ""?></td>
                <td><?=utf8_encode($cuenta['tarea'])?></td>
                <td><?=utf8_encode($cuenta['gestor'])?></td>
                <td><?=utf8_encode($cuenta['deuda_total'])?></td>
                <td><?=utf8_encode($cuenta['rango_corporativo'])?></td>
                <td><?=utf8_encode($cuenta['rango_sistema'])?></td>
                <td><?=utf8_encode($cuenta['tipo_servicio'])?></td>
                <td><?=utf8_encode($cuenta['semaforo3'])?></td>
                <td><?=utf8_encode($cuenta['color Semaforo'])?></td>
                <td><?=utf8_encode($cuenta['caracteristica_predio'])?></td>
                <td><?=utf8_encode($cuenta['giro'])?></td>
                <td><?=utf8_encode($cuenta['calle'])?></td>
                <td><?=utf8_encode($cuenta['num_ext'])?></td>
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
                <th class="bg_th">PLAZA</th>
                <th class="bg_th">NUMERO</th>
                <th class="bg_th">MES</th>
                <th class="bg_th">AÑO</th>
                <th class="bg_th">NUMERO DIA</th>
                <th class="bg_th">FECHA CALCULO</th>
                <th class="bg_th">FECHA CARGA</th>
                <th class="bg_th">MES 2</th>
                <th class="bg_th">SEMAFORO</th>
                <th class="bg_th">CUENTA</th>
                <th class="bg_th">PROPIETARIO</th>
                <th class="bg_th">FECHA CAPTURA</th>
                <th class="bg_th">TAREA</th>
                <th class="bg_th">GESTOR</th>
                <th class="bg_th">DEUDA TOTAL</th>
                <th class="bg_th">RANGO CORPORATIVO</th>
                <th class="bg_th">RANGO SISTEMA</th>
                <th class="bg_th">TIPO DE SERVICIO</th>
                <th class="bg_th">SEMAFORO 3</th>
                <th class="bg_th">COLOR SEMAFORO</th>
                <th class="bg_th">CARACTERISTICAS DEL PREDIO</th>
                <th class="bg_th">GIRO</th>
                <th class="bg_th">CALLE</th>
                <th class="bg_th">NUM. EXT.</th>
                <th class="bg_th">NUM. INT.</th>
                <th class="bg_th">COLONIA</th>
                <th class="bg_th">POBLACION</th>
                <th class="bg_th">CP</th>
                <th class="bg_th">ROL</th>
                <th class="bg_th">MESES ADEUDO</th>
                <th class="bg_th">FECHA INICIO</th>
                <th class="bg_th">FECHA FINAL</th>
                <th class="bg_th">DIAS VIGENCIA</th>
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
                <td><?=utf8_encode($cuenta['semaforo'])?></td>
                <td><?=$cuenta['cuenta']?></td>
                <td><?=utf8_encode($cuenta['propietario'])?></td>
                <td><?=($cuenta['fecha_captura']) ? date('d-m-Y', strtotime($cuenta['fecha_captura'])) : ""?></td>
                <td><?=utf8_decode($cuenta['tarea'])?></td>
                <td><?=utf8_encode($cuenta['gestor'])?></td>
                <td><?=$cuenta['deuda_total']?></td>
                <td><?=$cuenta['rango_corporativo']?></td>
                <td><?=$cuenta['rango_sistema']?></td>
                <td><?=utf8_encode($cuenta['tipo_servicio'])?></td>
                <td><?=utf8_encode($cuenta['semaforo3'])?></td>
                <td><?=utf8_encode($cuenta['color Semaforo'])?></td>
                <td><?=utf8_encode($cuenta['caracteristica_predio'])?></td>
                <td><?=utf8_encode($cuenta['giro'])?></td>
                <td><?=utf8_encode($cuenta['calle'])?></td>
                <td><?=$cuenta['num_ext']?></td>
                <td><?=$cuenta['num_int']?></td>
                <td><?=utf8_encode($cuenta['colonia'])?></td>
                <td><?=utf8_encode($cuenta['poblacion'])?></td>
                <td><?=$cuenta['cp']?></td>
                <td><?=utf8_encode($cuenta['rol'])?></td>
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

<?php sqlsrv_close($cnxa2); 

