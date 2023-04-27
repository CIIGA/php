<?php

$BD = $_GET['base'];
$fechaI = $_GET['fecha_inicial'];
$fechaF = $_GET['fecha_final'];
$serverName = "51.222.44.135";
//Funcion para generar conexiones dinamicas
function conexion($BD)
{
    $serverName = "51.222.44.135";
    $connectionInfo = array('Database' => $BD, 'UID' => 'sa', 'PWD' => 'vrSxHH3TdC');
    $cnx = sqlsrv_connect($serverName, $connectionInfo);
    date_default_timezone_set('America/Mexico_City');
    if ($cnx) {
        return $cnx;
    } else {
        echo "error de conexion";
        die(print_r(sqlsrv_errors(), true));
    }
}
$cnx = conexion($BD);
$procedure = "exec sp_RGCallCenter'$fechaI', '$fechaF'";
$exec = sqlsrv_query($cnx, $procedure);
$hasRows = sqlsrv_has_rows($exec);
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
        th,
        td {

            text-align: left;
            vertical-align: top;
            border: 1px solid #707070;
            border-spacing: 0;
        }

        /* color al titulo de las columnas */
        .bg_th {
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
            if ($hasRows) {
                while ($result = sqlsrv_fetch_array($exec, SQLSRV_FETCH_ASSOC)) {
                    // Buscamos en SolicitarFolio 
            ?>
                    <tr>
                        <td class='text-xs'><?= utf8_encode($result['Observaciones']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['FechaPromesaPago']->format('d/m/Y')) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['PersonaAtendio']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['TareaAnterior']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['TareaActual']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['Fecha']->format('d/m/Y')) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['Cuenta']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['Clave']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['CallCenter']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['Clasificacion']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['Telefono']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['TelRadio']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['TelRadio']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['TelefonoUsuario']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['CelularUsuario']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['TelRadioUsuario']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['TelefonoUsuario']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['CelularUsuario']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['TelRadioUsuario']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['Usuario']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['Direccion']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['Colonia']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['Distrito']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['Clave Catastral']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['Serie Medidor']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['Tipo Servicio']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['Giro']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['Razon Social']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['Deuda Total']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['Abogado']) ?></td>
                        <td class='text-xs'><?= utf8_encode($result['Gestor']) ?></td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td>No hay informacion</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</body>

</html>

<?php sqlsrv_close($cnx);
