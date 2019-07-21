<?php
/**
*   Clase para manejo de operaciones de tabla tipoproducto.
*/
class Department extends Validator
{

    /**
    *   Propiedades de la clase.
    */
    private $id= null;
    private $depto= null;
    private $estado = null;

    /**
    *   Método para comprobar un id.
    *
    *   @return boolean true si el valor es un id y false si el valor no es un id.
    *
    *   @param string $value el valor a verificar .
    */
    public function setId($value){
        if($this->validateId($value)){
            $this->id=$value;
            return true;
        }else{
            return false;
        }
    }

    /**
    *   Método para obtener el id
    *  
    *   @return int valor del id de un tipo de producto
    */
    public function  getId(){
        return $this->id;
    }

    /**
    *   Método para comprobar el tipo de producto.
    *
    *   @return boolean true si el valor es el esperaqdo y false si el valor no es adecuado.
    *
    *   @param string $value nombre del tipo de producto.
    */
    public function setDepto($value){
        if($this->validateAlphanumeric($value, 1, 100)){
            $this->depto=$value;
            return true;
        }else{
          return false;  
        }
    }

    /**
    *   Método para obtener el tipo de producto.
    *  
    *   @return string nombre del tipo de producto
    */
    public function getDepto(){
        return $this->depto;
    }

    /**
    *   Método para asignar estado de eliminacion a un tipo de producto
    * 
    *   @param $value el valor que se busca verificar y asignar
    * 
    *   @return boolean true si el parametro fue validado y asignado correctamnete y false en caso contrario
    */
    public function setEstado($value)
    {
        if($value == '1' || $value == '0'){
            $this->estado = $value;
            return true;
        }else{
            return false;
        }
    }

    /**
    *   Método para obtener el estado de eliminacion de un tipo de prodcto
    *
    *   @return int valor del estado de eliminacion del tipo de producto
    */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
    *   Método para leer todos departamento
    *
    *   @return array regresa los datos que cumplan la condicion de la consula SQL
    */
    public function readDepartment(){
        $sql='SELECT idDepartamento as codigo, nombreDepartamento as nombre from departamento WHERE estadoEliminacion = 1';
        $params=array(null);
        return Database::getRows($sql, $params);
    }

    /**
    *   Método para crear departamento
    *
    *   @return boolean true si se ejecuto exitosamente la sentencia y false en caso contrario
    */
    public function createDepartment(){
        $sql='INSERT INTO departamento(nombreDepartamento, estadoEliminacion) VALUES (?, 1)';
        $params=array($this->depto);
        return Database::executeRow($sql, $params);
    }

    /**
    *   Método para obtener la información departamento
    *
    *   @return array regresa los datos que cumplan la condicion de la consula SQL
    */
    public function getDepartment(){
        $sql='SELECT idDepartamento as codigo, nombreDepartamento as nombre from departamento WHERE idDepartamento = ?';
        $params=array($this->id);
        return Database::getRow($sql, $params);
    }

    /**
    *   Método para modificar departamento
    *
    *   @return boolean true si se ejecuto exitosamente la sentencia y false en caso contrario
    */
    public function updateDepartment()
    {
        $sql = 'UPDATE departamento SET nombreDepartamento = ? WHERE idDepartamento = ?';
        $params = array($this->depto, $this->id);
        return Database::executeRow($sql, $params);
    }

    /**
    *   Método para eliminar departamento
    *
    *   @return boolean true si se ejecuto exitosamente la sentencia y false en caso contrario
    */
    public function deleteDepartment(){
        $sql = 'UPDATE departamento SET estadoEliminacion = ? WHERE idDepartamento = ?';
        $params = array($this->estado, $this->id);
        return Database::executeRow($sql, $params);
    }

    /**
	*   Método para verificar el nombre del departamento
	*
	*   @return array regresa los datos que cumplan la condicion de la consula SQL
	*/	
    public function checkName()
    {
        $sql = 'SELECT estadoEliminacion FROM departamento WHERE nombreDepartamento = ?';
        $params = array($this->depto);
        return Database::getRow($sql, $params);
    }

    /**
	*   Método para verificar el nombre del departamento a la hora de modificar
	*
	*   @return array regresa los datos que cumplan la condicion de la consula SQL
	*/	
    public function checkUpdateName()
    {
        $sql = 'SELECT estadoEliminacion FROM departamento WHERE nombreDepartamento = ? AND idDepartamento NOT IN (?)';
        $params = array($this->depto, $this->id);
        return Database::getRow($sql, $params);
    }
}

?>