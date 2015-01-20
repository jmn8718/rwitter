<?php
require_once('clases.php');

define('BUCKET_USUARIOS', 'users');
define('BUCKET_RWEETS', 'rweets');
define('URI', 'mongodb://dev:dev@ds031531.mongolab.com:31531/openshift_90r4hnt6_rrru48g7');

function getCollection($collection){
	try {
		$conexion = new Mongo(URI);
		$db = $conexion->selectDB("openshift_90r4hnt6_rrru48g7");
		$myCollection = $db->$collection;
	} catch ( MongoConnectionException $e ) {
    	echo '<p>Couldn\'t connect to mongodb, is the "mongo" process running?</p>';
		exit();
	}
	return $myCollection;
}

function hasUser($user,$password){
	$col = getCollection(BUCKET_USUARIOS);
	$query = array('usuario'=>$user);
	$cursor = $col->find($query);
	if(sizeof(iterator_to_array($cursor))>0)	
		return TRUE;
	else
		return FALSE;
}

function hasValidUser($user,$password){
	$col = getCollection(BUCKET_USUARIOS);
	$query = array('usuario'=>$user);
	$cursor = $col->find($query)->limit(1);
	foreach ($cursor as $doc) {
		$pass = $doc['password'];
	}
	return $password==$pass;
}

function addUser($user, $password){
	$col = getCollection(BUCKET_USUARIOS);
	$usuario = new Usuario($user, $password);	
	$obj = $usuario->toArray();
	$col->insert($obj);
}

function getUsers(){
	$col = getCollection(BUCKET_USUARIOS);
	$cursor = $col->find();
	$usuarios = [];
	if(sizeof(iterator_to_array($cursor))>0)
		foreach ($cursor as $doc) {
			$us = $doc['usuario'];
			$usuarios[]= $us;
		}
	return $usuarios;
}

function addRweet($usuario, $texto){
	$col = getCollection(BUCKET_RWEETS);
	$rweet = new Rweet($usuario, $texto);
	$obj = $rweet->toArray();
	$col->insert($obj);
	return TRUE;
}

function removeRweet($id){
	$col = getCollection(BUCKET_RWEETS);
	$col->remove(array('_id' => new MongoId($id)));
	return TRUE;
}

function getNumberRweets($usuario){
	$col = getCollection(BUCKET_RWEETS);
	$query = array('usuario'=>$usuario);
	$cursor = $col->find($query);
	return sizeof(iterator_to_array($cursor));
}
function getRweets($usuario){
	$col = getCollection(BUCKET_RWEETS);
	$query = array('usuario'=>$usuario);
	$cursor = $col->find($query);
	$rweets = [];
	if(sizeof(iterator_to_array($cursor))>0)
		foreach ($cursor as $doc) {
			$rweet = new Rweet($doc['usuario'],$doc['texto'],$doc['fecha'],$doc['timestamp']);
			$rweets []= new KValue($doc['_id'],$rweet->toArray());
		}
	return $rweets;
}
?>
