<?php
function conexionEstatusPadron($BD)
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

function count_spEstatusPadron($BD)
{
    $cnx = conexionEstatusPadron($BD);
    $sql = "select count(cuenta) as total FROM EstatusPadron";
    $exec = sqlsrv_query($cnx, $sql);
    $result = sqlsrv_fetch_array($exec);
    //Se retorna el total
    return $result['total'];
}
function sp_EstatusPadron(
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
    $cnx = conexionEstatusPadron($BD);
    $sql = "select * FROM EstatusPadron order by cuenta desc
    OFFSET $inicioPaginacion ROWS FETCH NEXT $datosPorPagina ROWS ONLY";
    $exec = sqlsrv_query($cnx, $sql);
    $result = sqlsrv_fetch_array($exec);
    // print_r($exec);
    //se genera la tabla
    if ($result) {
        echo "<table class='table table-responsive table-condensed'>
        <thead class='thead-dark'>
        <tr>
            <th class='text-xs'>cuenta</th>
            <th class='text-xs'>ult_trimestre_pago</th>
            <th class='text-xs'>propietario</th>
            <th class='text-xs'>calle</th>
            <th class='text-xs'>num_ext</th>
            <th class='text-xs'>num_int</th>
            <th class='text-xs'>colonia</th>
            <th class='text-xs'>poblacion</th>
            <th class='text-xs'>cp</th>
            <th class='text-xs'>tipo_servicio</th>
            <th class='text-xs'>caracteristica_predio</th>
            <th class='text-xs'>giro</th>
            <th class='text-xs'>serie_medidor</th>
            <th class='text-xs'>fecha_actualizacion</th>
            <th class='text-xs'>fecha_corte</th>
            <th class='text-xs'>rango</th>
            <th class='text-xs'>fecha_ultimo_pago</th>
            <th class='text-xs'>estatus</th>
            <th class='text-xs'>efectos</th>
            <th class='text-xs'>latitud</th>
            <th class='text-xs'>longitud</th>
            <th class='text-xs'>manzana</th>
            <th class='text-xs'>lote</th>
            <th class='text-xs'>entre_calle_1</th>
            <th class='text-xs'>entre_calle_2</th>
            <th class='text-xs'>Referencia</th>
            <th class='text-xs'>razon_social</th>
            <th class='text-xs'>calle_notificacion</th>
            <th class='text-xs'>num_ext_notificacion</th>
            <th class='text-xs'>colonia_notificacion</th>
            <th class='text-xs'>cp_notificacion</th>
            <th class='text-xs'>poblacion_notificacion</th>
            <th class='text-xs'>pagos</th>
            <th class='text-xs'>total_pagado</th>
            <th class='text-xs'>sepomex</th>
            <th class='text-xs'>gestor_asignado</th>
            <th class='text-xs'>tarea_asignada_gestor</th>
            <th class='text-xs'>gestionada_gestor</th>
            <th class='text-xs'>ultimo_gestor_gestion</th>
            <th class='text-xs'>ultima_tarea_gestor</th>
            <th class='text-xs'>observaciones_gestor</th>
            <th class='text-xs'>fecha_captura_gestor</th>
            <th class='text-xs'>activa_gestor</th>
            <th class='text-xs'>abogado_asignado</th>
            <th class='text-xs'>tarea_asignada_abogado</th>
            <th class='text-xs'>gestionada_abogado</th>
            <th class='text-xs'>ultimo_abogado_gestion</th>
            <th class='text-xs'>ultima_tarea_abogado</th>
            <th class='text-xs'>observaciones_abogado</th>
            <th class='text-xs'>fecha_captura_abogado</th>
            <th class='text-xs'>activa_abogado</th>
            <th class='text-xs'>call_center_asignado</th>
            <th class='text-xs'>tarea_asignada_call_center</th>
            <th class='text-xs'>gestionada_call_center</th>
            <th class='text-xs'>ultimo_call_center_gestion</th>
            <th class='text-xs'>ultima_tarea_call_center</th>
            <th class='text-xs'>activa_call_center</th>
            <th class='text-xs'>telefono_local</th>
            <th class='text-xs'>telefono_celular</th>
            <th class='text-xs'>telefono_radio</th>
            <th class='text-xs'>telefono_local_usuario</th>
            <th class='text-xs'>telefono_celular_usuario</th>
            <th class='text-xs'>telefono_radio_usuario</th>
            <th class='text-xs'>reductores_asignado</th>
            <th class='text-xs'>tarea_asignada_reductores</th>
            <th class='text-xs'>gestionada_reductores</th>
            <th class='text-xs'>ultima_tarea_reductores</th>
            <th class='text-xs'>activa_reductores</th>
            <th class='text-xs'>sup_terreno_h</th>
            <th class='text-xs'>sup_construccion_h</th>
            <th class='text-xs'>valor_terreno_h</th>
            <th class='text-xs'>valor_construccion_h</th>
            <th class='text-xs'>valor_catastral_h</th>
            <th class='text-xs'>sup_terreno_valuado</th>
            <th class='text-xs'>sup_construccion_valuado</th>
            <th class='text-xs'>valor_terreno_valuado</th>
            <th class='text-xs'>valor_construccion_valuado</th>
            <th class='text-xs'>valor_catastral_valuado</th>
            </tr>
        </thead>
        <tbody>";
        while ($result = sqlsrv_fetch_array($exec)) {
            echo "<tr>
            <th class='text-xs'>". utf8_encode($result['cuenta']). "</th>
            <th class='text-xs'>". utf8_encode($result['ult_trimestre_pago']). "</th>
            <th class='text-xs'>". utf8_encode($result['propietario']). "</th>
            <th class='text-xs'>". utf8_encode($result['calle']). "</th>
            <th class='text-xs'>". utf8_encode($result['num_ext']). "</th>
            <th class='text-xs'>". utf8_encode($result['num_int']). "</th>
            <th class='text-xs'>". utf8_encode($result['colonia']). "</th>
            <th class='text-xs'>". utf8_encode($result['poblacion']). "</th>
            <th class='text-xs'>". utf8_encode($result['cp']). "</th>
            <th class='text-xs'>". utf8_encode($result['tipo_servicio']). "</th>
            <th class='text-xs'>". utf8_encode($result['caracteristica_predio']). "</th>
            <th class='text-xs'>". utf8_encode($result['giro']). "</th>
            <th class='text-xs'>". utf8_encode($result['serie_medidor']). "</th>
            <th class='text-xs'>". utf8_encode($result['fecha_actualizacion']->format('d/m/Y')). "</th>
            <th class='text-xs'>". utf8_encode($result['fecha_corte']->format('d/m/Y')). "</th>
            <th class='text-xs'>". utf8_encode($result['rango']). "</th>
            <th class='text-xs'>". utf8_encode($result['fecha_ultimo_pago']->format('d/m/Y')). "</th>
            <th class='text-xs'>". utf8_encode($result['estatus']). "</th>
            <th class='text-xs'>". utf8_encode($result['efectos']). "</th>
            <th class='text-xs'>". utf8_encode($result['latitud']). "</th>
            <th class='text-xs'>". utf8_encode($result['longitud']). "</th>
            <th class='text-xs'>". utf8_encode($result['manzana']). "</th>
            <th class='text-xs'>". utf8_encode($result['lote']). "</th>
            <th class='text-xs'>". utf8_encode($result['entre_calle_1']). "</th>
            <th class='text-xs'>". utf8_encode($result['entre_calle_2']). "</th>
            <th class='text-xs'>". utf8_encode($result['Referencia']). "</th>
            <th class='text-xs'>". utf8_encode($result['razon_social']). "</th>
            <th class='text-xs'>". utf8_encode($result['calle_notificacion']). "</th>
            <th class='text-xs'>". utf8_encode($result['num_ext_notificacion']). "</th>
            <th class='text-xs'>". utf8_encode($result['colonia_notificacion']). "</th>
            <th class='text-xs'>". utf8_encode($result['cp_notificacion']). "</th>
            <th class='text-xs'>". utf8_encode($result['total_pagado']). "</th>
            <th class='text-xs'>". utf8_encode($result['sepomex']). "</th>
            <th class='text-xs'>". utf8_encode($result['gestor_asignado']). "</th>
            <th class='text-xs'>". utf8_encode($result['tarea_asignada_gestor']). "</th>
            <th class='text-xs'>". utf8_encode($result['gestionada_gestor']). "</th>
            <th class='text-xs'>". utf8_encode($result['ultima_tarea_gestor']). "</th>
            <th class='text-xs'>". utf8_encode($result['observaciones_gestor']). "</th>
            <th class='text-xs'>". utf8_encode($result['fecha_captura_gestor']->format('d/m/Y')). "</th>
            <th class='text-xs'>". utf8_encode($result['activa_gestor']). "</th>
            <th class='text-xs'>". utf8_encode($result['abogado_asignado']). "</th>
            <th class='text-xs'>". utf8_encode($result['tarea_asignada_abogado']). "</th>
            <th class='text-xs'>". utf8_encode($result['gestionada_abogado']). "</th>
            <th class='text-xs'>". utf8_encode($result['ultima_tarea_abogado']). "</th>
            <th class='text-xs'>". utf8_encode($result['observaciones_abogado']). "</th>
            <th class='text-xs'>". utf8_encode($result['fecha_captura_abogado']->format('d/m/Y')). "</th>
            <th class='text-xs'>". utf8_encode($result['activa_abogado']). "</th>
            <th class='text-xs'>". utf8_encode($result['call_center_asignado']). "</th>
            <th class='text-xs'>". utf8_encode($result['tarea_asignada_call_center']). "</th>
            <th class='text-xs'>". utf8_encode($result['gestionada_call_center']). "</th>
            <th class='text-xs'>". utf8_encode($result['ultima_tarea_call_center']). "</th>
            <th class='text-xs'>". utf8_encode($result['activa_call_center']). "</th>
            <th class='text-xs'>". utf8_encode($result['telefono_local']). "</th>
            <th class='text-xs'>". utf8_encode($result['telefono_celular']). "</th>
            <th class='text-xs'>". utf8_encode($result['telefono_radio']). "</th>
            <th class='text-xs'>". utf8_encode($result['telefono_local_usuario']). "</th>
            <th class='text-xs'>". utf8_encode($result['telefono_celular_usuario']). "</th>
            <th class='text-xs'>". utf8_encode($result['telefono_radio_usuario']). "</th>
            <th class='text-xs'>". utf8_encode($result['reductores_asignado']). "</th>
            <th class='text-xs'>". utf8_encode($result['tarea_asignada_reductores']). "</th>
            <th class='text-xs'>". utf8_encode($result['gestionada_reductores']). "</th>
            <th class='text-xs'>". utf8_encode($result['ultima_tarea_reductores']). "</th>
            <th class='text-xs'>". utf8_encode($result['activa_reductores']). "</th>
            <th class='text-xs'>". utf8_encode($result['sup_terreno_h']). "</th>
            <th class='text-xs'>". utf8_encode($result['sup_construccion_h']). "</th>
            <th class='text-xs'>". utf8_encode($result['valor_terreno_h']). "</th>
            <th class='text-xs'>". utf8_encode($result['valor_construccion_h']). "</th>
            <th class='text-xs'>". utf8_encode($result['valor_catastral_h']). "</th>
            <th class='text-xs'>". utf8_encode($result['sup_terreno_valuado']). "</th>
            <th class='text-xs'>". utf8_encode($result['sup_construccion_valuado']). "</th>
            <th class='text-xs'>". utf8_encode($result['valor_terreno_valuado']). "</th>
            <th class='text-xs'>". utf8_encode($result['valor_construccion_valuado']). "</th>
            <th class='text-xs'>". utf8_encode($result['valor_catastral_valuado']). "</th>
            </tr>";
        }
        echo " </tbody>
        </table>";
        //Llamamos al total de registros para la paginacion
        $count = count_spEstatusPadron($BD);
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
    }
}

