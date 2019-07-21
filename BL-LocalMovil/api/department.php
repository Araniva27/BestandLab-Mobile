<?php
//Inclusión de archivos: database, validator y modelo de departamentos
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/department.php');
//Verificación de existencia de una accion
if(isset($_GET['action'])){
    //Inicio de sesión
    session_start();
    //Instanciación de clase Departmento
    $depto = new Department;
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    //Switch para verificar la accion que se desea realizar
    switch ($_GET['action']) {
        //Caso para leer todos los datos de la tabla de departamentos
        case 'read':
            if($result['dataset'] = $depto->readDepartment()){//Ejecución del método para leer departamentos
                $result['status'] = 1;                
            } else {
                $result['exception'] = 'No hay departamentos disponibles';
            } 
            break; 
        //Caso para verificar el nombre existente
        case 'checkName':
            if($depto->setDepto($_POST['value'])){//Asignación del nombre del departamento
                if($depto->checkName()){//Verficación si existe el nombre
                    $result['status'] = 1;
                    $result['message'] = 'Nombre existente';
                }
            }
            break;
        //Caso para crear un nuevo departamento
        case 'create':
            $_POST = $depto->validateForm($_POST);//Validación de espacios en blanco      
            if ($depto->setDepto($_POST['createName'])) {//Asigancion del nombre de departamento
                if(!$depto->checkName()){//Verificación si existe el nombre
                    if($depto->createDepartment()){//Ejecución del método para crear un nuevo departamento
                        $result['status'] = 1;
                        $result['message'] = 'Departamento registrado correctamente';
                    }else{
                        $result['exception'] = 'Operación fallida';
                    }
                } else {
                    $result['exception'] = 'Nombre existente';
                }
            } else {
                $result['exception'] = 'Nombre incorrecto';
            }
            break;  
        case 'get':
        //Caso para obtener los datos de un departamento en especifico
            if ($depto->setId($_POST['codigo'])) {//Asignación del id del registro del cual se quieren conocer los datos
                if ($result['dataset'] = $depto->getDepartment()) {//Ejecución del método para obtener datos del departamento
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'Departamento inexistente';
                }
            } else {
                $result['exception'] = 'Departamento incorrecto';
            }
            break;
        //Caso para verificar el nombre a la hora de modificar
        case 'checkUpdateName':
            if($depto->setId($_POST['identifier'])){//Asignación del nombre del departamento
                if($depto->setDepto($_POST['value'])){//Verificación del nombre 
                    if($depto->checkUpdateName()){//Ejecución del método para verificar el nombre
                        $result['status'] = 1;
                        $result['message'] = 'Nombre existente';
                    }
                }
            }
            break;
        //Caso para actualizar datos de un registro en especifico
        case 'update':
            $_POST = $depto->validateForm($_POST);//Validación de espacios en blanco en formulario
            if($depto->setId($_POST['idDepto'])){//Asignación del id del departamento a modificar
                if($depto->setDepto($_POST['updateName'])){//Asignación del nombre del departamento a modificar
                    if(!$depto->checkUpdateName()){//Verificación del nombre
                        if($depto->updateDepartment()){//Ejecución del método para actualizar datos del departamento
                            $result['status'] = 1;
                            $result['message'] = 'Departamento actualizado correctamente';
                        } else {
                            $result['exception'] = 'Operación fallida';
                        }
                    } else {
                        $result['exception'] = 'Nombre existente';
                    }
                } else {
                    $result['exception'] = 'Nombre incorrecto';
                }
            } else {
                $result['exception'] = 'Departamento incorrecto';
            }
            break;   
        //Caso para eliminar un departamento
        case 'delete':
            if ($depto->setId($_POST['identifier'])) {//Asignación del id del departamento a modificar
                if ($depto->getDepartment()){//Obtencion del departamento
                    if($depto->setEstado(0)){//Asignación del estado de eliminacion del departamento
                        if ($depto->deleteDepartment()) {//Ejecución del método para eliminar departamento
                            $result['status']=1;
                            $result['message'] = 'Departamento eliminado correctamente';
                        } else {
                            $result['exception'] = 'Operación fallida';
                        }
                    }                           
                } else {
                    $result['exception'] = 'Departamento inexistente';
                }
            } else {
                $result['exception'] = 'Departamento incorrecto';
            }
            break;
        default:
            exit('Acción no disponible');
    }
    print(json_encode($result));
} else {
    exit('Recurso denegado');
}
?>     