<?php
require_once('mongodb.php');

/**
*Funcion que comprueba si dado el usuario, si su password es correcto.
*Si coincide el password con el almacenado en la BD se devuelve TRUE, en caso contrario se devuelve FALSE.
*/
function compobarDatosUsuario($usuario,$password){
	return hasValidUser($usuario,$password);
}
/**
*Funcion que comprueba si existe un usuario en la BD.
*Si se encuentra se devuelve TRUE, en caso contrario se devuelve FALSE.
*/
function existeUsuario($usuario){
	return hasUser($usuario);
}
/**
*Funcion que crea un usuario, y lo inserta en la BD.
*Si se ha insertado correctamente se devuelve TRUE, en caso contrario se devuelve FALSE.
*/
function crearUsuario($usuario,$clave){
	if(!hasUser($usuario)){
		addUser($usuario,$clave);
		return hasUser($usuario);
	}
	return false;
}
/**
*Funcion que compara los usuarios $a y $b, para saber cual ha escrito mas rwits.
*Se ordena de mayor a menor, para conseguir que los usuarios con mas numero de mensajes primero.
*/
function compararUsuarios($a, $b)
{
    if ($a->value == $b->value) {
        return 0;
    }
    return ($a->value < $b->value) ? 1 : -1;
}
/**
*Funcion que devuelve una lista con los usuarios existentes en la tabla, y el numero de rwits escritos por el usuario;
*/
function listarUsuarios(){
	$usuarios = getUsers();
	foreach ($usuarios as $usuario) {
		$resul[] = new KValue($usuario, getNumberRweets($usuario));
	}
	usort($resul, "compararUsuarios");
	return $resul;
}
/**
*Funcion que crea un rtwit, y lo inserta en la BD.
*Si se ha insertado correctamente se devuelve TRUE, en caso contrario se devuelve FALSE.
*/
function crearRwit($usuario,$password,$texto){
	if(compobarDatosUsuario($usuario,$password)){
		return addRweet($usuario, $texto);
	} 
	return false;
}
/**
*Funcion que compara los elementos $a y $b, para saber cual es mas antiguo según el campo timestamp.
*Se ordena de mayor a menor, para conseguir que los objetos con un timestamp mayor (se han creado despues),
*esten en las primeras posiciones del array, para que se muestren los objetos mas nuevos primero.
*/
function compararRwits($a, $b) {
	if ($a->value['timestamp'] === $b->value['timestamp']) {
        return 0;
    }
    return ($a->value['timestamp'] < $b->value['timestamp']) ? 1 : -1;
}

/**
*Funcion que devuelve los rtwits de un usuario que se indica en el parametro $usuario.
*Se devuelve un array con los rtwits ordenados de mas nuevos a mas viejos.
*/
function listarRwits($usuario){
	if(existeUsuario($usuario)){
		$resul = getRweets($usuario);
		usort($resul, "compararRwits"); //ordena el array
		return $resul;
	}
	return null;
}
/**
*Funcion que borra un rweet dado el id que se indica en el $key, de un usuario dado en $usuario
*Si se ha eliminado correctamente se devuelve TRUE, en caso contrario se devuelve FALSE.
*/
function borrarRwit($usuario,$key){
	if(existeUsuario($usuario)){
		return removeRweet($key);
	}
	return false;
}
?>
