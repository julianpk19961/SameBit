
// SOLICITUD DE DATOS Y MODIFICACION DE ATRIBUTO H1 DENTRO DE DIV #TITTLE_CV
// do {
//     session_user = prompt('Bienvenido, Por favor ingrese su nombre de usuario');
// }while ( !session_user )

// bienvenida = 'Bienvenido '+ session_user ;
// alert('Hola' + session_user + ' que bueno verte de nuevo');
// tituloDocumento = document.querySelector('#title_cv > h1');
// console.log(tituloDocumento);
// tituloDocumento.innerHTML = bienvenida;


// CALCULO POTENCIA

// let base = prompt('Ingrese por favor la base');
// let exponente = prompt('Ingrese por favor el exponente');
// let total = 1;

// 5
// 3
// 5*5*5

// for(let i = 1 ; i <= exponente ; i++){
//     total = total * base;
// }

// console.log ('El resultado de : '+ base + ' eleveado a: '+ exponente + ' Es igual a : ' + total);


// DIBUJA TRIANGULO
// n = prompt('Por favor ingrese el numero de niveles');
// FORMA#1
// cadena = '-*-';
// for (let i = 1; i < n ; i++) {
//     salida = cadena.repeat(i);
//     console.log(salida);    
// }

// FROMA#2
// for (let i = 1; i <= n ; i++) {

//     let cadena = '';
//     for (let j = 1 ; j<=i ; j++){
//         cadena += '*';
//     }
//     console.log(cadena);
// }


// CICLO CON EXIT Y VALIDACION PARA SALTAR EXCEPCIONES (continue)
// for (let i = 0; i < 100; i++) {
//     let nombre = prompt('Ingrese su nombre');

//     if (nombre.toUpperCase() == 'EXIT') {
//         break;
//     }

//     if (nombre.length == 0) {
//         continue
//     }

//     console.log(nombre);
// }
// console.log('Salida del ciclo')


// Funcion factorial 
// function factorial (int = 0 ){
//    result = 1;
//    for ( let i = 1; i <= int; i++ ){
//     result *= i
//    }
// //    console.log('El resultado es:' + result);
//    return result;
// }

// n = prompt('A qué número desea aplicar el factorial(!)')
// result = factorial(n);
// console.log( 'El resultado de !'+ (n === '' ? 0 : n) +' = ' + result);

// Funciones y funciones con atajo (flecha)

// #modo1
// function Myname () {
//     console.log('Funcion estandar: Nombre');
//     console.log('function Myname(params) {}');
// } 

// // #modo2
// Mylastname=()=>{
//     console.log('Funcion con atajo (flecha): Lastname');
//     return'Mylastname=(params)=>{}';
// }

// // #modo3
// Myage=()=>console.log('Funcion con atajo reducido (flecha no {}): Age'+' Myage=(params)=>result');

// Myname();
// Msg = Mylastname();
// console.log(Msg);
// Myage();