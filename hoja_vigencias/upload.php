<?php
ini_set('max_execution_time', 0);
require_once('PhpSpreadsheet/vendor/autoload.php');
$plz = $_POST['plz'];
$serverName = "implementta.mx";
$connectionInfo = array('Database' => 'kpimplementta', 'UID' => 'sa', 'PWD' => 'vrSxHH3TdC');
$cnx = sqlsrv_connect($serverName, $connectionInfo);
date_default_timezone_set('America/Mexico_City');

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_FILES['file_upload'])) {
        $plz = $_POST['plz'];
        $tmpName = $_FILES['file_upload']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmpName);
        $spreadsheet = $spreadsheet->getActiveSheet();
        $data_array =  $spreadsheet->toArray();
        // print_r($data_array[1][0]);
        if (count($data_array) > 1) {
            sqlsrv_query($cnx, "TRUNCATE TABLE prueba_vigencias;");
            $count = count($data_array);
            if ($count > 1000) {
                $sobrante = $count % 1000;
                $bloques = ceil($count / 1000);
                carga($data_array, $cnx, 0, 1000, $bloques, $sobrante, $plz);
            }
            else{
                carga($data_array, $cnx, 0, $count, 1, 0, $plz);
            }
        } else {
            header('Location: ../map.php?error=1&msg=El Archivo No tiene Datos & plz=' . $plz);
        }
    } else {
        header('Location: ../map.php?error=1&msg=El archivo no es correcto & plz=' . $plz);
    }
} else {
    header('Location: ../map.php?error=1&msg=No hay datos & plz=' . $plz);
}
function carga($data_array, $cnx, $i, $cantidad, $bloques, $sobrante, $plz)
{
    $query = '';
    $query = 'INSERT INTO prueba_vigencias (cuenta, [FECHA INICIO], [FECHA FINAL]) VALUES';
    while ($i < $cantidad) {
        if (!is_string($data_array[$i][0]) && $data_array[$i][0] != "") {

            $FechaI = ($data_array[$i][1] == '') ? '' : date("Y-m-d", strtotime(str_replace('/', "-", $data_array[$i][1])));
            $FechaF = ($data_array[$i][2]);

            $query .= "('" . $data_array[$i][0] . "',";
            $query .= "'" . $FechaI . "',";
            $query .= "'" . $FechaF . "'), ";
        }
        $i += 1;
        if ($i == $cantidad) {
            $query = substr($query, 0, strlen($query) - 2);
            $query .= ";";
            sqlsrv_query($cnx, $query) or die(print_r(sqlsrv_errors()));
            $bloques -= 1;
            if ($bloques == 1) {
                $cantidad += $sobrante;
                carga($data_array, $cnx, $i, $cantidad, $bloques, $sobrante, $plz);
            } elseif ($bloques == 0) {
                header('Location: ../map.php?error=0&msg=Datos Guardados & plz=' . $plz);
            } else {
                $cantidad += 1000;
                carga($data_array, $cnx, $i, $cantidad, $bloques, $sobrante, $plz);
            }
        }
    }
}
