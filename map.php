<!DOCTYPE html>
<?php
session_start();
if (isset($_SESSION['user'])) {
  require "include/lib.php";
  require "../../acnxerdm/cnx.php";
  require "include/conexion_d.php";
  $plz = $_GET['plz'];

  $maNL = "select * from subregistro
    inner join plaza on plaza.id_plaza=subregistro.id_plaza
    where subregistro.id_plaza='$plz'";
  $mapNL = sqlsrv_query($cnx, $maNL);
  $mapaNL = sqlsrv_fetch_array($mapNL);

  $sql_estado = "select estado from plaza
  where id_plaza='$plz'";
  $estado_cnx = sqlsrv_query($cnx, $sql_estado);
  $estado = sqlsrv_fetch_array($estado_cnx);

  if (isset($_GET['mp'])) {
    $idkpi = $_GET['mp'];
    $ma = "SELECT * FROM plaza
    inner join subregistro on subregistro.id_plaza=plaza.id_plaza
    inner join kpi on kpi.id_subregistro=subregistro.id_subregistro
    where plaza.id_plaza='$plz' AND kpi.id_kpi='$idkpi'";
    $map = sqlsrv_query($cnx, $ma);
    $mapa = sqlsrv_fetch_array($map);
  }
  //semaforo de vencidas
  //validamos si se recibe el id plaza
  if (isset($_GET['id_plaza'])) {
  // lo guardamos en una variable
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
    $st=sqlsrv_query($cnxa,$store) or die ('Execute Stored Procedure Failed... Query map.php [sp_cuenta_vencida_detalle_actual]');
    $resultSt=sqlsrv_fetch_array($st);
    if($resultSt['resultado']!=1){
        echo '<script> alert("ERROR.")</script>';
        echo '<meta http-equiv="refresh" content="0,url=map.php?plz='.$plz.'">';
    }
    else{
      header('location:vencidas.php?id_plaza_servicioWeb='.$datos['id_plaza_servicioWeb'].'&nombre_plz='.$datos['nombreplaza']);
    }
}
?>
  <html lang="en">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>KPIs | Estrategas</title>
    <link rel="icon" href="../icono/icon.png">
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="../js/peticionAjax.js"></script>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.css"
        id="theme-styles">
    
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.css" id="theme-styles">
   
    <style>
      body {
        background-image: url(../img/back.jpg);
        background-repeat: no-repeat;
        background-size: 100%;
        background-attachment: fixed;
        /*        overflow-x:hidden;*/
        /*        overflow-y:hidden;*/
      }

      body {
        font-family: sans-serif;
        font-style: normal;
        font-weight: bold;
        font-size: 95%;
      }

      .jumbotron {
        background: rgba(83, 83, 83, 0.2);
      }

      .btn-whatsapp {
        display: block;
        width: 70px;
        height: 70px;
        color: #fff;
        position: fixed;
        left: 20px;
        bottom: 20px;
        border-radius: 50%;
        line-height: 80px;
        text-align: center;
        z-index: 999;
      }
    </style>
  </head>

  <body>
    <div class="d-flex" id="wrapper">
      <!-- Sidebar-->
      <div class="border-end bg-white" id="sidebar-wrapper">
        <div class="sidebar-heading border-bottom bg-light"><a href="acceso.php"><img src="../img/logoKPI.png" height="80" alt=""></a></div><br>
        <h5 style="text-shadow: 0px 0px 2px #717171;text-align:center;"><?php echo 'Plaza: ' . utf8_encode($mapaNL['nombreplaza']) ?></h5>
        <div class="list-group list-group-flush">
          <?php do { ?>
            <div id="accordion">
              <div class="card">
                <div class="card-header" id="headingTwo">
                  <h5 class="mb-0">
                    <button class="btn collapsed" data-toggle="collapse" data-target="#collapse<?php echo $mapaNL['id_subregistro'] ?>" aria-expanded="false" aria-controls="collapseTwo">
                      <h6 style="text-shadow: 0px 0px 2px #717171;"><i class="fas fa-chevron-right"></i> <?php echo utf8_encode($mapaNL['nombreSub']) ?></h6>
                    </button>
                  </h5>
                </div>
                <div id="collapse<?php echo $mapaNL['id_subregistro'] ?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">

                  <?php
                  $idSubreg = $mapaNL['id_subregistro'];
                  $kp = "select * from kpi
    inner join subregistro on subregistro.id_subregistro=kpi.id_subregistro
    where subregistro.id_subregistro='$idSubreg'";
                  $kpi = sqlsrv_query($cnx, $kp);
                  $kpis = sqlsrv_fetch_array($kpi);
                  ?>
                  <?php do { ?>
                    <div class="card-body">
                      <a class="list-group-item list-group-item-action list-group-item-light p-3" href="map.php?plz=<?php echo $_GET['plz'] . '&mp=' . $kpis['id_kpi'] ?>"><?php echo utf8_encode($kpis['nombreKpi']) ?></a>
                    </div>
                  <?php } while ($kpis = sqlsrv_fetch_array($kpi)); ?>

                </div>
              </div>
            </div>
          <?php } while ($mapaNL = sqlsrv_fetch_array($mapNL)); ?>
          <!-- Menus Estaticos -->
          <!-- <div>
            <div class="card-header" id="headingTwo">
              <h5 class="mb-0">
                <button class="btn collapsed" data-toggle="collapse" data-target="#collapse" aria-expanded="false" aria-controls="collapseTwo">
                  <h6 style="text-shadow: 0px 0px 2px #717171;"><i class="fas fa-chevron-right"></i> Reportes</h6>
                </button>
              </h5>
            </div>
            <div id="collapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
              <div class="card-body">
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href=""> CallCenter</a>
              </div>
            </div>
            <div id="collapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
              <div class="card-body">
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href=""> Pregrabadas</a>
              </div>
            </div>
            <div id="collapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
              <div class="card-body">
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href=""> Telefonos para Pregrabar</a>
              </div>
            </div>
          </div> -->
          <!-- Fin de Menus Estaticos -->
        </div>
      </div>
      <!-- Page content wrapper-->
      <div id="page-content-wrapper">
        <div class="btn-whatsapp">
          <button class="btn btn-link" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- <ul class="navbar-nav mr-auto">
              <?php if($estado['estado']==1){ ?>
                <li class="nav-item">
                <form method="GET" onsubmit="javascript:loadInfo();" autocomplete="off">
                  <a target="_blank" class="btn nav-link btn-sm toDownload" href="map.php?id_plaza=<?php echo $plz ?>&plz=<?php echo $plz ?>"><i class="fa fa-download"></i> Semaforo de vencidas</a>
                  </form>
                </li>
              <?php }?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Reportes
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="#">CallCenter</a>
                  <a class="dropdown-item" href="#">Pregrabadas</a>
                  <a class="dropdown-item" href="#">Telefonos Para Grabar</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">Sincronización</a>
                  <a class="dropdown-item" href="#">Bono</a>
                </div>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Padron
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="#">Estatus</a>
                  <a class="dropdown-item" href="#">Adeudo</a>
                  <a class="dropdown-item" href="#">Telefonos</a>
                </div>
              </li>
            </ul> -->
            <?php
                if (isset($_GET['error'])) {
                    $alert = ($_GET['error'] == 1) ? 'alert-danger' : 'alert-success';
                    $msg = $_GET['msg']; ?>
                    <div class="alert <?= $alert ?>"><strong><?=$msg?></strong></div>
                <?php } ?>
          </div>
        </nav>
        <!-- Page content-->
        <?php if (isset($_GET['mp'])) { ?>
          <div class="embed-responsive embed-responsive-16by9">
            <iframe src="<?php echo $mapa['url'] ?>" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
          </div>
        <?php } else { ?>
          <div class="alert alert-dark" role="alert"><i class="fas fa-chevron-left"></i> Seleccione una opción de KPI</div>
        <?php } ?>
      </div>
    </div>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="../js/scripts.js"></script>
    <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.fileDownload/1.4.2/jquery.fileDownload.min.js"></script>
    <script>
        var loadInfo = function () {
            Swal.fire({
                title: 'Obteniendo Datos',
                html: 'Espere un momento porfavor...',
                timer: 0,
                timerProgressBar: true,
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                willClose: () => {
                    return false;
                }
                }).then((result) => {}
            );
        }
        $('.toDownload').on('click', function () {
            toDownload($(this).attr('href'));
            return false;
        });

        var toDownload = function (url) {
            $.fileDownload(url, {
                successCallback: function (url) {
                    Swal.fire('Archivo Descargado', '', 'success' );
                },
                failCallback: function () {
                    Swal.fire('No se pudo descargar el archivo', '', 'error' );
                },
                prepareCallback: function () {
                    Swal.fire({
                        title: 'Generando Archivo Excel',
                        html: 'Espere un momento porfavor.',
                        timer: 0,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    }).then((result) => { })
                }
            });
        }
    </script>
  </body>
<?php
} else {
  header('location:../../login.php');
}
// require "include/footer.php"; 
?>

  </html>