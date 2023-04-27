<?php 
ini_set('max_execution_time', 0);
require_once('PhpSpreadsheet/vendor/autoload.php');
$plz=$_POST['plz'];
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_FILES['file_upload'])) {
        $plz=$_POST['plz'];
        $tmpName = $_FILES['file_upload']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmpName);
        $spreadsheet = $spreadsheet->getActiveSheet();
        $data_array =  $spreadsheet->toArray();
        $query = '';
        if(count($data_array) > 0) {
            $query = 'INSERT INTO prueba_vigencias (cuenta, [FECHA INICIO], [FECHA FINAL]) VALUES';
            foreach($data_array as $datos) {
                if(!is_string($datos[0]) && $datos[0] != "") {

                   
                    $FechaI = ($datos[1] == '') ? '' : date("Y-m-d", strtotime(str_replace('/', "-", $datos[1])));
                    $FechaF = ($datos[2] == '') ? '' : date("Y-m-d", strtotime(str_replace('/', "-", $datos[2])));
                    
                    $query .= "('" . $datos[0] . "',";
                    $query .= "'" . $FechaI . "',";
                    $query .= "'" . $FechaF . "'), ";
                    
                }
            }
        }
        
        // if($query != "") {
        //     $query = substr($query, 0, strlen($query) - 2);
        //     $query .= ";";

        //     $serverName = "implementta.mx";
        //     $connectionInfo = array('Database' => 'kpimplementta', 'UID' => 'sa', 'PWD' => 'vrSxHH3TdC');
        //     $cnx = sqlsrv_connect($serverName, $connectionInfo);
        //     date_default_timezone_set('America/Mexico_City');

        //     sqlsrv_query($cnx, "TRUNCATE TABLE prueba_vigencias;");
        //     sqlsrv_query($cnx, $query) or die(print_r(sqlsrv_errors()));
        //     sqlsrv_close($cnx);

        //     header('Location: ../map.php?error=0&msg=Datos Guardados & plz='.$plz);
        // } else {
        //     header('Location: ../map.php?error=1&msg=No hay datos en el archivo & plz='.$plz);
        // }
        echo $query;
    } else {
        header('Location: ../map.php?error=1&msg=El archivo no es correcto & plz='.$plz);
    }
} else {
    header('Location: ../map.php?error=1&msg=No hay datos & plz='.$plz);
}