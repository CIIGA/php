<?php
//Funcion para generar conexiones dinamicas
function conexionReporteAdeudo($BD)
{
    $serverName = "51.222.44.135";
    $connectionInfo = array('Database' => $BD, 'UID' => 'sa', 'PWD' => 'vrSxHH3TdC');
    $cnx = sqlsrv_connect($serverName, $connectionInfo);
    date_default_timezone_set('America/Mexico_City');
    if ($cnx) {
        return $cnx;
    } else {
        echo "error de conexion";
        die(print_r(sqlsrv_errors(), true));
    }
}
function count_spReporteAdeudo($BD)
{
    $cnx = conexionPregrabadas($BD);
    $sql = "select count (*) as total from (select count(*) as cuentas,
    sum(total) as total,
    fechaActualizacion as fecha_actualizacion, 
    fechaCorte as fecha_corte
    from Adeudos
    group by fechaActualizacion, 
      fechaCorte) as tabla ";
    $exec = sqlsrv_query($cnx, $sql);
    $result = sqlsrv_fetch_array($exec);
    //Se retorna el total
    return $result['total'];
}
function sp_ReporteAdeudo(
    $id_plaza,
    $sector,
    $fechaI,
    $fechaF,
    $BD,
    $pagina
) {
    //Se declara los datos por pagina que habra
    $datosPorPagina = 20;
    $inicioPaginacion = ($pagina - 1) * $datosPorPagina;
    if ($pagina == 1) {
        $resultInicio = 1;
        $resultFin = $datosPorPagina;
    } else {
        $resultInicio = ($datosPorPagina * ($pagina - 1)) + 1;
        $resultFin = ($resultInicio + $datosPorPagina - 1);
    }
    $cnx = conexionReporteAdeudo($BD);
    $sql = "select count(*) as cuentas,
    sum(total) as total,
    fechaActualizacion as fecha_actualizacion, 
    fechaCorte as fecha_corte
    from Adeudos
    group by fechaActualizacion, 
        fechaCorte
    order by fechaActualizacion desc
    OFFSET $inicioPaginacion ROWS FETCH next $datosPorPagina ROWS ONLY;";
    $exec = sqlsrv_query($cnx, $sql);
    $result = sqlsrv_fetch_array($exec);
    //se genera la tabla
    if ($result) {
        echo "<div class='center'>
        <table class='table table-responsive table-condensed'>
        <thead class='thead-dark'>
            <tr>
            <th class='text-sm'>Acción</th>
            <th class='text-sm'>Cuentas</th>
            <th class='text-sm'>Total</th>
            <th class='text-sm'>Fecha actualizacion</th>
            <th class='text-sm'>Fecha corte</th>
            </tr>
        </thead>
        <tbody>";
        while ($result = sqlsrv_fetch_array($exec)) {
            $pago= utf8_encode($result['fecha_corte']->format('d/m/Y'));
            echo "<tr>
            <td class='text-sm'>
                <button type='button' class='btn btn-success' onclick='excelAdeudo(`$BD`,`$pago`)'>
                <img src='https://img.icons8.com/fluency/24/null/download.png'/>
                </button>
            </td>
            <td class='text-sm'>" . utf8_encode($result['cuentas']) . "</td>
            <td class='text-sm'>" . utf8_encode($result['total']) . "</td>
            <td class='text-sm'>" . utf8_encode($result['fecha_actualizacion']->format('d/m/Y')) . "</td>
            <td class='text-sm'>" . utf8_encode($result['fecha_corte']->format('d/m/Y')) . "</td>
            </tr>";
        }
        echo " </tbody>
            </table>
        </div>";
        //Llamamos al total de registros para la paginacion
        $count = count_spReporteAdeudo($BD, $fechaI, $fechaF);
        //Se muestra el total de resultados que hay por pagina
        echo "<div class='center'> Resultados del $resultInicio al $resultFin </div>";
        //Inicio de la paginacion
        echo "<nav aria-label='Page navigation' class='center'>";
        echo '<ul class="pagination">';
        //Si la paginacion es mayor a 1 se genera '>' para el boton siguiente
        if ($pagina > 1) {
            $page = $pagina - 1;
            echo '<li class="page-item ">';
            echo "<a href='reporte.php?plz=$id_plaza&base=$BD&sector=$sector&fecha_inicial=$fechaI&fecha_final=$fechaF&page=$page&tabla='
            class='page-link' aria-label='Previous'> <span aria-hidden='true'>&laquo;</span> </a>";
            echo '</li>';
        }
        //Se hace el conteo del total de pagins que habra
        $cantidadPaginas = intdiv($count, $datosPorPagina);
        //Si el conteo divido por los datos de pagina es mayor a 0 que agregue la cantidad de paginas +1
        if ($count % $datosPorPagina > 0) {
            $cantidadPaginas++;
        }
        //Si la cantidad de paginas es mayor a 20 entonces x va ser la pagina que esta posicionado
        if ($cantidadPaginas > 20) {
            $x = $pagina;
            //Las paginas que habra en el front se iran sumando en 19 para que se
            //muestre en 20 contanto la pagina inicial
            $paginas = $pagina + 19;
            //sSe genera un bucle mientras x es menor al total de las paginas
            while ($x <= $paginas) {
                //Si x es diferente a la pagina actual que se encuentra el usuario
                //muestra la lista sin el active que muestra la posicion del quel usuario sepa en cual esta
                //posicionado si no, muestra la lista activa
                if ($x != $pagina) {
                    echo '<li class="page-item">';
                    echo "<a href='reporte.php?plz=$id_plaza&base=$BD&sector=$sector&fecha_inicial=$fechaI&fecha_final=$fechaF&page=$x&tabla=' class='page-link'>$x</a>";
                    echo '</li>';
                } else {
                    echo '<li class="page-item active" aria-current="page">';
                    echo "<a href='reporte.php?plz=$id_plaza&base=$BD&sector=$sector&fecha_inicial=$fechaI&fecha_final=$fechaF&page=$x&tabla=' class='page-link'>$x</a>";
                    echo '</li>';
                }
                //Se increment x
                $x += 1;
            }
        } else {
            //Si es menor a 20 entonces solo hace un recorrido de las paginas normales con un for
            for ($x = 1; $x <= $cantidadPaginas; $x++) {
                if ($x != $pagina) {
                    echo '<li class="page-item">';
                    echo "<a href='reporte.php?plz=$id_plaza&base=$BD&sector=$sector&fecha_inicial=$fechaI&fecha_final=$fechaF&page=$x&tabla=' class='page-link'>$x</a>";
                    echo '</li>';
                } else {
                    echo '<li class="page-item active" aria-current="page">';
                    echo "<a href='reporte.php?plz=$id_plaza&base=$BD&sector=$sector&fecha_inicial=$fechaI&fecha_final=$fechaF&page=$x&tabla=' class='page-link'>$x</a>";
                    echo '</li>';
                }
            }
        }
        //Si la posicion es mejor al total de las listas que siga mientrando el boton de siguiente
        if ($resultFin < $count) {
            $page = $pagina + 1;
            echo '<li class="page-item ">';
            echo " <a href='reporte.php?plz=$id_plaza&base=$BD&sector=$sector&fecha_inicial=$fechaI&fecha_final=$fechaF&page=$page&tabla=' class='page-link'
            aria-label='Next'> <span aria-hidden='true'>&raquo;</span> </a>";
            echo '</li>';
        }
        echo '</ul>';
        echo "</nav>";
    }
}

