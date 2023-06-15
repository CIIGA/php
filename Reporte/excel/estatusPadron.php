<?php

$BD = $_GET['base'];

$nombre='EstatusPadron_'.$BD;
require '../modules/estatusPadron.php';
header('Cache-Control: max-age=60, must-revalidate');
header("Pragma: public");
header("Expires: 0");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$nombre.xls");
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

    <?php
    sp_EstatusPadronExcel($BD);
    ?>

</body>

</html>