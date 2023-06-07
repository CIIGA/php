<?php
if (isset($_GET['plaza']) && isset($_GET['anio']) && isset($_GET['mes']) && isset($_GET['base'])) {

    $BD = $_GET['base'];
    $id = $_GET['plz'];
    $anio = $_GET['anio'];
    $mes = $_GET['mes'];
    $plaza = $_GET['plaza'];
    $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Nobiembre", "Diciembre"];
    $nombre = 'ReporteBono_' . $plaza . '_' . $meses[$mes - 1] . '_' . $anio;

?>
    <script>
        window.open("pdfBonos.php?plz=<?php echo $id ?>&base=<?php echo $BD ?>&plaza=<?php echo $plaza ?>&anio=<?php echo $anio ?>&mes=<?php echo $mes ?>&nombre=<?php echo $nombre ?>");

        setTimeout(() => {
            window.open("excelBonos.php?plz=<?php echo $id ?>&base=<?php echo $BD ?>&plaza=<?php echo $plaza ?>&anio=<?php echo $anio ?>&mes=<?php echo $mes ?>&nombre=<?php echo $nombre ?>");
        }, 1000);
    </script>
<?php
}

$zip = new ZipArchive();
$zipname = $nombre . '.zip';
if($zip->open($zipname, ZipArchive::CREATE)==true){
    $zip->addFile($nombre.'.pdf');
    $zip->addFile($nombre.'.xlsx');
    $zip->close();
    echo 'Se creo el archivo';
}else{
    echo "error al generar el .zip";
}
header('Content-Type: application/zip');
header('Content-disposition: attachment; filename='.$zipname);
// unlink($nombre.'.pdf');
// unlink($nombre.'.xlsx');
