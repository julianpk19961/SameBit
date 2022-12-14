localStorage.removeItem('user');

// Funcion en documento para envio de formulario login
$("#login").submit(function (e) {

    // Prevenir eventos automáticos por defecto
    e.preventDefault();
    $('#loader').removeClass('hidden');
    // var form_data = jQuery( this ).serializeArray();
    // Variables a envíar
    var parametros = {
        'Accion': 'login',
        'name': $("#login__username").val(),
        'pass': $("#login__password").val()
    };


    // Ajax
    jQuery.ajax({
        // Credenciales y datos para ejeccion
        url: '../config/conection.php',
        type: 'POST',
        data: parametros,

        success: function (response) {

            // return false;

            // Funcion para respuesta
            // Decomponer respuesta
            var data = JSON.parse(response);

            var Titulo = data.Title;
            var Mensaje = data.Mensaje;
            var Tipo = data.Tipo;
            var nombreusuario = data.nombreusuario;
            var url = data.url;

            // validaciones de sesión  
            if (Tipo == 'success' && nombreusuario != '') {
                // Mensaje de confirmación con respuesta obtenida.

                var datastr = JSON.stringify(response);
                localStorage.setItem('user', datastr);
                $('#loader').addClass('hidden');
                location.href = url;
            }

            Swal.fire({
                icon: Tipo,
                title: Titulo,
                text: Mensaje,
                timer: 5000
            })
        },
        fail: function (err) {
            // Alerta de error
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Oops parece que un cable se desconectó',
                timer: 5000
            })
        }
    });
    return false;
});