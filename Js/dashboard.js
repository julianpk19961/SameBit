var user = [];
var user = JSON.parse(localStorage.getItem('user'));

$(document).ready(function () {

    if (user == null) {
        location.href = 'http://localhost/samebit/pages/login.php';
        return false;
    } else {
        user = JSON.parse(user);
    }

    //Ocultar barras de busqueda e historico
    $('#search-patients').hide();
    $('#history-patient').hide();
});


// Acción para input de número de documento
$(document).on('keyup', '#Dni', function () {
    let dni = $('#Dni').val();
    // La función se activa cuando el tamaño del input cumpla con minimo 5 caracteres
    if (dni.length >= 4) {

        $('#table-patients').DataTable({
            destroy: true,
            processing: true,
            // serverSide: true,
            ajax: {
                url: '../config/getPatients.php',
                type: 'POST',
                data: { dni },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#table-patients').DataTable({
                        destroy: true,
                        "language": {
                            "url": "http://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                        }
                    })
                }
            },
            columns: [
                { data: 0 },
                { data: 1 },
                { data: 2 },
                null,
            ],
            columnDefs: [
                {
                    targets: 0,
                    visible: false,
                    searchable: false,
                }, {
                    targets: 3,
                    data: null,
                    defaultContent: "<button class='patient-select btn btn-info' style='width:100%; word-wrap: break-word;'>Selecionar</button>"
                }
            ],
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            }
        });
        $('#history-patient').hide();
        $('#search-patients').show();

    } else {
        // ocultar barras de selección e historico
        $('#search-patients').hide();
        $('#history-patient').hide();
    }
});

// Accion: seleccionar paciente del listado [Se ejecuta al oprimir el botón Seleccionar en la tabla generada con la función anterior]
$(document).on('click', '.patient-select', function () {

    if (confirm('¿Está seguro de querer selecionar el paciente')) {
        // Capturar el elemnento padre y posterior tomar el atributo almacenado en el id=pacientid
        let row = $(this).closest("tr");
        let data = $('#table-patients').DataTable().row(row).data();
        let PK_UUID = data[0];

        if (localStorage.getItem('Error')) {
            localStorage.removeItem('Error');
        }

        $.post('../config/usepatient.php', { PK_UUID }, function (response) {
            const patient = JSON.parse(response);
            $('#bitregister').trigger('reset');
            $('#PK_UUID').val(patient.PK_UUID);
            $('#Dni').val(patient.dni);
            $('#nombre').val(patient.name);
            $('#apellido').val(patient.lastname);
            $('#documenttype').val(patient.documentType);
            $('#Eps').val(pacients.eps);
            $('#EpsClassification').val(pacients.range);
            $('#search-patients').hide();
            $('#history-patient').show();

        });
        cargar_historico();
    }
});


function atentionswitch() {

    let accept = $('#approved').val();
    var AtentionDate = document.getElementById('AtentionDate');
    var AtentionTime = document.getElementById('AtentionTime');

    if (accept == 1) {
        AtentionDate.disabled = false;
        AtentionTime.disabled = false;
    } else {
        AtentionDate.disabled = true;
        AtentionTime.disabled = true;
        document.getElementById('AtentionDate').value = "";
        document.getElementById('AtentionTime').value = "";
    }
};
$(document).ready(atentionswitch);


$(document).on('change', '#approved', function () {
    atentionswitch();
});

function cargar_eps() {
    $.ajax({
        url: '../config/calleps.php',
        type: 'GET',
        success: function (response) {
            let eps = JSON.parse(response);
            let template = '<option value="" title="">Seleccione una opción</option>';
            eps.forEach(eps => {
                template += `
                    <option value=${eps.pk_uuid}>${eps.name} </option>
                    `
            });
            $('#Eps').html(template);

        }
    });
}
$(document).ready(cargar_eps);

function cargar_ips() {
    $.ajax({
        url: '../config/callips.php',
        type: 'GET',
        success: function (response) {
            let ipslist = JSON.parse(response);
            let template = '<option value="" title="">Seleccione una opción</option>';
            ipslist.forEach(ipslist => {
                template += `
                    <option value=${ipslist.pk_uuid}>${ipslist.name}</option>
                    `
            });
            $('#Ips').html(template);
        }
    });
}
$(document).ready(cargar_ips);

function cargar_diagnosis() {
    $.ajax({
        url: '../config/calldiagnosis.php',
        type: 'GET',
        success: function (response) {
            let diagnosis = JSON.parse(response);
            let template = '<option value="" title="">Seleccione una opción</option>';
            diagnosis.forEach(diagnosis => {
                template += `
                    <option value=${diagnosis.KP_UUID} title=${diagnosis.Observation}>${diagnosis.Codigo}</option>
                    `
            });
            $('#diagnosis').html(template);
        }
    });
}
//Cuando la página esté cargada ejecutará la función.
$(document).ready(cargar_diagnosis);

