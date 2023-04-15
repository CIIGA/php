<?php
session_start();
if ((isset($_SESSION['user'])) and (isset($_SESSION['tipousuario']))) {
    require 'modules/callCenter.php';
    $id_plaza = $_GET['plz'];
    $plaza = plaza($id_plaza);
    if (isset($_POST['base']) and isset($_POST['fecha_inicial']) and isset($_POST['fecha_final']) and isset($_POST['sector'])) {
        $sector = $_POST['sector'];
        $base = $_POST['base'];
        $fechaI = $_POST['fecha_inicial'];
        $fechaF = $_POST['fecha_final'];
        $BD = $plaza['base'];
        $exec = sp_RGCallCenter(
            $sector,
            $base,
            $fechaI,
            $fechaF,
            $BD);
            if($exec){
                while ($result = sqlsrv_fetch_array($exec)) {
                    echo utf8_encode($result['Observaciones']);
                    echo utf8_encode($result['FechaPromesaPago']->format('d/m/Y'));
                    echo utf8_encode($result['PersonaAtendio']);
                    echo utf8_encode($result['TareaAnterior']);
                    echo utf8_encode($result['TareaActual']);
                    echo utf8_encode($result['Fecha']->format('d/m/Y'));
                    echo utf8_encode($result['Cuenta']);
                    echo utf8_encode($result['Clave']);
                    echo utf8_encode($result['CallCenter']);
                    echo utf8_encode($result['Clasificacion']);
                    echo utf8_encode($result['Telefono']);
                    echo utf8_encode($result['TelRadio']);
                    echo utf8_encode($result['TelRadio']);
                    echo utf8_encode($result['TelefonoUsuario']);
                    echo utf8_encode($result['CelularUsuario']);
                    echo utf8_encode($result['TelRadioUsuario']);
                    echo utf8_encode($result['TelefonoUsuario']);
                    echo utf8_encode($result['CelularUsuario']);
                    echo utf8_encode($result['TelRadioUsuario']);
                    echo utf8_encode($result['Usuario']);
                    echo utf8_encode($result['Direccion']);
                    echo utf8_encode($result['Colonia']);
                    echo utf8_encode($result['Distrito']);
                    echo utf8_encode($result['Clave Catastral']);
                    echo utf8_encode($result['Serie Medidor']);
                    echo utf8_encode($result['Tipo Servicio']);
                    echo utf8_encode($result['Giro']);
                    echo utf8_encode($result['Razon Social']);
                    echo utf8_encode($result['Deuda Total']);
                    echo utf8_encode($result['Abogado']);
                    echo utf8_encode($result['Gestor']);
                }
            }
            else{
               echo $exec;
            }

    }
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Plazas | KPIs</title>
        <link rel="icon" href="../../icono/icon.png">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="../../css/bootstrap.css">
        <link href="../../fontawesome/css/all.css" rel="stylesheet">
        <link href="../Reporte_CallCenter/css/index.css" rel="stylesheet">
        <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <?php require "include/nav.php";?>
    </head>

    <body>
        <div class="container">
            <h2>Reporte de CallCenter</h2>
            <div class="row">
                <div class="col-md-6">
                    <form action="" method="post">
                        <input type="text" class="form-control" hidden id="plaza" value="<?php echo $plaza['base']; ?>" name="base">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">Plaza</label>
                            </div>
                            <input type="text" class="form-control" id="plaza" value="<?php echo $plaza['plaza']; ?>" name="plaza" readonly>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">Sector</label>
                            </div>
                            <select class="custom-select" id="inputGroupSelect01" name="sector" required>
                                <option selected>Selecionar Sector</option>
                                <option value="1">CallCenter</option>
                                <option value="2">Pregrabadas</option>
                            </select>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">Fecha inicial</label>
                            </div>
                            <input type="date" class="form-control" id="date" name="fecha_inicial" required>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">Fecha final</label>
                            </div>
                            <input type="date" class="form-control" id="date" name="fecha_final" required>
                        </div>
                        <div class="row center">
                            <button type="submit" class="btn btn-primary btn-blue">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                </svg>
                            </button>
                            <button class="btn btn-success btn-green">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-down" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M3.5 10a.5.5 0 0 1-.5-.5v-8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 0 0 1h2A1.5 1.5 0 0 0 14 9.5v-8A1.5 1.5 0 0 0 12.5 0h-9A1.5 1.5 0 0 0 2 1.5v8A1.5 1.5 0 0 0 3.5 11h2a.5.5 0 0 0 0-1h-2z" />
                                    <path fill-rule="evenodd" d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="card tarjet">
                        <div class="card-body ">
                            <h5 class="card-title text-center">Registros</h5>
                            <p class="card-text text-center bg-white">0</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
echo $id_plaza;
    echo "<br/>";
    echo $plaza['base'];
    echo "<br/>";
    echo $plaza['plaza'];
    ?>
        </div>
    </body>
<?php
} else {
    header('location:../../login.php');
}
require "include/footer.php";
?>

    </html>