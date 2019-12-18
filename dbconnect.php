<?php

if( !isset( $db ) ){

	define( "__DBNAME__", "db_name" );
	define( "__DBUSER__", "db_user" );
	define( "__DBPASS__", "dbpass" );
	define( "SALT", "3#зм%&" );

	$db = new PDO('mysql:host=db.mysql.db.internal;dbname='.__DBNAME__.';charset=utf8mb4', __DBUSER__, __DBPASS__ );
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}