// Función para cargar el historico
function cargar_historico() {
    // Establezco el valor de la CC
    let dni = $('#Dni').val();

    $.ajax({
        url: '../config/callhistory.php',
        type: 'POST',
        data: { dni },
        success: function (response) {
            // Sin respuesta
            if (response == 'error') {
                //Ocultar tablas por error.
                $('#history-patient').hide();
                $('#search-patients').hide();

            }
            else {

                // Decomponer el json que se capturo en el script ejecutado y medir su cantidad de resultados
                let history = JSON.parse(response);
                // Cuando exista más de un resultado, el sistema debe dibujar las opciones para ser seleccionadas
                let template = '';
                history.forEach(history => {
                    template +=
                        `<tr>
                        <td style="vertical-align:middle;">${history.commentdate} </td>
                        <td style="vertical-align:middle;">${history.commenttime} </td>
                        <td style="vertical-align:middle;">${history.createdUser}</td>
                        <td style="vertical-align:middle;">${history.comment0}</td>
                        </tr>`
                });
                // Mostrar el template en la etiqueta pacientes
                // Mostrar cuadro de selección y ocultar cuadro de historico
                $('#patienshistory').html(template);
                $('#search-patients').hide();
                $('#history-patient').show();
            }
        }
    });
}

// Acción: clic en el botón de limpiar formulario
$(document).on('click', '.bit-clean', function () {
    // confirmacion
    if (confirm('¿Está seguro de limpiar el formulario? Los datos no serán recuperados')) {
        // limpiar datos y ocultar tablass
        $('#bitregister').trigger('reset');
        $('#search-patients').hide();
        $('#history-patient').hide();
    }
});

// Accion: Oprimir el botón enviar
$(document).on('submit', '#bitregister', function (event) {
    // confirmacion
    if (confirm('¿Está seguro de enviar el formulario')) {

        //Validar campos vacios
        if ($('#documenttype').val() == '' || $('#Dni').val() == '' || $('#nombre').val() == '' || $('#apellido').val() == '' || $('#contacttype').val() == '' || $('#CommentDate').val() == '' || $('#CommentTime').val() == '' || $('#Eps').val() == '' || $('#Ips').val() == '' || $('#SentBy').val() == '' || $('#EpsStatus').val() == '' || $('#EpsClassification').val() == ''
        ) {
            // Error por campos vacios.
            Swal.fire({
                icon: 'error',
                title: 'Faltan datos',
                text: 'Campos Obligatorios vacios',
                timer: 5000
            });
            return false;
        }
        else {

            // Capturar datos a enviar
            const postData = {
                pk_uuid: $('#PK_UUID').val(),
                dni: $('#Dni').val(),
                documenttype: $('#documenttype').val(),
                name: $('#nombre').val(),
                lastname: $('#apellido').val(),
                contacttype: $('#contacttype').val(),
                CommentDate: $('#CommentDate').val(),
                CommentTime: $('#CommentTime').val(),
                approved: $('#approved').val(),
                AtentionDate: $('#AtentionDate').val(),
                AtentionTime: $('#AtentionTime').val(),
                Eps: $('#Eps').val(),
                Ips: $('#Ips').val(),
                EpsStatus: $('#EpsStatus').val(),
                EpsClassification: $('#EpsClassification').val(),
                diagnosis: $('#diagnosis').val(),
                CallNumber: $('#CallNumber').val(),
                SentBy: $('#SentBy').val(),
                Observation: $('#Observation0').val()
            };

            // event.preventDefault();postdata
            // console.log (postData);


            $.post('../config/commit.php', postData, function (response) {

                // console.log (response);

                // Error por campos vacios.
                $('#bitregister').trigger('reset');
                $('#search-patients').hide();
                $('#history-patient').hide();


            });
        }


    }
});

$('#reportSamebitModal').on('click', function () {
    if (user.privilegeSet != 'root' && user.privilegeSet != 'administrador') {
        Swal.fire({
            icon: 'error',
            title: 'ACCESO RESTRINGIDO',
            text: 'El usuario no está autorizado',
            timer: 5000
        })
        return false;
    }

    $('#modal-report').modal('show');

    $('#recordsSummary').DataTable({

        "ajax": {
            "method": "GET",
            "url": '../config/getPriorities.php',
        },
        "columns": [
            { "data": "FECHA_COMENTARIO" },
            { "data": "HORA_COMENTARIO" },
            { "data": "CC" },
            { "data": "PACIENTE" },
            { "data": "ENVIADO_POR" },
            { "data": "IPS" },
            { "data": "EPS" },
            { "data": "RANGO" },
            { "data": "DIAGNOSTICO" },
            { "data": "APROBADO" },
            { "data": "FECHA_CITA" },
            { "data": "CREADO_POR" }
        ],
        "paging": true,
        'scrollY': '300px',
        'scrollX': '300px',
        'scrollCollapse': true,
        responsive: true,
        'destroy': true,
        "deferRender": true,
        "orderClasses": false,
        "lengthMenu": [30, 50, 100, 200], /*"All"*/
        // "processing": true,
        "language": {
            "url": "http://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }

    });

});
