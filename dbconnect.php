<?php

if( !isset( $db ) ){

	define( "__DATABASE_SERVER__", " " );
	define( "__DBNAME__", " " );
	define( "__DBUSER__", " " );
	define( "__DBPASS__", " " );
	define( "SALT", "3#зм%&" );

	$db = new PDO('mysql:host='.__DATABASE_SERVER__.';dbname='.__DBNAME__.';charset=utf8mb4', __DBUSER__, __DBPASS__ );
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}