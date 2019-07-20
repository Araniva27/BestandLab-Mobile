$(document).ready(function() 
{ 
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); 
});
/*
*   Función para comprobar si una cadena de caracteres tiene formato JSON.
*
*   Expects: value (valor de la cadena de caracteres que se desea verificar).
*
*   Returns: true si el valor es correcto y false en caso contrario.
*/
function isJSONString(value)
{
    try {
        if (value != "[]") {
            JSON.parse(value);
            return true;
        } else {
            return false;
        }
    } catch(error) {
        return false;
    }
}
/*
*   Función para validar la imagen
*
*   Expects: id (Identificador del input) y label (Identificador de label)
*
*   Return ninguno.
*/
function validateImage(id,label)
{
    var foto = $('#'+id).val();
    if( foto != ''){
        var array = foto.split('fakepath');
        var allowedExtensions = /(.jpg|.jpeg|.png|.gif)$/i;
        if(!allowedExtensions.exec(array[1])){
            $('#'+label).text('Elija una imagen');
        } else {
            $('#'+label).text(array[1]);
        }
    } else {
        $('#'+label).text('Elija una imagen');
    }
}

function destroyDate(id){
    $('#'+id).datepicker('destroy');
}

/*
*   Función para inicializar los datapicker validados para fecha de nacimiento
*
*   Expects: id (Identificador de la Tabla)
*
*   Return ninguno.
*/
function initDateBirth(id)
{
    var hoy = new Date();
    $('#'+id).datepicker({
        uiLibrary: 'bootstrap4',
        maxDate: new Date(hoy.getFullYear()-18,hoy.getMonth(),hoy.getDate()),
        minDate: new Date(hoy.getFullYear()-99,hoy.getMonth(),hoy.getDate()),
        appendText: 
          ' <br/>Haga click para introducir una fecha<br>'+
          'Para cambiar de mes, clic en los extremos superiores del calendario'
    });
}

function initDateActive(id)
{
    var hoy = new Date();
    $('#'+id).datepicker({
        uiLibrary: 'bootstrap4',
        maxDate: new Date(hoy.getFullYear(),hoy.getMonth(),hoy.getDate()),
        minDate: new Date(hoy.getFullYear()-99,hoy.getMonth(),hoy.getDate()),
        appendText: 
          ' <br/>Haga click para introducir una fecha<br>'+
          'Para cambiar de mes, clic en los extremos superiores del calendario'
    });
}

/*
*   Función para inicializar los datapicker validados para empresa que sean cliente
*
*   Expects: id (Identificador de la Tabla)
*
*   Return ninguno.
*/
function initDateCustomer(id)
{
    var hoy = new Date();
    $('#'+id).datepicker({
        uiLibrary: 'bootstrap4',
        maxDate: new Date(hoy.getFullYear(),hoy.getMonth()-6,hoy.getDate()),
        minDate: new Date(hoy.getFullYear()-99,hoy.getMonth(),hoy.getDate()),
        appendText: 
          ' <br/>Haga click para introducir una fecha<br>'+
          'Para cambiar de mes, clic en los extremos superiores del calendario'
    });
}

function validateAlphanumeric(input, minLength ,maxLength, error)
{
    var value = $('#'+(input.id)).val();
    if(value != ''){
        if(/^[a-zA-Z0-9ñÑáÁéÉíÍóÓúÚ\s\.\,]+$/.test(value)){
            if(value.length >= minLength && value.length <= maxLength){
                $('#'+(input.id)+'Error').text('');
            } else {
                $('#'+(input.id)+'Error').text('Logitud incorrecta');
            }
        } else {
            $('#'+(input.id)+'Error').text(error);
        }
    } else {
        $('#'+(input.id)+'Error').text('Campo vacio');
    }
}

function validateAlphabetic(input, minLength ,maxLength, error)
{
    var value = $('#'+(input.id)).val();
    if(value != ''){
        if(/^[a-zA-ZñÑáÁéÉíÍóÓúÚ\s]+$/.test(value)){
            if(value.length >= minLength && value.length <= maxLength){
                $('#'+(input.id)+'Error').text('');
            } else {
                $('#'+(input.id)+'Error').text('Logitud incorrecta');
            }
        } else {
            $('#'+(input.id)+'Error').text(error);
        }
    } else {
        $('#'+(input.id)+'Error').text('Campo vacio');
    }
}

function validateNit(input)
{
    var value = $('#'+(input.id)).val();
    if(value != ''){
        if(/^([0-9]{4})(-)([0-9]{6})(-)([0-9]{3})(-)([0-9]{1})$/.test(value)){
            $('#'+(input.id)+'Error').text(' ');
        } else {
            $('#'+(input.id)+'Error').text('NIT incorrecto');
        }
    } else {
        $('#'+(input.id)+'Error').text('Campo Vacio');
    }
}

function validateNrc(input)
{
    var value = $('#'+(input.id)).val();
    if(value != ''){
        if(/^([0-9]{6})(-)([0-9]{1})$/.test(value)){
            $('#'+(input.id)+'Error').text(' ');
        } else {
            $('#'+(input.id)+'Error').text('NRC incorrecto');
        }
    } else {
        $('#'+(input.id)+'Error').text('Campo Vacio');
    }
}

function validatePhone(input)
{
    var value = $('#'+(input.id)).val();
    if(value != ''){
        if(/^([2,6,7][0-9]{3})(-)([0-9]{4})$/.test(value)){
            $('#'+(input.id)+'Error').text(' ');
        } else {
            $('#'+(input.id)+'Error').text('Teléfono incorrecto');
        }
    } else {
        $('#'+(input.id)+'Error').text('Campo Vacio');
    }
}

function validateEmail(input)
{
    var value = $('#'+(input.id)).val();
    if(value != ''){
        if(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)){
            $('#'+(input.id)+'Error').text(' ');
        } else {
            $('#'+(input.id)+'Error').text('Correo incorrecto');
        }
    } else {
        $('#'+(input.id)+'Error').text('Campo Vacio');
    }
}

function validatePlate(input)
{
    var value = $('#'+(input.id)).val();
    if(value != ''){
        if(/^(([0-9]{1,3})(-)([0-9]{3}))$/.test(value)){
            $('#'+(input.id)+'Error').text(' ');
        } else {
            $('#'+(input.id)+'Error').text('Número incorrecto');
        }
    } else {
        $('#'+(input.id)+'Error').text('Campo Vacio');
    }
}





