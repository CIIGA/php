$(document).on('change', '#existPlaza', function () {
	var idPlaza = $(this).val();
	if (idPlaza != "") {
		var url = new URL(window.location.href);
		var params = new URLSearchParams(url.search);
		params.set('plz',idPlaza);
		url.search = params.toString();
		window.location.href = url.toString();
	}
});
var loadInfo = function() {
	Swal.fire({
	  title: 'Obteniendo Datos',
	  html: 'Espere un momento por favor...',
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
	}).then((result) => {});
  }