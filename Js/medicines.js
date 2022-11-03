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


getTable = (searchbox) => {

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
                        `<tr>
                    <td hidden></td>
                    <td>${medicine.nombre} </td>
                    <td colspan="2"> ${(medicine.referencia == null ? '' : medicine.referencia)} </td>
                    <td colspan="3"> ${(medicine.observacion == null ? '' : medicine.observacion)} </td>
                    <td>
                        <div class="row col-sm-12 justify-content-around">
                            <button class="btn btn-primary btn-sm col-sm-4 text-white" >
                                Ver
                            </button>
                            <button class="btn btn-warning btn-sm col-sm-4 text-white" onclick="location.href = '';">
                                Modificar
                            </button>
                            <button class="btn btn-danger btn-sm col-sm-3" data-bs-toggle="modal" data-bs-target="#modal-delete-${medicine.KP_UUID}" type="button" id="${medicine.KP_UUID}">
                                Eliminar
                            </button>

                            <div class="modal fade" id="modal-delete-${medicine.KP_UUID}" tabindex="-1" aria-labelledby="" aria-hidden="true" >
                                <div class="modal-dialog">
                                    <form id="destroyMedicine_${medicine.KP_UUID}" method="POST">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="">Eliminar</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input id="medicine_id" hidden value=${medicine.KP_UUID}>
                                                ¡Está seguro de eliminar al registro: ${medicine.nombre}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Eliminar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                    </tr>`
                });
            }
            $('#dataMedicines').html(template);
        },
    });
}

$('#medicineStored').submit(function (e) {

    let emptyfields = '';
    $name = $('#name').val();

    if ($name.length == 0) {
        emptyfields += 'Nombre'
    }

    if (emptyfields) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Los siguientes campos están vacios: ' + emptyfields,
            // timer: 5000
        })
        return false;
    }

    let postdata = {
        name: $('#name').val(),
        reference: $('#reference').val(),
        observation: $('#observation').val()
    };
    stored(postdata);

});

$('#stored').on('mouseover', function (e) {
    let emptyfields = '';
    $name = $('#name').val();

    if ($name.length == 0) {
        $('#stored').addClass('btn-secondary').removeClass('btn-primary').removeClass('btn-success');
        $('#modal-head').addClass('bg-secondary').removeClass('bg-primary').removeClass('bg-success');
        return false;
    }
    $('#stored').addClass('btn-success').removeClass('btn-primary').removeClass('btn-secondary');
    $('#modal-head').addClass('bg-success').removeClass('bg-primary').removeClass('bg-secondary');

});

stored = (postdata) => {

    $.post('../config/medicinestored.php', postdata, function (response) {
        if (response.includes('Error')) {
            console.log(response);
            return false
        }
        $('#modal-record').modal('hide');
    });
    getTable(searchbox);

}


$('#dataMedicines').on('submit',function (e) {
    e.preventDefault();
    
});

