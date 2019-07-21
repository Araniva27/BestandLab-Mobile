<?php 
/**
*   Clase para las operaciones de la tabla ubicacion
*/
class Location extends Validator
{
/**
*   Declaración de propiedades.
*/
    private $id = null;
    private $nombre = null;
    private $departamento = null;
    private $eliminacion =  null;
/**
*   Método para asignar el valor en la propiedad id.
*  
*   @return boolean true cuando se asigna correctamente el valor y false en caso contrario.
* 
*   @param string $value es el valor a validar y asignar a la propiedad.
*/    
    public function setId($value)
    {
        if ($this->validateId($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }
/**
*   Método para obtener el valor de la propiedad id.
*
*   @return int el valor de la propiedad id.
*/    
    public function getId()
    {
        return $this->id;
    }
/**
*   Método para asignar el valor en la propiedad nombre.
* 
*   @return boolean true cuando se asigna correctamente el valor y false en caso contrario.
* 
*   @param string @value es el valor a validar y asignar a la propiedad. 
*/
    public function setNombre($value)
    {
        if($this->validateAlphanumeric($value, 1, 50)){
            $this->nombre = $value;
            return true;
        } else {
            return false;
        }
    }
/**
*   Método para obtener el valor de la propiedad nombre.
* 
*   @return int el valor de la propiedad nombre.
*/
    public function getNombre($value)
    {
        return $this->nombre;
    }
/**
*   Método para asignar el valor en la propiedad departamento.
*  
*   @return boolean true cuando se asigna correctamente el valor y false en caso contrario.
* 
*   @param string $value es el valor a validar y asignar a la propiedad.
*/    
    public function setDepto($value)
    {
        if ($this->validateId($value)) {
            $this->departamento = $value;
            return true;
        } else {
            return false;
        }
    }
/**
*   Método para obtener el valor de la propiedad departamento.
*
*   @return int el valor de la propiedad departamento.
*/    
    public function getDepto()
    {
        return $this->departamento;
    }

/**
* Metodo para asignar valor del estado de eliminacion
* 
* @return boolean true en caso que el estado se haya validado y asignado correctamente, false en caso contrario
* 
* @param $value, define el estado de eliminación que se quiere validar y asignar
*/

    public function setEliminacion($value)
    {
        if($value == '0' || $value == '1'){
            $this->eliminacion = $value;
            return true;
        }else{
            return false;
        }
    }

/**
* Metodo para obtener el estado de eliminacion de una ubicacion
*
* @return int el valor del estado de eliminacion
*/
    public function getEliminacion()
    {
        return $this->eliminacion;
    }
     
/**
*   Método para leer todas las ubicaciones
* 
*   @return array con los datos correspondiente a la consulta
*/
    public function readLocation()
    {
        $sql = 'SELECT idUbicacion as Codigo, nombreUbicacion as Nombre, nombreDepartamento as Depto FROM ubicacion INNER JOIN (departamento) USING (idDepartamento) WHERE departamento.estadoEliminacion = 1 and ubicacion.estadoEliminacion = 1 ORDER BY idDepartamento asc';
        $params = array(null);
        return Database::getRows($sql, $params);
    }

    /**
     * Metodo para registrar una nueva ubicacion
     */
    public function createLocation()
    {
        $sql = 'INSERT INTO ubicacion VALUES(NULL,?, ?,1)';
        $params = array($this->nombre, $this->departamento);
        return Database::executeRow($sql, $params);
    }

    /**
     * Metodo para actualizar datos de ubicacion
     */
    public function updateLocation()
    {
        $sql = 'UPDATE ubicacion SET nombreUbicacion = ?, idDepartamento = ? WHERE idUbicacion = ?';
        $params = array($this->nombre, $this->departamento, $this->id);
        return Database::executeRow($sql, $params); 
    }

    /**
     * Metodo para obtener los datos de un registro en especifico
     */
    public function getLocation()
    {
        $sql = 'SELECT idUbicacion as Codigo, nombreUbicacion  as Nombre, idDepartamento as Depto FROM ubicacion WHERE idUbicacion = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    /**
     * Metodo para eliminar ubicacion
     */
    public function deleteLocation()
    {
        $sql = 'UPDATE ubicacion SET estadoEliminacion = ? WHERE idUbicacion = ?';
        $params = array($this->eliminacion, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function checkLocation()
    {
        $sql = 'SELECT estadoEliminacion FROM ubicacion WHERE nombreUbicacion = ? AND  idDepartamento = ?';
        $params = array($this->nombre,$this->departamento);
        return Database::getRow($sql, $params);
    }

    public function checkLocationUpdate()
    {
        $sql = 'SELECT estadoEliminacion FROM ubicacion WHERE nombreUbicacion = ? AND  idDepartamento = ? AND idUbicacion NOT IN (?)';
        $params = array($this->nombre,$this->departamento,$this->id);
        return Database::getRow($sql, $params);
    }

    public function readDepartamentLocation()
    {
        $sql = 'SELECT idUbicacion,CONCAT(departamento.nombreDepartamento, " - ", ubicacion.nombreUbicacion) as ubicacion FROM departamento INNER JOIN ubicacion USING(idDepartamento)';
        $params = array(null);
        return Database::getRows($sql, $params);
    }
}

?>