<?php

if( !isset( $db ) ){

	define( "__DBNAME__", "weixelba_dungeon2" );
	define( "__DBUSER__", "weixelba_dnd" );
	define( "__DBPASS__", "aLzveT#12" );
	define( "SALT", "3#зм%&" );

	$db = new PDO('mysql:host=weixelba.mysql.db.internal;dbname='.__DBNAME__.';charset=utf8mb4', __DBUSER__, __DBPASS__ );
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}