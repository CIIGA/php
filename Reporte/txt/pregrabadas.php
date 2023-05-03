<?php
require '../modules/pregrabadas.php';
//Se condiciona si se reciben como paarametro lo siguiente
if ((isset($_GET['base'])) && (isset($_GET['fecha_inicial'])) && (isset($_GET['fecha_final']))) {
    //Lo convertimos a variables
    $BD = $_GET['base'];
    $fechaI = $_GET['fecha_inicial'];
    $fechaF = $_GET['fecha_final'];
    //Nombre del archivo
    $archivo = "prueba.txt";
    //Contenido del archivo
    $cnx = conexionPregrabadas($BD);
    $procedure = "exec sp_ReportePregrabadas '$fechaI', '$fechaF'";
    $exec = sqlsrv_query($cnx, $procedure);
    $result = sqlsrv_fetch_array($exec);
 
    $contenido = "";
    if ($result) {
        $contenido =$contenido."lead_id entry_date modify_date status user vendor_lead_code source_id list_id gmt_offset_now called_since_last_reset phone_code phone_number title first_name middle_initial last_name address1 address2 address3 city state province postal_code country_code gender date_of_birth alt_phone email security_phrase comments called_count last_local_call_time rank owner entry_list_id";
        while ($result = sqlsrv_fetch_array($exec)) {
            $contenido =$contenido.
                    utf8_encode($result['lead_id']) .
               ",". utf8_encode($result['entry_date']->format('d/m/Y')) .
               ",". utf8_encode($result['modify_date']->format('d/m/Y')) .
               ",". utf8_encode($result['status']) .
               ",". utf8_encode($result['user']) .
               ",". utf8_encode($result['vendor_lead_code']) .
               ",". utf8_encode($result['source_id']) .
               ",". utf8_encode($result['list_id']) .
               ",". utf8_encode($result['gmt_offset_now']) .
               ",". utf8_encode($result['called_since_last_reset']) .
               ",". utf8_encode($result['phone_code']) .
               ",". utf8_encode($result['phone_number']) .
               ",". utf8_encode($result['title']) .
               ",". utf8_encode($result['first_name']->format('d/m/Y')) .
               ",". utf8_encode($result['middle_initial']) .
               ",". utf8_encode($result['last_name']) .
               ",". utf8_encode($result['address1']) .
               ",". utf8_encode($result['address2']) .
               ",". utf8_encode($result['address3']) .
               ",". utf8_encode($result['city']) .
               ",". utf8_encode($result['state']) .
               ",". utf8_encode($result['province']) .
               ",". utf8_encode($result['postal_code']) .
               ",". utf8_encode($result['country_code']) .
               ",". utf8_encode($result['gender']) .
               ",". utf8_encode($result['date_of_birth']) .
               ",". utf8_encode($result['alt_phone']) .
               ",". utf8_encode($result['email']) .
               ",". utf8_encode($result['security_phrase']) .
               ",". utf8_encode($result['comments']) .
               ",". utf8_encode($result['called_count']) .
               ",". utf8_encode($result['last_local_call_time']->format('d/m/Y')) .
               ",". utf8_encode($result['rank']) .
               ",". utf8_encode($result['owner']) .
               ",". utf8_encode($result['entry_list_id'])."\n";
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
    header("Content-Disposition: attachment; filename=" . $enlace);
    header("Content-Type: application/octet-stream");
    header("Content-Length: " . filesize($enlace));
    readfile($enlace);
    //Se elimina el archivo desde el servidor para que no se encuentre
    unlink($archivo);
}
