<?php
session_start();
if ((isset($_SESSION['user'])) and (isset($_SESSION['tipousuario']))) {
    require "../../acnxerdm/cnx.php";
    $pl = "SELECT * FROM plaza
    left join proveniente on proveniente.id_proveniente=plaza.id_proveniente";
    $plz = sqlsrv_query($cnx, $pl);
    $plaza = sqlsrv_fetch_array($plz);

    $pro = "SELECT * FROM proveniente";
    $prov = sqlsrv_query($cnx, $pro);
    $prove = sqlsrv_fetch_array($prov);
    //*********************************** INICIO INSERT PLZ *******************************************************
    if (isset($_POST['save'])) {
        $nombre = $_POST['nombre'];
        $img = $_POST['image'];
        $prov = $_POST['proven'];

        $val = "select * from plaza
    where nombreplaza='$nombre'";
        $vali = sqlsrv_query($cnx, $val);
        $valida = sqlsrv_fetch_array($vali);
        if ($valida) {
            echo '<script>alert("El nombre de plaza ya esta agregado. \nVerifique registro")</script>';
            echo '<meta http-equiv="refresh" content="0,url=addplz.php">';
        } else {
            $unidad = "insert into plaza (id_proveniente,nombreplaza,img) values ('$prov','$nombre','$img')";
            sqlsrv_query($cnx, $unidad) or die('No se ejecuto la consulta isert nueva plz');
            echo '<script>alert("Plaza agregada correctamente")</script>';
            echo '<meta http-equiv="refresh" content="0,url=addplz.php">';
        }
    }
    //************************ FIN INSERT PLZ ******************************************************************
    //****************************ACTUALIZAR DATOS DE USUARIO******************************************************
    if (isset($_POST['update'])) {
        $idplaza = $_POST['idplz'];
        $name = $_POST['nombreplz'];
        $imagen = $_POST['imagen'];
        $prov = $_POST['prov'];

        $datos = "update plaza set id_proveniente='$prov', nombreplaza='$name', img='$imagen'
    where id_plaza='$idplaza'";
        sqlsrv_query($cnx, $datos) or die('No se ejecuto la consulta update datosart');
        echo '<script> alert("Registro actulizado correctamente.")</script>';
        echo '<meta http-equiv="refresh" content="0,url=addplz.php">';
    }
    //****************************FIN ACTUALIAR DATOS DE USUARIO***************************************************    
    //****************************ACTUALIZAR ESTADO PLAZA******************************************************
    if (isset($_GET['idpl'])) {
        $idplaza = $_GET['idpl'];
        $estado = $_GET['estado'];
        if ($estado == 1) {
            $estado = 0;
        } else {
            $estado = 1;
        }


        $updateestado = "update plaza set estado='$estado'
    where id_plaza='$idplaza'";
        sqlsrv_query($cnx, $updateestado) or die('No se ejecuto la consulta update datosart');

        echo '<script> alert(Estado actulizado correctamente.")</script>';
        echo '<meta http-equiv="refresh" content="0,url=addplz.php">';
    }
    //****************************FIN ACTUALIAR DATOS DE USUARIO***************************************************    
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Plazas | KPIs</title>
        <link rel="icon" href="../icono/icon.png">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="../css/bootstrap.css">
        <link href="../fontawesome/css/all.css" rel="stylesheet">
        <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="../js/peticionAjax.js"></script>
        <style>
            body {
                background-image: url(../img/back.jpg);
                background-repeat: repeat;
                background-size: 100%;
                /*        background-attachment: fixed;*/
                overflow-x: hidden;
                /* ocultar scrolBar horizontal*/
            }

            body {
                font-family: sans-serif;
                font-style: normal;
                font-weight: bold;
                width: 100%;
                height: 100%;
                margin-top: -1%;
                padding-top: 0px;
            }

            .jumbotron {
                margin-top: 0%;
                margin-bottom: 0%;
                padding-top: 3%;
                padding-bottom: 2%;
            }

            .padding {
                padding-right: 35%;
                padding-left: 35%;
            }
        </style>
        <?php require "include/nav.php"; ?>
    </head>

    <body>
        <div class="container">
            <h1 style="text-shadow: 1px 1px 2px #717171;">Plataforma de KPIs</h1>
            <h4 style="text-shadow: 1px 1px 2px #717171;"><i class="far fa-building"></i> Agregar nueva plaza</h4>
            <form action="" method="post">
                <div class="jumbotron">
                    <div class="form-group" style="text-align:center;">
                        <label for="exampleInputEmail1">Nombre de la plaza: *</label>
                        <input style="text-align:center;" type="text" class="form-control" name="nombre" placeholder="Nombre de la nueva plaza" required autofocus>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="md-form form-group">
                                <label for="exampleInputEmail1">Origen de datos: *</label>
                                <select name="proven" class="form-control" required>
                                    <option value="">Selecciona una opcion</option>
                                    <?php do { ?>
                                        <option value="<?php echo $prove['id_proveniente'] ?>"><?php echo utf8_encode($prove['nombreProveniente']) ?></option>
                                    <?php } while ($prove = sqlsrv_fetch_array($prov)); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="exampleInputEmail1">Direccion URL de imagen: *</label>
                            <input style="text-align:center;" type="text" class="form-control" name="image" placeholder="Direccion url de imagen icono" required autofocus>
                        </div>
                    </div>
                    <small id="e" class="form-text text-muted" style="font-size:14px;">*Todos los campos son requeridos.</small>
                    <div style="text-align:right;">
                        <button type="submit" class="btn btn-primary btn-sm" name="save"><i class="fas fa-plus"></i> Agregar nueva plaza</button>
                    </div>
                </div>
            </form>
            <hr>
            <div style="text-align:left;">
                <a href="origen.php" class="btn btn-dark btn-sm"><i class="fas fa-database"></i> Nuevo origen de datos</a>
            </div>
            <hr>
            <h3 style="text-shadow: 1px 1px 2px #717171;"><i class="fas fa-wrench"></i> Editar plazas</h3>
            <hr>
        </div>
        <div class="container">
            <?php if (isset($plaza)) { ?>
                <table class="table table-sm table-hover">
                    <thead>
                        <tr align="center">
                            <th scope="col">Estado</th>
                            <th scope="col">Actualizar</th>
                            <th scope="col">Plaza</th>
                            <th scope="col">Sincronización</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php do { ?>
                            <tr>
                                <?php if ($plaza['estado'] == 1) { ?>
                                    <td style="text-align:center;"><span class="badge badge-pill badge-success"><i class="fas fa-check"></i></span></td>
                                <?php } else { ?>
                                    <td style="text-align:center;"><span class="badge badge-pill badge-danger"><i class="fas fa-times-circle"></i></span></td>
                                <?php } ?>



                                <td style="text-align:center;"><a href="addplz.php?idpl=<?php echo $plaza['id_plaza'] ?>&estado=<?php echo $plaza['estado'] ?>" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Actualizar estado"><i class="fas fa-sync"></i> Cambiar</a></td>

                                <td><?php echo utf8_encode($plaza['nombreplaza']) ?></td>
                                <td style="text-align:center;"><a href="store.php?idpl=<?php echo $plaza['id_plaza'] ?>" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Sincronización"><i class="fas fa-sync"></i> Sync</a></td>
                                <td style="text-align:center;">

                                    <a href="urlMap.php?plz=<?php echo $plaza['id_plaza'] ?>" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Agregar URL de mapa"><i class="fas fa-chart-line"></i> KPIs</a>

                                    <a href="Reporte_CallCenter/reporte.php?plz=<?php echo $plaza['id_plaza'] ?>" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Reportes de plaza">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-bar-graph" viewBox="0 0 16 16">
                                            <path d="M4.5 12a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-1zm3 0a.5.5 0 0 1-.5-.5v-4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-1zm3 0a.5.5 0 0 1-.5-.5v-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-.5.5h-1z" />
                                            <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z" />
                                        </svg>
                                    </a>

                                    <a href="nuevoGrupo.php?plz=<?php echo $plaza['id_plaza'] ?>" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Subgrupos de KPIs"><i class="fas fa-layer-group"></i></a>

                                    <a href="" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#datos<?php echo $plaza['id_plaza'] ?>"><span aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Datos de plaza"><i class="far fa-edit"></i></span></a>

                                    <a href="delete.php?poneplz=1&plz=<?php echo $plaza['id_plaza'] ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar plaza" onclick="return Confirmar('¿Esta seguro que decea eliminar la plaza <?php echo utf8_encode($plaza['nombreplaza']) ?>?')"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>
                    </tbody>
                    <!-- *********************************MODAL PARA ACTUALIZAR UDATOS *************************************************** -->
                    <form action="" method="post">
                        <div class="modal fade" id="datos<?php echo $plaza['id_plaza'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" style="text-shadow: 0px 0px 2px #717171;">Editar nombre de plaza</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group" style="text-align:center;">
                                            <label for="exampleInputEmail1">Editar nombre de plaza: </label>
                                            <input type="text" class="form-control" name="nombreplz" value="<?php echo utf8_encode($plaza['nombreplaza']) ?>" required>
                                        </div>



                                        <?php
                                        $pro = "SELECT * FROM proveniente";
                                        $prov = sqlsrv_query($cnx, $pro);
                                        $prove = sqlsrv_fetch_array($prov);
                                        ?>
                                        <div class="md-form form-group">
                                            <label for="exampleInputEmail1">Datos provenientes: *</label>
                                            <select name="prov" class="form-control" required>
                                                <option value="<?php echo $plaza['id_proveniente'] ?>"><?php echo utf8_encode($plaza['nombreProveniente']) ?></option>
                                                <?php do { ?>
                                                    <option value="<?php echo $prove['id_proveniente'] ?>"><?php echo utf8_encode($prove['nombreProveniente']) ?></option>
                                                <?php } while ($prove = sqlsrv_fetch_array($prov)); ?>
                                            </select>
                                        </div>







                                        <div class="form-group" style="text-align:center;">
                                            <label for="exampleInputEmail1">Editar nombre de plaza: </label>
                                            <input type="text" class="form-control" name="imagen" value="<?php echo $plaza['img'] ?>" required>
                                        </div>
                                        <div style="text-align:center;">
                                            <a href="<?php echo $plaza['img'] ?>" class="btn btn-outline-primary btn-sm" target="_blank"><i class="far fa-image"></i> Ver Imagen</a>
                                        </div>
                                        <br>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" class="form-control" name="idplz" value="<?php echo $plaza['id_plaza'] ?>" placeholder="Agregar marca">
                                        <button type="submit" class="btn btn-primary" name="update">Actualizar</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- ***********************************FIN MODAL ACTUALIZAR DATOS *************************************************** -->
                <?php } while ($plaza = sqlsrv_fetch_array($plz)); ?>
                </table>
            <?php } else { ?>
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-terminal"></i> Todavía no has agregado ninguna plaza.
                </div>
            <?php } ?>
        </div>
        <br><br>
    <?php } else {
    header('location:../../login.php');
}
require "include/footer.php";
    ?>
    </body>
    <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <script>
        function Confirmar(Mensaje) {
            return (confirm(Mensaje)) ? true : false;
        }
    </script>

    </html>