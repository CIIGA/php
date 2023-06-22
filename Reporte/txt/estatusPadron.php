<?php 
ini_set('max_execution_time', 0);
require '../modules/estatusPadron.php';
//Se condiciona si se reciben como paarametro lo siguiente
if ((isset($_GET['plz'])) && (isset($_GET['base']))) {
    //Lo convertimos a variables
    $BD = $_GET['base'];
    $archivo = "EstatusPadron".$BD.".csv";
    //Contenido del archivo
    $cnx = conexionEstatusPadron($BD);
    $procedure = "select * FROM EstatusPadron'";
    $exec = sqlsrv_query($cnx, $procedure);
    $result = sqlsrv_fetch_array($exec);
    $contenido = "";
    if ($result) {
        $contenido =$contenido."Cuenta \tClave_Catastral \tult_trimestre_pago \tpropietario \tcalle \tnum_ext \tnum_int \tcolonia \tpoblacion \tcp \ttipo_servicio \tcaracteristica_predio \tgiro \tserie_medidor \tdeuda_total \testatus_de_adeudo \tfecha_actualizacion \tfecha_corte \trango \tfecha_ultimo_pago \testatus \tefectos \tlatitud \tlongitud \tsector \tMeses_Adeudo \testatus_zona \truta \tsecuencia \tmanzana \tlote \tentre_calle_1 \tentre_calle_2 \tReferencia \trazon_social \tcalle_notificacion \tnum_ext_notificacion \tcolonia_notificacion \tcp_notificacion \tpoblacion_notificacion \tpagos \ttotal_pagado \tsepomex \tgestor_asignado \ttarea_asignada_gestor \tgestionada_gestor \tultimo_gestor_gestion \tultima_tarea_gestor \tobservaciones_gestor \tfecha_captura_gestor \tactiva_gestor \tabogado_asignado \ttarea_asignada_abogado \tgestionada_abogado \tultimo_abogado_gestion \tultima_tarea_abogado \tobservaciones_abogado \tfecha_captura_abogado \tactiva_abogado \tcall_center_asignado \ttarea_asignada_call_center \tgestionada_call_center \tultimo_call_center_gestion \tultima_tarea_call_center \tactiva_call_center \ttelefono_local \ttelefono_celular \ttelefono_radio \ttelefono_local_usuario \ttelefono_celular_usuario \ttelefono_radio_usuario \treductores_asignado \ttarea_asignada_reductores \tgestionada_reductores \tultimo_reductor_gestion \tultima_tarea_reductores \tactiva_reductores \tultimo_carta_invitacion_gestion \tultima_tarea_carta_invitacion \tultimo_sepomex_gestion \tultima_tarea_sepomex \tultimo_pregrabada_gestion \tultima_tarea_pregrabada \tultimo_inspeccion_gestion \tultima_tarea_inspeccion \tsup_terreno_h \tsup_construccion_h \tvalor_terreno_h \tvalor_construccion_h \tvalor_catastral_h \tsup_terreno_valuado \tsup_construccion_valuado \tvalor_terreno_valuado \tvalor_construccion_valuado \tvalor_catastral_valuado \tcasilla \r\n";
        while ($result = sqlsrv_fetch_array($exec)) {
            $contenido =$contenido.
               utf8_encode($result['cuenta'])."\t".
               ",". utf8_encode($result['Clave_Catastral'])."\t".
               ",". utf8_encode($result['ult_trimestre_pago'])."\t".
               ",". utf8_encode($result['propietario'])."\t".
               ",". utf8_encode($result['calle'])."\t".
               ",". utf8_encode($result['num_ext'])."\t".
               ",". utf8_encode($result['num_int'])."\t".
               ",". utf8_encode($result['colonia'])."\t".
               ",". utf8_encode($result['poblacion'])."\t".
               ",". utf8_encode($result['cp'])."\t".
               ",". utf8_encode($result['tipo_servicio'])."\t".
               ",". utf8_encode($result['caracteristica_predio'])."\t".
               ",". utf8_encode($result['giro'])."\t".
               ",". utf8_encode($result['serie_medidor'])."\t".
               ",". utf8_encode($result['deuda_total'])."\t".
               ",". utf8_encode($result['estatus_de_adeudo'])."\t".
               ",". utf8_encode($result['fecha_actualizacion']->format('d/m/Y'))."\t".
               ",". utf8_encode($result['fecha_corte']->format('d/m/Y'))."\t".
               ",". utf8_encode($result['rango'])."\t".
               ",". utf8_encode($result['fecha_ultimo_pago']->format('d/m/Y'))."\t".
               ",". utf8_encode($result['estatus'])."\t".
               ",". utf8_encode($result['efectos'])."\t".
               ",". utf8_encode($result['latitud'])."\t".
               ",". utf8_encode($result['longitud'])."\t".
               ",". utf8_encode($result['sectr'])."\t".
               ",". utf8_encode($result['Meses_Adeudo'])."\t".
               ",". utf8_encode($result['estatus_zona'])."\t".
               ",". utf8_encode($result['ruta'])."\t".
               ",". utf8_encode($result['secuencia'])."\t".
               ",". utf8_encode($result['manzana'])."\t".
               ",". utf8_encode($result['lote'])."\t".
               ",". utf8_encode($result['entre_calle_1'])."\t".
               ",". utf8_encode($result['entre_calle_2'])."\t".
               ",". utf8_encode($result['Referencia'])."\t".
               ",". utf8_encode($result['razon_social'])."\t".
               ",". utf8_encode($result['calle_notificacion'])."\t".
               ",". utf8_encode($result['num_ext_notificacion'])."\t".
               ",". utf8_encode($result['colonia_notificacion'])."\t".
               ",". utf8_encode($result['cp_notificacion'])."\t".
               ",". utf8_encode($result['poblacion_notificacion'])."\t".
               ",". utf8_encode($result['pagos'])."\t".
               ",". utf8_encode($result['total_pagado'])."\t".
               ",". utf8_encode($result['sepomex'])."\t".
               ",". utf8_encode($result['gestor_asignado'])."\t".
               ",". utf8_encode($result['tarea_asignada_gestor'])."\t".
               ",". utf8_encode($result['gestionada_gestor'])."\t".
               ",". utf8_encode($result['ultimo_gestor_gestion'])."\t".
               ",". utf8_encode($result['ultima_tarea_gestor'])."\t".
               ",". utf8_encode($result['observaciones_gestor'])."\t".
               ",". utf8_encode($result['fecha_captura_gestor']->format('d/m/Y'))."\t".
               ",". utf8_encode($result['activa_gestor'])."\t".
               ",". utf8_encode($result['abogado_asignado'])."\t".
               ",". utf8_encode($result['tarea_asignada_abogado'])."\t".
               ",". utf8_encode($result['gestionada_abogado'])."\t".
               ",". utf8_encode($result['ultimo_abogado_gestion'])."\t".
               ",". utf8_encode($result['ultima_tarea_abogado'])."\t".
               ",". utf8_encode($result['observaciones_abogado'])."\t".
               ",". utf8_encode($result['fecha_captura_abogado']->format('d/m/Y'))."\t".
               ",". utf8_encode($result['activa_abogado'])."\t".
               ",". utf8_encode($result['call_center_asignado'])."\t".
               ",". utf8_encode($result['tarea_asignada_call_center'])."\t".
               ",". utf8_encode($result['gestionada_call_center'])."\t".
               ",". utf8_encode($result['ultimo_call_center_gestion'])."\t".
               ",". utf8_encode($result['ultima_tarea_call_center'])."\t".
               ",". utf8_encode($result['activa_call_center'])."\t".
               ",". utf8_encode($result['telefono_local'])."\t".
               ",". utf8_encode($result['telefono_celular'])."\t".
               ",". utf8_encode($result['telefono_radio'])."\t".
               ",". utf8_encode($result['telefono_local_usuario'])."\t".
               ",". utf8_encode($result['telefono_celular_usuario'])."\t".
               ",". utf8_encode($result['telefono_radio_usuario'])."\t".
               ",". utf8_encode($result['reductores_asignado'])."\t".
               ",". utf8_encode($result['tarea_asignada_reductores'])."\t".
               ",". utf8_encode($result['gestionada_reductores'])."\t".
               ",". utf8_encode($result['ultimo_reductor_gestion'])."\t".
               ",". utf8_encode($result['ultima_tarea_reductores'])."\t".
               ",". utf8_encode($result['activa_reductores'])."\t".
               ",". utf8_encode($result['ultimo_carta_invitacion_gestion'])."\t".
               ",". utf8_encode($result['ultima_tarea_carta_invitacion'])."\t".
               ",". utf8_encode($result['ultimo_sepomex_gestion'])."\t".
               ",". utf8_encode($result['ultima_tarea_sepomex'])."\t".
               ",". utf8_encode($result['ultimo_pregrabada_gestion'])."\t".
               ",". utf8_encode($result['ultima_tarea_pregrabada'])."\t".
               ",". utf8_encode($result['ultimo_inspeccion_gestion'])."\t".
               ",". utf8_encode($result['ultima_tarea_inspeccion'])."\t".
               ",". utf8_encode($result['sup_terreno_h'])."\t".
               ",". utf8_encode($result['sup_construccion_h'])."\t".
               ",". utf8_encode($result['valor_terreno_h'])."\t".
               ",". utf8_encode($result['valor_construccion_h'])."\t".
               ",". utf8_encode($result['valor_catastral_h'])."\t".
               ",". utf8_encode($result['sup_terreno_valuado'])."\t".
               ",". utf8_encode($result['sup_construccion_valuado'])."\t".
               ",". utf8_encode($result['valor_terreno_valuado'])."\t".
               ",". utf8_encode($result['valor_construccion_valuado'])."\t".
               ",". utf8_encode($result['valor_catastral_valuado'])."\t".
               ",". utf8_encode($result['casilla'])."\r\n";
        }
    }
    //Generamos el archivo y decimos que se va a escribir
    $f = fopen($archivo, "w");
    //Se escribe el contenido
    fwrite($f, $contenido);
    //Se cierra el archivo
    fclose($f);
    //El enlace a descargar del navegador es el mismo que se guarda en el servidor
    $enlace = $archivo;
    header("Content-Disposition: attachment; filename=" . $enlace);
    header("Content-Type: application/octet-stream");
    header("Content-Length: " . filesize($enlace));
    readfile($enlace);
    //Se elimina el archivo desde el servidor para que no se encuentre
    unlink($archivo);
}