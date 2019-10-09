<?php
error_reporting( E_ALL );

if (!defined('__ABSOLUTE_URL__')) {

	define("__ABSOLUTE_URL__","http://www.myserver.ch/dir/index.php");

}

if( session_status() === PHP_SESSION_NONE ){

	session_start();

}

if( !isset( $_SESSION['user_id'] ) ){
	
	//header("Location: ".__ABSOLUTE_URL__);
	//die();
}

include("dbconnect.php");

//Some functions that are potentially used in more than one script


// PHP >= 5.6
if( !function_exists("is_JSON")){
	function is_JSON( $string ) {
   		json_decode( $string );
    		return (json_last_error()===JSON_ERROR_NONE);
	}
}

if(!function_exists("compute")) {
function compute($input){

	if( preg_match( '#([a-zA-Z]+)#', $input ) ){

		echo $input;

	}


	$compute = @create_function('', 'return '.$input.';');

        return 0 + $compute();
    
}}

if(!function_exists("parse_string")) {

function parse_string( $current_val, $parse_str ){

	global $db;
	global $game;

	$parse_str = preg_replace_callback( '#(\d+)d(\d+)#', function( $match ){

		$result = 0;

		for( $x=0; $x<$match[1]; $x++ ){

			$result += rand( 1, $match[2] );

		}

		return $result;

	}, $parse_str );
	
	$parse_str = preg_replace_callback( '#char\[?([a-zA-Z0-9]+)?\]?\.([a-zA-Z0-9_\.]+)#', function( $matches  ){
		global $db;
		$new_game = new game( $db, $l, $lang );
		
		return $new_game->get_var( $matches[0] );
	
	}, $parse_str );

	if( strpos( $parse_str, "(" ) !== false && strpos( $parse_str, ")" ) !== false ){
	
		//echo "Found one!! :".$parse_str;

		$parse_str = preg_replace_callback( '#\((.+)\)#', function( $matches ){

			return compute( $matches[1] );

		}, $parse_str );

		//echo "Found one 2!! :".$parse_str;

	}
	
	if( preg_match( '#\+([0-9]*\.?[0-9]+)\s?\%#', $parse_str, $matches ) ){
		
		//Prozentual addieren
		return round( $current_val + $current_val * $matches[1] / 100, 2 );
	
	}else if( preg_match( '#\-([0-9]*\.?[0-9]+)\s?\%#', $parse_str, $matches ) ){
		
		//Prozentual subtrahieren
		return round( $current_val - $current_val * $matches[1] / 100, 2 );
	
	}else if( preg_match( '#([0-9]*\.?[0-9]+)\s?\%#', $parse_str, $matches ) ){
		
		//Prozentualer Wert
		return round( $current_val * $matches[1] / 100, 2 );
	
	}else if( preg_match( '#\+\s?([0-9]*\.?[0-9]+)#', $parse_str, $matches ) ){
		
		//Addieren
		return $current_val + $matches[1];
		
	}else if( preg_match( '#\-\s?([0-9]*\.?[0-9]+)#', $parse_str, $matches ) ){
		
		//Subtrahieren
		return $current_val - $matches[1];
		
	}else if( preg_match( '#\*+\s?([0-9]*\.?[0-9]+)#', $parse_str, $matches ) ){
		
		//Multiplizieren
		return $current_val * $matches[1];
		
	}
	
	else if( preg_match( '#[\:\/]\s?([0-9]*\.?[0-9]+)#', $parse_str, $matches ) ){
		
		//Dividieren
		return $current_val / $matches[1];
		
	}
	
	else if( preg_match( '#([0-9]*\.?[0-9]+)\!#', $parse_str, $matches ) ){
		
		//Absolutwert
		return $matches[1];
		
	}else{
		
		return floatval( $parse_str );
		
	}
	
}}