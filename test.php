<?php
require_once('mongodb.php');
require_once('app.php');
/*
function crearUsuarios($inicio, $cantidad){
	$formatoUsuario = 'user%1$04d';
	$formatoPassword = 'key%1$04d';

	for($i=$inicio;$i<$cantidad;$i++){
		$usuario = sprintf($formatoUsuario, $i);
		$password = sprintf($formatoPassword, $i);
		addUser($usuario,$password);
	}
}

function crearRwits($cantidad){
	$formatoUsuario = 'user%1$04d';
	$formatoPassword = 'key%1$04d';

	for($i=0;$i<$cantidad;$i++){
		$us = mt_rand(1, 25);
		$usuario = sprintf($formatoUsuario, $us);
		$te = mt_rand(1, 25);
		$texto = 'Pues hace un buen dia para el '.sprintf($formatoUsuario,$te).' by '.$usuario;
		addRweet($usuario,$texto);
		
	}
}

print('START!!<br>');
//crearUsuarios(1,25);
//crearRwits(50);
/*
echo 'test<br>';
echo '<br>testHasT<br>';
var_dump(hasUser("user0018"));echo '<br>';
echo '<br>testHasF<br>';
var_dump(hasUser("user00"));echo '<br>';

echo '<br>testHasT<br>';
var_dump(hasValidUser("user0018","key0018"));echo '<br>';
echo '<br>testHasF<br>';
var_dump(hasValidUser("user0018","key0030"));echo '<br>';
echo '<br>testHasF<br>';
var_dump(hasValidUser("user0030","key0030"));echo '<br>';


echo '<br>testHasRV<br>';
var_dump(getRweets("user0001"));echo '<br>';

echo '<br>testRe<br>';
var_dump(removeRweet("54ac84667d60c06c6e8b45a3"));echo '<br>';

echo '<br>testHasRV<br>';
var_dump(getRweets("user0001"));echo '<br>';*/

var_dump(listarUsuarios());
?>
