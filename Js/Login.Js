$("#login").submit(function(e){
	e.preventDefault();
    $('#loader').removeClass('hidden');
    var form_data = jQuery( this ).serializeArray();
    // console.log(form_data);
     jQuery.ajax({
        url: '../config/conection.php',
        type : 'post',
        data : form_data,
        success : function( response ) {
            console.log( response );
            if(response ==1 ){ 
                $('#loader').addClass('hidden');
                location.href = 'http://localhost/samebit/pages/dashboard.php';
            }
            else{
                confirm('Usuario o contraseña invalidos')
            }
        },
        fail : function( err ) {
            alert( "There was an error: " + err );
        }
    });
    return false;
});