<?php

$BD = $_GET['base'];
$fechaI = $_GET['fecha_inicial'];
$fechaF = $_GET['fecha_final'];
$nombre='Pregrabadas_'.$BD.'_'.$fechaI.'_'.$fechaF;
require '../modules/pregrabadas.php';
header('Cache-Control: max-age=60, must-revalidate');
header("Pragma: public");
header("Expires: 0");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$nombre.xls");
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    <?php
     //Se condiciona si se recib la pagina 
     if (isset($_GET['page'])) {
        $pagina = $_GET['page'];
        sp_ReportePregrabadasExcelPaginado(
            $fechaI,
            $fechaF,
            $BD,
            $pagina
        );
    } else {
        storePregrabadas(
            $BD,
            $fechaI,
            $fechaF
        );
    }
    ?>
</body>

</html>