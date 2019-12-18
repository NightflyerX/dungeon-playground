<?php

if( !isset( $db ) ){
	
	include('security.php');
	include('language.php');
	include("dbconnect.php");

}

function getMod( $lib, $attribute_value ){

	$tmp = 0;

	foreach( $lib->mods AS $mod ){
		
		if( $attribute_value < $mod->attribute_value ){
			
			return $tmp;

		}else{
			
			$tmp = $mod->mod_value;

		}

	}

}

function get_controller_user_id_by_name( $db, $controller_name ){
	
		try{

			$stmt = $db->prepare( "SELECT `user_id` FROM users WHERE `username`='".$controller_name."' " );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		return $row['user_id'];
		}
	
}

$page = isset( $_GET['page'] ) ? $_GET['page'] : '';


class game{

	public $game;
	public $chars;
	public $char;
	public $lib;
	public $db;
	public $char_names;
	public $current_damage = 0;
	public $l;
	public $lang;

	public function __construct( $db, $l, $lang ){

		$this->db = $db;
		$this->l = $l;
		$this->lang = $lang;
		$this->lang2 = $l[$lang];

		try{

			$stmt = $db->prepare( 'SELECT * FROM game WHERE `active`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		$this->char_names = array();

		foreach($stmt as $row) {
	    		$this->game = json_decode( $row['data'] );
			$this->chars = &$this->game->chars;
			$this->fields = &$this->game->fields;
		}

		foreach( $this->chars as $char ){

			$this->char_names[$char->char_id] = $char->name;

		}

	}

	public function loadlib( $db ){

		try{

			$stmt = $db->prepare( 'SELECT * FROM lib_data WHERE `data_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$this->lib = json_decode( $row['data'] );
		}

	}

	public function loadfields( $db ){

		try{

			$stmt = $db->prepare( 'SELECT * FROM fields WHERE `id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$this->stored_fields = json_decode( $row['fields'] );
		}

	}

	public function loadchars( $db ){

		try{

			$stmt = $db->prepare( 'SELECT * FROM chars WHERE `char_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$this->all_chars = json_decode( $row['data'] );
		}

	}

	public function loadshop(){

		global $db;

		$shop_id = (int) $_SESSION['camp_id'];

		try{

			$stmt = $db->prepare( 'SELECT * FROM shops WHERE `data_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$shops = json_decode( $row['data'] );
		}

		$this->shops = $shops;

		foreach( $shops AS $key => $shop ){

			if( $shop->shop_id == $shop_id ){

				$this->shop = $shop;
				$this->shop_key = $key;

			}

		}

	}
	
	public function writelog( $text, $user_id ){

		if( is_object( $text ) ){

			$values = get_object_vars($text);

			$newtext = '';
			$colors = array( 'yellow', 'orange', 'green', '#42f4f4' );
			$x = 0;

			foreach( $values AS $key => $val ){

				if( $x < count( $values ) -1 ){

					$newtext .= '<span style="color:'.$colors[$x].'">'.$val.' => </span>';

				}else{

					$newtext .= '<span style="color:'.$colors[$x].'">'.$val.'</span>';

				}
				$x++;

			}

			$text = $newtext;

		}

		if( !is_string( $text ) ){

			var_dump( $text );

		}
		
		try{

			$stmt = $this->db->prepare( 'INSERT INTO `chat` (`text`, `user_id`, `date` ) VALUES( \'<div style="font-size:10px;">'.$text.'</div>\', '.$user_id.', NOW() ) ' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}
		
	}

	public function loadactions( $db ){

		try{

			$stmt = $db->prepare( 'SELECT * FROM lib_actions WHERE `sorcery_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$this->actions = json_decode( $row['data'] );
		}

	}

	public function get_user_id_by_name( $char_name ){
		
		foreach( $this->chars AS $char ){
			
			if( $char->name == $char_name ){

				return $char->char_id;

			}

		}

	}

	public function get_user_id_by_name2( $char_name ){
		
		foreach( $this->all_chars AS $char ){

			
			if( $char->name == $char_name ){
				
				return $char->char_id;

			}

		}

	}

	public function char( $char_id ){

		foreach( $this->chars AS $char ){

			if( $char->char_id == $char_id ){

				$this->char = $char;
				return $char;

			}

		}

	}

	public function get_group_member_ids( $user_id, $leftright, $friendfoe, $include ){

		$return_array = array();

		foreach( $this->chars AS $char ){

			if( $friendfoe == 'friend' ){

				if( $char->leftright == $leftright ){

					if( $include === false && $user_id == $char->char_id ){

						continue;

					}

					$return_array[] = $char->char_id;

				}

			}else{

				if( $char->leftright != $leftright ){

					if( $include === false && $user_id == $char->char_id ){

						continue;

					}

					$return_array[] = $char->char_id;

				}

			}

		}

		return $return_array;

	}

	public function delete_char( $char_id ){

		for( $i=0,$j=count($this->chars),$index=0;$i<$j;$i++ ){

			if( $this->chars[$i]->char_id == $char_id ){

				$index = $i;

			}

		}
		$name = $this->game->chars[$index]->name;

		unset( $this->game->chars[$index] );		
		$this->game->chars = array_values( $this->game->chars );

		$this->writelog( $name.' removed from the game', 0 );

	}
	
	public function getlife(){
		
		return $this->get_var( 'char.pools.life.cur' );
		
	}
	
	public function getmana(){
		
		return $this->get_var( 'char.pools.mana.cur' );

	}
	
	public function getap(){
		
		return $this->get_var( 'char.pools.ap.cur' );

	}

	public function getmove(){

		return $this->get_var( 'char.pools.move.cur' );

	}
	
	public function setlife( $new_life ){
		
		$this->char->pools->life->cur = $new_life;
		
	}
	
	public function setmana( $new_mana ){
		
		$this->char->pools->mana->cur = $new_mana;
		
	}
	
	public function setap( $new_ap ){
		
		$this->char->pools->ap->cur = $new_ap;
		
	}

	public function setmove( $new_move ){
		
		$this->char->pools->move->cur = $new_move;
		
	}
	
	public function getcurrentround(){
		
		return $this->game->current_round;
		
	}
	
	public function addcurrentround( $add ){
		
		$this->game->current_round += $add;
		
	}
	
	public function deactivate_game( $db ){
		echo "Finish!";
		try{

			$stmt = $db->prepare( 'UPDATE game SET `active`=0 WHERE `active`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}
		
	}

	public function get_var( $var_path ){
		
		if( empty( $var_path ) ){

			return 0;

		}

		$parts = explode( ".", $var_path );
		
		foreach( $parts as $part ){
				
			if( strpos( $part, 'char' ) !== false && strpos( $part, 'charisma' ) === false  ){

				if( $part == 'char' ){
					
					$var = $this->char;
					$char = $this->char;

				}else if( preg_match( '#char\[(.+?)\]#', $part, $matches ) ){
					
					global $db;
					global $l;
					global $lang;
					$new_game = new game( $db, $l, $lang );
					(int) $char_id = is_numeric( $matches[1] ) ? $matches[1] : $new_game->get_user_id_by_name( $matches[1] );
					
					$char = $new_game->char( $char_id );

					$var = $char;

				}else{
					
					die( 'Char not found' );

				}
					
			}else{
				
				if( !isset( $var->{$part} ) ){
						
					echo '$var_path : '.$var_path."\n";
					echo '$var = ';
					var_dump($var);
					var_dump($part);
					echo "\n not found in this char: \n";
					var_dump( $this->char );
					trigger_error( 'Unexpected error ==> $part='.$part );
					continue;
						
				}

				if( $part == 'mod' ){ //When IsMod then recalc mod from attribute

					if( ! $this->lib ){

						$this->loadlib( $this->db );

					}

					$attr_value = $var->attr_value;
					

					if( isset( $char->tmpvars ) && !empty( $char->tmpvars ) ){

						foreach( $char->tmpvars AS $tmpvar ){
							
							if( $tmpvar->var_path == str_replace ( 'mod', 'attr_value', $var_path ) ){

								$attr_value = parse_string( $attr_value, $tmpvar->value );

							}

						}

					}
					
					$var = getMod( $this->lib, $attr_value );
					

				}else{
			
					$var = $var->{$part};

				}
					
			}
				
		}
		
		
		if( isset( $char->states ) && !empty( $char->states ) ){

			foreach( $char->states AS $state ){

				if( empty( $state->vars ) ){

					continue;

				}

				foreach( $state->vars as $tmpvar ){

					if( empty( $tmpvar->modifier ) || empty( $tmpvar->path ) ){

						continue;

					}
					
					if( $tmpvar->path != $var_path ){
						
						continue;
						
					}
				
					$value = $tmpvar->modifier;
					
					if( strpos( $value, 'field_owner' ) !== false ){
						
						if( $state->field_owner_id == 0 ){
							
							continue;
							
						}
						
						$value = str_replace( 'field_owner', $state->field_owner_id, $value );
						
					}
				
					$value = preg_replace_callback( '#char\[?([a-zA-Z0-9]+)?\]?\.([a-zA-Z0-9_\.]+)#', function( $matches ){
					
						$parts = explode( ".", $matches[0] );
					
						foreach( $parts as $part ){
						
							if( strpos( $part, 'char' ) !== false ){
					
								$var = $this->char;
								
							
							}else{

								if( is_object( $var ) ){
								
									$var = $var->{$part};

								}else{

									//var_dump( $var ); echo '$var ist kein Objekt!!';

								}
							
							}
						
						}
					
						return $var;
					
					}, $value );
				
				
				
					if( $tmpvar->path == preg_replace('#\[.*?\]#', '', $tmpvar->path) ){
					
						$var = parse_string( $var, $value );
					
					}

				}

			}

		}
		
		return $var;

	}

	public function get_state_id( $state_name, $char_id ){

		foreach( $this->chars as $char ){

			if( $char->char_id == $char_id ){

				foreach( $char->states as $state ){

					if( $state->name == $state_name ){

						return $state->id;

					}

				}

			}

		}

	}
	
	public function get_state_from_char( $state_id ){
		
		if( isset( $this->char->states ) && !empty( $this->char->states ) ){
		
			foreach( $this->char->states as $state ){
				
				if( $state->id == $state_id ){
				
					return (object) array( "state_name" => $state->name, "state_id" => $state->id, "vars" => $state->vars );

				}

			}

		}
		
	}
	
	public function setstate( $state_name, $state_id, $rounds, $user_id, $username, $state_vars = array(), $tank = array(), $field_id = 0, $field_owner_id = 0 ){
		
		if( !isset( $this->char->states ) ){
			
			$this->char->states = array();
			
		}

		if( $state_id == 0 && $state_name != '' ){

			$this->loadlib( $this->db );
			
			foreach( $this->lib->states AS $state ){

				if( $state->state_name == $state_name ){

					$state_id = $state->state_id;

				}

			}	

		}

		if( $state_id == 0 ){

			$state_id = time().rand(0,100);

		}
		
		
		$this->loadlib( $this->db );

		foreach( $this->lib->states AS $state ){

			if( $state->state_id == $state_id ){

				if( $state_name == '' ){

					$state_name = $state->state_name;

				}

				$state_vars = $state->vars;
				
				//foreach( $state->vars as $var ){

					//$var->modifier = str_replace( array( "caster", "field_owner" ), array( $user_id, $user_id ), $var->modifier );
					//$this->settmpvar( $state_name, $state_id, $var->variable, $modifier );

				//}

			}

		}

		$this->char->states[] = (object) array( "name" => $state_name, "id" => $state_id, "rounds" => $rounds, "caster" => $user_id, "caster_username" => $username, "vars" => $state_vars, "tank" => $tank, "field_id" => $field_id, "field_owner_id" => $field_owner_id );

		$this->writelog( $this->char->name.' '.$this->lang2['SETDATA_SETSTATE'][0].' '.$state_name.' '.$this->lang2['SETDATA_SETSTATE'][1].' '.$rounds.' '.$this->lang2['SETDATA_SETSTATE'][2], 0 );

		return $state_id;
			
	}

	public function add_statevars( $state_id, $vars ){

		foreach( $this->lib->states AS $state ){

			if( $state->state_id == $state_id ){

				$state->vars[] = $vars;

			}

		}

	}

	/*
	public function settmpvar( $origin, $origin_id, $key, $value ){
	
		$key = utf8_encode ( $key );

		if( !isset( $this->char->tmpvars ) ){

			$this->char->tmpvars = array();

		}

		$this->char->tmpvars[] = (object) array( 

			'origin' => $origin,
			'origin_id' => $origin_id,
			'var_path' => $key,
			'value' => $value

		);

	}
	
	public function deltmpvars( $state_id ){

		foreach( $this->char->tmpvars as $key => $tmpvar ){

			if( $tmpvar->origin_id == $state_id ){
		
				unset( $this->char->tmpvars[$key] );

			}

		}

		$this->char->tmpvars = array_values( $this->char->tmpvars );

	}
	*/
	public function delstate( $state_id ){

		foreach( $this->char->states as $key => $state_object ){
			
			if( $state_object->id == $state_id ){
				
				unset( $this->char->states[$key] );

			}

		}

		$this->char->states = array_values( $this->char->states );
	
		//$this->deltmpvars( $state_id );
		
	}

	public function save_state( $state ){

		if( !isset( $this->lib ) ){

			$this->loadlib( $this->db );

		}

		$state->state_id = time();
		$this->lib->states[] = $state;

	}
	
	public function get_armor( $char, $object, $armor_formula, $type ){
		
		$key = count( $this->armor->objects );
		
		if( isset( $object->weapon_name ) ){ //Is weapon
		
			$object_type = isset( $object->weapon_type ) ? $object->weapon_type : 'Other';
		
			$this->armor->objects[$key] = array(
				
				'name' => $object->weapon_name,
				'object_type' => $object_type,
				'object_id' => $object->weapon_id,
				'type' => 'weapon'
				
			);
			
		}else{
			
			$object_type = isset( $object->eq_type ) ? $object->eq_type : 'Other';
			
			$this->armor->objects[$key] = array(
				
				'name' => $object->equipment_name,
				'object_type' => $object_type,
				'object_id' => $object->equipment_id,
				'type' => 'equipment'
				
			);
			
		}
		
		$this->armor->objects[$key]['armor_type'] = $type;
		$this->armor->objects[$key]['formula'] = $armor_formula;
		
		$armor_formula = preg_replace_callback( '#char[\.(a-zA-Z0-9_)]+#', function( $match ){

			return $this->get_var( $match[0] );
			
		}, $armor_formula );
		
		if( !is_array( $object->tier_lvl ) ){
		
			$tier_lvl = substr( $object->tier_lvl, 5 );
			
		}else{
			
			$tier_lvl = substr( $object->tier_lvl[0]->tier_lvl_name, 5 );
			
		}

		$this->armor->objects[$key]['tier_lvl'] = $tier_lvl;

		$armor_formula = preg_replace_callback( '#tier_lvl\[(\d+?),(\d+?),(\d+?),(\d+?),(\d+?)\]#', function( $match ) use ($tier_lvl){

			return $match[$tier_lvl];

		}, $armor_formula );
		
		$armor_formula = str_replace( "tier_lvl", $tier_lvl, $armor_formula );
		
		if( isset( $object->attributes ) && !empty( $object->attributes ) ){
		
			$attributes = $object->attributes;
			$attr_values = array();
		
			foreach( $attributes as $attr ){
				
				$attr_values[] = $this->get_var( 'char.attributes.'.$attr.'.attr_value' ); //$char->attributes->{$attr}->attr_value;
				
			}
			
			$highest_attr = max( $attr_values );
			
			$armor_formula = str_replace( "attribut", $highest_attr, $armor_formula );
			
		}
		
		$this->armor->objects[$key]['formula2'] = $armor_formula;
		/*
		$calc = new field_calculate;
		$armor_formula = $calc->calculate( str_replace( " ", "", $armor_formula ) );
		*/

		$armor_formula = preg_replace_callback( '#(\d+)d(\d+)#', function( $match ){

			$result = 0;

			for( $x=0; $x<$match[1]; $x++ ){

				$result += rand( 1, $match[2] );

			}

			return $result;

		}, $armor_formula );

		$armor_formula = compute( str_replace( " ", "", $armor_formula ) );
		$this->armor->objects[$key]['formula3'] = $armor_formula;
		
		return $armor_formula;
		
	}

	public function calculate_armor(){
		
		$this->armor = (object) array( 
			'objects' => array(),
			'agility' => $this->get_var('char.attributes.agility.mod'),
			'wisdom' => $this->get_var('char.attributes.wisdom.mod'),
			'result_physical' => $this->get_var('char.attributes.agility.mod'),
			'result_magical' => $this->get_var('char.attributes.wisdom.mod'),
			'tier_lvl' => 0
		);
		
		//$this->resistances = array();
		$this->cost_affection = array();
		
		if( isset( $this->char->weapons ) && count( $this->char->weapons  ) > 0 ){

			foreach( $this->char->weapons as $weapon ){
	
				$this->armor->result_physical += (int) $this->get_armor( $this->char, $weapon, $weapon->armor_formula, 'physical' );
				$this->armor->result_magical += (int) $this->get_armor( $this->char, $weapon, $weapon->magic_armor_formula, 'magical' );
				$this->armor->tier_lvl = $weapon->tier_lvl;
				$this->armor->object_id = $weapon->weapon_id;
				$already_attached = false;

				if( !empty( $weapon->modifiers ) ){

					foreach( $this->char->states as $state ){

						if( $state->name == $weapon->weapon_name ){

							$already_attached = true;

						}

					}

					if( $already_attached === false ){

						$origin_id = $weapon->weapon_id;
						$this->char->states[] = (object) array( "name" => $weapon->weapon_name, "id" => $origin_id, "rounds" => 99999, "caster" => $this->char->char_id, "caster_username" => $this->char->name, "vars" => $weapon->modifiers);

						/*
						foreach( $weapon->modificators AS $modificator ){

							$this->settmpvar( $weapon->weapon_name, $origin_id, $modificator->variable, $modificator->modifikator );

						}*/

					}

				}
	
			}
			
		}
		
		if( isset( $this->char->equipment ) && count( $this->char->equipment ) > 0 ){
		
			foreach( $this->char->equipment as $equipment ){
	
				$this->armor->result_physical += (int) $this->get_armor( $this->char, $equipment, $equipment->armor_formula, 'physical' );
				$this->armor->result_magical += (int) $this->get_armor( $this->char, $equipment, $equipment->magic_armor_formula, 'magical' );
				$this->armor->tier_lvl = $equipment->tier_lvl[0]->tier_lvl_name;
				$this->armor->object_id = $equipment->equipment_id;
				$already_attached = false;

				if( $equipment->tier_lvl[0]->affects_cost ){
				
					foreach( $equipment->tier_lvl[0]->affects_cost AS $cost_aff ){

						if( strpos( $cost_aff->cost_formula, "+" ) === false && strpos( $cost_aff->cost_formula, "-" ) ){

							$cost_aff->cost_formula *= -1;

						}

						$this->cost_affection[] = (object) array( 'type' => $cost_aff->cost_type, 'value' => $cost_aff->cost_formula );

					}

				}
				if( !empty( $equipment->tier_lvl[0]->modifiers ) ){

					foreach( $this->char->states as $state ){
						
						if( $state->name == $equipment->equipment_name ){

							$already_attached = true;

						}

					}

					if( $already_attached === false ){

						$origin_id = $equipment->equipment_id;
						$this->char->states[] = (object) array( "name" => $equipment->equipment_name, "id" => $origin_id, "rounds" => 999, "caster" => $this->char->char_id, "caster_username" => $this->char->name, "vars" => $equipment->tier_lvl[0]->modifiers );

						/*
						foreach( $equipment->tier_lvl[0]->modificators AS $modificator ){

							$this->settmpvar( $equipment->equipment_name, $origin_id, $modificator->variable, $modificator->modifikator );

						}*/

					}

				}
	
			}
			
		}
		
		$this->char->armor = $this->armor;
		//$this->char->resistances = $this->resistances;
		$this->char->cost_affection = $this->cost_affection;

	}

	public function diceroll( $dice_roll_formula, $options = array() ){
		
		if( isset( $options ) && isset( $options->target_char_id ) ){
			
			$dice_roll_formula = str_replace( $this->lang2['SETDATA_DICEROLL_TARGET'], $options->target_char_id, $dice_roll_formula );
			$dice_roll_formula = str_replace( 'ziel', $options->target_char_id, $dice_roll_formula );
			$dice_roll_formula = str_replace( '%target%', $options->target_char_id, $dice_roll_formula );
			
		}
		if( strpos( $dice_roll_formula, 'field_owner' ) !== false ){

			if( empty( $this->field_owner_id ) ){

				$this->field_owner_id = 3;

			}
			
			$dice_roll_formula = str_replace( 'field_owner', $this->field_owner_id, $dice_roll_formula );

		}
		
		if( isset( $options ) && isset( $options->attribute ) ){

			$dice_roll_formula = str_replace( 'attribute ', $options->attribute.' ', $dice_roll_formula );
			$dice_roll_formula = str_replace( 'w_tier_lvl', $options->w_tier_lvl, $dice_roll_formula );
			$dice_roll_formula = str_replace( 'e_tier_lvl', $options->e_tier_lvl, $dice_roll_formula );

		}

		if( isset( $options ) && isset( $options->w_skill ) && !empty( $options->w_skill ) ){

			$skill = 'char.skills.offensive.'.$options->w_skill.'.skill';
			$dice_roll_formula = str_replace( 'w_skill', $this->get_var($skill), $dice_roll_formula );

		}else if( isset( $options ) && isset( $options->e_skill ) && !empty( $options->e_skill ) ){

			$skill = 'char.skills.defensive.'.$options->e_skill.'.skill';
			$dice_roll_formula = str_replace( 'e_skill', $this->get_var($skill), $dice_roll_formula );

		}

		if( isset( $options ) && isset( $options->m_skill ) && !empty( $options->m_skill ) ){
			
			$skill = 'char.skills.magic_types.'.$options->m_skill.'.skill';
			$dice_roll_formula = str_replace( 'm_skill', $this->get_var($skill), $dice_roll_formula );
			
		}
		
		foreach( $options as $key => $value ){
			
			if( !in_array( $key, array( 'target_char_id', 'attribute', 'w_tier_lvl', 'e_tier_lvl', 'w_skill', 'e_skill', 'm_skill' ) ) ){
				$dice_roll_formula = str_replace( $key, $value, $dice_roll_formula );
			}
			
		}

		
		
		$dice_roll_formula = preg_replace_callback( '#COUNT\s?\(\s?(char\[?[(\w\d)]*\]?[\.\w\d]+)\[([\w\d\-]+)\]\s?\)#', function( $match ){

			$path = $this->get_var( $match[1] );
			

			if( empty( $path ) ){

				return 0;

			}

			$count = 0;

			foreach( $path as $value ){

				if( $value == $match[2] ){

					$count++;

				}

			}


			return $count;

		}, $dice_roll_formula );
		
		$dice_roll_formula = preg_replace_callback( '#(\d+)%\s?max_([a-z]+)#', function( $match ){

			$pool = $match[2];
			$percent = intval( $match[1] ) / 100;
			$max_pool = $this->get_var( 'char.pools.'.$pool.'.max' );
			return $max_pool * $percent;

		
		}, $dice_roll_formula );
		

		if( isset( $this->current_damage ) ){

			$dice_roll_formula = str_replace( 'DAMAGE', $this->current_damage, $dice_roll_formula );

		}else{

			$dice_roll_formula = str_replace( 'DAMAGE', 0, $dice_roll_formula );

		}
		
		$dice_roll_formula = preg_replace_callback( '#char\[?([a-zA-Z0-9]+)?\]?\.([a-zA-Z0-9_\.]+)#', function( $match ){
			
			return $this->get_var( $match[0] );
			
		}, $dice_roll_formula );
	
		$dice_roll_formula = preg_replace_callback( '#(\d+)%#', function( $match ){
            
	 		return rand( 0,99) < $match[1] ? 1 : 0;

		}, $dice_roll_formula );
		
		if( isset( $options ) && isset( $options->target_char_id ) ){

			$dice_roll_formula = preg_replace_callback( '#target_armor_([a-z]+)#', function( $match ) use( $options ){
				
				return $this->get_var( 'char['.$options->target_char_id.'].armor.result_'.$match[1] );
	
			}, $dice_roll_formula );
			
		}
		
		$dice_roll_formula = preg_replace_callback( '#\{([\d\+\-\*\/\(\)]+)\}d(\d+)#', function( $match ){

			$result = 0;

			if( !is_numeric( $match[1] ) ){
				
				if( preg_match( '#[a-z]+#', $match[1] ) ){
					
					trigger_error( 'Error: RegEx@'.__LINE__ );
					
				}else{
					
					eval( '$match[1] = '.$match[1].";" );
					
				}
				
			}
			

			for( $x=0; $x<$match[1]; $x++ ){

				$result += rand( 1, $match[2] );

			}

			return $result;

		}, $dice_roll_formula );
		
		$dice_roll_formula = preg_replace_callback( '#(\d+)d(\d+)#', function( $match ){

			$result = 0;
	
			for( $x=0; $x<$match[1]; $x++ ){

				$result += rand( 1, $match[2] );

			}

			return $result;

		}, $dice_roll_formula );

		$formula1 = $dice_roll_formula;

		$dice_roll_formula = preg_replace_callback( '#(\d)\[(\d*),?(\d*),?(\d*),?(\d*),?(\d*),?(\d*),?\]#', function( $match ){

			$array = array();

			for( $x = 1, $y = count( $match ); $x<$y; $x++ ){

				if( $match[$x] != '' ){

					$array[] = $match[$x];

				}

			}

			return $array[$match[1]];

		}, $dice_roll_formula );
		
		$dice_roll_formula = preg_replace_callback( '#\[\s*([\d\.]+)\s*\?\s*([\d\.])?\s*\??\s*([\d\.])?\s*\??\s*([\d\.])?\s*\]#', function( $match ){

			$highest = 0;

			for( $x = 1, $y = count( $match ); $x<$y; $x++ ){

				if( $match[$x] > $highest ){

					$highest = $match[$x];

				}

			}

			return $highest;

		}, $dice_roll_formula );
		
		if( isset( $options ) && isset( $options->target_char_id ) ){
			
			$dice_roll_formula = preg_replace_callback( '#([\d{1,2}d]*\d{1,4}\s*(<=?|>=?))(.*)#', function( $match ) use ( $options ) {

				return $match[1].'(('.$match[3].') * '.$this->get_var('char.globals.hit_chance_value').') * '.$this->get_var( 'char['.$options->target_char_id.'].globals.get_hit_chance_value' );

			}, $dice_roll_formula );
			
		}
		
		$formula2 = $dice_roll_formula;
		
		$dice_roll_formula = preg_replace_callback( '#(.+)([<>]+\=?)(.+)#', function( $match ){
			
			return compute( $match[1] ).' '.$match[2].compute( $match[3] );
			
		}, $dice_roll_formula );

		preg_match( '#min\s?(\d+)#i', $dice_roll_formula, $min ); //Minumum?

		if( !empty( $min ) ){

			$dice_roll_formula = str_replace( $min[0], '', $dice_roll_formula );

		}

		preg_match( '#max\s?(\d+)#i', $dice_roll_formula, $max ); //Maximum?

		if( !empty( $max ) ){

			$dice_roll_formula = str_replace( $max[0], '', $dice_roll_formula );

		}
		
		$formula25 = $dice_roll_formula;

		$dice_roll_formula = compute( str_replace( " ", "", $dice_roll_formula ) );

		if( !empty( $min ) && $min[1] > $dice_roll_formula ){

			$dice_roll_formula = $min[1];

		}

		if( !empty( $max ) && $max[1] < $dice_roll_formula ){

			$dice_roll_formula = $max[1];

		}
		
		return (object) array( "formula1" => $formula1, "formula2" => $formula2, "formula25" => $formula25, "formula3" => $dice_roll_formula );

	}

	public function select_action_by_name( $action_name ){

		foreach( $this->actions AS $action ){

			if( $action->action_name == $action_name ){

				$this->action = $action;
				
			}

		}

	}
	
	public function pay_action_cost( $action_cost, $token_cost, $add_tokens ){

		if( !isset( $this->lib ) ){

			$this->loadlib( $this->db );

		}

		$type = array();

		foreach( $this->lib->pools as $pool ){

			$type{$pool->name} = $pool->id;

		}
		
		foreach( $action_cost AS $cost ){
			
			if( empty( $cost->cost_value ) ){

				$cost->cost_value = 0;

			}

			foreach( $this->char->cost_affection AS $cost_aff ){
				
				if( $cost_aff->type == $cost->cost_type ){

					$cost->cost_value .= ' '.$cost_aff->value;

				}

			}

			$cost->cost_value = $cost->cost_value > 0 ? $cost->cost_value : 0;

			$diceroll = $this->diceroll( $cost->cost_value );

			$this->char->pools->{$type[$cost->cost_type]}->cur -= $diceroll->formula3;
			
		}

		if( !empty( $token_cost ) ){

			foreach( $token_cost AS $cost ){

				if( !empty( $cost->token_val ) ){

					for( $x = 0, $y = $cost->token_val; $x<$y; $x++ ){

						$this->remove_token( $cost->token );

					}

				}else if( !empty( $cost->special_token_val ) ){

					for( $x = 0, $y = $cost->special_token_val; $x<$y; $x++ ){

						$this->remove_special_token( $cost->special_token );

					}

				}

			}

		}
		
		if( count( $add_tokens ) > 0 ){
		
			$this->char->pools->mana->cur -= count( $add_tokens ) * 2;
			
			foreach( $add_tokens AS $token ){
				
				$found = false;
				
				for( $x = 0, $y = count( $this->char->tokens ); $x<$y; $x++ ){
					
					if( $found === false && $this->char->tokens[$x] == $token ){
						
						unset( $this->char->tokens[$x] );
						$found = true;
					}
					
				}
				
				$this->char->tokens = (array) array_values( $this->char->tokens );
				
			}
			
		}
		
	}
	
	public function calculate_damage( $action, $damage, $tier_lvl_index, $add_tokens, $options ){
		
		$dmg_output = array( 'physical' => [], 'magical' => [] );
		$this->tokens = array();
		
		$add_token_count = count( $add_tokens );

		if( $add_token_count > 0 ){
		
			$damage[0]->formula .= ' +'.$add_token_count.'d8';

		}
		
		foreach( $damage AS $dmg ){
			
			$dmg_formula = $dmg->formula;
			$dmg->add_token = isset( $dmg->add_token ) ? $dmg->add_token : 0;
			
			$attribute = 0;
			
			foreach( $action->attributes AS $attr ){
				
				if( $this->char->attributes->{$attr}->mod > $attribute ){
					
					$attribute = $this->char->attributes->{$attr}->mod;
					
				}
				
			}
			
			$dmg_formula = str_replace( 'attribute', $attribute, $dmg_formula );
			
			//var_dump( $dmg );
			
			if( strpos( $dmg->damage_type,  "Magic" ) !== false ){
				
				if( $dmg->damage_heal != 'heal' ){

					foreach( $this->char->states as $state ){

						foreach( $state->vars AS $var ){
	
							if( $var->path == 'char.globals.magical_damage_output' ){
	
								$dmg_formula = '('.$dmg_formula.') '.$var->modifier;
	
							}
	
						}

					}
					
				}

				$dmg_calc = $this->diceroll( $dmg_formula, $options );
				
				//$magic = isset( $action->action_magic_type ) ? $action->action_magic_type : 'Magic';

				$dmg->add_token = empty( $dmg->add_token ) ? array() : $dmg->add_token;
				
				$dmg_output['magical'][] = array( 'type' => $dmg->damage_type, 'formula' => $dmg_formula, 'affected_damage_pool' => $dmg->affected_damage_pool, 'calc' => $dmg_calc, 'damage_heal' => $dmg->damage_heal, 'skip_resistance' => $dmg->skip_resistance, 'add_token' => $dmg->add_token );
				
				for( $x=0,$y=count($dmg->add_token);$x<$y;$x++ ){
					$this->tokens[] = $dmg->damage_type;
				}

				$this->writelog( $dmg->damage_type.' '.$dmg->formula.' '.$this->lang2['SETDATA_DAMAGE_ON'].' '.$dmg->affected_damage_pool, 0 );
				$this->writelog( $dmg_calc, 0 );
				
				
				
			}else{
				
				if( $dmg->damage_heal != 'heal' ){

					foreach( $this->char->states as $state ){

						foreach( $state->vars AS $var ){
	
							if( $var->path == 'char.globals.physical_damage_output' ){
	
								$dmg_formula = '('.$dmg_formula.') '.$var->modifier;
	
							}
	
						}

					}
					
				}

				$dmg_calc = $this->diceroll( $dmg_formula, $options );
				
				$dmg_output['physical'][] = array( 'type' => $dmg->damage_type, 'formula' => $dmg_formula, 'affected_damage_pool' => $dmg->affected_damage_pool, 'calc' => $dmg_calc, 'damage_heal' => $dmg->damage_heal, 'skip_resistance' => $dmg->skip_resistance, 'add_token' => $dmg->add_token );
				
				for( $x=0,$y=$dmg->add_token;$x<$y;$x++ ){
					$this->tokens[] = $dmg->damage_type;
				}

				$this->writelog( $dmg->damage_type.' '.$dmg->formula.' '.$this->lang2['SETDATA_DAMAGE_ON'].' '.$dmg->affected_damage_pool, 0 );
				$this->writelog( $dmg_calc, 0 );
			}
			
		}
		
		if( $action->action_type == 'Potion_Item' ){
				
				$action_name = $action->action_name;
				$eq_used = false;
				
				foreach( $this->char->equipment AS  $i => $equipment ){
					
					if( $eq_used === false && $equipment->equipment_name == $action->tier_lvl[$tier_lvl_index]->item_filters[0]->item_filter ){
						
						$equipment->tier_lvl[0]->durability -= 1;
						$this->writelog( "New item durability: ".$equipment->tier_lvl[0]->durability." (If 0 then item destroyed)", $this->char->controller_user_id );
						
						if( $equipment->tier_lvl[0]->durability <= 0 ){ //Item destroyed
						
							unset( $this->char->equipment[$i] );
							$this->char->equipment = array_values( $this->char->equipment );
							$this->calculate_armor();
						
						}
						
						
						$eq_used = true;
						
					}
					
				}
				
				
		}
		
		return $dmg_output;
		
	}

	public function recieve_damage( $calc_dmg, $options ){

		if( empty( $this->lib ) ){
			
			$this->loadlib( $this->db );

		}

		$type = array();

		foreach( $this->lib->pools as $pool ){

			$type{$pool->name} = $pool->id;

		}
		
		foreach( $calc_dmg['magical'] AS &$dmg ){
			
			$formula = $dmg["calc"]->formula3;
			
			if( $dmg['skip_resistance'] == 'false' && $dmg['damage_heal'] != 'heal' ){

				foreach( $this->char->states as $state ){

					foreach( $state->vars AS $var ){
	
						if( $var->path == 'char.globals.magical_damage_input' ){

							$value = parse_string( 1, $var->modifier );

							if( $value != 1 ){
						
								$formula = '('.$formula.') *'.$value;

							}
						
	
						}

					}
	
				}

				$key = array_search( $dmg['type'], array_column( $this->lib->magic_types, 'magic_class_name'));
				$magic_class = $this->lib->magic_types[$key]->magic_class_name;
	
				foreach( $this->char->resistances as $key => $res ){
		
						if( $key == $dmg['type'] || $key == 'Magic general' || $key == 'Magic '.$magic_class ){
							
							$value = $this->get_var( 'char.resistances.'.$key );

							if( $value != 0 ){

								if( strpos( $value, "+" ) === false && strpos( $value, "-" ) === false ){

									$value = "+ ".$value;

								}
							
		
								$switched = strtr( $value, array("+" => "-", "-" => "+"));
								$formula .= $switched;

							}
		
						}	
						
				}
				
			}
			
			$dmg["formula"] = $formula;
			$dmg["calc"] = $this->diceroll( $formula, $options );
			//$type = array( "Lebenspunkte" => "life", "Mana" =>"mana", "Aktionspunkte"=>"ap", "Bewegung"=>"move", "Vergiftungsresistenz"=>"poison", "Krankheitsresistenz"=>"sickness" );
			
			if( $dmg['damage_heal'] != 'heal' ){
			
				$dmg['calc']->formula3 = $dmg['calc']->formula3 < 1 ? 1 : $dmg['calc']->formula3;

				if( ( $dmg['affected_damage_pool'] == 'Life points' || $dmg['affected_damage_pool'] == 'Lebenspunkte' ) && $this->char->pools->lp_shield->cur > 0 ){

					$dmg_to_shield = $dmg['calc']->formula3 > $this->char->pools->lp_shield->cur ? $this->char->pools->lp_shield->cur : $dmg['calc']->formula3;
					$this->char->pools->lp_shield->cur -= $dmg_to_shield;
					$dmg['calc']->formula3 -= $dmg_to_shield;

				}

				$this->char->pools->{$type[$dmg['affected_damage_pool']]}->cur -= $dmg['calc']->formula3;
				$this->current_damage += $dmg['calc']->formula3;
				$texttitle = 'Effective damage';
				
			}else{
				
				$this->char->pools->{$type[$dmg['affected_damage_pool']]}->cur += $dmg['calc']->formula3;
				$texttitle = 'Effective healing';
				
			}
			
			$this->writelog( $texttitle.' '.$dmg["type"].' ['.$dmg["formula"].'] on '.$dmg["affected_damage_pool"], $this->char->controller_user_id );
			$this->writelog( $dmg["calc"], $this->char->controller_user_id );

			//$this->writelog( $text, $this->char->controller_user_id );

		}

		foreach( $calc_dmg['physical'] AS &$dmg ){

			$formula = $dmg["calc"]->formula3;
			
			if( $dmg['skip_resistance'] == 'false' && $dmg['damage_heal'] != 'heal' ){

				foreach( $this->char->states as $state ){

					foreach( $state->vars AS $var ){
	
						if( $var->path == 'char.globals.physical_damage_input' ){

							$value = parse_string( 1, $var->modifier);

							if( $value != 1 ){
						
								$formula = '('.$formula.') *'.$value;

							}

						}

					}
	
				}
				
				foreach( $this->char->resistances as $key => $res ){
	
					if( $key == $dmg['type'] || $key == 'Physical general' ){
				
						
						$value = $this->get_var( 'char.resistances.'.$key );

						if( $value != 0 ){

							if( strpos( $value, "+" ) === false && strpos( $value, "-" ) === false ){

								$value = "+ ".$value;

							}
		
							$switched = strtr( $value, array("+" => "-", "-" => "+"));
							$formula .= $switched;

						}
			
	
					}
					
				}
				
			}
			
			$dmg["formula"] = $formula;
		
			$dmg["calc"] = $this->diceroll( $formula, $options );
			
			//$type = array( "Lebenspunkte" => "life", "Mana" =>"mana", "Aktionspunkte"=>"ap", "Bewegung"=>"move", "Vergiftungsresistenz"=>"poison", "Krankheitsresistenz"=>"sickness" );
			
			if( $dmg['damage_heal'] != 'heal' ){
			
				$dmg['calc']->formula3 = $dmg['calc']->formula3 < 1 ? 1 : $dmg['calc']->formula3;

				if( ( $dmg['affected_damage_pool'] == 'Life points' || $dmg['affected_damage_pool'] == 'Lebenspunkte' ) && $this->char->pools->lp_shield->cur > 0 ){

					$dmg_to_shield = $dmg['calc']->formula3 > $this->char->pools->lp_shield->cur ? $this->char->pools->lp_shield->cur : $dmg['calc']->formula3;
					$this->char->pools->lp_shield->cur -= $dmg_to_shield;
					$dmg['calc']->formula3 -= $dmg_to_shield;

				}

				$this->char->pools->{$type[$dmg['affected_damage_pool']]}->cur -= $dmg['calc']->formula3;
				$this->current_damage += $dmg['calc']->formula3;
				$texttitle = 'Effective damage';
				
			}else{
				
				$this->char->pools->{$type[$dmg['affected_damage_pool']]}->cur += $dmg['calc']->formula3;
				$texttitle = 'Effective healing';
			}
			
			$text = '<div style="font-size:10px;"><span style="color:white;font-weight: bold;">'.$texttitle.'</span>: '.
					'<span style="color:white;">'.$dmg["type"].' ['.$dmg["formula"].'] on '.$dmg["affected_damage_pool"].'</span> => '.
					'<span style="color:yellow;">'.$dmg["calc"]->formula1.'</span> => '.
					'<span style="color:orange;">'.$dmg["calc"]->formula2.'</span> => '.
					'<span style="color:green;">'.$dmg["calc"]->formula3.'</span></div>';
			
			$this->writelog( $text, $this->char->controller_user_id );

		}

		return $calc_dmg;

	}
	
	public function add_tokens(){

		$this->trigger_event( 'on_add_token', $this->char->char_id, $this->char->char_id );

		$max_token = (int) $this->get_var( 'char.skilldegree' );
		$max_all_token = (int) $this->get_var( 'char.maxskill' );
		
		foreach( $this->tokens AS $token ){

			$count = 0;

			foreach( $this->char->tokens AS $token2 ){

				if( $token2 == $token ){

					$count++;

				}

			}
			
			if( $count < $max_token && count( $this->char->tokens ) < $max_all_token ){
				
				$this->char->tokens[] = $token;

			}
			
		}
		
	}

	public function add_special_tokens(){

		$max_token = 1000;
		$max_all_token = (int) $this->get_var( 'char.maxskill' );

		$this->trigger_event( 'on_add_special_token', $this->char->char_id, $this->char->char_id );
		
		foreach( $this->special_tokens AS $token ){

			$count = 0;

			foreach( $this->char->special_tokens AS $token2 ){

				if( $token2 == $token ){

					$count++;

				}

			}
			
			if( $count < $max_token && count( $this->char->special_tokens ) < $max_all_token ){
				
				$this->char->special_tokens[] = $token;

			}
			
		}
		
	}
	
	public function new_round( $leftright ){
		
		if( empty( $this->lib ) ){
			
			$this->loadlib( $this->db );

		}

		$type = array();

		foreach( $this->lib->pools as $pool ){

			$type{$pool->name} = $pool->id;

		}

		foreach( $this->chars AS $char ){
			
			$this->char( $char->char_id );
			
			if( $char->leftright == $leftright ){

				foreach( $type AS $key => $val ){
				
					$addval = $this->get_var( 'char.pools.'.$val.'.add' );
					
					if( $addval > 0 ){
						
						if( $val != 'ap' && $val != 'move' ){
							
							$this->writeLog( $char->name.' regenerates '.$addval.' '.$val.' points', 0 );
							
						}
						
						$char->pools->{$val}->cur += $addval;
						
					}
					
					$cur = $this->get_var( 'char.pools.'.$val.'.cur' );
					$max = $this->get_var( 'char.pools.'.$val.'.max' );
					$char->pools->{$val}->cur = $cur > $max ? $max : $cur;
					
				}
				
				for( $x = 0, $y = count($char->states); $x<$y; $x++ ){
					
					if( isset( $char->states[$x]->rounds ) ){
						
						$char->states[$x]->rounds--;
						
						if( $char->states[$x]->rounds <= 0 ){
							
							$this->writeLog( 'State '.$char->states[$x]->name.' of '.$char->name.' expired and removed', 0 );
							$this->delstate( $char->states[$x]->id );
							
						}
						
					}
					
				}
				
				if( count($char->states) < $y ){
					
					$char->states = array_values ( (array) $char->states );
					
				}
				
				foreach( $this->fields as $field ){
					
					if( $field->field_owner_id == $char->char_id && !empty( $field->cost ) ){
						
						$field->field_cost_paid = false;
						
					}
					
				}

			}

		}

		$this->game->tokenpool_users = array();

	}

	public function remove_token( $token_type ){

		$deleted = 0;
		$y = count($this->char->tokens);
		
		for( $x=0; $x<$y; $x++ ){
			
			if( $this->char->tokens[$x] == $token_type && $deleted == 0 ){
				
				unset( $this->char->tokens[$x] );
				$deleted = 1;

			}
		}

		if( $deleted ){

			$this->char->tokens = array_values ( (array) $this->char->tokens );

		}
		
	}

	public function remove_all_token( $token_type ){

		$deleted = 0;
		$y = count($this->char->tokens);
		
		for( $x=0; $x<$y; $x++ ){
			
			if( $this->char->tokens[$x] == $token_type ){
				
				unset( $this->char->tokens[$x] );
				$deleted += 1;

			}
		}

		if( $deleted ){

			$this->char->tokens = array_values ( (array) $this->char->tokens );

		}
		
	}

	public function remove_special_token( $special_token_type ){

		$deleted = 0;
		$y = count($this->char->special_tokens);
		
		for( $x=0; $x<$y; $x++ ){
			
			if( $this->char->special_tokens[$x] == $special_token_type && $deleted == 0 ){
				
				unset( $this->char->special_tokens[$x] );
				$deleted = 1;

			}
		}

		if( $deleted ){

			$this->char->special_tokens = array_values ( (array) $this->char->special_tokens );

		}
		
	}

	public function remove_all_special_token( $special_token_type ){

		$deleted = 0;
		$y = count($this->char->special_tokens);
		
		for( $x=0; $x<$y; $x++ ){
			
			if( $this->char->special_tokens[$x] == $special_token_type ){
				
				unset( $this->char->special_tokens[$x] );
				$deleted += 1;

			}
		}

		if( $deleted ){

			$this->char->special_tokens = array_values ( (array) $this->char->special_tokens );

		}
		
	}

	public function save_chars( $db ){

		$data = json_encode( $this->all_chars, JSON_PRETTY_PRINT );

		try{

			$stmt = $db->prepare( 'UPDATE chars SET `data`=:data WHERE `char_id`=1' );
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

			$success = true;

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

	}

	public function save_lib( $db ){

		$data = json_encode( $this->lib, JSON_PRETTY_PRINT );

		try{

			$stmt = $db->prepare( 'UPDATE lib_data SET `data`=:data WHERE `data_id`=1' );
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

			$success = true;

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

	}

	public function save_shop(){

		global $db;

		$this->shops[$this->shop_key] = $this->shop;

		$data = json_encode( $this->shops, JSON_PRETTY_PRINT );

		try{

			$stmt = $db->prepare( 'UPDATE shops SET `data`=:data WHERE `data_id`=1' );
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

			$success = true;

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

	}

	public function save( $db ){

		$data = json_encode( $this->game, JSON_PRETTY_PRINT );

		try{

			$stmt = $db->prepare( 'UPDATE game SET `data`=:data WHERE `active`=1' );
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

			$success = true;

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

	}

	public function select_targets( $targets, $char_id, $target_char_id ){

		switch( $targets ){
	
			case 'action_executor':
	
				return array( 0 => $char_id );
	
			break;
	
			case 'action_target':
	
				return array( 0 => $target_char_id );
	
			break;
			case 'executor_group':
	
				return $this->get_group_member_ids( $target_char_id, $this->char->leftright, 'friend', true );
	
			break;
			case 'target_group':
	
				return $this->get_group_member_ids( $target_char_id, $this->char->leftright, 'foe', true );
	
			break;
			case 'executor_group_others':
	
				return $this->get_group_member_ids( $target_char_id, $this->char->leftright, 'friend', false );
	
			break;
			case 'target_group_others':
	
				return $this->get_group_member_ids( $target_char_id, $this->char->leftright, 'foe', false );
	
			break;
	
		}

	}

	public function execute_effects_damage( $char_id, $target_char_id, $action, $effects_add_damage, $field_target_is_action_target = false ){

		if( empty( $effects_add_damage ) ){

			return true;

		}

		$options = (object) array( "target_char_id" => $target_char_id );

		if( isset( $_GET['data'] ) ){

			foreach( $_GET['data'] AS $key => $val ){

				$options->{$key} = $val;

			}

		}

		foreach( $effects_add_damage AS $effect ){

			$diceroll = $this->diceroll( $effect->chance );

			if( $diceroll->formula3 != 1 ){
				
				$this->writelog( 'Effect additional damage misses '.$this->char_names[$target_char_id], 0 );
				return true;

			}

			$this->writelog( 'Effect additional damage takes place', 0 );

			$effect_target_char_ids = $this->select_targets( $effect->target, $char_id, $target_char_id );



				foreach( $effect_target_char_ids AS $caster_char_id ){

					if( $field_target_is_action_target == true ){
						
						$caster_char_id = $this->action_target_id; //Switch target if required

					}

					$this->char( $caster_char_id );
						
					$dmg_output = array( 'physical' => [], 'magical' => [] );
	
					$this->writelog( 'Additional damage "'.$effect->damage_formula.' is diced ('.$caster_char_id.')', 0 );
					
					$dmg_calc = $this->diceroll( $effect->damage_formula, $options );

					$this->writelog( $dmg_calc, 0 );
					
					$this->char( $caster_char_id );
	
					if( strpos( $effect->damage_type,  "Magic" ) !== false ){
	
						$dmg_output['magical'][] = array( 'type' => $effect->damage_type, 'formula' => $effect->damage_formula, 'affected_damage_pool' => $effect->affected_damage_pool, 'calc' => $dmg_calc, 'damage_heal' => $effect->damage_heal, 'skip_resistance' => $effect->skip_resistance, 'add_token' => 0 );
	
					}else{
	
						$dmg_output['physical'][] = array( 'type' => $effect->damage_type, 'formula' => $effect->damage_formula, 'affected_damage_pool' => $effect->affected_damage_pool, 'calc' => $dmg_calc, 'damage_heal' => $effect->damage_heal, 'skip_resistance' => $effect->skip_resistance, 'add_token' => 0 );
	
					}
					
					$this->recieve_damage( $dmg_output, $options );

					$dmg_heal = $effect->damage_heal == 'damage' ? 'damage' : 'healing';
		
					$this->writelog( $this->char->name.' recieves '.$dmg_calc->formula3.' '.$effect->damage_type.' additional '.$dmg_heal.' on '.$effect->affected_damage_pool, $this->char->controller_user_id );
	
				}

				$this->char( $char_id );

		}

	}

	public function execute_effects_add_special_token( $char_id, $target_char_id, $action, $effects_add_special_token ){

		
		if( empty( $effects_add_special_token ) ){

			return true;

		}

		foreach( $effects_add_special_token AS $effect ){

			$diceroll = $this->diceroll( $effect->chance );

			if( $diceroll->formula3 != 1 ){

				$this->writelog( 'Effect additional special token misses '.$this->char_names[$target_char_id], 0 );
				return true;

			}

			$this->writelog( 'Effect special token takes place', 0 );

			$effect_target_char_ids = $this->select_targets( $effect->target, $char_id, $target_char_id );

			
				foreach( $effect_target_char_ids AS $caster_char_id ){

					$this->char( $caster_char_id );
	
					$this->special_tokens = array_fill( 0, 1, $effect->token );
	
					$this->add_special_tokens();
					$this->writelog( $this->char->name.' gets a '.$effect->token.' special token', 0 );

				}

				$this->char( $char_id );
		}

	}

	public function execute_effects_add_state( $char_id, $target_char_id, $action, $effects_add_state, $options ){

		if( empty( $effects_add_state ) ){

			return true;

		}

		foreach( $effects_add_state AS $effect ){

			$diceroll = $this->diceroll( $effect->chance );

			if( $diceroll->formula3 != 1 ){

				$this->writelog( 'Effect additional state misses '.$this->char_names[$target_char_id], 0 );
				return true;

			}

			$this->writelog( 'Effect additional state takes place', 0 );

			$effect_target_char_ids = $this->select_targets( $effect->target, $char_id, $target_char_id );

			
			
			foreach( $effect_target_char_ids AS $caster_char_id ){

				$rounds = $effect->rounds;

				if( !is_numeric( $rounds ) ){

					$roll = $this->diceroll( $rounds, $options );
					$rounds = $roll->formula3;

				}
	
				foreach( $effect_target_char_ids AS $caster_char_id ){
	
					$caster_username = $this->char->name;
	
					$this->char( $caster_char_id );

					if( $effect->add_remove == 'add' ){

						$this->setstate( $effect->state, 0, $rounds, $caster_char_id, $caster_username );
						$this->writelog( $this->char->name.' gets the state '.$effect->state.' for '.$rounds.' rounds', 0 );

					}else{

						$state_id = $this->get_state_id( $effect->state, $caster_char_id );
						$this->delstate( $state_id );
						$this->writelog( $this->char->name.' loses the state '.$effect->state, 0 );

					}
	
				}

			}

			$this->char( $char_id );

			

		}

	}

	public function execute_effects_summon_char( $char_id, $target_char_id, $action, $effects_summon_char ){

		global $db;

		if( empty( $effects_summon_char ) ){

			return true;

		}

		foreach( $effects_summon_char AS $effect ){

			$diceroll = $this->diceroll( $effect->chance );

			if( $diceroll->formula3 != 1 ){

				$this->writelog( 'Effect summoning misses '.$this->char_names[$target_char_id], 0 );
				return true;

			}

			$this->writelog( 'Effect summoning takes place', 0 );

			$this->loadchars( $db );

			$preset = array();

			if( isset( $effect->pool_preset ) ){

				$preset = $effect->pool_preset;
				
			}

			foreach( $preset as &$pool_preset ){

				$value = $this->diceroll( $pool_preset->pool_value );
				$pool_preset->pool_value = $value->formula3;

			}

			$this->add_char_to_game( $this->get_user_id_by_name2( $effect->char_name ), $this->char->leftright, $preset );
			
			
		}

	}
	
	public function execute_effects_add_field( $char_id, $target_char_id, $action, $effects_add_field ){
		
		global $db;
		
		if( empty( $effects_add_field ) ){

			return true;

		}
		
		foreach( $effects_add_field AS $effect ){
			
			$diceroll = $this->diceroll( $effect->chance );

			if( $diceroll->formula3 != 1 ){

				$this->writelog( 'Effect new field misses '.$this->char_names[$target_char_id], 0 );
				return true;

			}
			
			$this->writelog( 'Effect new field takes place', 0 );
			
			$this->loadfields( $db );
			
			foreach( $this->stored_fields as $field ){
				
				if( $field->field_name == $effect->field ){
					
					$field_id = $field->creation_date;
					$found = true;
					
				}
				
			}

			if( !isset( $found ) ){

				$this->writelog( 'The stored field '.$effect->field.' was not found. ERROR.', 0 );

			}
			
			$field_owner_id = $this->select_targets( $effect->field_owner, $char_id, $target_char_id );
			$field_target_ids = $this->select_targets( $effect->field_targets, $char_id, $target_char_id );
			/*
			if( is_array( $field_owner_id ) ){
				
				$field_owner_id = $field_owner_id[0];
				
			}
			*/
			$this->load_field( $field_id, $field_owner_id, $field_target_ids );
			
		}
		
	}

	public function add_char_to_game( $char_id, $leftright, $preset = array() ){

		global $db;
		global $l;
		global $lang;

		$in_game = false;
		$char_string = '';

		if( empty( $this->lib ) ){
			
			$this->loadlib( $this->db );

		}

		$type = array();

		foreach( $this->lib->pools as $pool ){

			$type{$pool->name} = $pool->id;

		}		

		foreach( $this->chars as $char ){

			$char_string .= $char->name.' | ';

			if( $char->char_id == $char_id ){

				$in_game = true;

			}

		}

		try{

			$stmt = $db->prepare( 'SELECT * FROM chars WHERE `char_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$chars = json_decode( $row['data'] );
		}

		$this->loadlib( $db );

		foreach( $chars as $char ){

			if( $char->char_id == $char_id ){

				if( $in_game == true ){

					$name = $char->name;
					
					preg_match_all( '#('.$name.')\s?(\d)*\s?\|#', $char_string, $matches );
					
					

					array_multisort( $matches[2], SORT_ASC, SORT_NATURAL );


					
					if( !empty( $matches[2][count($matches[2])-1] ) ){
		
						
						$num = (int) $matches[2][count($matches[2])-1];
						$num++;
						$char->name = $name.' '.$num;




					}else{
	

						$char->name = $name.' 2';

	

					}

					usleep(100000);
					$char->char_id = time();


				}

				$char->leftright = $leftright;

				$char->pools = (object) [
					'life' => (object) 	['cur' => intval($char->life->current_life), 'max' => intval($char->life->max_life), 'add' => 0],
					'mana' => (object) 	['cur' => intval($char->mana->current_mana), 'max' => intval($char->mana->max_mana), 'add' => 0],
					'ap' => (object) 	['cur' => 100, 'max' => 100, 'add' => 100],
					'move' => (object) 	['cur' => 5, 'max' => 5, 'add' => 10],
					'poison' => (object) 	['cur' => 0, 'max' => 100, 'add' => 0],
					'sickness' => (object) 	['cur' => 0, 'max' => 100, 'add' => 0],
					'lp_shield' => (object) ['cur' => 0, 'max' => 1000, 'add' => 0],
				];

				foreach( $preset as $pool_preset ){

					$char->pools->{$type[$pool_preset->pool_name]}->cur = $pool_preset->pool_value;
					$char->pools->{$type[$pool_preset->pool_name]}->max = $pool_preset->pool_value;

				}
				
				$char->controller_user_id = get_controller_user_id_by_name( $db, $char->controller );

				unset( $char->life );
				unset( $char->mana );

				//$char->aktionspunkte = (object) ["current_ap" => "100", "max_ap" => "100"];
				$char->states = [];
				$char->tokens = [];
				$char->special_tokens = [];
				$char->resistances = (object) array();

				foreach( $this->lib->damage_types_all as $damage ){

					$char->resistances->{$damage->name} = 0;

				}

				foreach( $char->fields as $field ){

					$field->field_start_round = $this->game->current_round;

				}
				
			
				$char->globals = (object) array(
					"physical_damage_input" => 1,
					"physical_damage_output" => 1,
					"magical_damage_input" => 1,
					"magical_damage_output" => 1,
					"hit_chance_value" => 1,
					"get_hit_chance_value" => 1
					
				);
				if( empty( $char->weapons ) ){

					$char->weapons = [];

				}

				if( empty( $char->equipment ) ){

					$char->equipment = [];

				}

				if( empty( $char->equip ) ){

					$char->equip = (object) array( "weapons" => [], "equipment" => [] );

				}

				if( !empty( $char->fields ) ){

					foreach( $char->fields as $field ){

						$this->game->fields[] = $field;

					}

				}

				if( $char->img_url == "" ){

					$char->img_url = 'monster.jpg';

				}

				$game_obj = new game( $db, $l, $lang );
				$game_obj->char = $char;

				$game_obj->calculate_armor();

				$this->game->chars[] = $char;

				$this->writelog( 'Character '.$char->name.' has joined the game', 0 );

			}

		}


	}

	public function new_field(){

		$field_id = time().rand(0,100);

		$newfield = (object) array(

			'creation_date' => $field_id,
			'field_name' => 'Field '.rand(100,999),
			'field_owner_id' => 0,
			'field_target_ids' => array(),
			'field_status' => array(),
			'field_events' => array(),
			'cost' => array(),
			'field_cost_paid' => false,
			'field_start_round' => $this->game->current_round
		);

		$this->game->fields[] = $newfield;
		$this->writelog( 'New field created: '.$newfield->field_name, 0 );

		return $field_id;

	}

	public function add_field_owner( $field_id, $field_owner_id ){

		foreach( $this->fields AS &$field ){

			if( $field->creation_date == $field_id ){

				$field->field_owner_id = $field_owner_id;

				$this->writelog( $this->char->name.' now controls field '.$field->field_name, 0 );
				
				foreach( $field->field_status as $state_id ){
					
					foreach( $this->chars as $char ){
						
						foreach( $char->states as $state ){
							
							if( $state->id == $state_id ){
								
								$state->field_owner_id = $field_owner_id;
															
							}
							
						}
						
					}
					
				}

			}

		}

	}

	public function add_field_targets( $field_id, $field_target_ids ){

		foreach( $this->fields AS &$field ){

			if( $field->creation_date == $field_id ){

				$field_owner_id = $field->field_owner_id;
				$this->char( $field_owner_id );
				$username = $this->char->name;

				foreach( $field_target_ids AS $target_id ){

					if( !in_array( $target_id, $field->field_target_ids ) ){

						$field->field_target_ids[] = $target_id;

						$this->char( $target_id );

						foreach( $field->field_status AS $state_id ){

							$this->setstate( '', $state_id, 999, $field_owner_id, $username, array(), array(), $field_id, $field_owner_id );

						}

						$this->writelog( $this->char->name.' is now affected by field '.$field->field_name.' ', 0 );

					}

				}

			}

		}

	}

	public function remove_field_target( $field_id, $field_target_id ){

		foreach( $this->fields AS &$field ){

			if( $field->creation_date == $field_id ){

				if( in_array( $field_target_id, $field->field_target_ids ) ){

					$field->field_target_ids = array_values( array_diff( $field->field_target_ids, array( $field_target_id ) ) );

					$this->char( $field_target_id );

					foreach( $field->field_status AS $state_id ){

						$this->delstate( $state_id );

					}


					$this->writelog( $this->char->name.' is not anymore affected by field '.$field->field_name.' ', 0 );

				}

			}

		}

	}

	public function remove_field_owner( $field_id ){

		foreach( $this->fields AS &$field ){

			if( $field->creation_date == $field_id ){

				$field->field_owner_id = 0;
				$this->writelog( 'Owner of field '.$field->field_name.' removed', 0 );

			}

		}

	}

	public function add_state_to_field( $field_id, $state_id ){

		if( $state_id == '' ){

			return false;

		}

		foreach( $this->fields AS &$field ){

			if( $field->creation_date == $field_id ){
				
				$field_owner_id = $field->field_owner_id;

				if( !in_array( $state_id, $field->status ) ){

					$field->field_status[] = $state_id;


					$this->char( $field_owner_id );
					$username = $this->char->name;

					foreach( $field->field_target_ids AS $char_id ){

						$this->char( $char_id );
						$this->setstate( '', $state_id, 999, $field->field_owner_id, $username, array(), array(), $field_id, $field_owner_id );

					}

				}

			}

		}

	}

	public function del_state_from_field( $field_id, $state_id ){

		foreach( $this->fields AS &$field ){

			if( $field->creation_date == $field_id ){

				if( in_array( $state_id, $field->field_status ) ){

					$field->field_status = array_values( array_diff( $field->field_status, array( $state_id ) ) );

					foreach( $field->field_target_ids AS $char_id ){

						$this->char( $char_id );
						$this->delstate( $state_id );

					}

				}

			}

		}

	}

	public function new_event( $field_id, $selected_rounds, $event_type, $data, $timelinedata ){

		foreach( $this->fields AS &$field ){

			if( $field->creation_date == $field_id ){

				$field->field_events[] = (object) array( "event_id" => time(), "rounds" => [], "event_type" => $event_type, "event_time" => $data['select_event_time'], "data" => $data, "timelinedata" => $timelinedata );
				$this->writelog( $field->field_name.' has recieved event '.$data['select_event_time'].' '.$event_type.' ', 0 );
			}

		}	

	}

	public function remove_field_event( $field_id, $event_id ){

		foreach( $this->fields AS &$field ){

			if( $field->creation_date == $field_id ){

				for( $x = 0, $y = count( $field->field_events ); $x<$y; $x++ ){

					if( $field->field_events[$x]->event_id == $event_id ){

						unset( $field->field_events[$x] );
						$field->field_events = array_values( $field->field_events );
						$this->writelog( 'Event '.$event_id.' removed', 0 );
					}

				}

			}

		}

	}

	public function edit_event( $field_id, $event_id, $selected_rounds, $event_type, $data, $timelinedata ){

		$this->remove_field_event( $field_id, $event_id );

		foreach( $this->fields AS &$field ){

			if( $field->creation_date == $field_id ){

				$field->field_events[] = (object) array( "event_id" => time(), "rounds" => [], "event_type" => $event_type, "event_time" => $data['select_event_time'], "data" => $data, "timelinedata" => $timelinedata );
				$this->writelog( 'Event '.$event_id.' changed', 0 );
			}

		}	

	}	
	
	
	public 	function timeline_activation_test( $start_round, $start_shift, $end_shift, $x_rounds, $dm, $current_round ){    
	
			$current_round = (float) $current_round; 
			
			if( fmod( $start_round, 1 ) == 0 ){
				
				  	$start_round -= 0.5;
				  
			  }	
	
			if( $dm === 'all' ){			
				
				return $current_round >= $start_round + $start_shift && $current_round <= $start_round + $end_shift;	
					
			}else{
				
				$dm = $dm === 'true' ? 1 : 0.5;
				$diff = $current_round-$start_shift + $dm - $start_round;
 				
				return $diff > 0 && fmod( $diff, $x_rounds ) == 0 && $current_round <= $start_round + $end_shift ? true : false;	   			
				
			}	
	}

	public function trigger_event( $event_time, $triggerer_id = 0, $target_id = 0, $action_type = '' ){
		
		foreach( $this->fields AS &$field ){
			
			if( !isset( $field->field_events ) ){
				
				return false;
				
			}

			foreach( $field->field_events as $event ){

				if( $event->event_time == $event_time ){

					if( $triggerer_id != 0 && $event->data->triggerer_restriction == 'only_trigger_is_owner' && $triggerer_id != $field->field_owner_id ){
						
						continue;

					} //Owner didn't trigger, skip

					if( $triggerer_id != 0 && $event->data->triggerer_restriction == 'only_trigger_is_target' && !in_array( $triggerer_id, $field->field_target_ids ) ){
						
						continue;

					} //Target didn't trigger, skip

					if( $target_id != 0 && isset( $event->data->only_trigger_target_is_target ) && $event->data->only_trigger_target_is_target == true && !in_array( $target_id, $field->field_target_ids ) ){
						
						continue;

					} //Target isn't field-target, skip

					if( $action_type != '' && isset( $event->data->trigger_action ) && !empty( $event->data->trigger_action ) && !in_array( $action_type, $event->data->trigger_action ) ){
						
						continue;

					} //Action type doesn't match
					
					$switch = isset( $event->data->field_target_is_action_target ) && $event->data->field_target_is_action_target == 'true' ? true : false; //Switch action target with $target_id

					$switch2 = isset( $event->data->action_target_is_field_owner ) && $event->data->action_target_is_field_owner == 'true' ? true : false; //Switch action target with $field_owner_id

					$switch3 = isset( $event->data->action_target_is_action_exec ) && $event->data->action_target_is_action_exec == 'true' ? true : false; //Switch action target with $triggerer_id
										
					if( isset( $event->timelinedata ) ){						
							
						
						if( $this->timeline_activation_test( $field->field_start_round, $event->timelinedata->start_shift, $event->timelinedata->end_shift, $event->timelinedata->x_rounds, $event->timelinedata->dm, $this->game->current_round ) === true ){							
							

							$this->writelog( '<div class="alert alert-success" role="alert">'.$field->field_name.' Event:</div>', 0 );
							$this->field_owner_id = $field->field_owner_id;
							
							$this->execute_event( $event_time, $field, $event, $target_id, $switch, $switch2, $switch3, $triggerer_id );
						
						}
																	
					}else{

						$this->writelog( 'Event '.$event_time.' tritt ein (Achtung: Dieser Aufruf ist veraltet)', 0 );
						if( in_array( $this->game->current_round, $event->rounds ) || $event->data->triggerer_restriction == 'always' ){


							$this->execute_event( $event_time, $field, $event, $target_id, $switch, $switch2, $switch3, $triggerer_id );

						}else{ $this->writelog( 'Debug3 '.$event_time.' tritt nicht ein ', 0 ); }				}

				}

			}

		}

	}

	public function execute_event( $event_time, $field, $event, $target_id, $switch, $switch2, $switch3, $triggerer_id ){
		
		if( $target_id != 0 && $switch === true ){
			
			$target_ids = [ $target_id ];
			
		}else if( $switch2 === true ){

			
			$target_ids = [ $this->field_owner_id ];

		}else if( $switch3 === true ){

			$target_ids = [ $triggerer_id ];

		}else{
			
			$target_ids = $field->field_target_ids;
			
		}

		$target_names = array();

		foreach( $target_ids as $target_id ){

			$target_names[] = $this->char_names[$target_id];

		}

		$this->writelog( 'Event '.$event_time.' hits '.implode(", ", $target_names), 0 );

		switch( $event->event_type ){

			case 'Add special token':

				foreach( $target_ids as $target_char_id ){

					$char_id = $field->field_owner_id;
					$action = array();

					$effects_add_special_token = array( (object) array(
						"chance" => $event->data->special_token_chance,
						"target" => "action_target",
						"token" => $event->data->special_token_token
					));
					
					$this->execute_effects_add_special_token( $char_id, $target_char_id, $action, $effects_add_special_token );

				}

			break;
			case 'Add normal token':

				foreach( $target_ids as $target_char_id ){

					$diceroll = $this->diceroll( $event->data->normal_token_chance );

					if( $diceroll->formula3 != 1 ){

						$this->writelog( $event->event_type. ' '.$this->char_names[$target_char_id].' erh&auml;lt kein Token', 0 );
						return true;

					}

					$this->char( $target_char_id );
					$this->tokens = array( $event->data->normal_token_token );
					$this->add_tokens();

				}

			break;
			case 'Add damage':

				foreach( $target_ids as $target_char_id ){


					$char_id = $field->field_owner_id;
					$action = array();
					$effects_add_damage = array( (object) array(
						"chance" => $event->data->add_damage_chance,
						"target" => "action_target",
						"damage_formula" => $event->data->add_damage_formula,
						"damage_type" => $event->data->add_damage_type,
						"affected_damage_pool" => $event->data->add_damage_affected_pool,
						"damage_heal" => $event->data->add_damage_damage_heal,
						"skip_resistance" => $event->data->add_damage_skip_resistance
						
					));

					$this->execute_effects_damage( $char_id, $target_char_id, $action, $effects_add_damage, false ); //$event->data->field_target_is_action_target
					
				}

			break;
			case 'Summon':

				foreach( $target_ids as $target_char_id ){

					$char_id = $field->field_owner_id;
					$action = array();
					$this->char( $target_char_id );

					$effects_summon_char = array( (object) array(

						"chance" => $event->data->summon_chance,
						"char_name" => $event->data->summon_char
					));

					$this->execute_effects_summon_char( $char_id, $target_char_id, $action, $effects_summon_char );

				}

			break;
			case 'Status':

				foreach( $target_ids as $target_char_id ){

					$char_id = $field->field_owner_id;
					$action = array();
					$effects_add_state = array( (object) array(
						"chance" => $event->data->state_chance,
						"rounds" => $event->data->rounds,
						"state_id" => $event->data->state_id,
						"target" => "action_target",
						"state" => ''
					));

					$this->char( $field->field_owner_id );

					switch( $event->data->state_addremove ){

						case 'add':

							$this->execute_effects_add_state( $char_id, $target_char_id, $action, $effects_add_state );

						break;
						case 'remove':

							$this->char( $target_char_id );
							$this->delstate( $event->data->state_id );

						break;

					}

				}

			break;

		}
	
	}

	public function delete_field( $field_id ){

		for( $x = 0, $y = count( $this->fields); $x<$y; $x++ ){

			if( $this->fields[$x]->creation_date == $field_id ){

				if( !empty( $this->fields[$x]->field_status ) ){

					foreach( $this->fields[$x]->field_target_ids as $target ){

						$this->char( $target );
						
						foreach( $this->fields[$x]->field_status as $state_id ){
							
							$this->delstate( $state_id );

						}

					}

				}

				$this->writelog( 'Field '.$this->fields[$x]->field_name.' is removed', 0 );
				unset( $this->fields[$x] );

			}

		}

		$this->fields = array_values( $this->fields );

	}

	public function unequip_object( $object_id, $type ){

		if( $type == 'weapon' ){

			foreach( $this->char->weapons as $key => $weapon ){

				if( $weapon->weapon_id == $object_id ){

					$this->char->equip->weapons[] = $weapon;
					unset( $this->char->weapons[$key] );
				
					$this->char->weapons = array_values( $this->char->weapons );
				}

			}

		}else if( $type == 'equipment' ){

			foreach( $this->char->equipment as $key => $equipment ){

				if( $equipment->equipment_id == $object_id ){

					$this->char->equip->equipment[] = $equipment;
					unset( $this->char->equipment[$key] );
				
					$this->char->equipment = array_values( $this->char->equipment );

					$this->delstate( $object_id );

				}

			}

		}

		$this->calculate_armor();

		$this->writelog( ucfirst($type).' stowed by '.$this->char->name.' ', 0 );

	}

	public function equip_object( $object_id, $type ){

		if( $type == 'weapon' ){

			foreach( $this->char->equip->weapons as $key => $weapon ){

				if( $weapon->weapon_id == $object_id ){

					$this->char->weapons[] = $weapon;
					unset( $this->char->equip->weapons[$key] );
					$this->char->equip->weapons = array_values( $this->char->equip->weapons );

				}

			}

		}else if( $type == 'equipment' ){

			foreach( $this->char->equip->equipment as $key => $equipment ){

				if( $equipment->equipment_id == $object_id ){

					$this->char->equipment[] = $equipment;
					unset( $this->char->equip->equipment[$key] );
					$this->char->equip->equipment = array_values( $this->char->equip->equipment );

				}

			}

		}

		$this->calculate_armor();

		$this->writelog( ucfirst($type).' equipped on '.$this->char->name.' ', 0 );

	}

	public function save_field( $field_id, $field_name ){

		$this->writelog( $field_id.' - '.$field_name, 0 );

		global $db;

		foreach( $this->fields as $field ){

			if( $field->field_name = $field_name ){

				foreach( $this->stored_fields as $i => $sto_field ){

					if( $sto_field->field_name == $field->field_name ){

						$already_stored_index = $i;

					}

				}

				if( isset( $already_stored_index ) ){

					$this->stored_fields[$already_stored_index] = $field;
					$this->writelog( 'Already existing Field overwritten', 0 );

				}else{

					$field->creation_date = time();
					$this->stored_fields[] = $field;

					$this->writelog( 'Stored as new Field', 0 );

				}

			}else if( $field->creation_date == $field_id ){

				$field->creation_date = time();
				$this->stored_fields[] = $field;

				$this->writelog( 'Stored as new Field', 0 );

			}

		}

		$tmp = json_encode( $this->stored_fields, JSON_PRETTY_PRINT );

		try {
		
			$stmt = $db->prepare("UPDATE `fields` SET fields=:fields WHERE `id`=1");
			$stmt->bindParam(':fields', $tmp, PDO::PARAM_STR);
			$stmt->execute();
		
		}catch(PDOException $ex) {
				echo "An Error occured! "; //user friendly message
				echo $ex->getMessage();
		}

	}

	public function load_field( $field_id, $field_owner_id = 0, $field_target_ids = array() ){
	
		if( is_array( $field_owner_id ) ){
			
			$field_owner_id = $field_owner_id[0];
			
		}

		foreach( $this->stored_fields as $sto_field ){
			
			if( $sto_field->creation_date == $field_id ){

				$sto_field->creation_date = time().rand(0,100);
				$add_field = clone $sto_field;
				$add_field->field_start_round = $this->game->current_round;

			}

		}

		foreach( $this->fields as $field ){

			if( $field->creation_date == $field_id ){

				$already_in_game = true; var_dump( $already_in_game );

			}

		}
		
		$targets = $add_field->field_target_ids;
		$target_found = array();
		
		
		foreach( $this->chars as $char ){
			
			if( $add_field->field_owner_id == $char->char_id ){
				
				$owner_found = true;
				
			}
			
			foreach( $targets as &$target ){
				
				if( $target == $char->char_id ){
					
					$target_found[] = $char->char_id;	

					unset( $target );
					
				}
				
			}
			
		}
		
		if( !isset( $owner_found ) ){
			
			$add_field->field_owner_id = 0;
			
		}
		
		if( $field_owner_id != 0 ){
			
			$add_field->field_owner_id = $field_owner_id;
			
		}
		
		if( !empty( $targets ) ){
			
			$add_field->field_target_ids = $target_found;
			
		}
		
		if( !empty( $field_target_ids ) ){
			
			$add_field->field_target_ids = $field_target_ids;

			foreach( $field_target_ids as $target ){

				foreach( $add_field->field_status as $state_id ){
					
					$this->char( $target );
	
									$this->setstate( '', $state_id, 99, $add_field->field_owner_id, $this->char_names[$add_field->field_owner_id], array(), array(), $field_id, $field_owner_id );

				}

			}
			
		}
		
		$add_field->field_cost_paid = true;

		if( !isset( $already_in_game ) ){

			$this->fields[] = $add_field;

		}

		$this->writelog( 'Field '.$add_field->field_name.' loaded', 0 );

	}

	public function rename_field( $field_id, $new_field_name ){

		foreach( $this->fields as $field ){

			if( $field->creation_date == $field_id ){

				$this->writelog( 'Field '.$field->field_name.' renamed to '.$new_field_name.' ', 0 );
				$field->field_name = $new_field_name;
				
			}

		}

	}

	public function add_field_to_char( $field_id, $field_name, $char_id ){ //all_chars, stored_fields

		global $db;

		foreach( $this->all_chars as $char ){

			if( $char->char_id == $char_id ){

				foreach( $this->stored_fields as $field ){

					if( $field->creation_date == $field_id && $field->field_name == $field_name ){

						if( empty( $char->fields ) ){

							$char->fields = array();

						}

						$field->creation_date = time();

						$char->fields[] = $field;

					}

				}

			}

		}

		$this->save_chars( $db );

	}

	public function delete_field_from_char( $field_id, $char_id ){

		global $db;

		foreach( $this->all_chars as $char ){

			if( $char->char_id == $char_id ){

				foreach( $char->fields as $key => $field ){
					
					if( $field->creation_date == $field_id ){

						unset( $char->fields[$key] );

					}

				}

				$char->fields = array_values( $char->fields );

			}

		}

		$this->save_chars( $db );

	}

	public function claim_weapon_from_shop( $weapon_id ){

		foreach( $this->shop->weapons as $key => $weapon ){

			if( $key == $weapon_id ){

				$tmp = $weapon;

				unset( $this->shop->weapons[$key] );

			}

		}

		if( isset( $tmp ) ){

			$this->shop->weapons = array_values( $this->shop->weapons );
			$this->char->equip->weapons[] = $tmp;

		}

	}

	public function claim_equipment_from_shop( $equipment_id ){

		foreach( $this->shop->equipment as $key => $equipment ){

			if( $key == $equipment_id ){

				$tmp = $equipment;

				unset( $this->shop->equipment[$key] );

			}

		}

		if( isset( $tmp ) ){

			$this->shop->equipment = array_values( $this->shop->equipment );
			$this->char->equip->equipment[] = $tmp;

		}

	}

	public function set_pools( $data ){

		$array = array( 'move', 'poison', 'sickness', 'lp_shield' );

		foreach( $array as $pool_name ){

			$this->char->pools->{$pool_name}->cur = parse_string( $this->char->pools->{$pool_name}->cur, $data[$pool_name] );

		}

	}

	public function add_pool_token( $token_type ){

		if( empty( $this->game->tokenpool ) ){

			$this->game->tokenpool = array();

		}

		if( empty( $this->game->tokenpool_users ) ){

			$this->game->tokenpool_users = array();

		}

		if( in_array( $this->char->char_id, $this->game->tokenpool_users ) ){

			echo 'Token already spent in this round!';
			return false;

		}

		$this->game->tokenpool[] = $token_type;
		$this->game->tokenpool_users[] = $this->char->char_id;
		echo 'Token added.';
		return true;

	}

	public function clear_tokenpool(){

		$this->game->tokenpool = array();
		$this->writelog( 'Token pool flushed.', 0 );
	}

	public function field_add_cost( $field_id, $data ){

		foreach( $this->fields as $field ){

			if( $field->creation_date == $field_id ){

				if( empty( $field->cost ) ){

					$field->cost = array();

				}

				$cost_select = array();
				$cost_select_value = array();

				foreach( $data as $point ){

					if( $point['name'] == 'cost_select[]' ){

						$cost_select[] = $point['value'];

					}else if( $point['name'] = 'cost_select_value[]' ){

						$cost_select_value[] = $point['value'];
					
					}

				}

				foreach( $cost_select as $key => $value ){

					$field->cost[] = (object) array( 'pool' => $value, 'value' => $cost_select_value[$key] );
					$this->writelog( 'Field '.$field->field_name.' costs '.$cost_select_value[$key].' per round '.$value, 0 );
					
				}

			}

		}

	}

	public function delete_field_cost( $field_id, $pool, $value ){
		
		foreach( $this->fields as $field ){

			if( $field->creation_date == $field_id ){

				for( $x = 0; $x < count($field->cost); $x++ ){

					if( $field->cost[$x]->pool == $pool && $field->cost[$x]->value == $value ){
						
						$this->writelog( 'Field '.$field->field_name.' cost '.$pool.' '.$value.' removed', 0 );
						unset( $field->cost[$x] );

					}

				}

			}

			$field->cost = (array) array_values( $field->cost );

		}

	}
	
	public function field_paycost( $field_id ){
		
		$this->loadlib( $this->db );

		$type = array();

		foreach( $this->lib->pools as $pool ){

			$type{$pool->name} = $pool->id;

		}
		
		foreach( $this->fields as $field ){

			if( $field->creation_date == $field_id ){
				
				$owner = $field->field_owner_id;
				$this->char( $owner );
				
				foreach( $field->cost as $cost ){
					
					$this->char->pools->{$type[$cost->pool]}->cur -= $cost->value;
					
				}
				
				$this->writelog( 'Field '.$field->field_name.': Upkeep-costen paid', 0 );
				$field->field_cost_paid = true;
				
			}
			
		}
		
	}

	public function reset(){

		foreach( $this->chars as $char ){

			$char->pools->ap->cur = 100;
			$char->pools->life->cur = 100;
			$char->pools->mana->cur = 100;
		}

		$this->writelog( 'Reset', 0 );

	}

	public function rename_char( $char_id, $new_char_name ){

		$this->char( $char_id );
		$this->writelog( $this->char->name.'is renamed to '.$new_char_name.' ', 0 );
		$this->char->name = $new_char_name;

	}

}


switch( $page ){
	case 'set_types':
	
		if( is_JSON( $_POST['data'] ) ){
			
			try {
		
				$stmt = $db->prepare("UPDATE `lib_data` SET data=:data WHERE `data_id`=1");
				$stmt->bindParam(':data', $_POST['data'], PDO::PARAM_STR);
				$stmt->execute();
		
			}catch(PDOException $ex) {
					echo "An Error occured! "; //user friendly message
					echo $ex->getMessage();
			}
			
		}	
	
	break;
	case 'set_weapons':
	
		if( is_JSON( $_POST['data'] ) ){
			
			try {
		
				$stmt = $db->prepare("UPDATE `lib_weapons` SET data=:data WHERE `weapon_id`=1");
				$stmt->bindParam(':data', $_POST['data'], PDO::PARAM_STR);
				$stmt->execute();
		
			}catch(PDOException $ex) {
					echo "An Error occured! "; //user friendly message
					echo $ex->getMessage();
			}
			
		}	
	
	break;
	case 'set_equipment':
	
		if( is_JSON( $_POST['data'] ) ){
			
			try {
		
				$stmt = $db->prepare("UPDATE `lib_equipment` SET data=:data WHERE `equipment_id`=1");
				$stmt->bindParam(':data', $_POST['data'], PDO::PARAM_STR);
				$stmt->execute();
		
			}catch(PDOException $ex) {
					echo "An Error occured! "; //user friendly message
					echo $ex->getMessage();
			}
			
		}	
	
	break;
	case 'set_sorceries':
	
		if( is_JSON( $_POST['data'] ) ){
			
			try {
		
				$stmt = $db->prepare("UPDATE `lib_actions` SET data=:data WHERE `sorcery_id`=1");
				$stmt->bindParam(':data', $_POST['data'], PDO::PARAM_STR);
				$stmt->execute();
		
			}catch(PDOException $ex) {
					echo "An Error occured! "; //user friendly message
					echo $ex->getMessage();
			}
			
		}	
	
	break;
	case 'set_char':
	
		if( is_JSON( $_POST['data'] ) ){
			
			try {
		
				$stmt = $db->prepare("UPDATE `chars` SET data=:data WHERE `char_id`=1");
				$stmt->bindParam(':data', $_POST['data'], PDO::PARAM_STR);
				$stmt->execute();
		
			}catch(PDOException $ex) {
					echo "An Error occured! "; //user friendly message
					echo $ex->getMessage();
			}
			
		}	
	
	break;
	case 'add_weapon_from_lib_to_shop':

		$shop_id = (int) $_SESSION['camp_id'];
		$weapon_id = (int) $_GET['weapon_id'];
		$tier_lvl = (string) $_GET['tier_lvl'];

		try{

			$stmt = $db->prepare( 'SELECT * FROM lib_weapons WHERE `weapon_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$weapons = json_decode( $row['data'] );
		}

		$x = 0;

		foreach( $weapons AS $weapon2 ){

			if( $x != $weapon_id ){
				
				$x++;
				continue;

			}

			$weapon2->tier_lvl = $tier_lvl;			

			$weapon = $weapon2;

			$x++;
		}

		$weapon->weapon_id = time();


		try{

			$stmt = $db->prepare( 'SELECT * FROM shops WHERE `data_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$shops = json_decode( $row['data'] );
		}

		$shop_exists = false;
		foreach( $shops AS &$shop ){

			if( $shop->shop_id == $shop_id ){

				$shop_exists = true;

				if( !$shop->weapons ){

					$shop->weapons = array();
					
				}

				$shop->weapons[] = $weapon;
				array_values( $shop->weapons );

			}
			
		}

		if( $shop_exists == false ){

			$shops[] = (object) [ 'shop_id' => $shop_id, 'weapons' => [], 'equipment' => [] ];

			foreach( $shops AS &$shop ){

				if( $shop->shop_id == $shop_id ){

					$shop->weapons[] = $weapon;

				}
			
			}

		}

		$data = json_encode( $shops, JSON_PRETTY_PRINT );

		try{

			$stmt = $db->prepare( 'UPDATE shops SET `data`=:data WHERE `data_id`=1' );
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}
	break;
	case 'add_equipment_from_lib_to_shop':
		
		$shop_id = (int) $_SESSION['camp_id'];
		$equipment_id = (int) $_GET['equipment_id'];

		try{

			$stmt = $db->prepare( 'SELECT * FROM lib_equipment WHERE `equipment_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$equipments = json_decode( $row['data'] );
		}

		$x = 0;

		

		foreach( $equipments AS $equipment2 ){

			if( $x != $equipment_id ){
				$x++;
				continue;

			}
			
			for( $y = 0, $z=count( $equipment2->tier_lvl ); $y<$z; $y++ ){
				
				if( $equipment2->tier_lvl[$y]->tier_lvl_name != $_GET['tier_lvl'] ){

					unset( $equipment2->tier_lvl[$y] );
					
				}
				
			}

			$equipment2->tier_lvl = array_values( (array) $equipment2->tier_lvl );
			$equipment = $equipment2;

			$x++;
		}

		$equipment->equipment_id = time();

		
		

		try{

			$stmt = $db->prepare( 'SELECT * FROM shops WHERE `data_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$shops = json_decode( $row['data'] );
		}

		$shop_exists = false;
		foreach( $shops AS &$shop ){

			if( $shop->shop_id == $shop_id ){
				$shop_exists = true;
			
				if( !$shop->equipment ){

					$shop->equipment = array();
					
				}

				$shop->equipment[] = $equipment;
				$shop->equipment = array_values( $shop->equipment );

			}
			
		}

		

		if( $shop_exists == false ){

			$shops[] = (object) [ 'shop_id' => $shop_id, 'weapons' => [], 'equipment' => [] ];
			
			foreach( $shops AS &$shop ){

				if( $shop->shop_id == $shop_id ){

					$shop->equipment[] = $equipment;

				}
			
			}

		}

		$data = json_encode( $shops, JSON_PRETTY_PRINT );

		try{

			$stmt = $db->prepare( 'UPDATE shops SET `data`=:data WHERE `data_id`=1' );
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}
		echo "Done equipment";
		
	break;
	case 'del_weapon_from_shop':
		$shop_id = (int) $_SESSION['camp_id'];
		$weapon_id = (int) $_GET['weapon_id'];

		try{

			$stmt = $db->prepare( 'SELECT * FROM shops WHERE `data_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$shops = json_decode( $row['data'] );
		}

		foreach( $shops AS &$shop ){

			if( $shop->shop_id == $shop_id ){

				
				for( $x=0, $y=count($shop->weapons); $x<$y; $x++ ){

					if( $x == $weapon_id ){

						unset( $shop->weapons[$x] );

					}

				}

			}
			
		}
		$shop->weapons = array_values( (array) $shop->weapons );
		$data = json_encode( $shops, JSON_PRETTY_PRINT );

		try{

			$stmt = $db->prepare( 'UPDATE shops SET `data`=:data WHERE `data_id`=1' );
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}
	break;
	case 'del_equipment_from_shop':
		$shop_id = (int) $_SESSION['camp_id'];
		$equipment_id = (int) $_GET['equipment_id'];

		try{

			$stmt = $db->prepare( 'SELECT * FROM shops WHERE `data_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$shops = json_decode( $row['data'] );
		}

		foreach( $shops AS &$shop ){

			if( $shop->shop_id == $shop_id ){

				
				for( $x=0, $y=count($shop->equipment); $x<$y; $x++ ){

					if( $x == $equipment_id ){

						unset( $shop->equipment[$x] );

					}

				}

			}
			
		}
		$shop->equipment = array_values( (array) $shop->equipment );
		$data = json_encode( $shops, JSON_PRETTY_PRINT );

		try{

			$stmt = $db->prepare( 'UPDATE shops SET `data`=:data WHERE `data_id`=1' );
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}
	break;
	case 'move_weapon_from_shop_to_char':

		$shop_id = (int) $_SESSION['camp_id'];
		$weapon_id = (int) $_GET['weapon_id'];
		$char_id = (int) $_GET['char_id'];

		try{

			$stmt = $db->prepare( 'SELECT * FROM shops WHERE `data_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$shops = json_decode( $row['data'] );
		}

		foreach( $shops AS &$shop ){

			if( $shop->shop_id == $shop_id ){

				
				for( $x=0; $x<count($shop->weapons);$x++ ){

					if( $shop->weapons[$x]->weapon_id == $weapon_id ){

						$weapon = $shop->weapons[$x];
						unset( $shop->weapons[$x] );

					}

				}

				$shop->weapons = array_values( (array) $shop->weapons );

			}
			
		}

		//Weapon loaded and removed from shop

		try{

			$stmt = $db->prepare( 'SELECT * FROM chars WHERE `char_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$chars = json_decode( $row['data'] );
		}

		foreach( $chars AS &$char ){

			if( $char->char_id == $char_id ){

				$char->weapons[] = $weapon;

			}

		}

		//Weapon attached to player

		$data = json_encode( $chars, JSON_PRETTY_PRINT );

		try{

			$stmt = $db->prepare( 'UPDATE chars SET `data`=:data WHERE `char_id`=1' );
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

			$success = true;

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		//Player saved

		//Save shop

		if( $success ){

			$data = json_encode( $shops, JSON_PRETTY_PRINT );

			try{

				$stmt = $db->prepare( 'UPDATE shops SET `data`=:data WHERE `data_id`=1' );
				$stmt->bindParam(':data', $data, PDO::PARAM_STR);
				$stmt->execute();

			}catch(PDOException $ex) {
				echo "An Error occured! "; //user friendly message
				echo $ex->getMessage();
			}

		}
	break;
	case 'move_equipment_from_shop_to_char':

		$shop_id = (int) $_SESSION['camp_id'];
		$equipment_id = (int) $_GET['equipment_id'];
		$char_id = (int) $_GET['char_id'];

		try{

			$stmt = $db->prepare( 'SELECT * FROM shops WHERE `data_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$shops = json_decode( $row['data'] );
		}

		foreach( $shops AS &$shop ){

			if( $shop->shop_id == $shop_id ){

				//var_dump( $shop->equipment );

				
				for( $x=0; $x<count($shop->equipment);$x++ ){

					if( $shop->equipment[$x]->equipment_id == $equipment_id ){

						$equipment = $shop->equipment[$x];
						unset( $shop->equipment[$x] );

					}

				}

				$shop->equipment = array_values( (array) $shop->equipment );

			}
			
		}

		//Weapon loaded and removed from shop

		try{

			$stmt = $db->prepare( 'SELECT * FROM chars WHERE `char_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$chars = json_decode( $row['data'] );
		}

		foreach( $chars AS &$char ){

			if( $char->char_id == $char_id ){

				$char->equipment[] = $equipment;

			}

		}

		//Weapon attached to player

		$data = json_encode( $chars, JSON_PRETTY_PRINT );

		try{

			$stmt = $db->prepare( 'UPDATE chars SET `data`=:data WHERE `char_id`=1' );
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

			$success = true;

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		//Player saved

		//Save shop

		if( $success ){

			$data = json_encode( $shops, JSON_PRETTY_PRINT );

			try{

				$stmt = $db->prepare( 'UPDATE shops SET `data`=:data WHERE `data_id`=1' );
				$stmt->bindParam(':data', $data, PDO::PARAM_STR);
				$stmt->execute();

			}catch(PDOException $ex) {
				echo "An Error occured! "; //user friendly message
				echo $ex->getMessage();
			}

		}
	break;
	case 'unequip_weapon_from_char':

		$weapon_id = (int) $_GET['weapon_id'];
		$char_id = (int) $_GET['char_id'];

		//load Char and weapon

		try{

			$stmt = $db->prepare( 'SELECT * FROM chars WHERE `char_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$chars = json_decode( $row['data'] );
		}

		foreach( $chars AS &$char ){

			if( $char->char_id == $char_id ){

				for( $x=0; $x<count($char->weapons);$x++ ){

					if( $char->weapons[$x]->weapon_id != $weapon_id ){

						continue;

					}

					$weapon = $char->weapons[$x]; //Save weapon to tmp

					unset( $char->weapons[$x] ); //Delete weapon in main array

				}

				$char->weapons = array_values( (array) $char->weapons );

				if( !$char->equip ){

					$char->equip = (object) [ 'weapons' => [], 'equipment' => [] ];

				}

				$char->equip->weapons[] = $weapon; 

			}

		}

		//Save chars

		$data = json_encode( $chars, JSON_PRETTY_PRINT );

		try{

			$stmt = $db->prepare( 'UPDATE chars SET `data`=:data WHERE `char_id`=1' );
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

			$success = true;

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}


	break;
	case 'unequip_equipment_from_char':

		$equipment_id = (int) $_GET['equipment_id'];
		$char_id = (int) $_GET['char_id'];

		//load Char and weapon

		try{

			$stmt = $db->prepare( 'SELECT * FROM chars WHERE `char_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$chars = json_decode( $row['data'] );
		}

		foreach( $chars AS &$char ){

			if( $char->char_id == $char_id ){

				for( $x=0; $x<count($char->equipment);$x++ ){

					if( $char->equipment[$x]->equipment_id != $equipment_id ){

						continue;

					}

					$equipment = $char->equipment[$x]; //Save equipment to tmp

					unset( $char->equipment[$x] ); //Delete weapon in main array

				}

				$char->equipment = array_values( (array) $char->equipment );

				if( !$char->equip ){

					$char->equip = (object) [ 'weapons' => [], 'equipment' => [] ];

				}

				$char->equip->equipment[] = $equipment; 

			}

		}

		//Save chars

		$data = json_encode( $chars, JSON_PRETTY_PRINT );

		try{

			$stmt = $db->prepare( 'UPDATE chars SET `data`=:data WHERE `char_id`=1' );
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

			$success = true;

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}


	break;
	case 'remove_weapon_from_equip':
	
		$weapon_id = (int) $_GET['weapon_id'];
		$char_id = (int) $_GET['char_id'];
		
		//load Char and weapon

		try{

			$stmt = $db->prepare( 'SELECT * FROM chars WHERE `char_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$chars = json_decode( $row['data'] );
		}

		foreach( $chars AS &$char ){

			if( $char->char_id == $char_id ){

				for( $x=0; $x<count($char->equip->weapons);$x++ ){

					if( $char->equip->weapons[$x]->weapon_id == $weapon_id ){

						unset( $char->equip->weapons[$x] ); //Delete weapon in main array

					}

				}

				$char->equip->weapons = array_values( (array) $char->equip->weapons );

			}

		}

		//Save chars

		$data = json_encode( $chars, JSON_PRETTY_PRINT );

		try{

			$stmt = $db->prepare( 'UPDATE chars SET `data`=:data WHERE `char_id`=1' );
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

			$success = true;

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}
	
	break;
	case 'remove_equipment_from_equip':
	
		$equipment_id = (int) $_GET['equipment_id'];
		$char_id = (int) $_GET['char_id'];
		
		//load Char and weapon

		try{

			$stmt = $db->prepare( 'SELECT * FROM chars WHERE `char_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$chars = json_decode( $row['data'] );
		}

		foreach( $chars AS &$char ){

			if( $char->char_id == $char_id ){

				for( $x=0; $x<count($char->equip->equipment);$x++ ){

					if( $char->equip->equipment[$x]->equipment_id == $equipment_id ){

						unset( $char->equip->equipment[$x] ); //Delete weapon in main array

					}

				}

				$char->equip->equipment = array_values( (array) $char->equip->equipment );

			}

		}

		//Save chars

		$data = json_encode( $chars, JSON_PRETTY_PRINT );

		try{

			$stmt = $db->prepare( 'UPDATE chars SET `data`=:data WHERE `char_id`=1' );
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

			$success = true;

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}
	
	break;
	case 'reequip_weapon_to_char':

		$weapon_id = (int) $_GET['weapon_id'];
		$char_id = (int) $_GET['char_id'];

		//load Char and weapon

		try{

			$stmt = $db->prepare( 'SELECT * FROM chars WHERE `char_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$chars = json_decode( $row['data'] );
		}

		foreach( $chars AS &$char ){

			if( $char->char_id == $char_id ){

				for( $x=0; $x<count($char->equip->weapons);$x++ ){

					if( $char->equip->weapons[$x]->weapon_id == $weapon_id ){

						$weapon = $char->equip->weapons[$x]; //Save weapon to tmp
						unset( $char->equip->weapons[$x] ); //Delete weapon in main array
						
					}

				}

				$char->equip->weapons = array_values( (array) $char->equip->weapons );

				if( !$char->weapons ){

					$char->weapons = array();

				}

				$char->weapons[] = $weapon; 

			}

		}

		//Save chars

		$data = json_encode( $chars, JSON_PRETTY_PRINT );

		try{

			$stmt = $db->prepare( 'UPDATE chars SET `data`=:data WHERE `char_id`=1' );
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

			$success = true;

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}


	break;
	case 'reequip_equipment_to_char':

		$equipment_id = (int) $_GET['equipment_id'];
		$char_id = (int) $_GET['char_id'];

		//load Char and weapon

		try{

			$stmt = $db->prepare( 'SELECT * FROM chars WHERE `char_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$chars = json_decode( $row['data'] );
		}

		foreach( $chars AS &$char ){

			if( $char->char_id == $char_id ){

				for( $x=0; $x<count($char->equip->equipment);$x++ ){

					if( $char->equip->equipment[$x]->equipment_id == $equipment_id ){

						$weapon = $char->equip->equipment[$x]; //Save equipment to tmp
						unset( $char->equip->equipment[$x] ); //Delete equipment in main array
						
					}

				}

				$char->equip->equipment = array_values( (array) $char->equip->equipment );

				if( !$char->equipment ){

					$char->equipment = array();

				}

				$char->equipment[] = $weapon; 

			}

		}

		//Save chars

		$data = json_encode( $chars, JSON_PRETTY_PRINT );

		try{

			$stmt = $db->prepare( 'UPDATE chars SET `data`=:data WHERE `char_id`=1' );
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

			$success = true;

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}


	break;
	case 'add_char_to_game':

		$char_id = (int) $_GET['char_id'];
		$leftright = (string) $_GET['leftright'];

		$game = new game( $db, $l, $lang );
		$game->add_char_to_game( $char_id, $leftright );
		$game->save( $db );

	break;
	case "copy_char":

		$char_id = (int) $_GET['char_id'];
		$new_name = (string) $_POST['new_name'];

		//load Char and weapon

		try{

			$stmt = $db->prepare( 'SELECT * FROM chars WHERE `char_id`=1' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

		foreach($stmt as $row) {
	    		$chars = json_decode( $row['data'] );
		}

		foreach( $chars AS $char ){

			if( $char->char_id == $char_id ){

				$new_char = clone $char;

			}

		}

		$new_char->name = $new_name;
		$new_char->creator = $_SESSION['username'];
		$new_char->controller = $_SESSION['username'];
		$new_char->char_id = time();
		$new_char->creation_date = date("Y-m-d H:i:s");

		$chars[] = $new_char;

		//Save chars

		$data = json_encode( $chars, JSON_PRETTY_PRINT );

		try{

			$stmt = $db->prepare( 'UPDATE chars SET `data`=:data WHERE `char_id`=1' );
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			$stmt->execute();

			$success = true;

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}

	break;
	case "delete_char_in_game":

		$char_id = (int) $_GET['char_id'];

		$game = new game( $db, $l, $lang );
		$game->delete_char( $char_id );
		$game->save( $db );

		echo 'Char '.$char_id.' deleted';

	break;
	case "setlife":
		$char_id = (int) $_GET['char_id'];
		$str = (string) $_GET['str'];

		$game = new game( $db, $l, $lang );
		$game->char( $char_id );
		$current_life = $game->getlife();
		$new_life = parse_string( $current_life, $str );
		$game->setlife( $new_life );
		$game->save($db);
		echo $new_life;
	break;
	case "setmana":
		$char_id = (int) $_GET['char_id'];
		$str = (string) $_GET['str'];

		$game = new game( $db, $l, $lang );
		$game->char( $char_id );
		$current_mana = $game->getmana();
		$new_mana = parse_string( $current_mana, $str );
		$game->setmana( $new_mana );
		$game->save($db);
		echo $new_mana;
	break;
	case "setap":
		$char_id = (int) $_GET['char_id'];
		$str = (string) $_GET['str'];

		$game = new game( $db, $l, $lang );
		$game->char( $char_id );
		$current_ap = $game->getap();
		$new_ap = parse_string( $current_ap, $str );
		$game->setap( $new_ap );
		$game->save($db);
		echo $new_ap;
	break;
	case "setstate":
		$char_id = (int) $_GET['char_id'];
		$state_id = (string) $_GET['state'];
		$rounds = (int) $_GET['rounds'];
		$game = new game( $db, $l, $lang );
		$game->char( $char_id );
		$game->setstate( '', $state_id, $rounds, $_SESSION['user_id'],$_SESSION['username'] );
		$game->save($db);
	break;
	case "delstate":
		$char_id = (int) $_GET['char_id'];
		$state_id = (int) $_GET['state'];

		$game = new game( $db, $l, $lang );
		$game->char( $char_id );
		$game->delstate( $state_id );
		$game->calculate_armor();
		$game->save($db);
	break;
	case "diceroll":
		$char_id = (int) $_GET['char_id'];
		$dice_roll_formula = $_GET['dice_roll_formula'];

		$writelog = isset( $_GET['writelog'] ) ? true : false;	
		
		$game = new game( $db, $l, $lang );
		$options = (object) array();
		
		if( isset( $_GET['target_char_id'] ) ){
			
			$options->target_char_id = $_GET['target_char_id'];

		}

		if( isset( $_GET['data'] ) ){

			$options->w_tier_lvl = isset( $_GET['data']['w_tier_lvl'] ) ? (int) substr( $_GET['data']['w_tier_lvl'], -1 ) : 0;
			$options->e_tier_lvl = isset( $_GET['data']['e_tier_lvl'] ) ? (int) substr( $_GET['data']['e_tier_lvl'], -1 ) : 0;
			$options->attribute = isset( $_GET['data']['attribute'] ) ? (int) $_GET['data']['attribute'] : 0;
			$options->w_skill = isset( $_GET['data']['w_skill'] ) ? (string) $_GET['data']['w_skill'] : '';
			$options->e_skill = isset( $_GET['data']['e_skill'] ) ? (string) $_GET['data']['e_skill'] : '';
			$options->m_skill = isset( $_GET['data']['m_skill'] ) ? (string) $_GET['data']['m_skill'] : '';
		
			foreach( $_GET['data'] as $key => $val ){
			
				if( !in_array( $key, array( 'w_tier_lvl', 'e_tier_lvl', 'w_skill', 'e_skill', 'm_skill' ) ) ){
				
					$options->{$key} = $val;
				
				}
			
			}

		}
		
		$game->char( $char_id );
		
		
		$diceroll = $game->diceroll( $dice_roll_formula, $options );
		
		if( $writelog === true ){

			$game->writelog( $diceroll->formula3.' rolled', 0 );
			$game->writelog( $diceroll, 0 );

		}

		echo json_encode( $diceroll );
		
	break;
	case 'addtmpvar':
		$char_id = (int) $_GET['char_id'];
		parse_str( $_GET['form'], $form );

		$char_id = (int) $_GET['char_id'];
		$state = $form['statename'];
		$rounds = $form['rounds'];

		$game = new game( $db, $l, $lang );
		$game->char( $char_id );

		$state_vars = array();

		for( $x=0; $x<count($form['variable']);$x++){

			$state_vars[] = (object) array( "path" => $form['variable'][$x], "modifier" => $form['modificator'][$x]  );

		}

		$state_id = $game->setstate( $state, 0, $rounds, $_SESSION['user_id'],$_SESSION['username'], $state_vars );

		/*
		for( $x=0; $x<count($form['variable']);$x++){
			$game->settmpvar( $state, $origin_id, $form['variable'][$x], $form['modificator'][$x] );
		}
		*/

		

		$game->calculate_armor();
		
		$game->save($db);

	break;
	case 'savestate':
		$char_id = (int) $_GET['char_id'];
		$state_id = (int) $_GET['state'];

		$game = new game( $db, $l, $lang );
		$game->char( $char_id );

		$state = $game->get_state_from_char( $state_id );
		$game->save_state( $state );
		$game->save_lib( $db );

		echo "Saved!";
	break;
	case 'tank_player':
		$tank_char_id = (int) $_GET['tank_char_id'];
		$tanked_char_id = (int) $_GET['tanked_char_id'];
		
		$game = new game( $db, $l, $lang );
		$game->char( $tanked_char_id );
		//setstate( $state, $state_id, $rounds, $user_id, $username ){
		$state_id = $game->setstate( 'Tanked', time(), 99, $_SESSION['user_id'], $_SESSION['username'], array(), array( "tank_char_id" => $tank_char_id, "tanked_char_id" => $tanked_char_id) );
		
		$game->save( $db );
		echo $tank_char_id." tankt ".$tanked_char_id;
	break;
	case  'mkdamage':

		$char_id = (int) $_GET['char_id'];
		$target_char_id = (int) $_GET['target_char_id'];
		$action_name = (string) urldecode( $_GET['action_name'] );
		$tier_lvl_index = (int) $_GET['tier_lvl_index'];
		$add_tokens = isset( $_GET['add_tokens'] ) ? (array) $_GET['add_tokens'] : array();
		$cost_paid = isset( $_GET['cost_paid'] ) ? $_GET['cost_paid'] : false;

		$options = (object) array();
		$options->target_char_id = $target_char_id;

		if( isset( $_GET['data'] ) ){

			$options->w_tier_lvl = isset( $_GET['data']['w_tier_lvl'] ) ? (int) substr( $_GET['data']['w_tier_lvl'], -1 ) : 0;
			$options->e_tier_lvl = isset( $_GET['data']['e_tier_lvl'] ) ? (int) substr( $_GET['data']['e_tier_lvl'], -1 ) : 0;
			$options->attribute = isset( $_GET['data']['attribute'] ) ? (int) $_GET['data']['attribute'] : 0;
			$options->w_skill = isset( $_GET['data']['w_skill'] ) ? (string) $_GET['data']['w_skill'] : '';
			$options->e_skill = isset( $_GET['data']['e_skill'] ) ? (string) $_GET['data']['e_skill'] : '';
			$options->m_skill = isset( $_GET['data']['m_skill'] ) ? (string) $_GET['data']['m_skill'] : '';

		}

		$game = new game( $db, $l, $lang );
		$game->char( $char_id );
		
		$game->loadactions( $db );
		$game->select_action_by_name( $action_name );

		$game->trigger_event( 'on_mk_damage', $char_id, $target_char_id, $game->action->action_type );

		if( $cost_paid !== 'true' ){

			$game->pay_action_cost( $game->action->tier_lvl[$tier_lvl_index]->cost, $game->action->tier_lvl[$tier_lvl_index]->token_cost, $add_tokens );

		}
		
	
		if( isset( $_GET['payonly'] ) ){
			
			$game->save($db);
			break;
			
		}

		$game->action_target_id = $target_char_id;
		
		$calc_dmg = $game->calculate_damage( $game->action, $game->action->tier_lvl[$tier_lvl_index]->damage, $tier_lvl_index, $add_tokens, $options );

		echo json_encode( $calc_dmg );
		$game->char( $target_char_id ); //Switch user to target

		$calc_dmg = $game->recieve_damage( $calc_dmg, $options );

		$game->trigger_event( 'on_made_damage', $char_id, $target_char_id, $game->action->action_type );
		
		$game->char( $char_id );
		$game->add_tokens();
		
		if( isset( $game->action->tier_lvl[$tier_lvl_index]->effect_add_damage) ){
		
			$game->execute_effects_damage( $char_id, $target_char_id, $game->action, $game->action->tier_lvl[$tier_lvl_index]->effect_add_damage );
		
		}
		
		if( isset( $game->action->tier_lvl[$tier_lvl_index]->effect_add_special_token) ){
		
			$game->execute_effects_add_special_token( $char_id, $target_char_id, $game->action, $game->action->tier_lvl[$tier_lvl_index]->effect_add_special_token );
		
		}
		
		if( isset( $game->action->tier_lvl[$tier_lvl_index]->effect_add_state) ){
		
			$game->execute_effects_add_state( $char_id, $target_char_id, $game->action, $game->action->tier_lvl[$tier_lvl_index]->effect_add_state, $options );
			
		}
		
		if( isset( $game->action->tier_lvl[$tier_lvl_index]->effect_summon_char) ){
		
			$game->execute_effects_summon_char( $char_id, $target_char_id, $game->action, $game->action->tier_lvl[$tier_lvl_index]->effect_summon_char );
			
		}
		
		if( isset( $game->action->tier_lvl[$tier_lvl_index]->effect_add_field) ){
		
			$game->execute_effects_add_field( $char_id, $target_char_id, $game->action, $game->action->tier_lvl[$tier_lvl_index]->effect_add_field );
			
		}
		
		//var_dump( $calc_dmg );
		$game->save($db);
	break;
	case 'new_round':
	
		$game = new game( $db, $l, $lang );
		$game->addcurrentround( 0.5 );
		$cur = $game->getcurrentround();

		if( floor( $cur ) == $cur ){

			//Players turn / left
			$game->writelog( '<div class="alert alert-warning" role="alert">New round ('.$cur.'). The players move.</div>', 0 );
			$game->new_round( 'left' );
			
		}else{

			//DMs turn / right
			$game->writelog( '<div class="alert alert-warning" role="alert">New round ('.$cur.'). The dungeonmasters move.</div>', 0 );
			$game->new_round( 'right' );

		}

		$game->trigger_event( 'on_round_start' );
		$game->save($db);

	break;
	case 'endgame':
	
		$game = new game( $db, $l, $lang );
		$game->deactivate_game( $db );
		$game->writelog( '<div class="alert alert-warning" role="alert">End of the game.</div>', 0 );
		unset( $_SESSION['current_game_id'] );
		
	break;
	case 'removetoken':

		$token_type = (string) $_GET['token_type'];
		$char_id = (int) $_GET['char_id'];
		$game = new game( $db, $l, $lang );
		$game->char( $char_id );
		$game->remove_token( $token_type );
		$game->save( $db );
		
	break;
	case 'removealltoken':

		$token_type = (string) $_GET['token_type'];
		$char_id = (int) $_GET['char_id'];
		$game = new game( $db, $l, $lang );
		$game->char( $char_id );
		$game->remove_all_token( $token_type );
		$game->save( $db );
		
	break;
	case 'remove_special_token':

		$token_type = (string) $_GET['token_type'];
		$char_id = (int) $_GET['char_id'];
		$game = new game( $db, $l, $lang );
		$game->char( $char_id );
		$game->remove_special_token( $token_type );
		$game->save( $db );
		
	break;
	case 'remove_all_special_token':

		$token_type = (string) $_GET['token_type'];
		$char_id = (int) $_GET['char_id'];
		$game = new game( $db, $l, $lang );
		$game->char( $char_id );
		$game->remove_all_special_token( $token_type );
		$game->save( $db );
		
	break;
	case 'newgame':
		
		try{

			$stmt = $db->prepare( 'INSERT INTO game ( data, active ) VALUES ( \'{}\', 1 )' );
			$stmt->execute();

		}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
		}
		
		$game = new game( $db, $l, $lang );
		
		$game_str->game_id = $db->lastInsertId();
		$_SESSION['current_game_id'] = $game->game_id;
		$game_str->start_time = time();
		$game_str->current_round = 0;
		$game_str->dungeonmaster_user_id = $_SESSION['user_id'];
    		$game_str->dungeonmaster_username = $_SESSION['username'];
    		$game_str->chars = array();
    		$game_str->fields = array();
	
    		$game->writelog( 'New game created. Round 0. The players move.', 0 );
    	
    		$game->game = $game_str;
    	
    		$game->save( $db );
    	
	break;
	case 'newfield':
		
		$game = new game( $db, $l, $lang );
		$game->new_field();
		$game->save( $db );

	break;
	case 'add_field_owner':

		$field_id = (int) $_GET['field_id'];
		$field_owner_id = (int) $_GET['field_owner_id'];

		$game = new game( $db, $l, $lang );
		$game->char( $field_owner_id );
		$game->add_field_owner( $field_id, $field_owner_id );
		$game->save( $db );

	break;
	case 'add_field_targets':

		$field_id = (int) $_GET['field_id'];
		$field_target_ids = (array) $_GET['field_target_ids'];

		$game = new game( $db, $l, $lang );
		$game->add_field_targets( $field_id, $field_target_ids );
		$game->save( $db );

	break;
	case 'remove_field_target':

		$field_id = (int) $_GET['field_id'];
		$field_target_id = (int) $_GET['field_target_id'];

		$game = new game( $db, $l, $lang );
		$game->remove_field_target( $field_id, $field_target_id );
		$game->save( $db );

	break;
	case 'remove_field_owner':

		$field_id = (int) $_GET['field_id'];

		$game = new game( $db, $l, $lang );
		$game->remove_field_owner( $field_id );
		$game->save( $db );

	break;
	case 'field_add_state':

		$field_id = (int) $_GET['field_id'];
		$state_id = (int) $_GET['state_id'];

		$game = new game( $db, $l, $lang );

		$game->add_state_to_field( $field_id, $state_id );
		$game->writelog( 'New field state', 0 );
		$game->save( $db );

	break;
	case 'field_del_state':

		$field_id = (int) $_GET['field_id'];
		$state_id = (int) $_GET['state_id'];

		$game = new game( $db, $l, $lang );

		$game->del_state_from_field( $field_id, $state_id );
		$game->writelog( 'Field state removed', 0 );
		$game->save( $db );

	break;
	case 'field_add_new_event':
		
		$field_id = (int) $_GET['field_id'];
		$event_id = (int) $_GET['event_id'];
		$selected_rounds = $_GET['selected_rounds'];
		$event_type = $_GET['event_type'];
		$data = $_GET['data'];		
		$timeline_data = $_GET['timelinedata'];
		$data2 = array();
		$trigger_action = array();

		foreach( $data as $point ){

			$data2[$point['name']] = $point['value'];
			
			if( strpos( $point['name'], 'trigger_action' ) !== false ){

				$trigger_action[] = $point['value'];

			}

		}

		$data2['trigger_action'] = $trigger_action;

		$game = new game( $db, $l, $lang );

		if( $event_id != 0 ){

			$game->edit_event( $field_id, $event_id, $selected_rounds, $event_type, $data2, $timeline_data );

		}else{

			$game->new_event( $field_id, $selected_rounds, $event_type, $data2, $timeline_data );

		}
		

		$game->save( $db );
	break;
	case 'remove_field_event':

		$field_id = (int) $_GET['field_id'];
		$event_id = (int) $_GET['event_id'];

		$game = new game( $db, $l, $lang );
		$game->remove_field_event( $field_id, $event_id );
		$game->save( $db );

	break;
	case 'delete_field':

		$field_id = (int) $_GET['field_id'];
		$game = new game( $db, $l, $lang );
		$game->delete_field( $field_id );
		$game->save( $db );


	break;
	case 'trigger_attack':

		$char_id = (int) $_GET['char_id'];
		$target_char_id = (int) $_GET['target_char_id'];
		$action_type = (int) $_GET['action_type'];
		$game = new game( $db, $l, $lang );
		$game->char( $char_id );

		$game->trigger_event( 'on_attack', $char_id, $target_char_id, $action_type );

		$game->save( $db );
	break;
	case 'unequip':

		$char_id = (int) $_GET['char_id'];
		$object_id = (int) $_GET['object_id'];
		$type = (string) $_GET['type'];

		$game = new game( $db, $l, $lang );
		$game->char( $char_id );

		$game->unequip_object( $object_id, $type );

		$game->save( $db );

	break;
	case 'equip':

		$char_id = (int) $_GET['char_id'];
		$object_id = (int) $_GET['object_id'];
		$type = (string) $_GET['type'];

		$game = new game( $db, $l, $lang );
		$game->char( $char_id );

		$game->equip_object( $object_id, $type );
		$game->save( $db );
		
	break;
	case 'save_field':

		$field_id = (int) $_GET['field_id'];
		$field_name = (string) $_GET['field_name'];
		$game = new game( $db, $l, $lang );
		$game->loadfields( $db );
		$game->save_field( $field_id, $field_name );
	break;
	case 'load_field':

		$field_id = (int) $_GET['field_id'];
		$game = new game( $db, $l, $lang );
		$game->loadfields( $db );
		$game->load_field( $field_id );
		$game->save( $db );
	break;
	case 'rename_field':

		$field_id = (int) $_GET['field_id'];
		$new_field_name = (string) $_GET['new_field_name'];

		$game = new game( $db, $l, $lang );
		$game->rename_field( $field_id, $new_field_name );
		$game->save( $db );
	break;
	case 'add_field_to_char':
		$field_id = (int) $_GET['field_id'];
		$field_name = (string) $_GET['field_name'];
		$char_id = (int) $_GET['char_id'];

		$game = new game( $db, $l, $lang );
		$game->loadchars( $db );
		$game->loadfields( $db );

		$game->add_field_to_char( $field_id, $field_name, $char_id );

	break;
	case 'delete_field_from_char':
		$field_id = (int) $_GET['field_id'];
		$char_id = (int) $_GET['char_id'];

		$game = new game( $db, $l, $lang );
		$game->loadchars( $db );
		$game->delete_field_from_char( $field_id, $char_id );
	break;
	case 'claim_weapon_from_shop':
		$weapon_id = (int) $_GET['weapon_id'];
		$char_id = (int) $_GET['char_id'];

		$game = new game( $db, $l, $lang );
		$game->loadshop();
		$game->char( $char_id );
		$game->claim_weapon_from_shop( $weapon_id, $char_id );
		$game->save_shop();
		$game->save( $db );
	break;
	case 'claim_equipment_from_shop':
		$equipment_id = (int) $_GET['equipment_id'];
		$char_id = (int) $_GET['char_id'];

		$game = new game( $db, $l, $lang );
		$game->loadshop();
		$game->char( $char_id );
		$game->claim_equipment_from_shop( $equipment_id, $char_id );
		$game->save_shop();
		$game->save( $db );
	break;
	case 'setpool':
		$char_id = (int) $_GET['char_id'];
		$data = (array) $_GET['data'];

		$game = new game( $db, $l, $lang );
		$game->char( $char_id );
		$game->set_pools( $data );
		$game->save($db);
	break;
	case 'token_to_pool':
		$token_type = (string) $_GET['token_type'];
		$char_id = (int) $_GET['char_id'];
		$game = new game( $db, $l, $lang );
		$game->char( $char_id );
		if( $game->add_pool_token( $token_type ) === true ){
			$game->remove_token( $token_type );
		}
		$game->save( $db );
		
	break;
	case 'special_token_to_pool':
		$token_type = (string) $_GET['token_type'];
		$char_id = (int) $_GET['char_id'];
		$game = new game( $db, $l, $lang );
		$game->char( $char_id );
		
		if( $game->add_pool_token( $token_type ) === true ){
			$game->remove_special_token( $token_type );
		}
		$game->save( $db );
	break;
	case 'clear_tokenpool':
		$game = new game( $db, $l, $lang );
		$game->clear_tokenpool();
		$game->save( $db );
	break;
	case 'field_add_cost':
		$field_id = (int) $_GET['field_id'];
		$data = (array) $_GET['data'];
		$game = new game( $db, $l, $lang );
		$game->field_add_cost( $field_id, $data );
		$game->save( $db );
	break;
	case 'field_paycost':
		$field_id = (int) $_GET['field_id'];
		$game = new game( $db, $l, $lang );
		$game->field_paycost( $field_id );
		$game->save( $db );
	break;
	case 'timeline':
		$game = new game( $db, $l, $lang );
		$timelinearray = array();
		
		for( $x = $_GET['current_round']; $x<$_GET['current_round']+20; $x=$x+0.5 ){
		
			$timelinearray[] = $game->timeline_activation_test( $_GET['current_round'], $_GET['start_shift'], $_GET['end_shift'], $_GET['x_rounds'], $_GET['dm'], $x );
			
		}
		
		echo json_encode( $timelinearray );
	break;
	case 'reset':
		$game = new game( $db, $l, $lang );
		$game->reset();
		$game->save( $db );
	break;
	case 'delete_field_cost':
		$field_id = (int) $_GET['field_id'];
		$pool = (string) $_GET['pool'];
		$value = (int) $_GET['value'];

		$game = new game( $db, $l, $lang );
		$game->delete_field_cost( $field_id, $pool, $value );
		$game->save( $db );
	break;
	case 'rename_char':
		$new_char_name = (string) $_GET['new_char_name'];
		$char_id = (int) $_GET['char_id'];
		$game = new game( $db, $l, $lang );
		$game->rename_char( $char_id, $new_char_name );
		$game->save( $db );
	break;
	case 'char_multiremove':
		$char_ids = (array) $_GET['char_ids'];
		$game = new game( $db, $l, $lang );

		foreach( $char_ids as $char_id ){

			$game->delete_char( $char_id );
			echo 'Char '.$char_id.' deleted';

		}
		$game->save( $db );
	break;
}