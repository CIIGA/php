$(meses());
//La funcion meses manda a conusultar los meses en base al año, 
//
function meses(anio, bd) {
	$.ajax({
		url: 'Bonos/mes.php',
		type: 'POST',
		dataType: 'html',
		data: { anio, bd },
	})

		.done(function (resultado) {
			$("#resultado").html(resultado);
		})
}
$(mesesDistinc());
//se hace una consulta a los meses exceptuando al mes que ya selecciono el usuario
function mesesDistinc(anio, bd, mes) {
	$.ajax({
		url: 'Bonos/mes.php',
		type: 'POST',
		dataType: 'html',
		data: { anio, bd, mes },
	})
		.done(function (resultado) {
			$("#resultado").html(resultado);
		})
}
//Por el metodo change se ejecuta la consulta del selected del anio
//el cual se extrae el año seleccionado y se manda a la funcion meses
$(document).on('change', '#anio', function () {
	var anio = $(this).val();
	var bd = document.getElementById("base").value
	//Se condiciona el año si es valido 
	if (anio != "") {
		meses(anio, bd);
	}
});
//La funcion extrae los parametros de la url una vez que termino de cargar
window.onload = function () {
	const urlSearchParams = new URLSearchParams(window.location.search);
	const anio = urlSearchParams.get("anio");
	const bd = urlSearchParams.get("base");
	const mes = urlSearchParams.get("mes");
	//Si los datos que se extrayeron de la url no son undiefined 
	//se manda a llamar la funcion mesesDisctinc
	if (anio != undefined && bd != undefined) {
		mesesDistinc(anio, bd, mes);
	}
}