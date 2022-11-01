$('#medicineStored').submit(function (e) {
    e.preventDefault();
    
    let emptyfields = '';
    let values = '';
    $name = $('#name').val();

    if ( $name.length == 0) {
        emptyfields += 'Nombre,'
    }

    if (emptyfields){
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Los siguientes campos están vacios: ' + emptyfields.slice(0,-1),
            // timer: 5000
          })
          return false;
    }

    const postdata = {
        code: $('#code').val(),
        name: $('#name').val(),
        reference: $('#reference').val(),
        observation: $('#observation').val()
    };

    stored(postdata);

});


function stored(postdata){

    console.log(postdata);

    $.post('../config/medicinestored.php',postdata,function (response){
        
        if (response.includes('Error')) {
            alert (response);
            return false
        }
    
    });
}