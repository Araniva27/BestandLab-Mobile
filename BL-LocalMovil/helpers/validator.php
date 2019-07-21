<?php
/**
*   Clase para realizar las validaciones del lado del servidor.
*/
class Validator
{
/**
*   Propiedades de la clase para manejar los datos necesarios al realizar las acciones respectivas.
*/
	private $imageError = null;
	private $imageName = null;
/**
*   Método para obtener el nombre de la imagen.
*
*   @return string con el nombre de la imagen.  
*/	
	public function getImageName()
	{
		return $this->imageName;
	}
/**
*   Método para obtener el mensaje de error al ocurrir una excepción con la imagen.
*
*   @return string con el error personalizado a mostrar.  
*/	
	public function getImageError()
	{
		switch ($this->imageError) {
			case 1:
				$error = 'El tipo de la imagen debe ser gif, jpg o png';
				break;
			case 2:
				$error = 'La dimensión de la imagen es incorrecta';
				break;
			case 3:
				$error = 'El tamaño de la imagen debe ser menor a 2MB';
				break;
			case 4:
				$error = 'El archivo de la imagen no existe';
				break;
			default:
				$error = 'Ocurrió un problema con la imagen';
		}
		return $error;
	}
/**
*   Método para validar los campos vacios del formulario.
*
*   @return array los campos con sus valores respectivos.
*
* 	@param array los campos con sus valores respectivos no validados.
*/
	public function validateForm($fields)
	{
		foreach ($fields as $index => $value) {
			$value = trim($value);
			$fields[$index] = $value;
		}
		return $fields;
	}
/**
*   Método para comprobar un id.
*
*   @return boolean true si el valor es un id y false si el valor no es un id.
*
* 	@param string $value el valor a verificar.
*/
	public function validateId($value)
	{
		if (filter_var($value, FILTER_VALIDATE_INT, array('min_range' => 1))) {
			return true;
		} else {
			return false;
		}
	}
/**
*   Método para comprobar una fecha de nacimiento.
*
*   @return boolean true si la edad es mayor a 18 años y false si la edad es menor a 18 años .
*
* 	@param string $value la fecha a verificar.
*/
	public function validateDate($value){
		$fechaActual = date("Y-m-d");
		$fechaMayor = date("Y-m-d",strtotime($fechaActual."- 18 year"));
		if($value <= $fechaMayor){
			return true;
		}else{
			return false;
		}

	}
/**
*   Método para comprobar una fecha para las empresas de cliente.
*
*   @return boolean true si la edad es mayor a 6 meses y false si la edad es menor a 6 meses.
*
* 	@param string $value la fecha a verificar.
*/
public function validateDateCustomer($value){
	$fechaActual = date("Y-m-d");
	$fechaMayor = date("Y-m-d",strtotime($fechaActual."- 6 month"));
	if($value <= $fechaMayor){
		return true;
	}else{
		return false;
	}

}
/**
*   Método para comprobar una imagen.
*
*   @return boolean true si imagen cumple con todos los requisitos y false si la imagen no cumple con todos los requisitos.
*
* 	@param string $file es el archivo a verificar.
*   @param string $path es la ruta de la imagen. 
*   @param string $name es el nombre del archivo.
*   @param int $maxWidth es el máximo de ancho que debe tener la imagen.
*   @param int $maxHeigtn es el máximo de alturo que debe tener la imagen.
*/
	public function validateImageFile($file, $path, $name, $maxWidth, $maxHeigth)
	{
		if ($file) {
	     	if ($file['size'] <= 2097152) {
		    	list($width, $height, $type) = getimagesize($file['tmp_name']);
				if ($width <= $maxWidth && $height <= $maxHeigth) {
					//Tipos de imagen: 1 - GIF, 2 - JPG y 3 - PNG
					if ($type == 1 || $type == 2 || $type == 3) {
						if ($name) {
							if (file_exists($path.$name)) {
								if($this->deleteFile($path, $name)){
									$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
									$this->imageName = uniqid().'.'.$extension;
									return true;
								} else {
									return false;
								}
							} else {
								$this->imageError = 4;
								return false;
							}
						} else {
							$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
							$this->imageName = uniqid().'.'.$extension;
							return true;
						}
					} else {
						$this->imageError = 1;
						return false;
					}
				} else {
					$this->imageError = 2;
					return false;
				}
	     	} else {
				$this->imageError = 3;
				return false;
	     	}
		} else {
			if (file_exists($path.$name)) {
				$this->imageName = $name;
				return true;
			} else {
				$this->imageError = 4;
				return false;
			}
		}
	}
/**
*   Método para comprobar el correo eléctronico.
*
*   @return boolean true si es un correo eléctronico válido y false si no es un correo eléctronico válido.
*
* 	@param string $email es el correo a verficar.
*/
	public function validateEmail($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		} else {
			return false;
		}
	}


	public function validatePlate($value)
	{
		if(preg_match('/^([0-9]{1,3})(-)([0-9]{3})$/',$value)){
			return true;
		}else{
			return false;
		}
	}

