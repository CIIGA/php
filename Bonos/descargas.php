<?php
if (isset($_GET['plaza']) && isset($_GET['anio']) && isset($_GET['mes']) && isset($_GET['base'])) {

    $BD = $_GET['base'];
    $id = $_GET['plz'];
    $anio = $_GET['anio'];
    $mes = $_GET['mes'];
    $plaza = $_GET['plaza'];

    // '<script type="text/javascript">window.open("PDFDeterminacion/' . $id[0]->id . '")</script>';
    // echo `<script type="text/javascript">window.open("Bonos/pdfBonos.php?plz=$id&base=$BD&plaza=$plaza&anio=$anio&mes=$mes)</script>`;
    ?>
    <script>
        window.open("pdfBonos.php?plz=<?php echo $id?>&base=<?php echo $BD?>&plaza=<?php echo $plaza?>&anio=<?php echo $anio?>&mes=<?php echo $mes ?>");
        setTimeout(()=>{
            window.open("excelBonos.php?plz=<?php echo $id?>&base=<?php echo $BD?>&plaza=<?php echo $plaza?>&anio=<?php echo $anio?>&mes=<?php echo $mes ?>");
        }, 1000);

    </script>
    <?php
    // echo `<script type="text/javascript">window.open("Bonos/excelBonos.php?plz=$id&base=$BD&plaza=$plaza&anio=$anio&mes=$mes)</script>`;

    // echo `<script type="text/javascript">window.open("Bonos/excelBonos.php?plz="+ingreso1+"&base="+ingreso2+"&plaza="+ingreso3+"&anio="+ingreso4+"&mes="+ingreso5)</script>`;
    // echo header(`Location: pdfBonos.php?plz=$id&base=$BD&plaza=$plaza&anio=$anio&mes=$mes`);
}
