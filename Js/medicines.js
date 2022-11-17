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
                        <td>  ${medicine.nombre} </td>
                        <td > ${(medicine.referencia == null ? '' : medicine.referencia)} </td>
                        <td > ${(medicine.observacion == null ? '' : medicine.observacion)} </td>
                        <td>
                            <div class="row col-sm-12 justify-content-around">
                                <button class="show-element btn btn-primary btn-sm col-sm-5 text-white">
                                    Ver
                                </button>
                                <button class="drop-element btn btn-${(medicine.z_xOne == 1 ? 'danger' : 'success')} btn-sm col-sm-5 text-white" data-bs-toggle="modal" data-bs-target="#modal-delete-" type="button" ">
                                ${(medicine.z_xOne == 1 ? 'Eliminar' : 'Activar')} 
                                </button>
                            </div>
                        </td>
                    </tr>`
                    i++;
                });
            }
            $('#dataMedicines').html(template);
            columns_print = [2, 3, 4, 5];
            varTitle = 'Listado Medicamentos';
            pagination('#medical_tbl', '15', columns_print, varTitle);
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

$(document).on('mouseover', '#stored', function (e) {
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
    });


});


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

        if (response.includes('error')) {

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Las cantidades ingresadas no se pueden procesar: ' + emptyfields,
                // timer: 5000
            })
            return false;
        }
        $('#newkardexmov').trigger('reset');
        getkardexRow(postdata);

    });

});


function getDataCells(table, row) {
    let dataRecord = table.rows[row].getElementsByTagName('td');
    return dataRecord;
}

function pagination(table, row, columns_print, varTitle) {

    let getTable = $(table);

    getTable.DataTable({
        destroy: true,
        "language": { "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json" },
        "lengthMenu": [row, 15, 30, 50, 100, "All"],
        "scrollX": false,
        paging: true,
        dom: '<"row"<"col-sm-6"Bl><"col-sm-6"f>>' +
            '<"row"<"col-sm-12"<"table-responsive"tr>>>' +
            '<"row"<"col-sm-5"i><"col-sm-7"p>>',
        dom: 'Bfrtip',
        buttons: {

            dom: {
                button: {
                    className: 'btn'
                }
            },

            buttons: [
                {
                    text: '<i class="bi bi-plus-circle-fill"></i>',
                    title: 'Nuevo Registro',
                    className: 'new-item bg-primary text-white m-1',
                },
                {
                    extend: 'pdfHtml5',
                    autoWidth: true,
                    text: '<i class="bi bi-filetype-pdf"></i>',
                    className: 'bg-danger text-white m-1',
                    exportOptions: { columns: columns_print },
                    title: varTitle,
                    pageSize: 'legal',
                    messageBottom: 'Reporte generado: ' + new Date().toLocaleDateString('es-CO'),

                    customize: function (doc) {
                        doc.defaultStyle.fontSize = 11;
                        doc.styles.tableHeader.fontSize = 13;
                        doc.pageMargins = [10, 10, 10, 10];
                        doc.watermark = { text: 'Samein SAS', color: '#0096ac', opacity: 0.1 };
                        doc.content.splice(0, 0, {
                            margin: [0, 0, 0, 12],
                            alignment: 'left',
                            image: 'data:image/png;base64,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',
                            width: 90,
                            height: 40
                        });

                        // resize td width
                        doc.content[2].table.widths = Array(doc.content[2].table.body[0].length + 1).join('*').split('');

                        tablerows = doc.content[2].table.body;

                        tablelength = tablerows.length;
                        tableColslength = tablerows.length;
                        let i = 0;

                        tablerows.forEach(row => {
                            let i = 0;
                            row.forEach(value => {
                                let val = value.text;
                                if (!isNaN(val) && val != '') {
                                    value.alignment = 'center';
                                }
                            });
                            i++;
                        });

                    }
                }
            ],
            columnDefs: [{
                targets: -1,
                visible: false
            }]

        }

    });

    // agregar atributos a botones
    $(table).on('init.dt', function () {

        if (table != '#kardex_tbl') {
            $('.new-item')
                .attr('data-bs-toggle', 'modal')
                .attr('data-bs-target', '#modal-record')
                .attr('id', 'new-item');
        } else {

            tableForm = document.getElementById('kardex').querySelector('.new-item').remove();
        }
    });

}

$("#medicaldiv").on("click", "#new-item", function (e) {
    selector = document.querySelector('#save-buttons');
    selector.innerHTML = `<button type="submit" class="btn btn-primary" id="stored">Guardar</button> <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>`;
    $('#observation').val('').attr('rows', '10');
    $('#medicineStored').trigger('reset');
    $('#kardex').html('');
    $('#modal-head h5').html('Nuevo  Medicamento');
});


$(document).on('click', '.show-element', function (e) {

    $('#observation').attr('rows', '1');
    table = document.getElementById('dataMedicines');
    row = $(this).closest('tr').index();
    template = '';

    dataRecord = getDataCells(table, row);


    $('#modal-record').modal('show');
    $('#modal-head h5').html('Ver Medicamento');
    // $('#modal-head .modal-title').innerHTML = 'VER MEDICAMENTO';




    pk_uuid = dataRecord[0].innerHTML;
    z_xone = dataRecord[1].innerHTML;
    Med_name = dataRecord[3].innerHTML;
    reference = dataRecord[4].innerHTML;
    observation = dataRecord[5].innerHTML;

    let postdata = { pk_uuid: pk_uuid };

    $('#name').val(Med_name);
    $('#reference').val(reference);
    $('#name').val(Med_name);

    selector = document.getElementById('save-buttons').innerHTML = "";

    getkardexRow(postdata);



});

function getkardexRow(postdata) {


    $('#pk_uuid').val(postdata.pk_uuid);

    template = '';
    template = `
        <table class="table table-lg table-striped mb-1 border align-middle" id="kardex_tbl">
            <thead>
                <tr class="table text-light border bg-primary">
                    <th>Fecha</th>
                    <th>Paciente-Factura</th>
                    <th>Clase Movimiento</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Saldo Final</th>
                </tr>
            </thead>
            <tbody id="kardexMov"></tbody>
        </table>
        <div class="container bg-light border p-1 my-2" id="kardexAdd"></div>`;

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
                            <td >${nueva = row.zCrea.split(" ")[0].split("-").reverse().join("-") + ' ' + row.zCrea.split(" ")[1]} </td>
                            <td >${row.patient + (row.bill == null ? '' : '-' + row.bill)}</td>
                            <td > ${row.category} </td>
                            <td > ${row.type == 1 ? row.quantity : '0'} </td>
                            <td > ${row.type == 0 ? row.quantity : '0'} </td>
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

            columns_print = ':visible';
            varTitle = 'Movimientos-' + $('#name').val();
            pagination('#kardex_tbl', '8', columns_print, varTitle);

        };
        return;
    });
};