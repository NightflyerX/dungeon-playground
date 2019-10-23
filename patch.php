<?php

include('security.php');
include('language.php');

$result = $db->query("SELECT `data` FROM game WHERE `active`=1");
$row = $result->fetch(PDO::FETCH_ASSOC);
$game = json_decode($row['data'] );

var_dump( $game );



foreach( $game->chars as $char ){
	
	foreach( $char->tmpvars as $tmpvar ){
		
		foreach( $char->states as $state ){
			
			if( !isset( $state->vars ) ){
				
				$state->vars = array();
				
			}
			
			if( $tmpvar->origin == $state->name ){
				
				$state->vars[] = (object) array( "path" => $tmpvar->var_path, "modifier" => $tmpvar->value );
				
			}
			
		}
		
	}
	
}







/*
	
	$type = $data->states_effects2;
		
		$states = array();
		
		foreach( $type as $states_old ){
			
			$found = 0;
			
			foreach( $states as $state ){
				
				if( $state->state_name == $states_old->state_name ){
					
					$found++;
					
				}
				
			}
			
			if( $found == 0 ){
			
				$states[] = (object) array( "state_name" => $states_old->state_name, "state_id" => $states_old->state_id, "vars" => array() );
				
			}
			
			foreach( $states as $state ){
				
				if( $state->state_name == $states_old->state_name ){
					
					$state->vars[] = (object) array( "path" => $states_old->variable, "modifier" => $states_old->modifier);
					
				}
				
			}
			
		}
	*/

$data = $game;

$data = json_encode( $data, JSON_PRETTY_PRINT );

try {

		

				$stmt = $db->prepare("UPDATE `game` SET data=:data WHERE `active`=1");

				$stmt->bindParam(':data', $data, PDO::PARAM_STR);

				$stmt->execute();

		

			}catch(PDOException $ex) {

					echo "An Error occured! "; //user friendly message

					echo $ex->getMessage();

			}