/**
*   Método para comprobar un valor si es alfabético.
*
*   @return boolean true si el valor cumple con todos requisitos y false si no es un valor válido.
*
* 	@param string $value valor a verificar.
*   @param string $minimun es el mínimo número de carácteres que debe tener.
*   @param string $maximum es el máximo número de carácteres que debe tener.
*/	
	public function validateAlphabetic($value, $minimum, $maximum)
	{
		if (preg_match('/^[a-zA-ZñÑáÁéÉíÍóÓúÚ\s]{'.$minimum.','.$maximum.'}$/', $value)) {
			return true;
		} else {
			return false;
		}
	}
/**
*   Método para comprobar un valor si es alfanúmerico.
*
*   @return boolean true si el valor cumple con todos requisitos y false si no es un valor válido.
*
* 	@param string $value valor a verificar.
*   @param string $minimun es el mínimo número de carácteres que debe tener.
*   @param string $maximum es el máximo número de carácteres que debe tener.
*/	
	public function validateAlphanumeric($value, $minimum, $maximum)
	{
		if (preg_match('/^[a-zA-Z0-9ñÑáÁéÉíÍóÓúÚ,\s\.]{'.$minimum.','.$maximum.'}$/', $value)) {
			return true;
		} else {
			return false;
		}
	}
/**
*   Método para comprobar un valor si es númerico.
*
*   @return boolean true si el valor cumple con todos requisitos y false si no es un valor válido.
*
* 	@param string $value valor a verificar.
*   @param string $minimun es el mínimo número de carácteres que debe tener.
*   @param string $maximum es el máximo número de carácteres que debe tener.
*/	
	public function validateNumeric($value, $minimum, $maximum)
	{
		if (preg_match('/^[0-9]{'.$minimum.','.$maximum.'}$/',$value))
			return true;
		else
			return false;
	}
/**
*   Método para comprobar un valor si es un valor de precio.
*
*   @return boolean true si el valor cumple con todos requisitos y false si no es un valor válido.
*
* 	@param string $value valor a verificar.
*/	
	public function validateMoney($value)
	{
		if (preg_match('/^[0-9]+(?:\.[0-9]{1,2})?$/', $value)) {
			return true;
		} else {
			return false;
		}
	}
/**
*   Método para comprobar un valor si es un número télefonico.
*
*   @return boolean true si el valor cumple con todos requisitos y false si no es un valor válido.
*
* 	@param string $value valor a verificar.
*/	
	public function validatePhone($value){
		if(preg_match('/^([2,6,7][0-9]{3})(-)([0-9]{4})$/',$value)){
			return true;
		}else{
			return false;
		}
	}
/**
*   Método para comprobar un valor si es un número de identidad tributaria.
*
*   @return boolean true si el valor cumple con todos requisitos y false si no es un valor válido.
*
* 	@param string $value valor a verificar.
*/	
public function validateNit($value){
	if(preg_match('/^([0-9]{4})(-)([0-9]{6})(-)([0-9]{3})(-)([0-9]{1})$/',$value)){
		return true;
	}else{
		return false;
	}
}
/**
*   Método para comprobar un valor si es un número de identidad tributaria.
*
*   @return boolean true si el valor cumple con todos requisitos y false si no es un valor válido.
*
* 	@param string $value valor a verificar.
*/	
public function validateNrc($value){
	if(preg_match('/^([0-9]{6})(-)([0-9]{1})$/',$value)){
		return true;
	}else{
		return false;
	}
}
/**
*   Método para comprobar un valor si es una contraseña.
*
*   @return boolean true si el valor tiene una longitud mínima de 6 carácteres y false si no es un valor válido.
*
* 	@param string $value valor a verificar.
*/	
	public function validatePassword($value)
	{
		if (strlen($value) > 5) {
			return true;
		} else {
			return false;
		}
	}
/**
*   Método para guardar la imagen.
*
*   @return boolean true si la imagen se guardo correctamente y false si la imagen no se guardo.
*
* 	@param string $file es el archivo a guardar.
* 	@param string $path es la ruta a la que se desea guardar.
* 	@param string $nome es el nombre con la cual se guardará la imagen.
*/
	public function saveFile($file, $path, $name)
    {
		if (file_exists($path)) {
			if (move_uploaded_file($file['tmp_name'], $path.$name)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
  	}
/**
*   Método para eliminar la imagen.
*
*   @return boolean true si la imagen se elimino correctamente y false si la imagen no se elimino.
*
* 	@param string $path es la ruta donde se encuentra en la imagen.
* 	@param string $nome es el nombre de la imagen que se desea eliminar.
*/
	public function deleteFile($path, $name)
    {
		if (file_exists($path)) {
			if (unlink($path.$name)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}
?>
