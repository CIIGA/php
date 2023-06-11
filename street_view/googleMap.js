$('#googleModal').on('shown.bs.modal', function() {
    // $('#myInput').trigger('focus')
    initialize();

  })
  var map;
  var mapBounds = new google.maps.LatLngBounds(
    new google.maps.LatLng(19.285539435788348, -99.589116883562681),
    new google.maps.LatLng(19.291687298166607, -99.568493182594949)
  );
  var mapMinZoom = 14;
  var mapMaxZoom = 20;

  function createOverlay(percent) {
    var pix4tiler = new google.maps.ImageMapType({
      getTileUrl: function(coord, zoom) {
        var proj = map.getProjection();
        var tileSize = 256 / Math.pow(2, zoom);
        var tileBounds = new google.maps.LatLngBounds(
          proj.fromPointToLatLng(
            new google.maps.Point(
              coord.x * tileSize,
              (coord.y + 1) * tileSize
            )
          ),
          proj.fromPointToLatLng(
            new google.maps.Point(
              (coord.x + 1) * tileSize,
              coord.y * tileSize
            )
          )
        );
        if (
          mapBounds.intersects(tileBounds) &&
          zoom >= mapMinZoom &&
          zoom <= mapMaxZoom
        )
          return (
            zoom +
            "/" +
            coord.x +
            "/" +
            (Math.pow(2, zoom) - coord.y - 1) +
            ".png"
          );
        else return "http://none.png";
      },
      tileSize: new google.maps.Size(256, 256),
      isPng: true,
      opacity: parseFloat(percent) / 100.0,
    });
    return pix4tiler;
  }

  function initialize() {
    var myOptions = {
      minZoom: 14,
      maxZoom: 20,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      streetViewControl: false,
    };
    map = new google.maps.Map(
      document.getElementById("map_canvas"),
      myOptions,

    );
    map.fitBounds(mapBounds);
    //Opacidad del mapa
    var pix4tiler = createOverlay("100");
    map.overlayMapTypes.insertAt(0, pix4tiler);



    var coord = {
      lat: 19.288331,
      lng: -99.573891
    };
    const fenway = {
      lat: 19.288331,
      lng: -99.573891
    };
    var marker = new google.maps.Marker({
      position: coord,
      map: map,
      title: "Datos del propietario:",
    });
    const contentString =
      '<div id="content">' +
      '<div id="siteNotice">' +
      "</div>" +
      '<h1 id="firstHeading" class="firstHeading">RUIZ ESTRADA ZOYLA</h1>' +
      '<div id="bodyContent">' +
      "<p><b>Cuenta</b> 101-20-535-13-00-0000 <br/> " +
      "<b>Direccion:</b> Calzada Hacienda del Coecillo sn, colonia El Coecillo, cp 50246, Toluca de Lerdo, Edo. México</p>" +
      "</div>" +
      "</div>";
    var infowindow = new google.maps.InfoWindow({
      content: contentString,
      ariaLabel: "Uluru",
    });
    infowindow.open(map, marker);
    map.setStreetView(marker);
    marker.addListener("click", () => {
      infowindow.open({
        anchor: marker,
        map,
      });
    });
  }