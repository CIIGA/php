<?php
ini_set('max_execution_time', 0);
require '../modules/pregrabadas.php';
//Se condiciona si se reciben como paarametro lo siguiente
if ((isset($_GET['base'])) && (isset($_GET['fecha_inicial'])) && (isset($_GET['fecha_final']))) {
    //Lo convertimos a variables
    $BD = $_GET['base'];
    $fechaI = $_GET['fecha_inicial'];
    $fechaF = $_GET['fecha_final'];
    //Nombre del archivo
    $archivo = 'Pregrabadas_' . $BD . '_' . $fechaI . '_' . $fechaF . '.txt';
    //Contenido del archivo
    $dsn = "sqlsrv:Server=51.222.44.135;Database=$BD";
    $usuario = 'sa';
    $contraseña = 'vrSxHH3TdC';

    try {
        $conexion = new PDO($dsn, $usuario, $contraseña);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . $e->getMessage();
    }
    $procedure = "exec sp_ReportePregrabadas '$fechaI', '$fechaF'";
    $resultado = $conexion->query($procedure);

    $contenido = "";
    if ($resultado) {
        $contenido .= "lead_id \tentry_date \tmodify_date \tstatus \tuser \tvendor_lead_code \tsource_id \tlist_id \tgmt_offset_now \tcalled_since_last_reset \tphone_code \tphone_number \ttitle \tfirst_name \tmiddle_initial \tlast_name \taddress1 \taddress2 \taddress3 \tcity \tstate \tprovince \tpostal_code \tcountry_code \tgender \tdate_of_birth \talt_phone \temail \tsecurity_phrase \tcomments \tcalled_count \tlast_local_call_time \trank \towner \tentry_list_id \r\n";
        while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $contenido .=
                utf8_encode($fila['lead_id']) . "\t" . "," .
                utf8_encode($fila['entry_date']) . "\t" . "," .
                utf8_encode($fila['modify_date']) . "\t" . "," .
                utf8_encode($fila['status']) . "\t" . "," .
                utf8_encode($fila['user']) . "\t" . "," .
                utf8_encode($fila['vendor_lead_code'])  . "\t" . "," .
                utf8_encode($fila['source_id']) . "\t" . "," .
                utf8_encode($fila['list_id']) . "\t" . "," .
                utf8_encode($fila['gmt_offset_now']) . "\t" . "," .
                utf8_encode($fila['called_since_last_reset']) . "\t" . "," .
                utf8_encode($fila['phone_code']) . "\t" . "," .
                utf8_encode($fila['phone_number'])  . "\t" . "," .
                utf8_encode($fila['title'])  . "\t" . "," .
                utf8_encode($fila['first_name'])  . "\t" . "," .
                utf8_encode($fila['middle_initial']) . "\t" . "," .
                utf8_encode($fila['last_name']) . "\t" . "," .
                utf8_encode($fila['address1']) . "\t" . "," .
                utf8_encode($fila['address2'])  . "\t" . "," .
                utf8_encode($fila['address3'])  . "\t" . "," .
                utf8_encode($fila['city']) . "\t" . "," .
                utf8_encode($fila['state']) . "\t" . "," .
                utf8_encode($fila['province']) . "\t" . "," .
                utf8_encode($fila['postal_code']) . "\t" . "," .
                utf8_encode($fila['country_code']) . "\t" . "," .
                utf8_encode($fila['gender']) . "\t" . "," .
                utf8_encode($fila['date_of_birth']) . "\t" . "," .
                utf8_encode($fila['alt_phone']) . "\t" . "," .
                utf8_encode($fila['email'])  . "\t" . "," .
                utf8_encode($fila['security_phrase']) . "\t" . "," .
                utf8_encode($fila['comments']) . "\t" . "," .
                utf8_encode($fila['called_count']) . "\t" . "," .
                utf8_encode($fila['last_local_call_time']) . "\t" . "," .
                utf8_encode($fila['rank']) . "\t" . "," .
                utf8_encode($fila['owner']) . "\t" . "," .
                utf8_encode($fila['entry_list_id']) . "\n";
        }
    }

    //El enlace a descargar del navegador es el mismo que se guarda en el servidor
    $enlace = $archivo;

    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$enlace");
    header('Set-Cookie: fileDownload=true; path=/');
    echo $contenido;
    // header("Content-Disposition: attachment; filename=" . $enlace);
    // header("Content-Type: application/octet-stream");
    // header("Content-Length: " . filesize($enlace));
    //Se elimina el archivo desde el servidor para que no se encuentre
    // unlink($archivo);
}
