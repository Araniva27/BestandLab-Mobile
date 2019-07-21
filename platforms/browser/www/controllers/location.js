$(document).ready(function(){   
    showTable();    
    fillSelect(departamento, 'createDepto', null);
    sweetAlert();
})

/**
 * Constante  para establecer las rutas y parámetros de comunicación con la API de ubicacion y departamento
 */
const api = 'http://192.168.1.8/BL-LocalMovil/api/location.php?action=';
const departamento = 'http://192.168.1.8/BL-LocalMovil/api/department.php?action=read';

/**
 * Funcion para abrir el modal de registro, limpiarlo y cargar la lista desplegable de departamentos
 * 
 * Expects: ninguno
 * 
 * Returns: ninguno
 */
function modalCreate()
{
    $('#createLocation').trigger("reset");
    $('p').text('');
    fillSelect(departamento, 'createDepto', null);
    $('#registroUbicacion').modal('show');
}
/**
 * 
 * Funcion para llenar la dabla de datos provenientes de la consulta SQL
 * 
 * Expects: rows, los registros obtenidos de la consulta sql
 * 
 * Returns:ninguno 
 */
function fillTable(rows)
{
    let content = "";
    rows.forEach(function(row){       
        content += `
            <tr>        
                <td>${row.Nombre}</td>
                <td>${row.Depto}</td>                
                <td>
                    <div class="row justify-content-center align-items-center">
                        <div class="col-xs-12">
                            <span data-toggle="tooltip" data-placement="left" title="Actualizar">
                                <button type="button" class="btn btn-info" title="Actualizar" onclick="modalUpdate(${row.Codigo})"><i
                                        class="fas fa-sync-alt"></i></button>
                            </span>
                        </div> &nbsp
                        <div class="col-xs-12">
                            <span data-toggle="tooltip" data-placement="right" title="Eliminar">
                                <button type="button" class="btn btn-danger" onclick="confirmDelete('${api}', ${row.Codigo}, 'tblUbi')"><i class="fas fa-trash"></i>
                                </button>
                                <span>
                        </div>
                    </div> 
                </td>
            </tr>
        `;
    });
    $('#tbody-location').html(content);
    initTable('tblUbi');
    $('[data-toggle="tooltip"]').tooltip();
}
/**
 * Funcion para leer registros de la tabla ubicacion
 * 
 * Expects: ninguno
 * 
 * Returns: ninguno
 */
function showTable()
{
    $.ajax({
        url: api + 'read',
        type: 'post',
        data: null,
        datatype: 'json'
    })
    
    .done(function(response){
        // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
        if (isJSONString(response)) {
            const result = JSON.parse(response);
            // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
            if (!result.status) {
                sweetAlert(4, result.exception, null);
            }
            fillTable(result.dataset);
        } else {
            console.log(response);
            sweetAlert(2,error(response),null);
        }
    })
    .fail(function(jqXHR){
        // Se muestran en consola los posibles errores de la solicitud AJAX
        console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
    });
}

function checkLocation()
{
    checkDatas(api+'checkLocation', $('#createLocation').serialize(),'locationError');
    //checkDatas(api+'checkLocation', {name: $('#createName').val(), depto:$('#createDepto').val()});
}



/**
 * Funcion para registrar una nueva ubicacion
 * 
 * Expects: ninguno
 * 
 * Returns: ninguno
 */
$('#createUbi').submit(function(){
    event.preventDefault();
    $.ajax({
        url: api + 'create',
        type: 'post',
        data: $('#createUbi').serialize(),
        datatype: 'json',
    })
    .done(function(response){
        // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
        if (isJSONString(response)) {
            const result = JSON.parse(response);
            // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
            if (result.status) {
                $('#registroUbicacion').modal('hide');
                destroyTable('tblUbi');
                showTable();
                sweetAlert(1, result.message, null);
            } else {
                sweetAlert(2, result.exception, null);
            }
        } else {
            console.log(response);
            sweetAlert(2,error(response),null);
        }
    })
    .fail(function(jqXHR){
        // Se muestran en consola los posibles errores de la solicitud AJAX
        console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
    });
})
/**
 * Funcion para abrir modal y llenar los campos con la informacion del registro correspondiente
 * 
 * Expects: id, define la ubicacion de la cual se quieren obtener los datos
 * 
 * Returns: ninguno
  */
function modalUpdate(id){
    $.ajax({
        url: api + 'get',
        type: 'post',
        data:{
            Codigo: id
        },
        datatype: 'json'
    })
    .done(function(response){
        // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
        if (isJSONString(response)) {
            const result = JSON.parse(response);
            // Se comprueba si el resultado es satisfactorio para mostrar los valores en el formulario, sino se muestra la excepción
            if (result.status) {
                $('#updateLocation').trigger("reset");
                $('p').text('');
                $('#idLocation').val(result.dataset.Codigo);
                $('#updateName').val(result.dataset.Nombre);
                fillSelect(departamento, 'updateDepto', result.dataset.Depto);
                $('#modificacionUbicacion').modal('show');
            } else {
                sweetAlert(2, result.exception, null);
            }
        } else {
            console.log(response);
            sweetAlert(2,error(response),null);
        }
    })
    .fail(function(jqXHR){
        // Se muestran en consola los posibles errores de la solicitud AJAX
        console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
    });
}

function checkLocationUpdate()
{
    checkDatas(api+'checkLocationUpdate', $('#updateLocation').serialize(),'locationErrorUpdate');
    //checkDatas(api+'checkLocation', {name: $('#createName').val(), depto:$('#createDepto').val()});
}

/**
* Funcion para modificar datos de ubicacion
*
*Expects : ninguno
*
*Returns: ninguno
 */
$('#updateUbi').submit(function()
{
    event.preventDefault();
    $.ajax({
        url: api + 'update',
        type: 'post',
        data: new FormData($('#updateUbi')[0]),
        datatype: 'json',
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(response){
        // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
        if (isJSONString(response)) {
            const result = JSON.parse(response);
            // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
            if (result.status) {
                destroyTable('tblUbi');
                $('#modificacionUbicacion').modal('hide');
                showTable();
                sweetAlert(1, result.message, null);
            } else {
                sweetAlert(2, result.exception, null);
            }
        } else {
            console.log(response);
            sweetAlert(2,error(response),null);
        }
    })
    .fail(function(jqXHR){
        // Se muestran en consola los posibles errores de la solicitud AJAX
        console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
    });
})

function error(response){
    switch (response) {
        case 'Dato duplicado, no se puede guardar':   
            mensaje = 'Ubicación ya existente';
            break;
        default:
            mensaje = 'Ocurrio un problema, reportese con su administrador';
            break;
    }
    return mensaje;
}
