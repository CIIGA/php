<?php
function conexionTelefonosPregrabadas($BD)
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
function count_spTelefonosPregrabadas($BD, $tipo)
{
    if ($tipo == 1) {
        $cnx = conexionTelefonosPregrabadas($BD);
        $sql = "select count(*) as total from telefono_pregrabadaP3$";
        $exec = sqlsrv_query($cnx, $sql);
        $result = sqlsrv_fetch_array($exec);
        return $result['total'];
    } else if ($tipo == 0) {
        $cnx = conexionTelefonosPregrabadas($BD);
        $sql = "select count(*) as total from telefono_pregrabadaP2$";
        $exec = sqlsrv_query($cnx, $sql);
        $result = sqlsrv_fetch_array($exec);
        return $result['total'];
    }
}
function sp_TelefonosPregrabadas(
    $id_plaza,
    $sector,
    $fechaI,
    $fechaF,
    $BD,
    $pagina,
    $tipo
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
    $cnx = conexionTelefonosPregrabadas($BD);
    $store = "exec sp_telefono_pregrabada $tipo";
    $exec_store = sqlsrv_query($cnx, $store);
    $result='';
    if ($tipo == 1) {
        $cnn = conexionTelefonosPregrabadas($BD);
        $sql = "select * from telefono_pregrabadaP3$ order by source_id OFFSET $inicioPaginacion ROWS FETCH next $datosPorPagina ROWS ONLY;";
        $exec = sqlsrv_query($cnn, $sql);
        $result = sqlsrv_fetch_array($exec); 
        if ($result) {
        echo "<div class='center'>
        <table class='table table-responsive table-condensed'>
        <thead class='thead-dark'>
            <tr>
            <th class='text-sm'>source_id</th>
            <th class='text-sm'>phone_number</th>
            <th class='text-sm'>title</th>
            </tr>
        </thead>
        <tbody>";
        while ($result = sqlsrv_fetch_array($exec)) {
            echo "<tr>
            <td class='text-sm'>" . utf8_encode($result['source_id']) . "</td>
            <td class='text-sm'>" . utf8_encode($result['phone_number']) . "</td>
            <td class='text-sm'>" . utf8_encode($result['title']) . "</td>
            </tr>";
        }
        echo " </tbody>
            </table>
        </div>";
    }
    } else if ($tipo == 0) {
        $cnn = conexionTelefonosPregrabadas($BD);
        $sql = "select * from telefono_pregrabadaP2$ order by source_id OFFSET $inicioPaginacion ROWS FETCH next $datosPorPagina ROWS ONLY;";
        $exec = sqlsrv_query($cnn, $sql);
        $result = sqlsrv_fetch_array($exec);
        if ($result) {
            echo "<div class='center'>
            <table class='table table-responsive table-condensed'>
            <thead class='thead-dark'>
                <tr>
                <th class='text-sm'>source_id</th>
                <th class='text-sm'>phone_number</th>
                <th class='text-sm'>title</th>
                </tr>
            </thead>
            <tbody>";
            while ($result = sqlsrv_fetch_array($exec)) {
                echo "<tr>
                <td class='text-sm'>" . utf8_encode($result['source_id']) . "</td>
                <td class='text-sm'>" . utf8_encode($result['phone_number']) . "</td>
                <td class='text-sm'>" . utf8_encode($result['title']) . "</td>
                </tr>";
            }
            echo " </tbody>
                </table>
            </div>";
        }
    }
        //Llamamos al total de registros para la paginacion
        $count = count_spTelefonosPregrabadas($BD, $tipo);
        //Se muestra el total de resultados que hay por pagina
        echo "<div class='center'> Resultados del $resultInicio al $resultFin </div>";
        //Inicio de la paginacion
        echo "<nav aria-label='Page navigation' class=''>";
        echo '<ul class="pagination">';
        //Si la paginacion es mayor a 1 se genera '>' para el boton siguiente
        if ($pagina > 1) {
            $page = $pagina - 1;
            echo '<li class="page-item ">';
            echo "<a href='reporte.php?plz=$id_plaza&base=$BD&sector=$sector&fecha_inicial=$fechaI&fecha_final=$fechaF&page=$page&tabla=&tipoTelefonosPregrabadas=$tipo'
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
                    echo "<a href='reporte.php?plz=$id_plaza&base=$BD&sector=$sector&fecha_inicial=$fechaI&fecha_final=$fechaF&page=$x&tabla=&tipoTelefonosPregrabadas=$tipo' class='page-link'>$x</a>";
                    echo '</li>';
                } else {
                    echo '<li class="page-item active" aria-current="page">';
                    echo "<a href='reporte.php?plz=$id_plaza&base=$BD&sector=$sector&fecha_inicial=$fechaI&fecha_final=$fechaF&page=$x&tabla=&tipoTelefonosPregrabadas=$tipo' class='page-link'>$x</a>";
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
                    echo "<a href='reporte.php?plz=$id_plaza&base=$BD&sector=$sector&fecha_inicial=$fechaI&fecha_final=$fechaF&page=$x&tabla=&tipoTelefonosPregrabadas=$tipo' class='page-link'>$x</a>";
                    echo '</li>';
                } else {
                    echo '<li class="page-item active" aria-current="page">';
                    echo "<a href='reporte.php?plz=$id_plaza&base=$BD&sector=$sector&fecha_inicial=$fechaI&fecha_final=$fechaF&page=$x&tabla=&tipoTelefonosPregrabadas=$tipo' class='page-link'>$x</a>";
                    echo '</li>';
                }
            }
        }
        //Si la posicion es mejor al total de las listas que siga mientrando el boton de siguiente
        if ($resultFin < $count) {
            $page = $pagina + 1;
            echo '<li class="page-item ">';
            echo " <a href='reporte.php?plz=$id_plaza&base=$BD&sector=$sector&fecha_inicial=$fechaI&fecha_final=$fechaF&page=$page&tabla=&tipoTelefonosPregrabadas=$tipo' class='page-link'
            aria-label='Next'> <span aria-hidden='true'>&raquo;</span> </a>";
            echo '</li>';
        }
        echo '</ul>';
        echo "</nav>";
    
}