function sp_storeReporteAdeudo(
    $BD,
    $fecha
) {
    $cnx = conexionReporteAdeudo($BD);
    $sql = "select Cuenta
    ,SaldoCorriente
    ,SaldoAtraso
    ,SaldoRezago
    ,RecargosAcum
    ,SConvenioAgua
    ,VencidoConvenio
    ,RecargosConvenio
    ,SConvenioObra
    ,VencidoContrato
    ,RecargosContrato
    ,GastosEj
    ,Multas
    ,MultasOtros
    ,Total
    ,Impuesto
    ,Fomento
    ,Actualizacion
    ,Recargos
    ,FechaUltimoPago
    ,FechaActualizacion
    ,FechaCorte
    ,ult_trimestre_pago
    from Adeudos
    where format(convert(date,fechaCorte),'d/MM/yyyy') = '$fecha'";
    $exec = sqlsrv_query($cnx, $sql);
    $result = sqlsrv_fetch_array($exec);
    // print_r(sqlsrv_errors());
    //se genera la tabla
    if ($result) {
        echo "
        <table class='table'>
        <thead class='thead-dark'>
            <tr>
            <th class='text-sm'>Cuenta</th>
			<th class='text-sm'>SaldoCorriente</th>
			<th class='text-sm'>SaldoAtraso</th>
			<th class='text-sm'>SaldoRezago</th>
			<th class='text-sm'>RecargosAcum</th>
			<th class='text-sm'>SConvenioAgua</th>
			<th class='text-sm'>VencidoConvenio</th>
			<th class='text-sm'>RecargosConvenio</th>
			<th class='text-sm'>SConvenioObra</th>
			<th class='text-sm'>VencidoContrato</th>
			<th class='text-sm'>RecargosContrato</th>
			<th class='text-sm'>GastosEj</th>
			<th class='text-sm'>Multas</th>
			<th class='text-sm'>MultasOtros</th>
			<th class='text-sm'>Total</th>
			<th class='text-sm'>Impuesto</th>
			<th class='text-sm'>Fomento</th>
			<th class='text-sm'>Actualizacion</th>
			<th class='text-sm'>Recargos</th>
			<th class='text-sm'>FechaUltimoPago</th>
			<th class='text-sm'>FechaActualizacion</th>
			<th class='text-sm'>FechaCorte</th>
			<th class='text-sm'>ult_trimestre_pago</th>
            </tr>
        </thead>
        <tbody>";
        while ($result = sqlsrv_fetch_array($exec)) {
            echo "<tr>
               <td class='text-sm'>".utf8_encode($result['Cuenta'])."</td>
			   <td class='text-sm'>".utf8_encode($result['SaldoCorriente'])."</td>
			   <td class='text-sm'>".utf8_encode($result['SaldoAtraso'])."</td>
			   <td class='text-sm'>".utf8_encode($result['SaldoRezago'])."</td>
			   <td class='text-sm'>".utf8_encode($result['RecargosAcum'])."</td>
			   <td class='text-sm'>".utf8_encode($result['SConvenioAgua'])."</td>
			   <td class='text-sm'>".utf8_encode($result['VencidoConvenio'])."</td>
			   <td class='text-sm'>".utf8_encode($result['RecargosConvenio'])."</td>
			   <td class='text-sm'>".utf8_encode($result['SConvenioObra'])."</td>
			   <td class='text-sm'>".utf8_encode($result['VencidoContrato'])."</td>
			   <td class='text-sm'>".utf8_encode($result['RecargosContrato'])."</td>
			   <td class='text-sm'>".utf8_encode($result['GastosEj'])."</td>
			   <td class='text-sm'>".utf8_encode($result['Multas'])."</td>
			   <td class='text-sm'>".utf8_encode($result['MultasOtros'])."</td>
			   <td class='text-sm'>".utf8_encode($result['Total'])."</td>
			   <td class='text-sm'>".utf8_encode($result['Impuesto'])."</td>
			   <td class='text-sm'>".utf8_encode($result['Fomento'])."</td>
			   <td class='text-sm'>".utf8_encode($result['Actualizacion'])."</td>
			   <td class='text-sm'>".utf8_encode($result['Recargos'])."</td>
			   <td class='text-sm'>".utf8_encode($result['FechaUltimoPago']->format('d/m/Y'))."</td>
			   <td class='text-sm'>".utf8_encode($result['FechaActualizacion']->format('d/m/Y'))."</td>
			   <td class='text-sm'>".utf8_encode($result['FechaCorte']->format('d/m/Y'))."</td>
			   <td class='text-sm'>".utf8_encode($result['ult_trimestre_pago'])."</td>
            </tr>";
        }
        echo " </tbody>
            </table>";
        
    }
}