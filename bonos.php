<?php
session_start();
header('Content-type: text/html; charset=utf-8');
if ((isset($_SESSION['user'])) and (isset($_SESSION['tipousuario']))) {
    require 'Bonos/plaza.php';
    $id_plaza = $_GET['plz'];
    $plaza = plaza($id_plaza);
    $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Nobiembre", "Diciembre"];

    //Conexion para buscar las plazas disponibles 
    $serverName = "51.222.44.135";
    $connectionInfo = array('Database' => 'kpis', 'UID' => 'sa', 'PWD' => 'vrSxHH3TdC');
    $cnr = sqlsrv_connect($serverName, $connectionInfo);
    date_default_timezone_set('America/Mexico_City');
    $sql = "select id_plaza,nombreplaza, estado FROM plaza where estado = 1";
    $exec = sqlsrv_query($cnr, $sql);
    $existsPlaza = sqlsrv_fetch_array($exec);
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Administrador | KPIs</title>
        <link rel="icon" href="../icono/icon.png">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="../css/bootstrap.css">
        <link href="../fontawesome/css/all.css" rel="stylesheet">
        <link href="Bonos/tabla.css" rel="stylesheet">
        <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="Bonos/mesAjax.js"></script>
        <script src="Bonos/anioAjax.js"></script>
        <?php require "include/nav.php"; ?>
    </head>

    <body>
        <div class="container mb-4">
            <div class="col-md-4 mx-auto">
                <h2 class="text-center"> Calculo de bonos</h2>
                <!-- Se manda al mismo archivo y por el metodo get -->
                <form action="" method="">
                    <div class="mx-auto">
                        <input type="text" class="form-control" hidden id="id_plaza" value="<?php echo $id_plaza; ?>" name="plz" hidden>
                        <input type="text" class="form-control" hidden id="base" value="<?php echo $plaza['base']; ?>" name="base">
                        <?php if ((isset($_SESSION['tipousuario']))) { ?>
                            <div class="input-group mb-3">
                                <select class='custom-select' id='existPlaza' name='existPlaza' required>
                                    <option> Seleccione la plaza</option>
                                    <?php do { ?>
                                        <option value="<?php echo $existsPlaza['id_plaza'] ?>"><?php echo $existsPlaza['nombreplaza'] ?> </option>
                                    <?php }while (($existsPlaza = sqlsrv_fetch_array($exec)));  ?>
                                </select>
                            </div>
                            <?php }?>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">Plaza</label>
                            </div>
                           <input type="text" class="form-control" id="plaza" value="<?php echo $plaza['plaza']; ?>" name="plaza" readonly>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">Año</label>
                            </div>
                            <?php
                            if (isset($_GET['anio'])) {
                                $anios = anioArray($plaza['base']);
                                $tempAnio = $_GET['anio'];
                                $countAnios = count($anios);
                            ?>
                                <select class="custom-select" id="anio" name="anio" required>
                                    <option value="<?php echo $tempAnio ?>" selected><?php echo $tempAnio ?></option>
                                    <?php
                                    for ($i = 0; $i < $countAnios; $i++) {
                                        if ($tempAnio != $anios[$i]) {
                                    ?>
                                            <option value="<?php echo $anios[$i] ?>"> <?php echo $anios[$i] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            <?php
                            } else {
                                anio($plaza['base']);
                            }
                            ?>
                        </div>
                        <div id="resultado"></div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="row">
                                    <button type="submit" class="btn btn-primary btn-blue">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                        </svg>
                                        Buscar
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <button type="button" class="btn btn-success btn-warning" onclick="reenvio()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-down" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M3.5 10a.5.5 0 0 1-.5-.5v-8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 0 0 1h2A1.5 1.5 0 0 0 14 9.5v-8A1.5 1.5 0 0 0 12.5 0h-9A1.5 1.5 0 0 0 2 1.5v8A1.5 1.5 0 0 0 3.5 11h2a.5.5 0 0 0 0-1h-2z" />
                                        <path fill-rule="evenodd" d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z" />
                                    </svg>
                                    Descargar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="container">
            <?php
            if (isset($_GET['plaza']) && isset($_GET['anio']) && isset($_GET['mes']) && isset($_GET['base'])) {

                $BD = $_GET['base'];
                $anio = $_GET['anio'];
                $mes = $_GET['mes'];
                $plaza = $_GET['plaza'];
            ?>
                <div class="mx-auto">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-center">
                                Plaza: <?php echo $plaza; ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-center">
                                Mes: <?php $mes = $_GET['mes'];
                                        echo $meses[$mes - 1]; ?>
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <h5>
                                Recaudado: $<?php $recaudado = recaudado($BD, $anio, $mes);
                                            echo number_format($recaudado, 2); ?>
                            </h5>
                        </div>
                    </div>
                </div>
            <?php
                storProcedure($BD, $anio, $mes);
            }

            ?>
        </div>
    </body>
    <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="Bonos/reenvio.js"></script>
<?php
} else {
    header('location: logout.php');
}
require "include/footer.php";
?>

    </html>