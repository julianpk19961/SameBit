$(document).ready(function () {
    getTable();
});


getTable = () => {
    
    template = '';
    $.ajax({
        // script para verificación de cc existentes
        url: '../config/medicines.php',
        type: 'GET',

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
                        <td class=" text-center text-white bg-${(medicine.z_xOne == 1 ? 'success' : 'secondary')} opacity-75"> <input class="z_xOne" hidden>  ${(medicine.z_xOne == 1 ? 'ACTIVO' : 'INACTIVO')} </td>
                        <td>${medicine.nombre} </td>
                        <td > ${(medicine.referencia == null ? '' : medicine.referencia)} </td>
                        <td > ${(medicine.observacion == null ? '' : medicine.observacion)} </td>
                        <td>
                            <div class="row col-sm-12 justify-content-around">
                                <button class="show-element btn btn-primary btn-sm col-sm-5 text-white">
                                    Ver
                                </button>
                                <button class="drop-element btn btn-danger btn-sm col-sm-5" data-bs-toggle="modal" data-bs-target="#modal-delete-" type="button" ">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>`
                    i++;
                });
            }
            $('#dataMedicines').html(template);
            pagination ('#medical_tbl','8');
        },
    });
}

$('#new-item').on('click', function (e) {

    selector = document.querySelector('.save-buttons').innerHTML = `<button type="submit" class="btn btn-primary" id="stored">Guardar</button> <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>`;
    $('#observation').val(observation).attr('rows', '1');
    $('#kardex').html('');

    $('#name').val('');
    $('#reference').val('');
    $('#observation').val('').attr('rows', '10');



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
        getTable();
    });
});

$(document).on('click', '.show-element', function (e) {

    $('#observation').attr('rows', '1');
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

    let postdata = { pk_uuid: pk_uuid };

    $('#name').val(Med_name);
    $('#reference').val(reference);
    $('#name').val(Med_name);
    selector = document.querySelector('.save-buttons').innerHTML = ``;

    getkardexRow(postdata);

});

function getkardexRow(postdata) {

    $('#pk_uuid').val(postdata.pk_uuid);

    template = '';
    template = `
        <table class="table table-sm table-striped mb-1" id="kardex_tbl">
            <thead>
                <tr class="table text-light border bg-primary">
                    <th>Fecha</th>
                    <th>Paciente-Factura</th>
                    <th>Clase Movimiento</th>
                    <th>Cantidad</th>
                    <th>Saldo Final</th>
                </tr>
            </thead>
            <tbody id="kardexMov"></tbody>
        </table>
        <div class="container border p-1 my-2" id="kardexAdd">
        </div>`;
    $('#kardex').html(template);

    templatebody = '';
    templateoption = '<option value="">Seleccion categoría</option>';

    $.post('../config/getkardexmov.php', postdata, function (response) {

        if (response == 'error') {
            templatebody += `<tr> <td colspan='9'><strong>Aún no se registran movimientos</strong></td></tr>`
        } else {

            let data = JSON.parse(response);
            data[0].forEach(row => {
                templateoption += `<option value="${row.KP_UUID}" title="${row.name} - ${row.abbr}">${row.name}</option>`
            });

            if (data[1] == 'error') {
                templatebody += `<tr> <td colspan='9'><strong>Aún no se registran movimientos</strong></td></tr>`
            } else {
                data[1].forEach(row => {
                    templatebody +=
                        `<tr class="border">
                            <td>${nueva = row.zCrea.split(" ")[0].split("-").reverse().join("-")} </td>
                            <td >${row.patient} </td>
                            <td> ${row.category} </td>
                            <td class="text-center"> ${row.quantity} </td>
                            <td class="text-center"> ${row.finalQuantity} </td>
                        </tr>`
                });
            }

            $('#kardexMov').html(templatebody);
            
            templateKardex = `
            <form id="newkardexmov" clas="row g-4" method="POST">
                <div class="row p-1">
                    <div class="form-group col-3">
                        <label for="categorymov">Categoría</label>
                        <select class="form-select" id="categorymov" aria-label="categorymov">
                        </select>
                    </div>
                    <div class="form-group col-4">
                        <label for="patientmov" title="Persona/Entidad que realiza el movimiento" >Tercero</label>
                        <input type="text" class="form-control" id="patientmov" placeholder="Ingresar Nombre">
                    </div>
                    <div class="form-group col-3">
                        <label for="bill">Factura</label>
                        <input type="text" class="form-control" id="bill" placeholder="Nro Factura">
                    </div>
                    <div class="form-group col-2">
                        <label for="quantity">Cantidad</label>
                        <input type="number" class="form-control" id="quantity" placeholder="Cantidad">
                    </div>
                </div>
                <div class="m-2 float-end">
                    <button type="submit" class="btn btn-success">+</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>`;
            $('#kardexAdd').html(templateKardex);
            $('#categorymov').html(templateoption);


            pagination ('#kardex_tbl','8');

        };
    });
};


$(document).on('submit', '#newkardexmov', (e) => {

    e.preventDefault();
    let emptyfields = '';

    category = $('#categorymov').val();
    patient = $('#patientmov').val();
    bill = $('#bill').val();
    quantity = $('#quantity').val();

    if (category.length == 0) {
        emptyfields += 'Categoria, '
    } if (patient.length == 0 && bill.length == 0) {
        emptyfields += 'Paciente y Factura, '
    } if (quantity.length == 0) {
        emptyfields += 'Cantidad'
    }

    if (emptyfields) {
        alert('Los siguientes campos están vacios: ' + emptyfields);
        e.preventDefault();
        return false;
    }

    pk_uuid = $('#pk_uuid').val();
    
    let postdata = {
        pk_uuid: pk_uuid,
        category: category,
        patient: patient,
        bill: bill,
        quantity: quantity,

    };


    $.post('../config/newkardexmov.php', postdata, (response) => {
        console.log(response);
        $('#newkardexmov').trigger('reset');
        getkardexRow(postdata);

    });

});


function getDataCells(table, row) {
    let dataRecord = table.rows[row].getElementsByTagName('td');
    return dataRecord;
}

function pagination(table, row) {


    $(table).DataTable({
        "language": { "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json" },
        "lengthMenu": [15, 30, 50,100, "All"],
        "scrollX": false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'pdfHtml5',
                download: 'open'
            }
        ]

        // buttons: [
        //     'copy', 'csv', 'excel', 'pdf', 'print'
        // ]
    });
    
    var table = $('#example').DataTable( {
        lengthChange: false,
        buttons: [
            {
                extend: 'csv',
                split: [ 'pdf', 'excel'],
            }
        ]
    } );
 
     table.buttons().container()
        .appendTo( '#example_wrapper .col-md-6:eq(0)' );

    
}