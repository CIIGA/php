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
    $archivo = 'Pregrabadas_'.$BD.'_'.$fechaI.'_'.$fechaF.'.txt';
    //Contenido del archivo
    $cnx = conexionPregrabadas($BD);
    $procedure = "exec sp_ReportePregrabadas '$fechaI', '$fechaF'";
    $exec = sqlsrv_query($cnx, $procedure);
    $result = sqlsrv_fetch_array($exec);
 
    $contenido = "";
    if ($result) {
        $contenido =$contenido."lead_id \tentry_date \tmodify_date \tstatus \tuser \tvendor_lead_code \tsource_id \tlist_id \tgmt_offset_now \tcalled_since_last_reset \tphone_code \tphone_number \ttitle \tfirst_name \tmiddle_initial \tlast_name \taddress1 \taddress2 \taddress3 \tcity \tstate \tprovince \tpostal_code \tcountry_code \tgender \tdate_of_birth \talt_phone \temail \tsecurity_phrase \tcomments \tcalled_count \tlast_local_call_time \trank \towner \tentry_list_id \r\n";
        while ($result = sqlsrv_fetch_array($exec)) {
            $contenido =$contenido.
                    utf8_encode($result['lead_id']) ."\t".
               ",". utf8_encode($result['entry_date']->format('d/m/Y')) ."\t".
               ",". utf8_encode($result['modify_date']->format('d/m/Y')) ."\t".
               ",". utf8_encode($result['status']) ."\t".
               ",". utf8_encode($result['user']) ."\t".
               ",". utf8_encode($result['vendor_lead_code']) ."\t".
               ",". utf8_encode($result['source_id']) ."\t".
               ",". utf8_encode($result['list_id']) ."\t".
               ",". utf8_encode($result['gmt_offset_now']) ."\t".
               ",". utf8_encode($result['called_since_last_reset']) ."\t".
               ",". utf8_encode($result['phone_code']) ."\t".
               ",". utf8_encode($result['phone_number']) ."\t".
               ",". utf8_encode($result['title']) ."\t".
               ",". utf8_encode($result['first_name']->format('d/m/Y')) ."\t".
               ",". utf8_encode($result['middle_initial']) ."\t".
               ",". utf8_encode($result['last_name']) ."\t".
               ",". utf8_encode($result['address1']) ."\t".
               ",". utf8_encode($result['address2']) ."\t".
               ",". utf8_encode($result['address3']) ."\t".
               ",". utf8_encode($result['city']) ."\t".
               ",". utf8_encode($result['state']) ."\t".
               ",". utf8_encode($result['province']) ."\t".
               ",". utf8_encode($result['postal_code']) ."\t".
               ",". utf8_encode($result['country_code']) ."\t".
               ",". utf8_encode($result['gender']) ."\t".
               ",". utf8_encode($result['date_of_birth']) ."\t".
               ",". utf8_encode($result['alt_phone']) ."\t".
               ",". utf8_encode($result['email']) ."\t".
               ",". utf8_encode($result['security_phrase']) ."\t".
               ",". utf8_encode($result['comments']) ."\t".
               ",". utf8_encode($result['called_count']) ."\t".
               ",". utf8_encode($result['last_local_call_time']->format('d/m/Y')) ."\t".
               ",". utf8_encode($result['rank']) ."\t".
               ",". utf8_encode($result['owner']) ."\t".
               ",". utf8_encode($result['entry_list_id'])."\r\n";
        }
    }
    //Generamos el archivo y decimos que se va a escribir
    $f = fopen($archivo, "w");
    //Se escribe el contenido
    fwrite($f, $contenido);
    //Se cierra el archivo
    fclose($f);
    //El enlace a descargar del navegador es el mismo que se guarda en el servidor
    $enlace = $archivo;
    header('Set-Cookie: fileDownload=true; path=/');
    header("Content-Disposition: attachment; filename=" . $enlace);
    header("Content-Type: application/octet-stream");
    header("Content-Length: " . filesize($enlace));
    readfile($enlace);
    //Se elimina el archivo desde el servidor para que no se encuentre
    unlink($archivo);
}
