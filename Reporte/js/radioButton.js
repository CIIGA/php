let sector = document.getElementById("sector"); // Encuentra el elemento "p" en el sitio
sector.addEventListener('click', e => {
    e.preventDefault();
    selectSector = sector.value;
    var contenedor = document.getElementById('addRadioButton');
    if (selectSector == 6) {
        contenedor.style.visibility = 'visible';
    }else{
        contenedor.style.visibility = 'hidden';

    }
});