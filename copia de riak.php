<?php
require_once('clases.php');

define('BUCKET_USUARIOS', 'users');
define('BUCKET_RTWITS', 'rtweets');
define('URI', 'mongodb://dev:dev@ds031531.mongolab.com:31531/openshift_90r4hnt6_rrru48g7');

function getBucket($collection){
	echo 'getBucket----<br>';
	try {
print_r($collection);echo '<br>';
		$conexion = new Mongo(URI);
print_r($conexion);echo '<br>';
		$db = $conexion->selectDB("openshift_90r4hnt6_rrru48g7");
print_r($db);echo '<br>';
		$myCollection = $db->$bucket;
	} catch ( MongoConnectionException $e ) {
    	echo '<p>Couldn\'t connect to mongodb, is the "mongo" process running?</p>';
		exit();
	}
	return $myCollection;
}

function hasKey($bucket,$key){
	//echo 'hasKey----<br>';
	$myBucket = getBucket($bucket);
	if($myBucket->hasKey($key)){
		return true;
	} else {
		return false;
	}
}

function setKValue($bucket,$key,$value){
	//echo 'setKValue----<br>';
	$myBucket = getBucket($bucket);
	$obj = $myBucket->newObject($key, $value);
	$obj->store();
	$http_code = $obj->status();
	if ($http_code==200) {
		//print('creado<br>');
	} elseif ($http_code==201) {
		//print('Creado sin clave<br>');
	} elseif ($http_code==204) {
		//print('no content<br>');
	} elseif ($http_code==300) {
		//print('Creado con hijos<br>');
	} else {		
		//print('ERROR<br>');
		return false;
	}
	return true;
}

function getKValue($bucket,$key){
	//echo 'getKValue----<br>';
	$myBucket = getBucket($bucket);
	$fetched = $myBucket->get($key);
	$http_code = $fetched->status();
	//echo $http_code.'---<br>';
	if ($http_code==200) {
		//print('HIJO UNICOss<br>');
		/*$resul[] = new KValue($fetched->getKey(),$fetched->getData());
		return $resul;*/
		//echo '*********<br>';
		//print("key: ".$fetched->getKey().'<br>');
		//print("data: ");print_r($fetched->getData()[texto]); echo '<br>';		
		return new KValue($fetched->getKey(),$fetched->getData());
	} elseif ($http_code==300) {
		//print('SIBLINGS<br>');
		$siblings;
		$sib = $fetched->getSiblingCount();
		$siblingsKeys = $fetched->siblings;
		for ($i=0; $i < $sib; $i++) { 
			$sibling = $fetched->getSibling($i);
			$siblings[] = new KValue($siblingsKeys[$i],$sibling->getData());
		}
		return $siblings;
	}
	return null;
}

function getKeys($bucket){
	//echo 'getKeys----<br>';
	$myBucket = getBucket($bucket);
	$keys = $myBucket->getKeys();
	return $keys;
}

function getNumberOfValues($key){
	$keys = getKeys($key);
	$i = 0;
	foreach ($keys as $k) {
		if (getKValue($key,$k) !== null)
			$i++;
	}
	return $i;
}

function printBucket($bucket){
	//echo 'printBucket----<br>';
	$myBucket = getBucket($bucket);
	$keys = $myBucket->getKeys();
	if (count($keys)==0) {
		print('Bucket vacio<br>');
	} else {
		print('Hay '.count($keys).' claves<br>');
		foreach ($keys as $key) {
			$obj = $myBucket->get($key);
			print('-----'.$obj->getKey().'-----<br>');
			print('-----'.$obj->status().'-----<br>');
			if ($obj->status()==200) {
				//print('HIJO UNICO<br>');
				print('key: '.$key.' : '.$obj->getData().'<br>');
			} elseif ($obj->status()==300) {
				//print('SIBLINGS<br>');
				$sib = $obj->getSiblingCount();
				print('key: '.$obj->getKey().'<br>');
				for ($i=0; $i < $sib; $i++) { 
					$f = $obj->getSibling($i);
					print(' | value: ');print_r($f->getData());print('<br>');
				}
			} else {		
				print('ERROR<br>');
			}
		}
	}
}

function printBuckets(){
	//echo 'printBuckets----<br>';
	$client = new Basho\Riak\Riak(HOST, PORT);
	$keys = $client->buckets();
	foreach ($keys as $key) {
		print('Name: '.$key->getName().'--------<br>');
		//printBucket($key->getName());
	}
}



function removeKey($bucket,$key){
	//echo 'removeKey----<br>';
	$myBucket = getBucket($bucket);
	$myBucket->setW(3);
	$fetched = $myBucket->get($key);
	$fetched->delete();
	$status = $fetched->status();
	//print('Name: '.$fetched->getKey().' | ');
	if($status===204){
		//print('Borrado con exito--------<br>');
		return true;
	}/* elseif ($status===404) {
		print('Not found--------<br>');
	} elseif ($status===400) {
		print('ERROR----------------------<br>');
	}*/
	return false;
}

function vaciarBucket($bucket){
	//echo 'vaciarBucket----<br>';
	$myBucket = getBucket($bucket);
	$keys = $myBucket->getKeys();
	foreach ($keys as $key) {
		removeKey($bucket,$key);
	}
}
?>
