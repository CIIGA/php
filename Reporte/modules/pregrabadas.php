<?php
//Funcion para generar conexiones dinamicas
function conexionPregrabadas($BD)
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
function count_spReportePregrabadas($BD, $fechaI, $fechaF)
{
    $cnx = conexionPregrabadas($BD);
    $sql = "select  count(rp.cuenta) as total from RegistroPregrabadas rp
    inner join ContactosCuenta cc on rp.cuenta = cc.cuenta
    where convert(date,rp.fechaCaptura) between '$fechaI' and '$fechaF' ";
    $exec = sqlsrv_query($cnx, $sql);
    $result = sqlsrv_fetch_array($exec);
    //Se retorna el total
    return $result['total'];
}
function sp_ReportePregrabadas(
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
    $cnx = conexionPregrabadas($BD);
    $sql = "select ROW_NUMBER() OVER (ORDER BY rp.cuenta ) as lead_id,
            fechaCaptura as entry_date,
            fechaCaptura as modify_date,
            case when charindex('-',observaciones) = 0 then observaciones
                else substring(observaciones,1,charindex('-',observaciones) - 1) end as status,
            'VDAD' as [user],
            '' as vendor_lead_code,
            rp.cuenta as source_id,
            101 as list_id,
            '-4.00' as gmt_offset_now,
            'Y' as called_since_last_reset,
            1 as phone_code,
            case when cc.TelefonoLocal <> '(000) 000-0000' then cc.TelefonoLocal else cc.telefonoCelular end as phone_number,
            case when cc.TelefonoLocal <> '(000) 000-0000' then 'Loca' else 'Celu' end as title,
            convert(datetime,fechaCaptura) as first_name,
            '' as middle_initial,
            '' as last_name,
            '' as address1,
            '' as address2,
            '' as address3,
            '' as city,
            '' as state,
            '' as province,
            '' as postal_code,
            '' as country_code,
            '' as gender,
            '0000-00-00' as date_of_birth,
            '' as alt_phone,
            '' as email,
            '' as security_phrase,
            '' as comments,
            '' as called_count,
            fechaCaptura as last_local_call_time,
            '0' as rank,
            '' as owner,
            '0' as entry_list_id 
        from RegistroPregrabadas rp
        inner join ContactosCuenta cc on rp.cuenta = cc.cuenta
        where convert(date,rp.fechaCaptura) between '2023-04-01' and '2023-04-27' 
        ORDER BY rp.cuenta 
        OFFSET $inicioPaginacion ROWS FETCH next $datosPorPagina ROWS ONLY;";
    $exec = sqlsrv_query($cnx, $sql);
    $result = sqlsrv_fetch_array($exec);
    //se genera la tabla
    if ($result) {
        echo "<table class='table'>
        <thead class='thead-dark'>
            <tr>
            <th class='text-xs'>lead_id</th>
            <th class='text-xs'>entry_date</th>
            <th class='text-xs'>modify_date</th>
            <th class='text-xs'>status</th>
            <th class='text-xs'>user</th>
            <th class='text-xs'>vendor_lead_code</th>
            <th class='text-xs'>source_id</th>
            <th class='text-xs'>list_id</th>
            <th class='text-xs'>gmt_offset_now</th>
            <th class='text-xs'>called_since_last_reset</th>
            <th class='text-xs'>phone_code</th>
            <th class='text-xs'>phone_number</th>
            <th class='text-xs'>title</th>
            <th class='text-xs'>first_name</th>
            <th class='text-xs'>middle_initial</th>
            <th class='text-xs'>last_name</th>
            <th class='text-xs'>address1</th>
            <th class='text-xs'>address2</th>
            <th class='text-xs'>address3</th>
            <th class='text-xs'>city</th>
            <th class='text-xs'>state</th>
            <th class='text-xs'>province</th>
            <th class='text-xs'>postal_code</th>
            <th class='text-xs'>country_code</th>
            <th class='text-xs'>gender</th>
            <th class='text-xs'>date_of_birth</th>
            <th class='text-xs'>alt_phone</th>
            <th class='text-xs'>email</th>
            <th class='text-xs'>security_phrase</th>
            <th class='text-xs'>comments</th>
            <th class='text-xs'>called_count</th>
            <th class='text-xs'>last_local_call_time</th>
            <th class='text-xs'>rank</th>
            <th class='text-xs'>owner</th>
            <th class='text-xs'>entry_list_id</th>
            </tr>
        </thead>
        <tbody>";
        while ($result = sqlsrv_fetch_array($exec)) {
            echo "<tr>
            <td class='text-xs'>" . utf8_encode($result['lead_id']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['entry_date']->format('d/m/Y')) . "</td>
            <td class='text-xs'>" . utf8_encode($result['modify_date']->format('d/m/Y')) . "</td>
            <td class='text-xs'>" . utf8_encode($result['status']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['user']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['vendor_lead_code']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['source_id']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['list_id']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['gmt_offset_now']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['called_since_last_reset']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['phone_code']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['phone_number']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['title']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['first_name']->format('d/m/Y')) . "</td>
            <td class='text-xs'>" . utf8_encode($result['middle_initial']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['last_name']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['address1']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['address2']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['address3']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['city']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['state']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['province']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['postal_code']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['country_code']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['gender']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['date_of_birth']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['alt_phone']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['email']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['security_phrase']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['comments']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['called_count']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['last_local_call_time']->format('d/m/Y')) . "</td>
            <td class='text-xs'>" . utf8_encode($result['rank']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['owner']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['entry_list_id']) . "</td>
            </tr>";
        }
        echo " </tbody>
        </table>";
        //Llamamos al total de registros para la paginacion
        $count = count_spReportePregrabadas($BD, $fechaI, $fechaF);
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
function storePregrabadas($BD, $fechaI, $fechaF)
{
    $cnx = conexionPregrabadas($BD);
    $procedure = "exec sp_ReportePregrabadas '$fechaI', '$fechaF'";
    $exec = sqlsrv_query($cnx, $procedure);
    $result = sqlsrv_fetch_array($exec);
    if ($result) {
        echo "<table class='table table-hover table-bordered' style='font-size: 11px;'>
        <thead>
            <tr>
            <th class='bg_th'>lead_id</th>
            <th class='bg_th'>entry_date</th>
            <th class='bg_th'>modify_date</th>
            <th class='bg_th'>status</th>
            <th class='bg_th'>user</th>
            <th class='bg_th'>vendor_lead_code</th>
            <th class='bg_th'>source_id</th>
            <th class='bg_th'>list_id</th>
            <th class='bg_th'>gmt_offset_now</th>
            <th class='bg_th'>called_since_last_reset</th>
            <th class='bg_th'>phone_code</th>
            <th class='bg_th'>phone_number</th>
            <th class='bg_th'>title</th>
            <th class='bg_th'>first_name</th>
            <th class='bg_th'>middle_initial</th>
            <th class='bg_th'>last_name</th>
            <th class='bg_th'>address1</th>
            <th class='bg_th'>address2</th>
            <th class='bg_th'>address3</th>
            <th class='bg_th'>city</th>
            <th class='bg_th'>state</th>
            <th class='bg_th'>province</th>
            <th class='bg_th'>postal_code</th>
            <th class='bg_th'>country_code</th>
            <th class='bg_th'>gender</th>
            <th class='bg_th'>date_of_birth</th>
            <th class='bg_th'>alt_phone</th>
            <th class='bg_th'>email</th>
            <th class='bg_th'>security_phrase</th>
            <th class='bg_th'>comments</th>
            <th class='bg_th'>called_count</th>
            <th class='bg_th'>last_local_call_time</th>
            <th class='bg_th'>rank</th>
            <th class='bg_th'>owner</th>
            <th class='bg_th'>entry_list_id</th>
            </tr>
        </thead>
        <tbody>";
        while ($result = sqlsrv_fetch_array($exec)) {
            echo "<tr>
            <td class='text-xs'>" . utf8_encode($result['lead_id']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['entry_date']->format('d/m/Y')) . "</td>
            <td class='text-xs'>" . utf8_encode($result['modify_date']->format('d/m/Y')) . "</td>
            <td class='text-xs'>" . utf8_encode($result['status']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['user']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['vendor_lead_code']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['source_id']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['list_id']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['gmt_offset_now']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['called_since_last_reset']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['phone_code']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['phone_number']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['title']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['first_name']->format('d/m/Y')) . "</td>
            <td class='text-xs'>" . utf8_encode($result['middle_initial']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['last_name']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['address1']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['address2']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['address3']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['city']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['state']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['province']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['postal_code']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['country_code']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['gender']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['date_of_birth']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['alt_phone']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['email']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['security_phrase']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['comments']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['called_count']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['last_local_call_time']->format('d/m/Y')) . "</td>
            <td class='text-xs'>" . utf8_encode($result['rank']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['owner']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['entry_list_id']) . "</td>
            </tr>";
        }
    } else {
        echo "<tr>
                        <td>No hay información</td>
                    </tr>";
    }
    echo "
            </tbody>
        </table>";
}

//La funcion genera la tabla de los datos paginados
function sp_ReportePregrabadasExcelPaginado(
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
    $cnx = conexionPregrabadas($BD);
    $sql = "select ROW_NUMBER() OVER (ORDER BY rp.cuenta ) as lead_id,
            fechaCaptura as entry_date,
            fechaCaptura as modify_date,
            case when charindex('-',observaciones) = 0 then observaciones
                else substring(observaciones,1,charindex('-',observaciones) - 1) end as status,
            'VDAD' as [user],
            '' as vendor_lead_code,
            rp.cuenta as source_id,
            101 as list_id,
            '-4.00' as gmt_offset_now,
            'Y' as called_since_last_reset,
            1 as phone_code,
            case when cc.TelefonoLocal <> '(000) 000-0000' then cc.TelefonoLocal else cc.telefonoCelular end as phone_number,
            case when cc.TelefonoLocal <> '(000) 000-0000' then 'Loca' else 'Celu' end as title,
            convert(datetime,fechaCaptura) as first_name,
            '' as middle_initial,
            '' as last_name,
            '' as address1,
            '' as address2,
            '' as address3,
            '' as city,
            '' as state,
            '' as province,
            '' as postal_code,
            '' as country_code,
            '' as gender,
            '0000-00-00' as date_of_birth,
            '' as alt_phone,
            '' as email,
            '' as security_phrase,
            '' as comments,
            '' as called_count,
            fechaCaptura as last_local_call_time,
            '0' as rank,
            '' as owner,
            '0' as entry_list_id 
        from RegistroPregrabadas rp
        inner join ContactosCuenta cc on rp.cuenta = cc.cuenta
        where convert(date,rp.fechaCaptura) between '2023-04-01' and '2023-04-27' 
        ORDER BY rp.cuenta 
        OFFSET $inicioPaginacion ROWS FETCH next $datosPorPagina ROWS ONLY;";
    $exec = sqlsrv_query($cnx, $sql);
    $result = sqlsrv_fetch_array($exec);
    //se genera la tabla
    if ($result) {
        echo "<table class='table table-hover table-bordered' style='font-size: 11px;'>
        <thead>
            <tr>
            <th class='bg_th'>lead_id</th>
            <th class='bg_th'>entry_date</th>
            <th class='bg_th'>modify_date</th>
            <th class='bg_th'>status</th>
            <th class='bg_th'>user</th>
            <th class='bg_th'>vendor_lead_code</th>
            <th class='bg_th'>source_id</th>
            <th class='bg_th'>list_id</th>
            <th class='bg_th'>gmt_offset_now</th>
            <th class='bg_th'>called_since_last_reset</th>
            <th class='bg_th'>phone_code</th>
            <th class='bg_th'>phone_number</th>
            <th class='bg_th'>title</th>
            <th class='bg_th'>first_name</th>
            <th class='bg_th'>middle_initial</th>
            <th class='bg_th'>last_name</th>
            <th class='bg_th'>address1</th>
            <th class='bg_th'>address2</th>
            <th class='bg_th'>address3</th>
            <th class='bg_th'>city</th>
            <th class='bg_th'>state</th>
            <th class='bg_th'>province</th>
            <th class='bg_th'>postal_code</th>
            <th class='bg_th'>country_code</th>
            <th class='bg_th'>gender</th>
            <th class='bg_th'>date_of_birth</th>
            <th class='bg_th'>alt_phone</th>
            <th class='bg_th'>email</th>
            <th class='bg_th'>security_phrase</th>
            <th class='bg_th'>comments</th>
            <th class='bg_th'>called_count</th>
            <th class='bg_th'>last_local_call_time</th>
            <th class='bg_th'>rank</th>
            <th class='bg_th'>owner</th>
            <th class='bg_th'>entry_list_id</th>
            </tr>
        </thead>
        <tbody>";
        while ($result = sqlsrv_fetch_array($exec)) {
            echo "<tr>
            <td class='text-xs'>" . utf8_encode($result['lead_id']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['entry_date']->format('d/m/Y')) . "</td>
            <td class='text-xs'>" . utf8_encode($result['modify_date']->format('d/m/Y')) . "</td>
            <td class='text-xs'>" . utf8_encode($result['status']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['user']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['vendor_lead_code']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['source_id']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['list_id']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['gmt_offset_now']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['called_since_last_reset']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['phone_code']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['phone_number']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['title']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['first_name']->format('d/m/Y')) . "</td>
            <td class='text-xs'>" . utf8_encode($result['middle_initial']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['last_name']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['address1']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['address2']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['address3']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['city']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['state']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['province']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['postal_code']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['country_code']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['gender']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['date_of_birth']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['alt_phone']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['email']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['security_phrase']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['comments']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['called_count']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['last_local_call_time']->format('d/m/Y')) . "</td>
            <td class='text-xs'>" . utf8_encode($result['rank']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['owner']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['entry_list_id']) . "</td>
            </tr>";
        }
        echo " </tbody>
        </table>";
    }else{
        echo "<h4 class='text-center mt-2'>No hay información</h4>";
    }
}

