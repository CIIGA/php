<?php

function plaza($id_plaza)
{
    $serverName = "51.222.44.135";
    $connectionInfo = array('Database' => 'kpis', 'UID' => 'sa', 'PWD' => 'vrSxHH3TdC');
    $cnx = sqlsrv_connect($serverName, $connectionInfo);
    date_default_timezone_set('America/Mexico_City');
    $pl = "SELECT p.data as base, pl.nombreplaza as plaza FROM plaza as pl INNER JOIN proveniente as p ON pl.id_proveniente=p.id_proveniente where pl.id_plaza='$id_plaza'";
    $plz = sqlsrv_query($cnx, $pl);
    $result = sqlsrv_fetch_array($plz);
    return $result;
}
//Funcion para generar conexiones dinamicas
function conexion($BD)
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

//La funcion hace el conteo del total de datos que hay en la plaza
function count_spCallCenter($BD,$fechaI,$fechaF)
{
    $cnx = conexion($BD);
    $sql = "select  count(*) as total
    from (
    select Cuenta,IdTarea,convert(varchar(max),Observaciones) as Observaciones,IdAspUser,FechaPromesaPago,FechaCaptura, IdObservacionesLlamadas,PersonaAtendio,IdMensaje, [NumConvenio/Recibo]
    from [dbo].[RegistroCallCenter]
    where convert(date,FechaCaptura) between '$fechaI' and '$fechaF' AND (IdTarea <> 73 and IdTarea <> 72 AND IdTarea <> 85)
    group by Cuenta,IdTarea,convert(varchar(max),Observaciones) ,IdAspUser,FechaPromesaPago,FechaCaptura , IdObservacionesLlamadas,PersonaAtendio,IdMensaje, [NumConvenio/Recibo]
    ) rcc
    inner join [dbo].[AspNetUsers] u on rcc.IdAspUser = u.Id
    inner join [dbo].[ContactosCuenta] cc on rcc.Cuenta = cc.Cuenta
    inner join [dbo].[implementta] i on rcc.Cuenta = i.Cuenta
    inner join [dbo].[CatalogoObservacionesLlamadas] col on rcc.IdObservacionesLlamadas = col.IdObservacionesLlamadas
    inner JOIN CatalogoTareas c ON rcc.IdTarea = c.IdTarea
    inner join AsignacionCallCenter acc on rcc.cuenta = acc.cuenta
    inner join CatalogoTareas cat on acc.idTarea = cat.IdTarea
    left join [dbo].[AsignacionAbogado] aa on rcc.cuenta = aa.cuenta
    left join [dbo].[AspNetUsers] a on aa.IdAspUser = a.Id
    left join [dbo].[AsignacionGestor] gg on  rcc.cuenta=gg.cuenta
    left join [dbo].[AspNetUsers] g on gg.IdAspUser = g.Id ";
    $exec = sqlsrv_query($cnx, $sql);
    $result = sqlsrv_fetch_array($exec);
    //Se retorna el total
    return $result['total'];
}
//La funcion genera la tabla de los datos paginados
function sp_RGCallCenter(
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
    $cnx = conexion($BD);
    $sql = "select  rcc.Observaciones, rcc.FechaPromesaPago,
        rcc.PersonaAtendio,
           c.DescripcionTarea as TareaAnterior, cat.descripcionTarea as TareaActual, rcc.FechaCaptura as Fecha, rcc.Cuenta,i.Clave, u.Nombre as CallCenter,col.Descripcion as Clasificacion,
           cc.TelefonoLocal as Telefono, cc.TelefonoCelular as Celular, cc.TelefonoRadio as TelRadio,
           cc.TelefonoRadio as TelRadio,
           cc.TelefonoLocalUsuario as TelefonoUsuario, cc.TelefonoCelularUsuario as CelularUsuario,cc.TelefonoRadioUsuario as TelRadioUsuario,
           cc.TelefonoLocalUsuario as TelefonoUsuario, cc.TelefonoCelularUsuario as CelularUsuario,cc.TelefonoRadioUsuario as TelRadioUsuario,
           i.Propietario as Usuario, isnull(i.Calle, '') + ' ' + isnull(i.NumExt, '') + ' ' + isnull(i.NumInt, '') as Direccion,
           isnull(i.Colonia, '') as Colonia, isnull(i.Poblacion, '') as Distrito,
           isnull(i.Clave, '') as 'Clave Catastral', isnull(i.SerieMedidor, '') as 'Serie Medidor',
           isnull(i.TipoServicio, '') as 'Tipo Servicio', isnull(i.Giro, '') as Giro,
           isnull(i.RazonSocial, '') as 'Razon Social', i.DeudaTotal as 'Deuda Total', a.Nombre as 'Abogado', g.Nombre as 'Gestor'
           ---into temp1
           from (
           select Cuenta,IdTarea,convert(varchar(max),Observaciones) as Observaciones,IdAspUser,FechaPromesaPago,FechaCaptura, IdObservacionesLlamadas,PersonaAtendio,IdMensaje, [NumConvenio/Recibo]
           from [dbo].[RegistroCallCenter]
           where convert(date,FechaCaptura) between '$fechaI' and '$fechaF' AND (IdTarea <> 73 and IdTarea <> 72 AND IdTarea <> 85)
           group by Cuenta,IdTarea,convert(varchar(max),Observaciones) ,IdAspUser,FechaPromesaPago,FechaCaptura , IdObservacionesLlamadas,PersonaAtendio,IdMensaje, [NumConvenio/Recibo]
           ) rcc
           inner join [dbo].[AspNetUsers] u on rcc.IdAspUser = u.Id
           inner join [dbo].[ContactosCuenta] cc on rcc.Cuenta = cc.Cuenta
           inner join [dbo].[implementta] i on rcc.Cuenta = i.Cuenta
           inner join [dbo].[CatalogoObservacionesLlamadas] col on rcc.IdObservacionesLlamadas = col.IdObservacionesLlamadas
           inner JOIN CatalogoTareas c ON rcc.IdTarea = c.IdTarea
           inner join AsignacionCallCenter acc on rcc.cuenta = acc.cuenta
           inner join CatalogoTareas cat on acc.idTarea = cat.IdTarea
           left join [dbo].[AsignacionAbogado] aa on rcc.cuenta = aa.cuenta
           left join [dbo].[AspNetUsers] a on aa.IdAspUser = a.Id
           left join [dbo].[AsignacionGestor] gg on  rcc.cuenta=gg.cuenta
           left join [dbo].[AspNetUsers] g on gg.IdAspUser = g.Id
           order by rcc.fechaCaptura OFFSET $inicioPaginacion ROWS FETCH next $datosPorPagina ROWS ONLY;";
    $exec = sqlsrv_query($cnx, $sql);
    $result = sqlsrv_fetch_array($exec);
    //se genera la tabla
    if ($result) {
        echo "<table class='table'>
        <thead class='thead-dark'>
            <tr>
            <th class='text-xs'>Observaciones</th>
            <th class='text-xs'>FechaPromesaPago</th>
            <th class='text-xs'>PersonaAtendio</th>
            <th class='text-xs'>TareaAnterior</th>
            <th class='text-xs'>TareaActual</th>
            <th class='text-xs'>Fecha</th>
            <th class='text-xs'>Cuenta</th>
            <th class='text-xs'>Clave</th>
            <th class='text-xs'>CallCenter</th>
            <th class='text-xs'>Clasificacion</th>
            <th class='text-xs'>Telefono</th>
            <th class='text-xs'>TelRadio</th>
            <th class='text-xs'>TelRadio</th>
            <th class='text-xs'>TelefonoUsuario</th>
            <th class='text-xs'>CelularUsuario</th>
            <th class='text-xs'>TelRadioUsuario</th>
            <th class='text-xs'>TelefonoUsuario</th>
            <th class='text-xs'>CelularUsuario</th>
            <th class='text-xs'>TelRadioUsuario</th>
            <th class='text-xs'>Usuario</th>
            <th class='text-xs'>Direccion</th>
            <th class='text-xs'>Colonia</th>
            <th class='text-xs'>Distrito</th>
            <th class='text-xs'>Clave Catastral</th>
            <th class='text-xs'>Serie Medidor</th>
            <th class='text-xs'>Tipo Servicio</th>
            <th class='text-xs'>Giro</th>
            <th class='text-xs'>Razon Social</th>
            <th class='text-xs'>Deuda Total</th>
            <th class='text-xs'>Abogado</th>
            <th class='text-xs'>Gestor</th>
            </tr>
        </thead>
        <tbody>";
        while ($result = sqlsrv_fetch_array($exec)) {
            echo "<tr>
            <td class='text-xs'>" . utf8_encode($result['Observaciones']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['FechaPromesaPago']->format('d/m/Y')) . "</td>
            <td class='text-xs'>" . utf8_encode($result['PersonaAtendio']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['TareaAnterior']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['TareaActual']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Fecha']->format('d/m/Y')) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Cuenta']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Clave']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['CallCenter']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Clasificacion']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Telefono']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['TelRadio']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['TelRadio']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['TelefonoUsuario']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['CelularUsuario']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['TelRadioUsuario']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['TelefonoUsuario']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['CelularUsuario']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['TelRadioUsuario']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Usuario']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Direccion']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Colonia']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Distrito']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Clave Catastral']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Serie Medidor']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Tipo Servicio']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Giro']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Razon Social']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Deuda Total']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Abogado']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Gestor']) . "</td>
            </tr>";
        }
        echo " </tbody>
        </table>";
        //Llamamos al total de registros para la paginacion
        $count = count_spCallCenter($BD,$fechaI,$fechaF);
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

function storeCallCenter($BD, $fechaI, $fechaF)
{
    $cnx = conexion($BD);
    $procedure = "exec sp_RGCallCenter'$fechaI', '$fechaF'";
    $exec = sqlsrv_query($cnx, $procedure);
    $hasRows = sqlsrv_has_rows($exec);

    echo "<table class='table table-hover table-bordered' style='font-size: 11px;'>
        <thead>
            <tr>
                <th class='bg_th'>Observaciones</th>
                <th class='bg_th'>FechaPromesaPago</th>
                <th class='bg_th'>PersonaAtendio</th>
                <th class='bg_th'>TareaAnterior</th>
                <th class='bg_th'>TareaActual</th>
                <th class='bg_th'>Fecha</th>
                <th class='bg_th'>Cuenta</th>
                <th class='bg_th'>Clave</th>
                <th class='bg_th'>CallCenter</th>
                <th class='bg_th'>Clasificacion</th>
                <th class='bg_th'>Telefono</th>
                <th class='bg_th'>TelRadio</th>
                <th class='bg_th'>TelRadio</th>
                <th class='bg_th'>TelefonoUsuario</th>
                <th class='bg_th'>CelularUsuario</th>
                <th class='bg_th'>TelRadioUsuario</th>
                <th class='bg_th'>TelefonoUsuario</th>
                <th class='bg_th'>CelularUsuario</th>
                <th class='bg_th'>TelRadioUsuario</th>
                <th class='bg_th'>Usuario</th>
                <th class='bg_th'>Direccion</th>
                <th class='bg_th'>Colonia</th>
                <th class='bg_th'>Distrito</th>
                <th class='bg_th'>Clave Catastral</th>
                <th class='bg_th'>Serie Medidor</th>
                <th class='bg_th'>Tipo Servicio</th>
                <th class='bg_th'>Giro</th>
                <th class='bg_th'>Razon Social</th>
                <th class='bg_th'>Deuda Total</th>
                <th class='bg_th'>Abogado</th>
                <th class='bg_th'>Gestor</th>
            </tr>
        </thead>
        <tbody>";
    if ($hasRows) {
        while ($result = sqlsrv_fetch_array($exec, SQLSRV_FETCH_ASSOC)) {
            // Buscamos en SolicitarFolio 
            echo "<tr>
                        <td class='text-xs'>" . utf8_encode($result['Observaciones']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['FechaPromesaPago']->format('d/m/Y')) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['PersonaAtendio']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['TareaAnterior']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['TareaActual']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['Fecha']->format('d/m/Y')) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['Cuenta']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['Clave']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['CallCenter']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['Clasificacion']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['Telefono']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['TelRadio']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['TelRadio']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['TelefonoUsuario']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['CelularUsuario']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['TelRadioUsuario']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['TelefonoUsuario']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['CelularUsuario']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['TelRadioUsuario']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['Usuario']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['Direccion']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['Colonia']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['Distrito']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['Clave Catastral']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['Serie Medidor']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['Tipo Servicio']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['Giro']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['Razon Social']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['Deuda Total']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['Abogado']) . "</td>
                        <td class='text-xs'>" . utf8_encode($result['Gestor']) . "</td>
                    </tr>";
        }
    } else {
        echo "<tr>
                    <td>No hay informacion</td>
                </tr>";
    }
    echo "
        </tbody>
    </table>";
}

//La funcion genera la tabla de los datos paginados
function sp_RGCallCenterEXcelPaginado(
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
    $cnx = conexion($BD);
    $sql = "select  rcc.Observaciones, rcc.FechaPromesaPago,
        rcc.PersonaAtendio,
           c.DescripcionTarea as TareaAnterior, cat.descripcionTarea as TareaActual, rcc.FechaCaptura as Fecha, rcc.Cuenta,i.Clave, u.Nombre as CallCenter,col.Descripcion as Clasificacion,
           cc.TelefonoLocal as Telefono, cc.TelefonoCelular as Celular, cc.TelefonoRadio as TelRadio,
           cc.TelefonoRadio as TelRadio,
           cc.TelefonoLocalUsuario as TelefonoUsuario, cc.TelefonoCelularUsuario as CelularUsuario,cc.TelefonoRadioUsuario as TelRadioUsuario,
           cc.TelefonoLocalUsuario as TelefonoUsuario, cc.TelefonoCelularUsuario as CelularUsuario,cc.TelefonoRadioUsuario as TelRadioUsuario,
           i.Propietario as Usuario, isnull(i.Calle, '') + ' ' + isnull(i.NumExt, '') + ' ' + isnull(i.NumInt, '') as Direccion,
           isnull(i.Colonia, '') as Colonia, isnull(i.Poblacion, '') as Distrito,
           isnull(i.Clave, '') as 'Clave Catastral', isnull(i.SerieMedidor, '') as 'Serie Medidor',
           isnull(i.TipoServicio, '') as 'Tipo Servicio', isnull(i.Giro, '') as Giro,
           isnull(i.RazonSocial, '') as 'Razon Social', i.DeudaTotal as 'Deuda Total', a.Nombre as 'Abogado', g.Nombre as 'Gestor'
           ---into temp1
           from (
           select Cuenta,IdTarea,convert(varchar(max),Observaciones) as Observaciones,IdAspUser,FechaPromesaPago,FechaCaptura, IdObservacionesLlamadas,PersonaAtendio,IdMensaje, [NumConvenio/Recibo]
           from [dbo].[RegistroCallCenter]
           where convert(date,FechaCaptura) between '$fechaI' and '$fechaF' AND (IdTarea <> 73 and IdTarea <> 72 AND IdTarea <> 85)
           group by Cuenta,IdTarea,convert(varchar(max),Observaciones) ,IdAspUser,FechaPromesaPago,FechaCaptura , IdObservacionesLlamadas,PersonaAtendio,IdMensaje, [NumConvenio/Recibo]
           ) rcc
           inner join [dbo].[AspNetUsers] u on rcc.IdAspUser = u.Id
           inner join [dbo].[ContactosCuenta] cc on rcc.Cuenta = cc.Cuenta
           inner join [dbo].[implementta] i on rcc.Cuenta = i.Cuenta
           inner join [dbo].[CatalogoObservacionesLlamadas] col on rcc.IdObservacionesLlamadas = col.IdObservacionesLlamadas
           inner JOIN CatalogoTareas c ON rcc.IdTarea = c.IdTarea
           inner join AsignacionCallCenter acc on rcc.cuenta = acc.cuenta
           inner join CatalogoTareas cat on acc.idTarea = cat.IdTarea
           left join [dbo].[AsignacionAbogado] aa on rcc.cuenta = aa.cuenta
           left join [dbo].[AspNetUsers] a on aa.IdAspUser = a.Id
           left join [dbo].[AsignacionGestor] gg on  rcc.cuenta=gg.cuenta
           left join [dbo].[AspNetUsers] g on gg.IdAspUser = g.Id
           order by rcc.fechaCaptura OFFSET $inicioPaginacion ROWS FETCH next $datosPorPagina ROWS ONLY;";
    $exec = sqlsrv_query($cnx, $sql);
    $result = sqlsrv_fetch_array($exec);
    //se genera la tabla
    if ($result) {
        echo "<table class='table table-hover table-bordered' style='font-size: 11px;'>
        <thead>
            <tr>
            <th class='bg_th'>Observaciones</th>
            <th class='bg_th'>FechaPromesaPago</th>
            <th class='bg_th'>PersonaAtendio</th>
            <th class='bg_th'>TareaAnterior</th>
            <th class='bg_th'>TareaActual</th>
            <th class='bg_th'>Fecha</th>
            <th class='bg_th'>Cuenta</th>
            <th class='bg_th'>Clave</th>
            <th class='bg_th'>CallCenter</th>
            <th class='bg_th'>Clasificacion</th>
            <th class='bg_th'>Telefono</th>
            <th class='bg_th'>TelRadio</th>
            <th class='bg_th'>TelRadio</th>
            <th class='bg_th'>TelefonoUsuario</th>
            <th class='bg_th'>CelularUsuario</th>
            <th class='bg_th'>TelRadioUsuario</th>
            <th class='bg_th'>TelefonoUsuario</th>
            <th class='bg_th'>CelularUsuario</th>
            <th class='bg_th'>TelRadioUsuario</th>
            <th class='bg_th'>Usuario</th>
            <th class='bg_th'>Direccion</th>
            <th class='bg_th'>Colonia</th>
            <th class='bg_th'>Distrito</th>
            <th class='bg_th'>Clave Catastral</th>
            <th class='bg_th'>Serie Medidor</th>
            <th class='bg_th'>Tipo Servicio</th>
            <th class='bg_th'>Giro</th>
            <th class='bg_th'>Razon Social</th>
            <th class='bg_th'>Deuda Total</th>
            <th class='bg_th'>Abogado</th>
            <th class='bg_th'>Gestor</th>
            </tr>
        </thead>
        <tbody>";
        while ($result = sqlsrv_fetch_array($exec)) {
            echo "<tr>
            <td class='text-xs'>" . utf8_encode($result['Observaciones']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['FechaPromesaPago']->format('d/m/Y')) . "</td>
            <td class='text-xs'>" . utf8_encode($result['PersonaAtendio']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['TareaAnterior']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['TareaActual']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Fecha']->format('d/m/Y')) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Cuenta']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Clave']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['CallCenter']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Clasificacion']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Telefono']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['TelRadio']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['TelRadio']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['TelefonoUsuario']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['CelularUsuario']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['TelRadioUsuario']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['TelefonoUsuario']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['CelularUsuario']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['TelRadioUsuario']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Usuario']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Direccion']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Colonia']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Distrito']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Clave Catastral']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Serie Medidor']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Tipo Servicio']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Giro']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Razon Social']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Deuda Total']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Abogado']) . "</td>
            <td class='text-xs'>" . utf8_encode($result['Gestor']) . "</td>
            </tr>";
        }
        echo " </tbody>
        </table>";
    }
    else{
        echo "<h4 class='text-center mt-2'>No hay información</h4>";
    }
}
