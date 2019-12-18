<?php
include('security.php');
include('language.php');

$page = $_GET['page'];

switch( $page ){
	case 'get_types':
		$result = $db->query("SELECT `data` FROM lib_data WHERE `data_id`=1");
		$row = $result->fetch(PDO::FETCH_ASSOC);
		echo $row['data'];
	break;
	case 'get_weapons':
		$result = $db->query("SELECT `data` FROM lib_weapons WHERE `weapon_id`=1");
		$row = $result->fetch(PDO::FETCH_ASSOC);
		echo $row['data'];
	break;
	case 'get_equipment':
		$result = $db->query("SELECT `data` FROM lib_equipment WHERE `equipment_id`=1");
		$row = $result->fetch(PDO::FETCH_ASSOC);
		echo $row['data'];
	break;
	case 'get_actions':
		$result = $db->query("SELECT `data` FROM lib_actions WHERE `sorcery_id`=1");
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$data = json_decode($row['data']);
		
		if( isset( $_GET['sortme'] ) ){

			function cmp($a, $b){
	
	    
	    
				$types = array( 'Class ability' => 0, 'Physical action' => 1, 'Magical action' => 2, 'Potion_Item' => 3, 'Other_action' => 4, 'Diceroll' => 5 );
	    
	
	    
	    
	
				if( $types[$a->action_type] != $types[$b->action_type] ){
	    
	        
	
					return $types[$a->action_type] < $types[$b->action_type] ? -1 : 1;
	        
	    
	
				}
	    
	    
	
				return strcmp($a->action_name, $b->action_name) < 0 ? -1 : 1;
	
			
	
			}
	
			
	
			usort($data, "cmp");
			
		}

		echo json_encode($data);

	break;
	case 'get_char':
		$result = $db->query("SELECT `data` FROM chars WHERE `char_id`=1");
		$row = $result->fetch(PDO::FETCH_ASSOC);
		echo $row['data'];
	break;
	case 'get_shop':
		$result = $db->query("SELECT `data` FROM shops WHERE `data_id`=1");
		$row = $result->fetch(PDO::FETCH_ASSOC);
		echo $row['data'];
	break;
	case 'get_game':
		$result = $db->query("SELECT `data` FROM game WHERE `game_id`=".$_GET['game_id']."");
		$row = $result->fetch(PDO::FETCH_ASSOC);

		$data = json_decode($row['data']);

		foreach( $data->chars AS &$char ){

			if( $char->leftright == "right" && $_SESSION['user_id'] !== $data->dungeonmaster_user_id  ){

				$char->pools->life->cur = 1000;
				$char->pools->life->max = 1000;
				$char->pools->mana->cur = 1000;
				$char->pools->mana->max = 1000;

			}
			
			if( !isset( $char->states ) && empty( $char->states ) ){

				continue;

			}

			if( isset( $char->states ) && !empty( $char->states ) ){

				foreach( $char->states as $state ){

					if( empty( $state->vars ) ){

						continue;

					}

					foreach( $state->vars AS &$tmpvar ){
				
						if( empty( $tmpvar->path ) || empty( $tmpvar->modifier ) ){

							continue;

						}

						$parts = explode( ".", $tmpvar->path );
				
						foreach( $parts as $part ){
				
							if( $part == 'char' ){
					
								$var = &$char;
					
							}else{
					
								if( !isset( $var->{$part} ) ){
						
									//echo "Unexpected error ".$part;
									$var = '';
									continue;
								}
					
								$var = &$var->{$part};
					
							}
				
						}
						
						$value = $tmpvar->modifier;
						
						if( 
							strpos( $value, 'field_owner' ) !== false ){
							
							if( $state->field_owner_id == 0 ){
								
								continue;
								
							}
/*
							if( is_array( $state->field_owner_id ) ){

								$state->field_owner_id = $state->field_owner_id[0];

							}
					*/		
							$value = str_replace( 'field_owner', $state->field_owner_id, $value );
							
						}
				
						$var = parse_string( $var, $value, $char->char_id );

					}

				}
		
				
			}

		}

		echo json_encode($data);
	break;
	case 'get_fields':
		$result = $db->query("SELECT `fields` FROM fields WHERE `id`=1");
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$data = json_decode($row['fields']);

		function cmp($a, $b){    
       
    

			return strcmp($a->field_name, $b->field_name) < 0 ? -1 : 1;

		

}

		

		usort($data, "cmp");
		echo json_encode($data);
	break;
}
