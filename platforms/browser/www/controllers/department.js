$(document).ready(function()
{
    showTable();
})

/*
* Constante para establecer comunicacion con la api de departamentos
*/
const api = 'http://192.168.1.8/BL-LocalMovil/api/department.php?action=';

/*
* Método para mostrar en la tabla todos los registros
* 
* Expects: rows, resultado de la consulta SQL para leer los registros de departamentos
* 
* Returns: ninguno 
*/
function fillTable(rows)
{
    let content = "";

    rows.forEach(function(row){
        content += `
            <tr>                                            
                <td>${row.nombre} </td>
                <td>
                    <div class="row justify-content-center align-items-center">
                        <div class="col-xs-12 button-action"> 
                            <span data-toggle="tooltip" data-placement="left" title="Actualizar">
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modificacionDepto"
                                title="Actualizar" onclick=modalUpdate(${row.codigo})><i class="fas fa-sync-alt"></i></button>
                            </span>
                        </div> &nbsp
                        <div class="col-xs-12 button-action">
                            <span data-toggle="tooltip" data-placement="right" title="Eliminar">
                            <button type="button" class="btn btn-danger" onclick="confirmDelete('${api}', ${row.codigo}, 'tblDepto')" id="eliminacionDepto"><i class="fas fa-trash"></i>
                            </button>
                            <span>
                        </div>
                    </div>
                </td>
            </tr>
        `;
    });
    $('#tbody-depto').html(content);
    initTable('tblDepto');
    initTooltip();
}

/*
* Función para leer los departamentos y agregarselos a la Función fillTable
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

/*
* Función para abrir y mostrar el formulario de registro de departamento en blanco
* 
* Expects: ninguno
* 
* Returns: ninguno
*/
function modalCreate()
{
    $('#createDepto').trigger('reset');
    $('p').text('');
    $('#registroDepto').modal('show');
}

/*
* Función para crear un nuevo departamento
* 
* Expects: ninguno
* 
* Returns: ninguno
*/
$('#createDepto').submit(function(){
    event.preventDefault();
    $.ajax({
        url: api + 'create',
        type: 'post',
        data: $('#createDepto').serialize(),
        datatype: 'json',
    })
    
    .done(function(response){
        // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
        if (isJSONString(response)) {
            const result = JSON.parse(response);
            // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
            if (result.status) {
                $('#registroDepto').modal('hide');
                destroyTable('tblDepto');
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

/*
*   Función para verificar el nombre si existe.
*
*   Expects: input(caja de texto a verificar).
*
*   Returns: ninguno.
*/
function checkCreateName(input)
{
    checkData(api + 'checkName', input, null);
}

/*
* Función para mostrar modal de actualizar y llenar los campos con los datos correspondientes
*  
* Expects: id, define el producto del cual se quieren obtener los datos
* 
* Returns: ninguno
*/
function modalUpdate(id)
{
    $.ajax({
        url: api + 'get',
        type: 'post',
        data:{
            codigo: id
        },
        datatype: 'json'
    })
    .done(function(response){
        // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
        if (isJSONString(response)) {
            const result = JSON.parse(response);
            // Se comprueba si el resultado es satisfactorio para mostrar los valores en el formulario, sino se muestra la excepción
            if (result.status) {
                $('p').text('');
                $('#updateDepto').trigger('reset');
                $('#idDepto').val(result.dataset.codigo);
                $('#updateName').val(result.dataset.nombre);                
                $('#modificacionDepto').modal('show');
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

/*
*   Función para verificar el nombre si existe para actualizar.
*
*   Expects: input(caja de texto a verificar).
*
*   Returns: ninguno.
*/
function checkUpdateName(input)
{
    checkData(api + 'checkUpdateName', input, $('#idDepto').val());
}

/*
* Función para actualizar datos del departamento
* 
* Expects: ninguno
* 
* Returns: ninguno
*/
$('#updateDepto').submit(function(){
    event.preventDefault();
    $.ajax({
        url: api + 'update',
        type: 'post',
        data: $('#updateDepto').serialize(),
        datatype: 'json'
    })
    .done(function(response){
        // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
        if (isJSONString(response)) {
            const result = JSON.parse(response);
            // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
            if (result.status) {
                $('#modificacionDepto').modal('hide');
                destroyTable('tblDepto');
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

/*
*   Función para el control de errores
*
*   Expects: response(la respuesta de ajax con el error).
*
*   Returns: ninguno.
*/
function error(response){
    switch (response) {
        case 'Dato duplicado, no se puede guardar':   
            mensaje = 'Departamento ya existente';
            break;
        default:
            mensaje = 'Ocurrio un problema, reportese con su administrador';
            break;
    }
    return mensaje;
}