localStorage.removeItem('user');

$("#login").submit(function (e) {
    e.preventDefault();
    $('#loader').removeClass('hidden');

    var parametros = {
        'Accion': 'login',
        'name':   $("#login__username").val(),
        'pass':   $("#login__password").val()
    };

    $.ajax({
        url:  '../config/conection.php',
        type: 'POST',
        data: parametros,
        success: function (response) {
            var data = JSON.parse(response);

            if (data.Tipo === 'success' && data.nombreusuario !== '') {
                localStorage.setItem('user', JSON.stringify(data));
                location.href = data.url;
                return;
            }

            $('#loader').addClass('hidden');
            Swal.fire({
                icon:  data.Tipo,
                title: data.Title,
                text:  data.Mensaje,
                timer: 4000
            });
        },
        error: function () {
            $('#loader').addClass('hidden');
            Swal.fire({
                icon:  'error',
                title: 'Error de conexión',
                text:  'No se pudo conectar con el servidor',
                timer: 4000
            });
        }
    });
});
