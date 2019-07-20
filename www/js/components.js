/*
*   Función para manejar los mensajes de notificación al usuario.
*
*   Expects: type (tipo de mensaje), text (texto a mostrar) y url (dirección para enviar).
*
*   Returns: ninguno.
*/
function sweetAlert(type, text, url)
{
    switch (type) {
        case 1:
            title = "Éxito";
            icon = "success";
            break;
        case 2:
            title = "Error";
            icon = "error";
            break;
        case 3:
            title = "Advertencia";
            icon = "warning";
            break;
        case 4:
            title = "Aviso";
            icon = "info";
    }
    if (url) {
        swal({
            title: title,
            text: text,
            icon: icon,
            button: 'Aceptar',
            closeOnClickOutside: false,
            closeOnEsc: false
        })
        .then(function(value){
            location.href = url
        });
    } else {
        swal({
            title: title,
            text: text,
            icon: icon,
            button: 'Aceptar',
            closeOnClickOutside: false,
            closeOnEsc: false
        });
    }
}

/*
*   Función para inicializar dataTable y cambiar su idioma
*
*   Expects: id (identificador de la tabla)
*
*   Return ninguno.
*/

function initTable(id)
{
    var table = $('#'+id).DataTable({
        "oLanguage":{
          "sProcessing": "Procesando",
          "sLengthMenu": 'Mostrar <select class="form-control" >'+
            '<option value="10">10</option>'+
            '<option value="20">20</option>'+
            '<option value="30">30</option>'+
            '<option value="40">40</option>'+
            '<option value="50">50</option>'+
            '</select> registros',
          "sZeroRecords": "No se encontraron resultados",
          "sEmptyTable": "Ningún dato disponible en esta tabla",
          "sInfo": "Mostrando del (_START_ al _END_) de un total de _TOTAL_ registro",
          "sInfoEmpty": "Mostrando del 0 al 0 de un total de 0 registro",
          "sInfoFiltered": "(Filtrando de un total de _MAX_ registro)",
          "sInfoPostFix": "",
          "sSearch": "Buscar:",
          "sUrl": "",
          "sInfoThousands": ",",
          "sLoadingRecords": "Por favor espere",
          "oPaginate":{
              "sFirst": "Primero",
              "sLast": "Último",
              "sNext": "Siguiente",
              "sPrevious": "Anterior"
          },
          "aria": {
            "sortAscending":	"Ordenación ascendente",
            "sortDescending":	"Ordenación descendente"
          }
        },
        retrieve: true,
        
      });
}

/*
*   Función para destruir el dataTable 
*
*   Expects: id (identificador de la tabla)
*
*   Return ninguno.
*/

function destroyTable(id)
{
    $('#'+id).dataTable().fnDestroy();
}

/*
*   Función para inicializar los tooltips
*
*   Expects: ninguno.
*
*   Return ninguno.
*/
function initTooltip()
{
    $('[data-toggle="tooltip"]').tooltip();
}

/*
*   Función para cargar las opciones en un select de formulario.
*
*   Expects: api (origen de los datos a mostrar), id (identificador del select en el formulario) y selected (valor seleccionado).
*
*   Returns: ninguno.
*/
function fillSelect(api, id, selected)
{
    $.ajax({
        url: api,
        type: 'post',
        data: null,
        datatype: 'json'
    })
    .done(function(response){
        // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
        if (isJSONString(response)) {
            const result = JSON.parse(response);
            // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
            if (result.status) {
                let content = '';
                if (!selected) {
                    content += '<option value="" disabled selected>Seleccione una opción</option>';
                }
                result.dataset.forEach(function(row){
                    value = Object.values(row)[0];
                    text = Object.values(row)[1];
                    if (Object.values(row)[0] != selected) {
                        content += `<option value="${value}">${text}</option>`;
                    } else {
                        content += `<option value="${value}" selected>${text}</option>`;
                    }
                });
                $('#' + id).html(content);
            } else {
                $('#' + id).html('<option value="">No hay opciones</option>');
            }
        } else {
            console.log(response);
        }
    })
    .fail(function(jqXHR){
        // Se muestran en consola los posibles errores de la solicitud AJAX
        console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
    });
}

/*
*   Función para eliminar un registro seleccionado
*
*   Expects: api (ruta del servidor para borrar un registro), id (identificador del registro a eliminar) y file (nombre del arhivo a eliminar).
*
*   Returns: ninguno.
*/
function confirmDelete(api, id , table)
{
    swal({
        title: 'Advertencia',
        text: '¿Desea eliminar el registro?',
        icon: 'warning',
        buttons: ['Cancelar', 'Aceptar'],
        closeOnClickOutside: false,
        closeOnEsc: false
    })
    .then(function(value){
        if (value) {
            $.ajax({
                url: api + 'delete',
                type: 'post',
                data: {identifier: id},
                datatype: 'json'
            })
            .done(function(response){
                // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
                if (isJSONString(response)) {
                    const result = JSON.parse(response);
                    // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
                    if (result.status) {
                        destroyTable(table);
                        showTable();
                        sweetAlert(1, result.message, null);
                    } else {
                        sweetAlert(2, result.exception, null);
                    }
                } else {
                    console.log(response);
                }
            })
            .fail(function(jqXHR){
                // Se muestran en consola los posibles errores de la solicitud AJAX
                console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
            });
        }
    });
}

/*
*   Función para manejar la revisión de un dato en la base de datos.
*
*   Expects: api (ruta del servidor para buscar), input (valor a buscar) y data (Nombre del dato).
*
*   Returns: ninguno.
*/
function checkData(api, input, id)
{
    $.ajax({
        url: api,
        type: 'post',
        data: {
            value: $('#'+(input.id)).val(),
            identifier: id
        },
        datatype: 'json',
    })
    .done(function(response){
        // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
        if (isJSONString(response)) {
            const result = JSON.parse(response);
            // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
            if (result.status) {
                $('#'+(input.id)+'Error').text(result.message);
            }
        }  else {
            console.log(response);
        }
    })
    .fail(function(jqXHR){
        // Se muestran en consola los posibles errores de la solicitud AJAX
        console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
    });   
}

function checkDatas(api, dataset,id)
{
    $.ajax({
        url: api,
        type: 'post',
        data: dataset,
        datatype: 'json',
    })
    .done(function(response){
        // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
        if (isJSONString(response)) {
            const result = JSON.parse(response);
            // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
            if (result.status) {
                $('#'+id).text(result.message);
            } else {
                $('#'+id).text('');
            }
        }  else {
            console.log(response);
        }
    })
    .fail(function(jqXHR){
        // Se muestran en consola los posibles errores de la solicitud AJAX
        console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
    });   
}
  
