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
        dataType: 'json',
        success: function (data) {
            $('#loader').addClass('hidden');

            if (data.Tipo === 'success' && data.nombreusuario !== '') {
                localStorage.setItem('user', JSON.stringify(data));
                window.location.href = data.url;
                return;
            }

            Swal.fire({
                icon:  data.Tipo,
                title: data.Title,
                text:  data.Mensaje,
                timer: 4000
            });
        },
        error: function (xhr, status, error) {
            $('#loader').addClass('hidden');
            console.error('Login error:', status, error);
            console.error('Response:', xhr.responseText);
            Swal.fire({
                icon:  'error',
                title: 'Error de conexión',
                text:  'No se pudo conectar con el servidor: ' + (error || 'Error desconocido'),
                timer: 4000
            });
        }
    });
});
