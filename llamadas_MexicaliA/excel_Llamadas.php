<?php 
session_start();
if (isset($_SESSION['user'])) {
if(isset($_POST['lista_anio']) and isset($_POST['lista_mes'])){
require "../include/conexion_d.php";

$anio=$_POST['lista_anio'];
$mes=$_POST['lista_mes'];

$fechai=$anio.'-0'.$mes.'-01';
$fechaf=$anio.'-0'.$mes.'-'.date( 't', strtotime( $fechai ));

 //ejecuto mi otro store pasandole el id plaza
 $db='implementtaMexicaliA';

 //en una variable mando a llamar la conexion y le paso el nombre de la base de datos como parametro
 $cnx=conexion($db);

 // ejecuto mi store con la conexion que le corresponde
 $store="execute [dbo].[sp_RDuracionLlamadaCall] '$fechai','$fechaf',$mes,$anio";

 $datos=sqlsrv_query($cnx,$store) or die ('Execute Stored Procedure Failed... Query excel_Llamadas.php [sp_RDuracionLlamadaCall]');
 $hasRows=sqlsrv_has_rows($datos);

header('Set-Cookie: fileDownload=true; path=/');
header('Cache-Control: max-age=60, must-revalidate');
header("Pragma: public");
header("Expires: 0");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=DuracionLlamadas_$fechai al $fechaf.xls");
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
   
        <table class="" style="font-size: 11px; ">
        <thead>
            <tr>
                <th class="bg_th">Observaciones</th>
                <th class="bg_th">FechaPromesaPago</th>
                <th class="bg_th">Persona Atendio</th>
                <th class="bg_th">Tarea Anterior</th>
                <th class="bg_th">Tarea Actual</th>
                <th class="bg_th">Fecha</th>
                <th class="bg_th">Cuenta</th>
                <th class="bg_th">Clave</th>
                <th class="bg_th">Callcenter</th>
                <th class="bg_th">Clasificacion</th>
                <th class="bg_th">Telefono</th>
                <th class="bg_th">Celular</th>
                <th class="bg_th">TelRadio</th>
                <th class="bg_th">Telefono Usuario</th>
                <th class="bg_th">Celular Usuario</th>
                <th class="bg_th">TelRadio Usuario</th>
                <th class="bg_th">Usuario</th>
                <th class="bg_th">Direccion</th>
                <th class="bg_th">Colonia</th>
                <th class="bg_th">Distrito</th>
                <th class="bg_th">Clave catastral</th>
                <th class="bg_th">Serie Medidor</th>
                <th class="bg_th">Tipo de Servicio</th>
                <th class="bg_th">Giro</th>
                <th class="bg_th">Razon Social</th>
                <th class="bg_th">Deuda Total</th>
                <th class="bg_th">Abogado</th>
                <th class="bg_th">Gestor</th>
                <th class="bg_th">Duracion Llamada</th>
                <th class="bg_th">FileOut</th>
               
              
                
            </tr>
        </thead>
        <tbody>
            <?php 
            if($hasRows) {
                while($cuenta = sqlsrv_fetch_array($datos, SQLSRV_FETCH_ASSOC)) {
                    // Buscamos en SolicitarFolio 
            ?>
            <tr>
                <td><?=utf8_encode($cuenta['Observaciones'])?></td>
                <td><?=($cuenta['FechaPromesaPago']) ? date('d-m-Y', strtotime($cuenta['FechaPromesaPago'])) : ""?></td>
                <td><?=utf8_encode($cuenta['PersonaAtendio'])?></td>
                <td><?=utf8_encode($cuenta['TareaAnterior'])?></td>
                <td><?=utf8_encode($cuenta['TareaActual'])?></td>
                <td><?=($cuenta['Fecha']) ? date('d-m-Y', strtotime($cuenta['Fecha'])) : ""?></td>
                <td><?=utf8_encode($cuenta['Cuenta'])?></td>
                <td><?=utf8_encode($cuenta['Clave'])?></td>
                <td><?=utf8_encode($cuenta['CallCenter'])?></td>
                <td><?=utf8_encode($cuenta['Clasificacion'])?></td>
                <td><?=utf8_encode($cuenta['Telefono'])?></td>
                <td><?=utf8_encode($cuenta['Celular'])?></td>
                <td><?=utf8_encode($cuenta['TelRadio'])?></td>
                <td><?=utf8_encode($cuenta['TelefonoUsuario'])?></td>
                <td><?=utf8_encode($cuenta['CelularUsuario'])?></td>
                <td><?=utf8_encode($cuenta['TelRadioUsuario'])?></td>
                <td><?=utf8_encode($cuenta['Usuario'])?></td>
                <td><?=utf8_encode($cuenta['Direccion'])?></td>
                <td><?=utf8_encode($cuenta['Colonia'])?></td>
                <td><?=utf8_encode($cuenta['Distrito'])?></td>
                <td><?=utf8_encode($cuenta['Clave Catastral'])?></td>
                <td><?=utf8_encode($cuenta['Serie Medidor'])?></td>
                <td><?=utf8_encode($cuenta['Tipo Servicio'])?></td>
                <td><?=utf8_encode($cuenta['Giro'])?></td>
                <td><?=utf8_encode($cuenta['Razon Social'])?></td>
                <td><?=utf8_encode($cuenta['Deuda Total'])?></td>
                <td><?=utf8_encode($cuenta['Abogado'])?></td>
                <td><?=utf8_encode($cuenta['Gestor'])?></td>
                <td><?=utf8_encode($cuenta['Duracion Llamada'])?></td>
                <td><?=utf8_encode($cuenta['Fileout'])?></td>
               
                
            <?php } } else { ?>
            <tr>
                <td>No hay informacion</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    
</body>

</html>

<?php sqlsrv_close($cnxa2); 
}else{
    header('location: ../../php/acceso.php');
}
}else{
    header('location: ../../../login.php');
}