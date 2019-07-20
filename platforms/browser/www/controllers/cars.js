$(document).ready(function(){
    //initTooltip();
    showTable();    
})

/*
*   Constante para establecer la ruta y parámetros de comunicación con la api de carros
*/

const api = 'http://192.168.1.8/BL-LocalMovil/api/cars.php?action=';
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
        switch(row.estado){
            case '1':
                state = 'Disponible';
            break;
            case '2':
                state = 'Ocupado';
            break;
            case '3':
                state = 'Reparación';
            break;
        }
        content += `
            <tr>        
                <td>${row.placa}</td>
                <td>${state}</td>                
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
                                <button type="button" class="btn btn-danger" onclick="confirmDelete('${api}', ${row.Codigo}, 'tblCars')"><i class="fas fa-trash"></i>
                                </button>
                                <span>
                        </div>
                    </div> 
                </td>
            </tr>
        `;
    });
    $('#tbody-cars').html(content);
    initTable('tblCars');
    $('[data-toggle="tooltip"]').tooltip();
}
/**
 * Funcion para leer los registros de la tabla carros
 * 
 * Expects: rows, los registros obtenidos de la consulta sql
 * 
 * Returns:ninguno 
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

/**
 * Funcion para abrir el modal de registro
 * 
 * Expects: ninguno
 * 
 * Returns: ninguno
 */
function modalCreate()
{
    $('#createCar')[0].reset();    
    $('#registroVehiculo').modal('show');
}

/**
 * Funcion para crear un nuevo vehiculo
 * 
 * Expects: ninguno
 * 
 * Returns: ninguno
 */
$('#createCar').submit(function(){
    event.preventDefault();
    $.ajax({
        url: api + 'create',
        type: 'post',
        data: $('#createCar').serialize(),
        datatype: 'json',
    })
    .done(function(response){
        // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
        if (isJSONString(response)) {
            const result = JSON.parse(response);
            // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
            if (result.status) {
                $('#registroVehiculo').modal('hide');
                //destroyTable('tblCars');
                showTable();
                sweetAlert(1, result.message, null);
            } else {
                sweetAlert(2, result.exception, null);
            }
        } else {
            console.log(response);
            //sweetAlert(2,error(response),null);
        }
    })
    .fail(function(jqXHR){
        // Se muestran en consola los posibles errores de la solicitud AJAX
        console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
    });
})

/**
 * Funcion para cargar el modal de actualizar
 * 
 * Expects: id (identificador del vehiculo)
 * 
 * Returns: ninguno
 */
function modalUpdate(id)
{
    $.ajax({
        url: api + 'get',
        type: 'post',
        data: {
            identifier: id
        },
        datatype: 'json'
    })
    .done(function(response){
        //Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
        if (isJSONString(response)) {
            const result = JSON.parse(response);
            // Se comprueba que hay acciones registrados para el tipo de usuario
            if (result.status) {
                $('#updateCar')[0].reset();
                $('#idCar').val(result.dataset.Codigo);
                var array = result.dataset.Placa.split(' ');
                fillType(array[0]);
                $('#updateNum').val(array[1]);
                fillStatus(result.dataset.Estado);
                $('#actualizarVehiculo').modal('show'); 
            } else {
                sweetAlert(3, result.exception, null);
            }
        } else {
            console.log(response);
            sweetAlert(3,error(response),null);
        }
    })
    .fail(function(jqXHR){
        //Se muestran en consola los posibles errores de la solicitud AJAX
        console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
    });
}

/**
 * Funcion para cargar el comobox de modificar
 * 
 * Expects: type (el tipo de placa)
 * 
 * Returns: ninguno
 */
function fillType(type)
{
    let content = `
        <option value="${type}" selected>${type}</option>
    `;
    switch (type){
        case 'P':
            content +=`
                <option value="MB"> MB</option>
                <option value="A">A</option>
            `;
            break;
        case 'MB':
            content += `
                <option value="P">P</option>
                <option value="A">A</option>
            `;
            break;
        case 'A':
                content += `
                <option value="P">P</option>
                <option value="MB">MB</option>
            `;
            break;
    }
    $('#updatePlate').html(content);
}

/**
 * Funcion para cargar el combobox de modificar el estado
 * 
 * Expects: status (estado del vehiculo)
 * 
 * Returns: ninguno
 */
function fillStatus(status)
{
    let cont = '';
    switch (status){
        case '1':
            cont +=`
                <option value="1" selected>Disponible</option>
                <option value="2">Ocupado</option>
                <option value="3">Reparación</option>
            `;
            break;
        case '2':
            cont += `
                <option value="2" selected>Ocupado</option>
                <option value="1">Disponible</option>
                <option value="3">Reparación</option>
            `;
            break;
        case '3':
            cont += `
                <option value="3" selected>Reparación</option>
                <option value="1">Disponible</option>
                <option value="2">Ocupado</option>
            `;
            break;
    }
    $('#updateStatus').html(cont);
}

/**
 * Funcion para modificar un nuevo vehiculo
 * 
 * Expects: ninguno
 * 
 * Returns: ninguno
 */
$('#updateCar').submit(function(){
    event.preventDefault();
    $.ajax({
        url: api + 'update',
        type: 'post',
        data: $('#updateCar').serialize(),
        datatype: 'json',
    })
    .done(function(response){
        // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
        if (isJSONString(response)) {
            const result = JSON.parse(response);
            // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
            if (result.status) {
                $('#actualizarVehiculo').modal('hide');
                destroyTable('tblCars');
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
            mensaje = 'Carro ya existente';
            break;
        default:
            mensaje = 'Ocurrio un problema, reportese con su administrador';
            break;
    }
    return mensaje;
}