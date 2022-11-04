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
                let i = 0;
                medicines.forEach(medicine => {

                    template +=
                        `<tr id='${i}'>
                    <td hidden>${medicine.KP_UUID}</td>
                    <td hidden>${medicine.z_xOne}</td>
                    <td class=" text-center text-white bg-${(medicine.z_xOne == 1 ? 'success' : 'secondary')} opacity-75"><input class="z_xOne" hidden>  ${(medicine.z_xOne == 1 ? 'ACTIVO' : 'INACTIVO')} </td>
                    <td>${medicine.nombre} </td>
                    <td colspan="2"> ${(medicine.referencia == null ? '' : medicine.referencia)} </td>
                    <td colspan="3"> ${(medicine.observacion == null ? '' : medicine.observacion)} </td>
                    <td>
                        <div class="row col-sm-12 justify-content-around">
                            <button class="show-element btn btn-primary btn-sm col-sm-4 text-white">
                                Ver
                            </button>
                            <button class="btn btn-warning btn-sm col-sm-4 text-white" onclick="location.href = '';">
                                Modificar
                            </button>
                            <button class="drop-element btn btn-danger btn-sm col-sm-3" data-bs-toggle="modal" data-bs-target="#modal-delete-" type="button" ">
                                Eliminar
                            </button>
                        </div>
                    </td>
                    </tr>`
                    i++;
                });
            }
            $('#dataMedicines').html(template);
        },
    });
}

$('#new-item').on('click', function (e) {

    selector = document.querySelector('.save-buttons').innerHTML = `<button type="submit" class="btn btn-primary" id="stored">Guardar</button> <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>`;
    $('#observation').val(observation).attr('rows','1');
    $('#kardex').html('');

    $('#name').val('');
    $('#reference').val('');
    $('#observation').val('').attr('rows','10');



});

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
            // console.log(response);
            return false
        }
        $('#modal-record').modal('hide');
    });
    getTable(searchbox);

}


$(document).on('click', '.drop-element', function (e) {

    tabla = document.getElementById('dataMedicines');
    row = $(this).closest('tr').index();
    getDataCells(tabla, row);


});

$(document).on('click', '.drop-element', function (e) {

    table = document.getElementById('dataMedicines');
    row = $(this).closest('tr').index();

    dataRecord = getDataCells(table, row);

    pk_uuid = dataRecord[0].innerHTML;
    z_xone = dataRecord[1].innerHTML;
    Med_name = dataRecord[3].innerHTML;

    titulo = document.querySelector('.modal-body > p').innerHTML;
    nuevotitulo = titulo.replace('registro', Med_name);
    document.querySelector('.modal-body > p').innerHTML = nuevotitulo;

    document.getElementById('modaldel_pk_uuid').innerHTML = pk_uuid;
    document.getElementById('modaldel_z_xone').innerHTML = z_xone;

});

$('#destroyMedicine').submit((e) => {

    e.preventDefault();

    let postdata = {
        pk_uuid: document.getElementById('modaldel_pk_uuid').innerText,
        z_xone: document.getElementById('modaldel_z_xone').innerText
    };

    $.post('../config/medicinedown.php', postdata, function (response) {
        data = JSON.parse(response);
        Swal.fire({
            icon: data.icon,
            title: data.tittle,
            text: data.text,
            timer: 5000
        });

        searchbox = $('#searchbox').val()
        getTable(searchbox);
        // return false;

    });
});

$(document).on('click', '.show-element', function (e) {

    table = document.getElementById('dataMedicines');
    row = $(this).closest('tr').index();
    template = '';

    dataRecord = getDataCells(table, row);

    $('#modal-record').modal('show');

    pk_uuid = dataRecord[0].innerHTML;
    z_xone = dataRecord[1].innerHTML;
    Med_name = dataRecord[3].innerHTML;
    reference = dataRecord[4].innerHTML;
    observation = dataRecord[5].innerHTML;

    template += `
    <input hidden>${dataRecord[0].innerHTML}</input>
    <input hidden>${dataRecord[1].innerHTML}</input>`;

    // document.getElementById('name').innerHTML = Med_name;
    $('#name').val(Med_name);
    $('#reference').val(reference);
    $('#observation').val(observation).attr('rows','1');
    // $('#observation').attr('rows','1');
    selector = document.querySelector('.save-buttons').innerHTML = `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>`;

    template = '';
    template = `
    <table class="table table-striped mb-1">
        <thead>
            <tr class="table text-light align-middle border bg-primary">
                <th class="text-center"> Estado </th>
                <th> Fecha </th>
                <th> Tipo </th>
                <th> Categoria </th>
                <th class="text-center"> Saldo Anterior</th>
                <th class="text-center"> Cantidad </th>
                <th class="text-center"> Saldo Actual</th>
            </tr>
        </thead>
        <tbody id="dataMedicines">

        </tbody>
    </table>`;

    $('#kardex').html(template);


});

function getDataCells(table, row) {

    let dataRecord = table.rows[row].getElementsByTagName('td');
    return dataRecord;

}