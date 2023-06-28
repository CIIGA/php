<?php
function conexionPagos($BD)
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
function count_spPagosBrutos($BD)
{
    $cnx = conexionPagos($BD);
    $sql = "select count(*) as total from (select count(*) as pagos
    from Pagos 
    group by datepart(month,fechaPago),
        datename(month,fechaPago),
        datepart(year,fechaPago) 
    ) as t";
    $exec = sqlsrv_query($cnx, $sql);
    $result = sqlsrv_fetch_array($exec);
    //Se retorna el total
    return $result['total'];
}
function sp_PagosBrutos(
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
    $cnx = conexionPagos($BD);
    $sql = "select count(*) as pagos,
    count(distinct cuenta) as cuentas,
    sum(total) as total,
    datename(month,fechaPago) as mes,
    datepart(year,fechaPago) as anio,
    convert(date,convert(varchar(4),datepart(year,fechaPago)) + '-' + convert(varchar(2),datepart(month,fechaPago)) + '-01') as fecha_pago
    from Pagos 
    group by datepart(month,fechaPago),
        datename(month,fechaPago),
        datepart(year,fechaPago) 
    order by datepart(month,fechaPago) ,datepart(year,fechaPago) desc 
    OFFSET $inicioPaginacion ROWS FETCH NEXT $datosPorPagina ROWS ONLY";
    $exec = sqlsrv_query($cnx, $sql);
    $result = sqlsrv_fetch_array($exec);
    // print_r($exec);
    //se genera la tabla
    if ($result) {
        echo "<table class='table table-responsive table-condensed'>
        <thead class='thead-dark'>
            <tr>
            <th class='text-xs'>Acción</th>
            <th class='text-xs'>Pagos</th>
            <th class='text-xs'>Cuentas</th>
            <th class='text-xs'>Total</th>
            <th class='text-xs'>Mes</th>
            <th class='text-xs'>Año</th>
            </tr>
        </thead>
        <tbody>";
        while ($result = sqlsrv_fetch_array($exec)) {
           $pago= utf8_encode($result['fecha_pago']->format('d/m/Y'));
            echo "<tr>
            <td class='text-xs'>
                <button type='button' class='btn btn-success' onclick='excelPagosBrutos(`$BD`,`$pago`)'>
                <img src='https://img.icons8.com/fluency/24/null/download.png'/>
                </button>
            </td>
            <td class='text-xs'>" . utf8_encode($result['pagos']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['cuentas']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['total']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['mes']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['anio']) . "</td>
            </tr>";
        }
        echo " </tbody>
        </table>";
        //Llamamos al total de registros para la paginacion
        $count = count_spPagosBrutos($BD);
        //Se muestra el total de resultados que hay por pagina
        echo "<div> Resultados del $resultInicio al $resultFin </div>";
        //Inicio de la paginacion
        echo "<nav aria-label='Page navigation'>";
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
    }else{
        echo "<h4 class='text-center mt-2'>No hay información</h4>";
    }
}

function sp_PagosNetos(
    $id_plaza,
    $sector,
    $fechaI,
    $fechaF,
    $BD,
    $pagina
){
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
 $cnx = conexionPagos($BD);
 $sql = "select count(*) as pagos,
 count(distinct cuenta) as cuentas,
 sum(total) as total,			   
 datename(month,fechaPago) as mes,
 datepart(year,fechaPago) as anio,
 convert(date,convert(varchar(4),datepart(year,fechaPago)) + '-' + convert(varchar(2),datepart(month,fechaPago)) + '-01') as fecha_pago		   
from PagosFactura
group by datepart(month,fechaPago),
   datename(month,fechaPago),
   datepart(year,fechaPago)
order by datepart(month,fechaPago),
   datepart(year,fechaPago) desc 
   OFFSET $inicioPaginacion ROWS FETCH NEXT $datosPorPagina ROWS ONLY";
 $exec = sqlsrv_query($cnx, $sql);
 $result = sqlsrv_fetch_array($exec);
 //se genera la tabla
 if ($result) {
     echo "<table class='table table-responsive table-condensed'>
     <thead class='thead-dark'>
         <tr>
         <th class='text-xs'>Acción</th>
         <th class='text-xs'>Pagos</th>
         <th class='text-xs'>Cuentas</th>
         <th class='text-xs'>Total</th>
         <th class='text-xs'>Mes</th>
         <th class='text-xs'>Año</th>
         </tr>
     </thead>
     <tbody>";
     while ($result = sqlsrv_fetch_array($exec)) {
        $pago= utf8_encode($result['fecha_pago']->format('d/m/Y'));
         echo "<tr>
         <td class='text-xs'>
             <button type='button' class='btn btn-success' onclick='excelPagosNetos(`$BD`,`$pago`)'>
             <img src='https://img.icons8.com/fluency/24/null/download.png'/>
             </button>
         </td>
         <td class='text-xs'>" . utf8_encode($result['pagos']) . "</td>
         <td class='text-xs'>" . utf8_encode($result['cuentas']) . "</td>
         <td class='text-xs'>" . utf8_encode($result['total']) . "</td>
         <td class='text-xs'>" . utf8_encode($result['mes']) . "</td>
         <td class='text-xs'>" . utf8_encode($result['anio']) . "</td>
         </tr>";
     }
     echo " </tbody>
     </table>";
     //Llamamos al total de registros para la paginacion
     $count = count_spPagosBrutos($BD);
     //Se muestra el total de resultados que hay por pagina
     echo "<div> Resultados del $resultInicio al $resultFin </div>";
     //Inicio de la paginacion
     echo "<nav aria-label='Page navigation'>";
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
 }else{
    echo "<h4 class='text-center mt-2'>No hay información</h4>";
}
}