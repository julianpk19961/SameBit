$(document).ready(function () {
    searchbox = $('#searchbox').val()
    getTable(searchbox);
});


$('#searchbtn').click(function () {
    searchbox = $('#searchbox').val()
    getTable(searchbox);
});


$('#searchbox').on('change', function () {
    searchbox = $('#searchbox').val()
    getTable(searchbox);
})


function getTable(searchbox) {

    template = '';
    // console.log(searchbox);
    $.ajax({
        // script para verificación de cc existentes
        url: '../config/medicines.php',
        type: 'POST',
        data: { searchbox },

        // Funcion para recorrer los resultados y dibujarlos en pantalla o seleccionarlo para ser usado (Cuando solo sea una registro)
        success: function (response) {

            if (response == 'error') {
                template += `<tr> <td colspan='8'>"No hay resultados"</td></tr>`
            } else {

                let medicines = JSON.parse(response);
                medicines.forEach(medicine => {
                    template +=
                        `<tr id="${medicine.id}">
                    <td style="display:none;">${medicine.KP_UUID}</td>
                    <td>${medicine.nombre} </td>
                    <td colspan="2"> ${(medicine.referencia == null ? '' : medicine.observacion)} </td>
                    <td colspan="3"> ${(medicine.observacion == null ? '' : medicine.observacion)} </td>
                    <td>
                        <div class="row col-sm-12 justify-content-around">
                            <button class="btn btn-primary btn-sm col-sm-4 text-white" >
                                Ver
                            </button>
                            <button class="btn btn-warning btn-sm col-sm-4 text-white" onclick="location.href = '';">
                                Modificar
                            </button>
                            <button class="btn btn-danger btn-sm col-sm-3" data-bs-toggle="modal" data-bs-target="#modal-delete-${medicine.id}" type="button">
                                Eliminar
                            </button>
                        </div>
                    </td>
                    </tr>`
                });
            }
            $('#dataMedicines').html(template);
        },
    });
}
