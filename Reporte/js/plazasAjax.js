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
