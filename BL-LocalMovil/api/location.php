<?php
//Inclusión de archivos: database, validator y modelo location
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/location.php');
//Verificación de existencia de una accion
if(isset($_GET['action'])){
    session_start();//Inicio de sesion
    ini_set('date.timezone', 'America/El_Salvador');//Inicializacion de fecha y hora
    
    $location = new Location;//Instanciacion de clase Location
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    //Switch para determinar la ccion a realizar
    switch ($_GET['action']) {
        //Caso para leer todos los registros de ubicación    
        case 'read':
            if($result['dataset'] = $location->readLocation()){//Ejecucion de metodo para leer las ubicaciones
                $result['status'] = 1;                
            }else{
                $result['exception'] = 'No hay ubicaciones registradas';
            }
            break;  
        case 'readDepartamentLocation':
            if($result['dataset'] = $location->readDepartamentLocation()){//Ejecucion de metodo para leer las ubicaciones
                $result['status'] = 1;                
            }else{
                $result['exception'] = 'No hay ubicaciones registradas';
            }
            break;       
        case 'checkLocation':
            if(isset($_POST['createDepto'])){
                if($location->setDepto($_POST['createDepto'])){
                    if($location->setNombre($_POST['createName'])){
                        if($location->checkLocation()){
                            $result['status'] = 1;
                            $result['message'] = 'Ubicación existente';
                        }
                    }
                }
            }
            break;
        case 'create':        
            //Caso para crear una nueva ubicacion
            $_POST = $location->validateForm($_POST);//Validacion de espacios en blanco en el formulario
            if(isset($_POST['createDepto'])){
                if($location->setNombre($_POST['createName'])){//Asignacion de nombre de ubicacion
                    if($location->setDepto($_POST['createDepto'])){//Asignacion de departamento donde se encuentra la ubicacion
                        if(!$location->checkLocation()){
                            if($location->createLocation()){//Ejecuccion del metodo para crear ubicacion
                                $result['status'] = 1;
                                $result['message'] = 'Ubicación registrada correctamente';
                            }else{
                                $result['exception'] = 'Operación fallida';
                            }
                        } else {
                            $result['exception'] = 'Ubicación existente';    
                        }
                    }else{
                        $result['exception'] = 'Departamento incorrecto';
                    }
                }else{
                    $result['exception'] = 'Nombre incorrecta';
                }
            } else {
                $result['exception'] = 'Seleccione un departamento';
            }
            break;
        case 'get':
            //Caso para obtnener los datos de un registro en especifico
            if ($location->setId($_POST['Codigo'])) {//Asignacion de id para determinar la ubicacion de la que se quieren conocer los datos
                if ($result['dataset'] = $location->getLocation()) {//Ejecucion del metodo para obtener ubicacion
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'Ubicación inexistente';
                }
            } else {
                $result['exception'] = 'Ubicación incorrecta';
            }
            break;  
        case 'checkLocationUpdate':
            if(isset($_POST['updateDepto'])){
                if($location->setId($_POST['idLocation'])){
                    if($location->setDepto($_POST['updateDepto'])){
                        if($location->setNombre($_POST['updateName'])){
                            if($location->checkLocationUpdate()){
                                $result['status'] = 1;
                                $result['message'] = 'Ubicación existente';
                            }
                        }
                    }
                }
            }
            break;
        case 'update':
            //Caso para actualizar un registro
            $_POST = $location->validateForm($_POST);//Validacion de espacios en blanco en el formulario
            if(isset($_POST['updateDepto'])){
                if($location->setId($_POST['idLocation'])){//Definición del id de la ubicacion a modificar
                    if($location->setNombre($_POST['updateName'])){//Asignacion del nombre a modificar
                        if($location->setDepto($_POST['updateDepto'])){//Asignacion del departamento donde se encuentra la ubicacion
                            if(!$location->checkLocationUpdate()){
                                if($location->updateLocation()){//Ejecucion del metodo para actualizar datos de la ubicacion
                                    $result['status'] = 1;
                                    $result['message'] = 'Ubicación actualizada correctamente';
                                }else{
                                    $result['exception'] = 'Operación Fallida';
                                }
                            } else {
                                $result['exception'] = 'Ubicación existente';    
                            }
                        }else{
                            $result['exception'] = 'Departamento incorrecto';
                        }
                    }else{
                        $result['exception'] = 'Nombre incorrecta';
                    }     
                } else {
                    $result['exception'] = 'Ubicación incorrecta';
                }
            } else {
                $result['exception'] = 'Seleccione un departamento';
            }                   
            break;  
        case 'delete':
            //Caso para eliminar una ubicacion
            if ($location->setId($_POST['identifier'])) {//Definicion del id de la ubicacion a eliminar
                if ($location->getLocation()) {//Ejecucion del metodo para obtener ubicacion
                    if($location->setEliminacion(0)){//Asignacion del estado de eliminacion
                        if ($location->deleteLocation()) {//Ejecucion del metodo para eliminar ubicacion
                            $result['status']=1;
                            $result['message'] = 'Ubicación eliminada correctamente';
                        } else {
                            $result['exception'] = 'Operación fallida';
                        }
                    }                           
                } else {
                    $result['exception'] = 'Cliente inexistente';
                }
            } else {
                $result['exception'] = 'Cliente incorrecto';
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