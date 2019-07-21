<?php
/**
 * Inclusion de archivos php , database, validator y el modelo de carros
 */
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/cars.php');
//Verificacion de existencia de una accion
if(isset($_GET['action'])){
   session_start();//Inicio de sesion 
    $cars = new Cars; //Instanciacion de clase Cars
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    //Switch para verificar la accion que se desea realizar
    switch ($_GET['action']) {
        case 'read'://Accion para leer todos los registros de la tabla carros
            if($result['dataset'] = $cars->readCars()){
                $result['status'] = 1;                
            }else{
                $result['exception'] = 'No hay vehiculos disponibles';
            }
        break;  
        case 'checkPlate':
            if($cars->setPlaca($_POST['type'], $_POST['plate'])){
                if($cars->checkPlate()){
                    $result['status'] = 1;
                    $result['message'] = 'Placa existente';
                }
            }
        case 'create'://Accion para crear un nuevo carro
            $_POST = $cars->validateForm($_POST);            
            if($cars->setPlaca($_POST['create_tipoP'], $_POST['create_placaC'])){//Asignacion de placa del vehiculo
                if($cars->setEstado($_POST['create_estadoV'])){//Asignacion del estado del vehiculo
                    if($cars->createCar()){//Ejecucion del metodo para crear carro
                        $result['status'] = 1;
                        $result['message'] = 'Vehículo registrado correctamente';
                    }else{
                        $result['exception'] = 'Error al agregar';
                    }
                }else{
                    $result['exception'] = 'Estado del vehículo incorrecto';
                }
            }else{
                $result['exception'] = 'Placa incorrecta';                
            }
        break;
        case 'get'://Caso para obtener los datos de un registro en especifico
            if ($cars->setId($_POST['identifier'])) {//Asignacion del id del carro del cual se desean obtener los datos
                if ($result['dataset'] = $cars->getCars()) {//Metodo para obtener carro
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'Vehículo inexistente';
                }
            } else {
                $result['exception'] = 'Vehículo incorrecto';
            }
        break;  
        //Caso para actualizar datos de un registro
        case 'update':
            $_POST = $cars->validateForm($_POST);//Validacion de espacios en blanco en el formulario
            if($cars->setId($_POST['idCar'])){//Asignacion del id del carro a modificar
                if($cars->setPlaca($_POST['updatePlate'], $_POST['updateNum'])){//Asignacion de placa
                    if($cars->setEstado($_POST['updateStatus'])){//Asignacion del estado del carro
                        if($cars->updateCar()){//Ejecucion del metodo para actualizar datso del carro
                            $result['status'] = 1;
                            $result['message'] = 'Vehículo modificado correctamente';
                        }else{
                            $result['exception'] = 'Operación fallida';
                        }
                    }else{
                        $result['exception'] = 'Estado del vehículo incorrecto';
                    }
                }else{
                    $result['exception'] = 'Placa incorrecta';                
                }
            }else{
                $result['exception'] = 'Carro incorrecto';
            }
        break;  
        //Caso para eliminar carros
        case 'delete':
            if ($cars->setId($_POST['identifier'])) {//Asignacion del carro a eliminar
                if($cars->getCars()){ //Obtencion del carro
                    if($cars->setEliminacion(0)){//Asignacion del estado de eliminacion
                        if ($cars->deleteCars()) {//Ejecucion del metodo eliminar carros
                            $result['status']=1;
                            $result['message'] = 'Registro eliminado correctamente';
                        } else {
                            $result['exception'] = 'Operación fallida';
                        }
                    } else {
                        $result['exception'] = 'Estado incorrecto';
                    }
                } else {
                    $result['exception'] = 'Carro inexistente';
                }
            } else {
                $result['exception'] = 'Carro incorrecto';
            }
        break;     
    }
    print(json_encode($result));
} else {
	exit('Recurso denegado');
}
?>