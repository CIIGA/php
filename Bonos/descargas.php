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
// if (file_exists($nombre . '.pdf') && file_exists($nombre . '.xlsx')) {
    do {
        echo 'Si entro';
        $zip = new ZipArchive();
        $zipname = $nombre . '.zip';
        if ($zip->open($zipname, ZipArchive::CREATE) == true) {
            $zip->addFile($nombre . '.pdf');
            $zip->addFile($nombre . '.xlsx');
            $zip->close();
            echo 'Creando archivo...';
        } else {
            echo "error al generar el .zip";
        }
    } while (!file_exists($nombre . '.zip'));
// }

?>

<?php if (file_exists($nombre . '.zip')){?>
<script>
    window.onload = function() {
        let nameFile = '<?php echo $nombre . '.zip' ?>';
        window.open(nameFile);
    }
</script>
<?php }?>