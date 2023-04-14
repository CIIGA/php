<?php
session_start();
if ((isset($_SESSION['user']))) {
    require "db/conexion.php";
    require "db/conexion_d.php";
    //consulto el id plaza de servicion web asi como la base de datosn que corresponde
    $id_plaza = $_GET['id_plaza'];
    $sql_datos="SELECT plaza.nombreplaza,proveniente.data,proveniente.id_plaza_servicioWeb FROM plaza
    inner join proveniente on plaza.id_proveniente=proveniente.id_proveniente
    where plaza.id_plaza='$id_plaza'";
    $cnx_datos = sqlsrv_query($cnx, $sql_datos);
    $datos = sqlsrv_fetch_array($cnx_datos);
    //obtengo el nombre de la base de datos
    $nombredb=$datos['data'];
    //obtengo las fechas
    $ini=date('Y-m-d');
    $fin=date('Y-m-d');

    //en una variable mando a llamar la conexion y le paso el nombre de la base de datos como parametro
    $cnxa=conexion($nombredb);
    // ejecuto mi store con la conexion que le corresponde
    $store="execute [dbo].[sp_cuenta_vencida_detalle_actual] '$ini','$fin',1";
    $st=sqlsrv_query($cnxa,$store) or die ('Execute Stored Procedure Failed... Query store.php');
    $resultSt=sqlsrv_fetch_array($st);

    
    
    
?>
    <!DOCTYPE html>
    <html>

    <hxead>
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Plazas | KPIs</title>
        <link rel="icon" href="../../icono/icon.png">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="../../css/bootstrap.css">
        <link href="../../fontawesome/css/all.css" rel="stylesheet">
        <link href="css/index.css" rel="stylesheet">
        <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <?php require "include/nav.php"; ?>
    </hxead>

    <body>
       <?php  
       //valido si se ejecuto el primer store
    //    si el resultado es diferente de 1 manda una alerta
       if($resultSt['resultado']!=1){
        echo "<script>
            Swal.fire({
              icon: 'error',
              title: 'error!',
              text: 'Error al procesar la petición', 
             time:80000,
              showConfirmButton: false,
              })
             window.location= history.back()
    </script>"; 
    echo "<script>
            
             window.location= history.back()
    </script>"; 
    // en caso contrario me manda al modulo
    } else{
        //ejecuto mi otro store pasandole el id plaza
    $nombredb2='kpimplementta';
    $id_plaza_servicioWeb=$datos['id_plaza_servicioWeb'];
   
    //en una variable mando a llamar la conexion y le paso el nombre de la base de datos como parametro
    $cnxa2=conexion($nombredb2);
    // ejecuto mi store con la conexion que le corresponde
    $store2="execute [dbo].[sp_vencidaskpis] '$id_plaza_servicioWeb'";
    $st2=sqlsrv_query($cnxa2,$store2) or die ('Execute Stored Procedure Failed... Query store.php');
    $resultSt2=sqlsrv_fetch_array($st2);
    
        ?>

        <div class="p-4">
        <div class="row">
            <div class="col-sm-12">
            <h5  style="text-shadow: 0px 0px 2px #717171;"><?php echo 'Plaza: ' . $datos['nombreplaza'] ?></h5>
            </div>
        </div>
        <hr>
        <div class="card">
            <div class="card-body">
                
                
                    <div class="row">
                        
                        <div class="col-sm-5 mt-2">
                            
                            <a target="_blank" class="btn btn-info btn-sm toDownload" href="#"><i class="fa fa-download"></i> Descargar Excel</a>
                            <!-- <a target="_blank" class="btn btn-info btn-sm toDownload" href="./excel.php?search=<?=$search?>&pagina=<?=$pagina?>"><i class="fa fa-download"></i> Descargar Pagina</a> -->
                            <!-- <a target="_blank" class="btn btn-warning btn-sm toDownload" href="./exceltodos.php"><i class="fa fa-download"></i> Descargar Todo</a> -->
                            <!-- <a class="btn btn-dark btn-sm" data-toggle="modal" data-target="#modal-upload-file-reporte" href="#"><i class="fa fa-upload"></i> Subir Datos</a> -->
                        </div>
                    </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" style="font-size: 11px;">
                        <thead>
                            <tr>
                                <th class="text-center th-title" colspan="18">SEMAFORO DE VENCIDAS</th>
                            </tr>
                            <tr>
                                <th class="p-1">NO. EXPEDIENTE</th>
                                <th class="p-1">CUENTA PREDIAL</th>
                                <th class="p-1">FECHA DE RESOLUCI&Oacute;N</th>
                                <th class="p-1">FECHA DE NOTIFICACI&Oacute;N DE RESOLUCI&Oacute;N </th>
                                <th class="p-1">ELABOR&Oacute;</th>
                                <th class="p-1">FECHA DE ELABORACI&Oacute;N</th>
                                <th class="p-1">NO. DE FOLIO</th>
                                <th class="p-1">FECHA FOLIO</th>
                                <th class="p-1">MONTO</th>
                                <th class="p-1">QUIEN FIRMA Y SELLA</th>
                                <th class="p-1">FECHA DE ENVIO CON GUSTAVO</th>
                                <th class="p-1">FECHA DE INGRESO CON TESORER&Iacute;A O DIR. INGRESOS</th>
                                <th class="p-1">FECHA DE REGRESO CON SELLO Y FIRMA</th>
                                <th class="p-1">FECHA DE ASIGNACI&Oacute;N</th>
                                <th class="p-1">NOTIFICADOR A QUIEN SE LE ASIGN&Oacute;</th>
                                <th class="p-1">FECHA DE NOTIFICACI&Oacute;N</th>
                                <th class="p-1">FECHA DE TIMBRADO</th>
                                <th class="p-1">ETAPA EN QUE SE ENCUENTRA</th>
                                <th class="p-1">ESTATUS FINAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php do { ?>
        
    
                            <tr>
                                <td class="p-1"></td>
                                <td class="p-1"></td>
                                <td class="p-1"></td>
                                <td class="p-1"></td>
                                <td class="p-1"></td>
                                <td class="p-1"></td>
                                <td class="p-1"></td>
                                <td class="p-1"></td>
                                
                    
                                <td class="p-1"></td>
                                
                                
                                <td class="p-1"></td>
                                <td class="p-1"></td>
                                <td class="p-1"></td>
                                <td class="p-1"></td>
                                <td class="p-1"></td>
                                <td class="p-1"></td>

                                <td class="p-1"></td>
                                
                                <td class="p-1"></td>
                                
                                <td class="p-1"></td>
                                <td class="p-1"></td>
                            </tr>
                        <?php } while ($resultSt2=sqlsrv_fetch_array($st2));?>
                        </tbody>
                        <tfoot>
                        
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php }?>
    </body>
<?php
} else {
    header('location:../../login.php');
}
require "include/footer.php";
?>

    </html>

