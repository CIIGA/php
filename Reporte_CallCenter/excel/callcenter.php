<?php 
require "../../include/conexion_d";
$BD=$_GET['base'];
$fechaI=$_GET['fechaI'];
$fechaI=$_GET['fechaI'];
$fechaF=$_GET['fechaF'];
$cnx = conexion($BD);
    $procedure = "exec sp_RGCallCenter'$fechaI', '$fechaF'";
    $exec = sqlsrv_query($cnx, $procedure);
    $hasRows=sqlsrv_has_rows($exec);
    header('Set-Cookie: fileDownload=true; path=/');
    header('Cache-Control: max-age=60, must-revalidate');
    header("Pragma: public");
    header("Expires: 0");
    header("Content-type: application/x-msdownload");
    header("Content-Disposition: attachment; filename=callcenter.xls");
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
   
    <table class="table table-hover table-bordered" style="font-size: 11px;">
        <thead>
            <tr>
            <th class='bg_th'>Observaciones</th>
            <th class='bg_th'>FechaPromesaPago</th>
            <th class='bg_th'>PersonaAtendio</th>
            <th class='bg_th'>TareaAnterior</th>
            <th class='bg_th'>TareaActual</th>
            <th class='bg_th'>Fecha</th>
            <th class='bg_th'>Cuenta</th>
            <th class='bg_th'>Clave</th>
            <th class='bg_th'>CallCenter</th>
            <th class='bg_th'>Clasificacion</th>
            <th class='bg_th'>Telefono</th>
            <th class='bg_th'>TelRadio</th>
            <th class='bg_th'>TelRadio</th>
            <th class='bg_th'>TelefonoUsuario</th>
            <th class='bg_th'>CelularUsuario</th>
            <th class='bg_th'>TelRadioUsuario</th>
            <th class='bg_th'>TelefonoUsuario</th>
            <th class='bg_th'>CelularUsuario</th>
            <th class='bg_th'>TelRadioUsuario</th>
            <th class='bg_th'>Usuario</th>
            <th class='bg_th'>Direccion</th>
            <th class='bg_th'>Colonia</th>
            <th class='bg_th'>Distrito</th>
            <th class='bg_th'>Clave Catastral</th>
            <th class='bg_th'>Serie Medidor</th>
            <th class='bg_th'>Tipo Servicio</th>
            <th class='bg_th'>Giro</th>
            <th class='bg_th'>Razon Social</th>
            <th class='bg_th'>Deuda Total</th>
            <th class='bg_th'>Abogado</th>
            <th class='bg_th'>Gestor</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if($hasRows) {
                while($result = sqlsrv_fetch_array($exec, SQLSRV_FETCH_ASSOC)) {
                    // Buscamos en SolicitarFolio 
            ?>
            <tr>
                <td><?=utf8_encode($result['Observaciones'])?></td>
                <td><?=utf8_encode($result['FechaPromesaPago'])?></td>
                <td><?=utf8_encode($result['PersonaAtendio'])?></td>
                <td><?=utf8_encode($result['TareaAnterior'])?></td>
                <td><?=utf8_encode($result['TareaActual'])?></td>
                <td><?=utf8_encode($result['Fecha'])?></td>
                <td><?=utf8_encode($result['Cuenta'])?></td>
                <td><?=utf8_encode($result['Clave'])?></td>
                <td><?=utf8_encode($result['CallCenter'])?></td>
                <td><?=utf8_encode($result['Clasificacion'])?></td>
                <td><?=utf8_encode($result['Telefono'])?></td>
                <td><?=utf8_encode($result['TelRadio'])?></td>
                <td><?=utf8_encode($result['TelefonoUsuario'])?></td>
                <td><?=utf8_encode($result['CelularUsuario'])?></td>
                <td><?=utf8_encode($result['TelRadioUsuario'])?></td>
                <td><?=utf8_encode($result['TelefonoUsuario'])?></td>
                <td><?=utf8_encode($result['CelularUsuario'])?></td>
                <td><?=utf8_encode($result['TelRadioUsuario'])?></td>
                <td><?=utf8_encode($result['Usuario'])?></td>
                <td><?=utf8_encode($result['Direccion'])?></td>
                <td><?=utf8_encode($result['Colonia'])?></td>
                <td><?=utf8_encode($result['Distrito'])?></td>
                <td><?=utf8_encode($result['Clave Catastral'])?></td>
                
              
                
                
                
                
            <?php } } else { ?>
            <tr>
                <td>No hay informacion</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    
</body>

</html>

<?php sqlsrv_close($cnx); 

