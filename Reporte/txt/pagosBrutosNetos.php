<?php 
ini_set('max_execution_time', 0);
require '../modules/pagos.php';
//Se condiciona si se reciben como paarametro lo siguiente
if ((isset($_GET['base'])) && (isset($_GET['pago']))) {
    //Lo convertimos a variables
    $BD = $_GET['base'];
    $Fecha_pago = $_GET['pago'];
    $archivo = "PagosBrutos_$BD.txt";
    //Contenido del archivo
    $cnx = conexionPagos($BD);
    $procedure = "exec sp_pagosBrutoDetalle '$Fecha_pago'";
    $exec = sqlsrv_query($cnx, $procedure);
    $result = sqlsrv_fetch_array($exec);
    $contenido = "";
    if ($result) {
        $contenido =$contenido."Cuenta \tReferencia \tRecibo \t\tDescripcion \tTotal \tFechaPago \tPropietario \tSubsidio \tConvenio \tMensualidadPago \tMesesConvenio \r\n";
        while ($result = sqlsrv_fetch_array($exec)) {
            $contenido =$contenido.
                    utf8_encode($result['Cuenta']) ."\t".
               ",". utf8_encode($result['Referencia']) ."\t\t".
               ",". utf8_encode($result['Recibo']) ."\t".
               ",". utf8_encode($result['Descripcion']) ."\t\t".
               ",". utf8_encode($result['Total']) ."\t".
               ",". utf8_encode($result['Propietario']) ."\t".
               ",". utf8_encode($result['FechaPago']->format('d/m/Y')) ."\t".
               ",". utf8_encode($result['Subsidio']) ."\t".
               ",". utf8_encode($result['Convenio']) ."\t".
               ",". utf8_encode($result['MensualidadPago']) ."\t".
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
