<?php 
ini_set('max_execution_time', 0);
require '../modules/estatusPadron.php';
//Se condiciona si se reciben como paarametro lo siguiente
if ((isset($_GET['plz'])) && (isset($_GET['base']))) {
    //Lo convertimos a variables
    $BD = $_GET['base'];
    $archivo = "estatusPadron.csv";
    //Contenido del archivo
    $cnx = conexionEstatusPadron($BD);
    $procedure = "select * FROM EstatusPadron'";
    $exec = sqlsrv_query($cnx, $procedure);
    $result = sqlsrv_fetch_array($exec);
    $contenido = "";
    if ($result) {
        $contenido =$contenido."cuenta Clave_Catastral ult_trimestre_pago propietario calle num_ext num_int colonia poblacion cp tipo_servicio caracteristica_predio giro serie_medidor deuda_total estatus_de_adeudo fecha_actualizacion fecha_corte rango fecha_ultimo_pago estatus efectos latitud longitud sectr Meses_Adeudo estatus_zona ruta secuencia manzana lote entre_calle_1 entre_calle_2 Referencia razon_social calle_notificacion num_ext_notificacion colonia_notificacion cp_notificacion poblacion_notificacion pagos total_pagado sepomex gestor_asignado tarea_asignada_gestor gestionada_gestor ultimo_gestor_gestion ultima_tarea_gestor observaciones_gestor fecha_captura_gestor activa_gestor abogado_asignado tarea_asignada_abogado gestionada_abogado ultimo_abogado_gestion ultima_tarea_abogado observaciones_abogado fecha_captura_abogado activa_abogado call_center_asignado tarea_asignada_call_center gestionada_call_center ultimo_call_center_gestion ultima_tarea_call_center activa_call_center telefono_local telefono_celular telefono_radio telefono_local_usuario telefono_celular_usuario telefono_radio_usuario reductores_asignado tarea_asignada_reductores gestionada_reductores ultimo_reductor_gestion ultima_tarea_reductores activa_reductores ultimo_carta_invitacion_gestion ultima_tarea_carta_invitacion ultimo_sepomex_gestion ultima_tarea_sepomex ultimo_pregrabada_gestion ultima_tarea_pregrabada ultimo_inspeccion_gestion ultima_tarea_inspeccion sup_terreno_h sup_construccion_h valor_terreno_h valor_construccion_h valor_catastral_h sup_terreno_valuado sup_construccion_valuado valor_terreno_valuado valor_construccion_valuado valor_catastral_valuado casilla \r\n";
        while ($result = sqlsrv_fetch_array($exec)) {
            $contenido =$contenido.
               utf8_encode($result['cuenta']).
               ",". utf8_encode($result['Clave_Catastral']).
               ",". utf8_encode($result['ult_trimestre_pago']).
               ",". utf8_encode($result['propietario']).
               ",". utf8_encode($result['calle']).
               ",". utf8_encode($result['num_ext']).
               ",". utf8_encode($result['num_int']).
               ",". utf8_encode($result['colonia']).
               ",". utf8_encode($result['poblacion']).
               ",". utf8_encode($result['cp']).
               ",". utf8_encode($result['tipo_servicio']).
               ",". utf8_encode($result['caracteristica_predio']).
               ",". utf8_encode($result['giro']).
               ",". utf8_encode($result['serie_medidor']).
               ",". utf8_encode($result['deuda_total']).
               ",". utf8_encode($result['estatus_de_adeudo']).
               ",". utf8_encode($result['fecha_actualizacion']->format('d/m/Y')).
               ",". utf8_encode($result['fecha_corte']->format('d/m/Y')).
               ",". utf8_encode($result['rango']).
               ",". utf8_encode($result['fecha_ultimo_pago']->format('d/m/Y')).
               ",". utf8_encode($result['estatus']).
               ",". utf8_encode($result['efectos']).
               ",". utf8_encode($result['latitud']).
               ",". utf8_encode($result['longitud']).
               ",". utf8_encode($result['sectr']).
               ",". utf8_encode($result['Meses_Adeudo']).
               ",". utf8_encode($result['estatus_zona']).
               ",". utf8_encode($result['ruta']).
               ",". utf8_encode($result['secuencia']).
               ",". utf8_encode($result['manzana']).
               ",". utf8_encode($result['lote']).
               ",". utf8_encode($result['entre_calle_1']).
               ",". utf8_encode($result['entre_calle_2']).
               ",". utf8_encode($result['Referencia']).
               ",". utf8_encode($result['razon_social']).
               ",". utf8_encode($result['calle_notificacion']).
               ",". utf8_encode($result['num_ext_notificacion']).
               ",". utf8_encode($result['colonia_notificacion']).
               ",". utf8_encode($result['cp_notificacion']).
               ",". utf8_encode($result['poblacion_notificacion']).
               ",". utf8_encode($result['pagos']).
               ",". utf8_encode($result['total_pagado']).
               ",". utf8_encode($result['sepomex']).
               ",". utf8_encode($result['gestor_asignado']).
               ",". utf8_encode($result['tarea_asignada_gestor']).
               ",". utf8_encode($result['gestionada_gestor']).
               ",". utf8_encode($result['ultimo_gestor_gestion']).
               ",". utf8_encode($result['ultima_tarea_gestor']).
               ",". utf8_encode($result['observaciones_gestor']).
               ",". utf8_encode($result['fecha_captura_gestor']->format('d/m/Y')).
               ",". utf8_encode($result['activa_gestor']).
               ",". utf8_encode($result['abogado_asignado']).
               ",". utf8_encode($result['tarea_asignada_abogado']).
               ",". utf8_encode($result['gestionada_abogado']).
               ",". utf8_encode($result['ultimo_abogado_gestion']).
               ",". utf8_encode($result['ultima_tarea_abogado']).
               ",". utf8_encode($result['observaciones_abogado']).
               ",". utf8_encode($result['fecha_captura_abogado']->format('d/m/Y')).
               ",". utf8_encode($result['activa_abogado']).
               ",". utf8_encode($result['call_center_asignado']).
               ",". utf8_encode($result['tarea_asignada_call_center']).
               ",". utf8_encode($result['gestionada_call_center']).
               ",". utf8_encode($result['ultimo_call_center_gestion']).
               ",". utf8_encode($result['ultima_tarea_call_center']).
               ",". utf8_encode($result['activa_call_center']).
               ",". utf8_encode($result['telefono_local']).
               ",". utf8_encode($result['telefono_celular']).
               ",". utf8_encode($result['telefono_radio']).
               ",". utf8_encode($result['telefono_local_usuario']).
               ",". utf8_encode($result['telefono_celular_usuario']).
               ",". utf8_encode($result['telefono_radio_usuario']).
               ",". utf8_encode($result['reductores_asignado']).
               ",". utf8_encode($result['tarea_asignada_reductores']).
               ",". utf8_encode($result['gestionada_reductores']).
               ",". utf8_encode($result['ultimo_reductor_gestion']).
               ",". utf8_encode($result['ultima_tarea_reductores']).
               ",". utf8_encode($result['activa_reductores']).
               ",". utf8_encode($result['ultimo_carta_invitacion_gestion']).
               ",". utf8_encode($result['ultima_tarea_carta_invitacion']).
               ",". utf8_encode($result['ultimo_sepomex_gestion']).
               ",". utf8_encode($result['ultima_tarea_sepomex']).
               ",". utf8_encode($result['ultimo_pregrabada_gestion']).
               ",". utf8_encode($result['ultima_tarea_pregrabada']).
               ",". utf8_encode($result['ultimo_inspeccion_gestion']).
               ",". utf8_encode($result['ultima_tarea_inspeccion']).
               ",". utf8_encode($result['sup_terreno_h']).
               ",". utf8_encode($result['sup_construccion_h']).
               ",". utf8_encode($result['valor_terreno_h']).
               ",". utf8_encode($result['valor_construccion_h']).
               ",". utf8_encode($result['valor_catastral_h']).
               ",". utf8_encode($result['sup_terreno_valuado']).
               ",". utf8_encode($result['sup_construccion_valuado']).
               ",". utf8_encode($result['valor_terreno_valuado']).
               ",". utf8_encode($result['valor_construccion_valuado']).
               ",". utf8_encode($result['valor_catastral_valuado']).
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