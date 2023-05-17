<?php 
ini_set('max_execution_time', 0);
require '../modules/pagos.php';
//Se condiciona si se reciben como paarametro lo siguiente
if ((isset($_GET['base'])) && (isset($_GET['pago']))) {
    //Lo convertimos a variables
    $BD = $_GET['base'];
    $Fecha_pago = $_GET['pago'];
    $archivo = "PagosNetos.txt";
    //Contenido del archivo
    $cnx = conexionPagos($BD);
    $procedure = "exec sp_pagosNetoDetalle '$Fecha_pago'";
    $exec = sqlsrv_query($cnx, $procedure);
    $result = sqlsrv_fetch_array($exec);
    $contenido = "";
    if ($result) {
        $contenido =$contenido."Cuenta Referencia Recibo Descripcion Total FechaPago Propietario Subsidio Convenio MensualidadPago MesesConvenio \r\n";
        while ($result = sqlsrv_fetch_array($exec)) {
            $contenido =$contenido.
                    utf8_encode($result['Cuenta']) .
               ",". utf8_encode($result['Referencia']) .
               ",". utf8_encode($result['Recibo']) .
               ",". utf8_encode($result['Descripcion']) .
               ",". utf8_encode($result['Total']) .
               ",". utf8_encode($result['FechaPago']->format('d/m/Y')) .
               ",". utf8_encode($result['Propietario']) .
               ",". utf8_encode($result['Subsidio']) .
               ",". utf8_encode($result['Convenio']) .
               ",". utf8_encode($result['MensualidadPago']) .
               ",". utf8_encode($result['MesesConvenio']) ."\r\n";
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
