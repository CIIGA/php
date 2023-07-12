$(anio());
//La funcion anio manda a conusultar el anio de la plaza 
function anio(idPlaza) {
	$.ajax({
		url: 'Bonos/anio.php',
		type: 'POST',
		dataType: 'html',
		data: { idPlaza },
	})
		.done(function (resultado) {
			$("#resultado").html(resultado);
		})
}
//Por el metodo change se ejecuta la consulta del selected del anio
//el cual se extrae el año seleccionado y se manda a la funcion meses
$(document).on('change', '#existPlaza', function () {
	var idPlaza = $(this).val();
	//Se condiciona el año si es valido 
	if (idPlaza != "") {
		var url = new URL(window.location.href);
		var params = new URLSearchParams(url.search);
		params.set('plz',idPlaza);
		params.delete('anio');
		params.delete('mes');
		params.delete('existPlaza');
		params.delete('base'); 
		params.delete('excel'); 
		url.search = params.toString();
		window.location.href = url.toString();


// 		// Paso 4: Crea una nueva URL sin los parámetros eliminados
// var nuevaUrl = url.origin + url.pathname + '?' + params.toString();

// // Paso 5: Reemplaza la URL actual con la nueva URL
// window.history.replaceState({}, '', nuevaUrl);
	}
});
