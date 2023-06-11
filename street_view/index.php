<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Stratimex</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet" />
  <link href="index.css" rel="stylesheet" />
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css" />
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


</head>

<body>
  <div class="page-wrapper chiller-theme toggled d-flex" id="wrapper">
    <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
      <i class="fas fa-bars"></i>
    </a>
    <nav id="sidebar-wrapper" class="sidebar-wrapper">
      <div class="sidebar-content">
        <div class="sidebar-brand">
          <!-- <a href="">Stratimex</a> -->
          <img class="logo" width="150px" height="50px" src="icons/startimex_logo_Sombra.png" />
          <div id="close-sidebar">
            <i class="fas fa-times"></i>
          </div>
        </div>
        <!-- Siderbar Menu  -->
        <div class="sidebar-menu">
          <ul>
            <li class="header-menu">
              <span>Datos del propietario</span>
              <span class="data-propietario">Cuenta: 101-20-535-13-00-0000</span>
              <span class="data-propietario">Propietario: RUIZ ESTRADA ZOYLA</span>
            </li>
            <hr />
            <li>
              <a href="#">
                <i class="fa fa-camera"></i>
                <span>Vista 360</span>
              </a>
            </li>
            <li class="sidebar-dropdown">
              <a href="#">
                <i class="fa fa-cube"></i>
                <span>Modelo 3D</span>
              </a>
              <div class="sidebar-submenu">
                <ul>
                  <li>
                    <a href="#">Ver QR </a>
                  </li>
                  <li>
                    <a href="">Modelo 3D</a>
                  </li>
                </ul>
              </div>
            </li>
            <li>
              <a href="#">
                <i class="fa fa-arrows-alt"></i>
                <span>Street View</span>
              </a>
            </li>
            <li>
              <a type="button" data-toggle="modal" data-target="#googleModal">
                <i class="fa fa-map-marker"></i>
                <span>Foto Área</span>
              </a>
            </li>
            <li class="sidebar-dropdown">
              <a href="#">
                <i class="fa fa-folder"></i>
                <span>Ficha Digital</span>
              </a>
              <div class="sidebar-submenu">
                <ul>
                  <li>
                    <a href="#">Ficha PDF </a>
                  </li>
                  <li>
                    <a href="">Mapear Argis</a>
                  </li>
                  <li>
                    <a href="">Perfil Cuidadano</a>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
        <!-- sidebar-menu  -->
      </div>
    </nav>
    <!-- sidebar-wrapper  -->
    <div class="page-content">
      <div class="container-fluid">
        <div class="embed-responsive embed-responsive-16by9">
          <iframe src="https://panoraven.com/es/embed/eqkA9Tqhkt" style="border: 0" allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>
    </div>
    <!-- page-content" -->
  </div>
  <!-- page-wrapper -->

  <!-- Modal  Google Maps-->
  <div class="modal fade" id="googleModal" tabindex="-1" role="dialog" aria-labelledby="googleModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="width: 100%; height: 100%">
      <div class="modal-content" style="width: 100%; height: 100%">
        <div class="modal-header">
        <h5>Foto Area</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="map_canvas" >

        </div>
      </div>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="index.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <!-- CDn de google maps -->
  <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcF4oi3SweowzVYo29ifjqXJsl1eE7C8M"></script>
  <script src="googleMap.js"></script>

  <!-- CDn de google maps -->
</body>

</html>