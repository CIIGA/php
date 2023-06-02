<?php
require "plaza.php";
?>
<?php
if (isset($_POST['anio']) && isset($_POST['bd']) && isset($_POST['mes'])) {

    $anio = $_POST['anio'];
    $BD = $_POST['bd'];
    $mes = $_POST['mes'];
    $cnx = conexion($BD);
    $sql = "select distinct datepart(month,fechaPago) as mes_numero,
    datename(month,fechaPago) as mes		       
    from PagosFactura
    where datepart(year,fechaPago) = $anio and fechaPago is not null and  datepart(month,fechaPago) not in ($mes)
    order by datepart(month,fechaPago) asc ";
    $exec = sqlsrv_query($cnx, $sql);
    $result  = sqlsrv_fetch_array($exec);
    $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Nobiembre", "Diciembre"];

    
?>
    <?php if ($result) { ?>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <label class="input-group-text" for="inputGroupSelect01">Mes</label>
            </div>
            <select class='custom-select' id='mes' name='mes' required>
                <option value="<?php echo trim($mes)?>" selected><?php echo $meses[$mes - 1] ?> </option>
                <?php
                do {
                    $mesNum = $result['mes_numero'] - 1;
                    $mesNum2 = $result['mes_numero'];
                    echo "<option value='$mesNum2'>". utf8_encode($meses[$mesNum]) . "</option>";
                } while (($result = sqlsrv_fetch_array($exec)));
                ?>
            </select>
        </div>
    <?php } else { ?>
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle"></i> No hay resultados
        </div>
    <?php  }
} else if (isset($_POST['anio']) && isset($_POST['bd'])) {
    $anio = $_POST['anio'];
    $BD = $_POST['bd'];
    $cnx = conexion($BD);
    $sql = "select distinct datepart(month,fechaPago) as mes_numero,
    datename(month,fechaPago) as mes		       
    from PagosFactura
    where datepart(year,fechaPago) = $anio and fechaPago is not null
    order by datepart(month,fechaPago) asc ";
    $exec = sqlsrv_query($cnx, $sql);
    $result  = sqlsrv_fetch_array($exec);
    $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Nobiembre", "Diciembre"];

    ?>
    <?php if ($result) { ?>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <label class="input-group-text" for="inputGroupSelect01">Mes</label>
            </div>
            <select class='custom-select' id='mes' name='mes' required>
                <?php
                do {
                    $mesNum = $result['mes_numero'] - 1;
                    $mesNum2 = $result['mes_numero'];
                    echo "<option value='$mesNum2' >" . utf8_encode($meses[$mesNum]) . "</option>";
                } while (($result = sqlsrv_fetch_array($exec)));
                ?>
            </select>
        </div>
    <?php } else { ?>
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle"></i> No hay resultados
        </div>
<?php }
}