function sp_TelefonosPregrabadasExcel(
    $BD,
    $tipo
) {
   
    $cnx = conexionTelefonosPregrabadas($BD);
    $store = "exec sp_telefono_pregrabada $tipo";
    $exec_store = sqlsrv_query($cnx, $store);
    $result='';
    if ($tipo == 1) {
        $cnn = conexionTelefonosPregrabadas($BD);
        $sql = "select * from telefono_pregrabadaP3$";
        $exec = sqlsrv_query($cnn, $sql);
        $result = sqlsrv_fetch_array($exec); 
        if ($result) {
        echo "<div class='center'>
        <table class='table table-responsive table-condensed'>
        <thead class='thead-dark'>
            <tr>
            <th class='text-sm'>source_id</th>
            <th class='text-sm'>phone_number</th>
            <th class='text-sm'>title</th>
            </tr>
        </thead>
        <tbody>";
        while ($result = sqlsrv_fetch_array($exec)) {
            echo "<tr>
            <td class='text-sm'>" . utf8_encode($result['source_id']) . "</td>
            <td class='text-sm'>" . utf8_encode($result['phone_number']) . "</td>
            <td class='text-sm'>" . utf8_encode($result['title']) . "</td>
            </tr>";
        }
        echo " </tbody>
            </table>
        </div>";
    }else{
        echo "<h4 class='text-center mt-2'>No hay información</h4>";
    }
    } else if ($tipo == 0) {
        $cnn = conexionTelefonosPregrabadas($BD);
        $sql = "select * from telefono_pregrabadaP2$";
        $exec = sqlsrv_query($cnn, $sql);
        $result = sqlsrv_fetch_array($exec);
        if ($result) {
            echo "<div class='center'>
            <table class='table table-responsive table-condensed'>
            <thead class='thead-dark'>
                <tr>
                <th class='text-sm'>source_id</th>
                <th class='text-sm'>phone_number</th>
                <th class='text-sm'>title</th>
                </tr>
            </thead>
            <tbody>";
            while ($result = sqlsrv_fetch_array($exec)) {
                echo "<tr>
                <td class='text-sm'>" . utf8_encode($result['source_id']) . "</td>
                <td class='text-sm'>" . utf8_encode($result['phone_number']) . "</td>
                <td class='text-sm'>" . utf8_encode($result['title']) . "</td>
                </tr>";
            }
            echo " </tbody>
                </table>
            </div>";
        }else{
            echo "<h4 class='text-center mt-2'>No hay información</h4>";
        }
    }
        
    
}
