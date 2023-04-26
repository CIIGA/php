<?php
session_start();
if ((isset($_SESSION['user'])) and (isset($_SESSION['tipousuario']))) {
    //Se llama al modulo de callcenter
    require 'modules/callCenter.php';
    //Se extrae la plaza 
    $id_plaza = $_GET['plz'];
    $plaza = plaza($id_plaza);

    

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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <?php require "include/nav.php";?>
        <style>
        .text-xs{
        font-size: 10px !important;
        }
        .content{
         margin-left:10px;
         margin-right:10px;
         overflow-x: auto;
        }
        </style>
    </head>

    <body>
        <div class="container">
            <h2>Reporte de CallCenter</h2>
            <div class="row">
                <div class="col-md-6">
                    <!-- Se manda al mismo archivo y por el metodo get -->
                    <form action="" method="">
                        <input type="text" class="form-control" hidden id="id_plaza" value="<?php echo $id_plaza; ?>" name="plz" hidden>
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
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-blue" name="tabla">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                    </svg>
                                </button>
                                <button class="btn btn-success btn-green" name="excel">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-down" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M3.5 10a.5.5 0 0 1-.5-.5v-8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 0 0 1h2A1.5 1.5 0 0 0 14 9.5v-8A1.5 1.5 0 0 0 12.5 0h-9A1.5 1.5 0 0 0 2 1.5v8A1.5 1.5 0 0 0 3.5 11h2a.5.5 0 0 0 0-1h-2z" />
                                        <path fill-rule="evenodd" d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="card tarjet">
                        <div class="card-body ">
                            <h5 class="card-title text-center">Registros</h5>
                            <?php
                            //Si recibe los datos por el metodo get manda al archivo de callcenter el conteo total de los que hay
                            if (isset($_GET['base']) and isset($_GET['fecha_inicial']) and isset($_GET['fecha_final']) and isset($_GET['sector'])) {
                                $BD = $plaza['base'];
                                echo ' <p class="card-text text-center bg-white">'.count_spCallCenter($BD).'</p>';
                            }
                            ?>
                           </p>
                        </div>
                    </div>
                </div>
            </div>
    </div>
        <div class="content">
            <?php
            
if (isset($_GET['base']) and isset($_GET['fecha_inicial']) and isset($_GET['fecha_final']) and isset($_GET['sector'] ) and isset($_GET['tabla'])) {
    //Se  extrae los datos enviados por la url
        $sector = $_GET['sector'];
        $fechaI = $_GET['fecha_inicial'];
        $fechaF = $_GET['fecha_final'];
        $BD = $plaza['base'];
        //Se condiciona si se recib la pagina 
        if(isset($_GET['page']) and is_numeric($_GET['page']) == 1){
            $pagina = intval($_GET['page']);
        }
        //Si no recibe declaramos que el valor por defecto es 1
        else{
            $pagina = 1; 
        }
        //Se manda a llamar la funcion de callcenter
        sp_RGCallCenter(
            $id_plaza,
            $sector,
            $fechaI,
            $fechaF,
            $BD,
            $pagina);
    }
            
if (isset($_GET['base']) and isset($_GET['fecha_inicial']) and isset($_GET['fecha_final']) and isset($_GET['sector'] ) and isset($_GET['excel'])) {
    //Se  extrae los datos enviados por la url
        $sector = $_GET['sector'];
        $base = $_GET['base'];
        $fechaI = $_GET['fecha_inicial'];
        $fechaF = $_GET['fecha_final'];
        $BD = $plaza['base'];
        
        // header('Location: excel/callcenter.php?sector='.$sector.'&base='.$base.'&fechaI='.$fechaI.'&fechaF='.$fechaF.'&id_plaza='.$id_plaza);
        // echo "<meta http-equiv='refresh' content='/excel/callcenter.php'>";
        // ?plz=$id_plaza&base=$base&plaza=$plaza&fecha_inicial=$fechaI&fecha_final=$fechaF

        //  //Se manda a llamar la funcion de callcenter
        //  excel(
        //     $id_plaza,
        //     $sector,
        //     $fechaI,
        //     $fechaF,
        //     $BD);
}
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