function sp_EstatusPadronExcel(
    $BD
) {
   
    $cnx = conexionEstatusPadron($BD);
    $sql = "select * FROM EstatusPadron order by cuenta desc";
    $exec = sqlsrv_query($cnx, $sql);
    $result = sqlsrv_fetch_array($exec);
    // print_r($exec);
    //se genera la tabla
    if ($result) {
        echo "<table class='table table-responsive table-condensed'>
        <thead class='thead-dark'>
        <tr>
              <th class='text-xs'>cuenta</th>
              <th class='text-xs'>Clave_Catastral</th>
              <th class='text-xs'>ult_trimestre_pago</th>
              <th class='text-xs'>propietario</th>
              <th class='text-xs'>calle</th>
              <th class='text-xs'>num_ext</th>
              <th class='text-xs'>num_int</th>
              <th class='text-xs'>colonia</th>
              <th class='text-xs'>poblacion</th>
              <th class='text-xs'>cp</th>
              <th class='text-xs'>tipo_servicio</th>
              <th class='text-xs'>caracteristica_predio</th>
              <th class='text-xs'>giro</th>
              <th class='text-xs'>serie_medidor</th>
              <th class='text-xs'>estatus_de_adeudo</th>
              <th class='text-xs'>fecha_actualizacion</th>
              <th class='text-xs'>fecha_corte</th>
              <th class='text-xs'>rango</th>
              <th class='text-xs'>fecha_ultimo_pago</th>
              <th class='text-xs'>estatus</th>
              <th class='text-xs'>efectos</th>
              <th class='text-xs'>latitud</th>
              <th class='text-xs'>longitud</th>
              <th class='text-xs'>sectr</th>
              <th class='text-xs'>Meses_Adeudo</th>
              <th class='text-xs'>estatus_zona</th>
              <th class='text-xs'>ruta</th>
              <th class='text-xs'>secuencia</th>
              <th class='text-xs'>manzana</th>
              <th class='text-xs'>lote</th>
              <th class='text-xs'>entre_calle_1</th>
              <th class='text-xs'>entre_calle_2</th>
              <th class='text-xs'>Referencia</th>
              <th class='text-xs'>razon_social</th>
              <th class='text-xs'>calle_notificacion</th>
              <th class='text-xs'>num_ext_notificacion</th>
              <th class='text-xs'>colonia_notificacion</th>
              <th class='text-xs'>cp_notificacion</th>
              <th class='text-xs'>poblacion_notificacion</th>
              <th class='text-xs'>pagos</th>
              <th class='text-xs'>total_pagado</th>
              <th class='text-xs'>sepomex</th>
              <th class='text-xs'>gestor_asignado</th>
              <th class='text-xs'>tarea_asignada_gestor</th>
              <th class='text-xs'>gestionada_gestor</th>
              <th class='text-xs'>ultimo_gestor_gestion</th>
              <th class='text-xs'>ultima_tarea_gestor</th>
              <th class='text-xs'>observaciones_gestor</th>
              <th class='text-xs'>fecha_captura_gestor</th>
              <th class='text-xs'>activa_gestor</th>
              <th class='text-xs'>abogado_asignado</th>
              <th class='text-xs'>tarea_asignada_abogado</th>
              <th class='text-xs'>gestionada_abogado</th>
              <th class='text-xs'>ultimo_abogado_gestion</th>
              <th class='text-xs'>ultima_tarea_abogado</th>
              <th class='text-xs'>observaciones_abogado</th>
              <th class='text-xs'>fecha_captura_abogado</th>
              <th class='text-xs'>activa_abogado</th>
              <th class='text-xs'>call_center_asignado</th>
              <th class='text-xs'>tarea_asignada_call_center</th>
              <th class='text-xs'>gestionada_call_center</th>
              <th class='text-xs'>ultimo_call_center_gestion</th>
              <th class='text-xs'>ultima_tarea_call_center</th>
              <th class='text-xs'>activa_call_center</th>
              <th class='text-xs'>telefono_local</th>
              <th class='text-xs'>telefono_celular</th>
              <th class='text-xs'>telefono_radio</th>
              <th class='text-xs'>telefono_local_usuario</th>
              <th class='text-xs'>telefono_celular_usuario</th>
              <th class='text-xs'>telefono_radio_usuario</th>
              <th class='text-xs'>reductores_asignado</th>
              <th class='text-xs'>tarea_asignada_reductores</th>
              <th class='text-xs'>gestionada_reductores</th>
              <th class='text-xs'>ultimo_reductor_gestion</th>
              <th class='text-xs'>ultima_tarea_reductores</th>
              <th class='text-xs'>activa_reductores</th>
              <th class='text-xs'>ultimo_carta_invitacion_gestion</th>
              <th class='text-xs'>ultima_tarea_carta_invitacion</th>
              <th class='text-xs'>ultimo_sepomex_gestion</th>
              <th class='text-xs'>ultima_tarea_sepomex</th>
              <th class='text-xs'>ultimo_pregrabada_gestion</th>
              <th class='text-xs'>ultima_tarea_pregrabada</th>
              <th class='text-xs'>ultimo_inspeccion_gestion</th>
              <th class='text-xs'>ultima_tarea_inspeccion</th>
              <th class='text-xs'>sup_terreno_h</th>
              <th class='text-xs'>sup_construccion_h</th>
              <th class='text-xs'>valor_terreno_h</th>
              <th class='text-xs'>valor_construccion_h</th>
              <th class='text-xs'>valor_catastral_h</th>
              <th class='text-xs'>sup_terreno_valuado</th>
              <th class='text-xs'>sup_construccion_valuado</th>
              <th class='text-xs'>valor_terreno_valuado</th>
              <th class='text-xs'>valor_construccion_valuado</th>
              <th class='text-xs'>valor_catastral_valuado</th>
              <th class='text-xs'>casilla</th>
            </tr>
        </thead>
        <tbody>";
        while ($result = sqlsrv_fetch_array($exec)) {
            echo "<tr>
              <th class='text-xs'>".utf8_encode($result['cuenta'])."</th>
              <th class='text-xs'>".utf8_encode($result['Clave_Catastral'])."</th>
              <th class='text-xs'>".utf8_encode($result['ult_trimestre_pago'])."</th>
              <th class='text-xs'>".utf8_encode($result['propietario'])."</th>
              <th class='text-xs'>".utf8_encode($result['calle'])."</th>
              <th class='text-xs'>".utf8_encode($result['num_ext'])."</th>
              <th class='text-xs'>".utf8_encode($result['num_int'])."</th>
              <th class='text-xs'>".utf8_encode($result['colonia'])."</th>
              <th class='text-xs'>".utf8_encode($result['poblacion'])."</th>
              <th class='text-xs'>".utf8_encode($result['cp'])."</th>
              <th class='text-xs'>".utf8_encode($result['tipo_servicio'])."</th>
              <th class='text-xs'>".utf8_encode($result['caracteristica_predio'])."</th>
              <th class='text-xs'>".utf8_encode($result['giro'])."</th>
              <th class='text-xs'>".utf8_encode($result['serie_medidor'])."</th>
              <th class='text-xs'>".utf8_encode($result['estatus_de_adeudo'])."</th>
              <th class='text-xs'>".utf8_encode($result['fecha_actualizacion']->format('d/m/Y'))."</th>
              <th class='text-xs'>".utf8_encode($result['fecha_corte']->format('d/m/Y'))."</th>
              <th class='text-xs'>".utf8_encode($result['rango'])."</th>
              <th class='text-xs'>".utf8_encode($result['fecha_ultimo_pago']->format('d/m/Y'))."</th>
              <th class='text-xs'>".utf8_encode($result['estatus'])."</th>
              <th class='text-xs'>".utf8_encode($result['efectos'])."</th>
              <th class='text-xs'>".utf8_encode($result['latitud'])."</th>
              <th class='text-xs'>".utf8_encode($result['longitud'])."</th>
              <th class='text-xs'>".utf8_encode($result['sectr'])."</th>
              <th class='text-xs'>".utf8_encode($result['Meses_Adeudo'])."</th>
              <th class='text-xs'>".utf8_encode($result['estatus_zona'])."</th>
              <th class='text-xs'>".utf8_encode($result['ruta'])."</th>
              <th class='text-xs'>".utf8_encode($result['secuencia'])."</th>
              <th class='text-xs'>".utf8_encode($result['manzana'])."</th>
              <th class='text-xs'>".utf8_encode($result['lote'])."</th>
              <th class='text-xs'>".utf8_encode($result['entre_calle_1'])."</th>
              <th class='text-xs'>".utf8_encode($result['entre_calle_2'])."</th>
              <th class='text-xs'>".utf8_encode($result['Referencia'])."</th>
              <th class='text-xs'>".utf8_encode($result['razon_social'])."</th>
              <th class='text-xs'>".utf8_encode($result['calle_notificacion'])."</th>
              <th class='text-xs'>".utf8_encode($result['num_ext_notificacion'])."</th>
              <th class='text-xs'>".utf8_encode($result['colonia_notificacion'])."</th>
              <th class='text-xs'>".utf8_encode($result['cp_notificacion'])."</th>
              <th class='text-xs'>".utf8_encode($result['poblacion_notificacion'])."</th>
              <th class='text-xs'>".utf8_encode($result['pagos'])."</th>
              <th class='text-xs'>".utf8_encode($result['total_pagado'])."</th>
              <th class='text-xs'>".utf8_encode($result['sepomex'])."</th>
              <th class='text-xs'>".utf8_encode($result['gestor_asignado'])."</th>
              <th class='text-xs'>".utf8_encode($result['tarea_asignada_gestor'])."</th>
              <th class='text-xs'>".utf8_encode($result['gestionada_gestor'])."</th>
              <th class='text-xs'>".utf8_encode($result['ultimo_gestor_gestion'])."</th>
              <th class='text-xs'>".utf8_encode($result['ultima_tarea_gestor'])."</th>
              <th class='text-xs'>".utf8_encode($result['observaciones_gestor'])."</th>
              <th class='text-xs'>".utf8_encode($result['fecha_captura_gestor']->format('d/m/Y'))."</th>
              <th class='text-xs'>".utf8_encode($result['activa_gestor'])."</th>
              <th class='text-xs'>".utf8_encode($result['abogado_asignado'])."</th>
              <th class='text-xs'>".utf8_encode($result['tarea_asignada_abogado'])."</th>
              <th class='text-xs'>".utf8_encode($result['gestionada_abogado'])."</th>
              <th class='text-xs'>".utf8_encode($result['ultimo_abogado_gestion'])."</th>
              <th class='text-xs'>".utf8_encode($result['ultima_tarea_abogado'])."</th>
              <th class='text-xs'>".utf8_encode($result['observaciones_abogado'])."</th>
              <th class='text-xs'>".utf8_encode($result['fecha_captura_abogado']->format('d/m/Y'))."</th>
              <th class='text-xs'>".utf8_encode($result['activa_abogado'])."</th>
              <th class='text-xs'>".utf8_encode($result['call_center_asignado'])."</th>
              <th class='text-xs'>".utf8_encode($result['tarea_asignada_call_center'])."</th>
              <th class='text-xs'>".utf8_encode($result['gestionada_call_center'])."</th>
              <th class='text-xs'>".utf8_encode($result['ultimo_call_center_gestion'])."</th>
              <th class='text-xs'>".utf8_encode($result['ultima_tarea_call_center'])."</th>
              <th class='text-xs'>".utf8_encode($result['activa_call_center'])."</th>
              <th class='text-xs'>".utf8_encode($result['telefono_local'])."</th>
              <th class='text-xs'>".utf8_encode($result['telefono_celular'])."</th>
              <th class='text-xs'>".utf8_encode($result['telefono_radio'])."</th>
              <th class='text-xs'>".utf8_encode($result['telefono_local_usuario'])."</th>
              <th class='text-xs'>".utf8_encode($result['telefono_celular_usuario'])."</th>
              <th class='text-xs'>".utf8_encode($result['telefono_radio_usuario'])."</th>
              <th class='text-xs'>".utf8_encode($result['reductores_asignado'])."</th>
              <th class='text-xs'>".utf8_encode($result['tarea_asignada_reductores'])."</th>
              <th class='text-xs'>".utf8_encode($result['gestionada_reductores'])."</th>
              <th class='text-xs'>".utf8_encode($result['ultimo_reductor_gestion'])."</th>
              <th class='text-xs'>".utf8_encode($result['ultima_tarea_reductores'])."</th>
              <th class='text-xs'>".utf8_encode($result['activa_reductores'])."</th>
              <th class='text-xs'>".utf8_encode($result['ultimo_carta_invitacion_gestion'])."</th>
              <th class='text-xs'>".utf8_encode($result['ultima_tarea_carta_invitacion'])."</th>
              <th class='text-xs'>".utf8_encode($result['ultimo_sepomex_gestion'])."</th>
              <th class='text-xs'>".utf8_encode($result['ultima_tarea_sepomex'])."</th>
              <th class='text-xs'>".utf8_encode($result['ultimo_pregrabada_gestion'])."</th>
              <th class='text-xs'>".utf8_encode($result['ultima_tarea_pregrabada'])."</th>
              <th class='text-xs'>".utf8_encode($result['ultimo_inspeccion_gestion'])."</th>
              <th class='text-xs'>".utf8_encode($result['ultima_tarea_inspeccion'])."</th>
              <th class='text-xs'>".utf8_encode($result['sup_terreno_h'])."</th>
              <th class='text-xs'>".utf8_encode($result['sup_construccion_h'])."</th>
              <th class='text-xs'>".utf8_encode($result['valor_terreno_h'])."</th>
              <th class='text-xs'>".utf8_encode($result['valor_construccion_h'])."</th>
              <th class='text-xs'>".utf8_encode($result['valor_catastral_h'])."</th>
              <th class='text-xs'>".utf8_encode($result['sup_terreno_valuado'])."</th>
              <th class='text-xs'>".utf8_encode($result['sup_construccion_valuado'])."</th>
              <th class='text-xs'>".utf8_encode($result['valor_terreno_valuado'])."</th>
              <th class='text-xs'>".utf8_encode($result['valor_construccion_valuado'])."</th>
              <th class='text-xs'>".utf8_encode($result['valor_catastral_valuado'])."</th>
              <th class='text-xs'>".utf8_encode($result['casilla'])."</th>
            </tr>";
        }
        echo " </tbody>
        </table>";
        
    }
}