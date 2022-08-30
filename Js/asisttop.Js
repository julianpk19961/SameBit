var d = new Date();

// // $("input:radio[name='pregunta']:checked").val();

// guardar todos los datos.
$('#saveForm').click(() => {
    var datas = {
        tda: $('#tipoAtencion'),
        fac: $('#fechaActual'),
        fat: $('#fechaAtencion'),
        man: $('#nombreCompleto'),
        edd: $('#edadEncuestado'),
        idn: $('#idIdentificacion'),
        tel: $('#telefono'),
        eps: $('#epsPaciente'),
        p01: $('input:radio[name=pregunta1]:checked'),
        p02: $('input:radio[name=pregunta2]:checked'),
        p03: $('input:radio[name=pregunta3]:checked'),
        p04: $('input:radio[name=pregunta4]:checked'),
        p05: $('input:radio[name=pregunta5]:checked'),
        p06: $('input:radio[name=pregunta6]:checked'),
        p07: $('input:radio[name=pregunta7]:checked'),
        p08: $('input:radio[name=pregunta8]:checked'),
        p09: $('input:radio[name=pregunta9]:checked'),
        p10: $('input:radio[name=pregunta10]:checked'),
        p11: $('input:radio[name=pregunta11]:checked'),
        p12: $('input:radio[name=pregunta12]:checked'),
        p13: $('#pregunta13'),
        p14: $('#pregunta14'),
        com: $('#comentarios'),
    }
    console.log('ejecutando...')
    console.log(datas)
    console.log(d)
});

// $('#clearForm').click(()=>{
//   alert('hola mundos')
// });

