<?php
function getDb(){
	try{
		$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
		$user = DB_USER;
		$password = DB_PASSWORD;
		$options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		); 
		return $dbh = new PDO($dsn, $user, $password, $options);
	}catch(Exception $e){
		$e->getMessage();
	}
}
