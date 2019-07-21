<?php
/**
 * Clase para manejo de operaciones para la tabla carros
 */
class Cars extends Validator
{
/**
*  Propiedades de la clase para manejar los datos necesarios al realizar las acciones respectivas.
*/
    private $id = null;
    private $placa = null;
    private $estado = null;
    private $eliminacion = null;
/**
*   Método para asignar el valor del id del carro
* 
*   @param int $value define el id que se quiere asignar al carro
* 
*   @return boolean true en caso que el id haya sido validado y asignado correctamente, false en caso contrario
*/
    public function setId($value)
    {
        if($this->validateId($value)){
            $this->id = $value;
            return true;
        }else{
            return false;
        }
    }
/**
*   Método para obtener el id del carro
*
*   @return int el id de un carro
*/
    public function getId()
    {
        return $this->id;
    }
/**
*   Método para asignar y validar que el formato de placa sea el correcto
*
*   @return boolean true si se le asigno el valor y false en caso contrario
*
*   @param string $tipo es el tipo de placa y $valores es el numero de placa
*/  
    public function setPlaca($tipo, $valores)
    {
        if($tipo == 'MB' || $tipo == 'P' || $tipo == 'A'){
            if($this->validatePlate($valores)){
                $this->placa = $tipo." ".$valores;
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
/**
*   Método para obtener la placa del carro
*
*   @return string la placa del vehiculo
*/
    public function getPlaca()
    {
        return $this->placa;
    }
/**
*   Método para asignar estado al vehiculo
* 
*   @param int $value define el estado que se deseea asignar al carro, 1: Disponible, 2:Ocupado, 3:Reparacion
*
*   @return int el valor del estado que posee el vehculo
*/
    public function setEstado($value)
    {
        if($value == '1' || $value == '2' || $value == '3'){
            $this->estado = $value;
            return true;
        }else{
            return false;
        }
    }
/**
*   Metodo para obtener el estado del vehiculo
*
*   @return int con el valor de la propiedad de Estado
*/
    public function getEstado()
    {
        return $this->estado;
    }
/**
*   Metodo para asignar valor al estado de eliminacion del vehiculo 
*
*   @param int define el estado de eliminacion del vehiculo, 0: si esta eliminado, 1:si no esta eliminado
*
*   @return boolean true en caso que se asigno valido y asigno correctamente el estado de eliminacion, false en caso contrario        
*/
    public function setEliminacion($value)
    {
        if($value == '0' || $value == '1'){
            $this->eliminacion = $value;
            return true;
        } else {
            return false;
        }
    }
/**
*   Metodo para obtemer el estado de eliminacion
*
*   @return int el valor del estado de eliminacion del vehiculo
*/
    public function getEliminacion()
    {
        return $this->eliminacion;
    }
/**
*   Metodo para leer los registros de la tabla carros
*
*   @return array los valores de la consulta SQL
*/
    public function readCars()
    {
        $sql = 'SELECT idCarro as Codigo, placaCarro as placa, estadoCarro as estado FROM carros WHERE estadoEliminacion = 1';
        $params = array(null);
        return Database::getRows($sql, $params);
    }
/**
*   Metodo para registrar vehiculos
*
*   @return boolean true si se ejecuto con exito y false caso contrario
*/
    public function createCar()
    {
        $sql = 'INSERT INTO carros VALUES (NULL,?, ?, 1)';
        $params = array($this->placa, $this->estado);
        return Database::executeRow($sql, $params);
    }
/**
*   Método para obtener informacion de un carro
* 
*   @return array con los datos correspondiente
*/
	public function getCars()
	{
		$sql = 'SELECT idCarro as Codigo, placaCarro as Placa, estadoCarro as Estado FROM carros WHERE idCarro = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
/**
*   Metodo para modificar vehiculos
*
*   @return boolean true si se ejecuto con exito y false caso contrario
*/
    public function updateCar()
    {
        $sql = 'UPDATE carros SET placaCarro = ?, estadoCarro = ? WHERE idCarro = ?';
        $params = array($this->placa, $this->estado,$this->id);
        return Database::executeRow($sql, $params);
    }    
/**
* Método para eliminar un vehiculo
* 
* @return boolean true si se ejecuto con exito y false en caso contrario
*/
    public function deleteCars()
    {
        $sql = 'UPDATE carros SET estadoEliminacion = ? WHERE idCarro = ?';
        $params = array($this->eliminacion, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function checkPlate()
    {
        $sql = 'SELECT estadoEliminacion FROM carros WHERE placaCarro = ?';
        $params = array($this->placa);
        return Database::getRow($sql,$params);
    }

    public function checkPlateUpdate()
    {
        $sql = 'SELECT estadoEliminacion FROM carros WHERE placaCarro = ? AND idCarro NOT IN (?)';
        $params = array($this->placa,$this->id);
        return Database::getRow($sql,$params);
    }
 }
?>