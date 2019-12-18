<?php
include('security.php');
include('language.php');
include('header.php');
?>


<style type="text/css">

form input,select{
	margin-top: 2px;
	margin-bottom: 2px;
}

form fieldset{
	margin: 5px;
	border: 1px solid white;
	padding: 10px;
}
form fieldset legend{
	width: 150px;
	border: 1px solid white;
	text-align: center;
}
</style>

<div class="modal show">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create or update char</h5>
      </div>
      <div class="modal-body">


<?

$character = new stdClass();
$character->char_id = isset( $_POST['char_id'] ) && !empty( $_POST['char_id'] ) ? $_POST['char_id'] : time();

$result = $db->query("SELECT `data` FROM chars WHERE `char_id`=1");
$row = $result->fetch(PDO::FETCH_ASSOC);
$char_data = json_decode( $row['data'] );

$char_already_exists = false;
$arr_key = false;

foreach( $char_data AS $key => $char ){
	
	if( $char->char_id == $character->char_id ){

		$character = $char;
		
		$char_already_exists = true;
		$arr_key = $key;
		//var_dump($arr_key);
		//$character->fields = $char->fields;
		
	}
	
}

if( $char_already_exists == false ){

	$character = new stdClass();

}

$character->img_url = $_POST['img_url'];
$character->name = $_POST['char_name'];
$character->gender = $_POST['gender'];
$character->race = $_POST['race'];
$character->type = $_POST['type'];
$character->level = $_POST['level'];
$character->life_factor = $_POST['life_factor'];
$character->mana_factor = $_POST['mana_factor'];
$character->skilldegree = $_POST['skilldegree'];
$character->maxskill = $_POST['maxskill'];
$character->dm_only = $_POST['dm_only'];
$character->creator = isset( $_POST['creator'] )&& !empty( $_POST['creator'] ) ? $_POST['creator'] : $_SESSION['username'];
$character->controller = isset( $_POST['controller'] )&& !empty( $_POST['controller'] ) ? $_POST['controller'] : $_SESSION['username'];
$character->creation_date = isset( $_POST['creation_date'] )&& !empty( $_POST['creation_date'] ) ? $_POST['creation_date'] : date("Y-m-d H:i:s");
$character->char_id = isset( $_POST['char_id'] ) && !empty( $_POST['char_id'] ) ? $_POST['char_id'] : time();
$character->fields = empty( $character->fields ) ? array() : $character->fields;

$character->life = (object) ['current_life' => $_POST['life'], 'max_life' => $_POST['life']];
$character->mana = (object) ['current_mana' => $_POST['mana'], 'max_mana' => $_POST['mana']];

$character->attributes = new stdClass();

$result = $db->query("SELECT `data` FROM lib_data WHERE `data_id`=1");
$row = $result->fetch(PDO::FETCH_ASSOC);
$lib = json_decode( $row['data'] );

foreach( $lib->attributes AS $key => $val ){
	
	$attr = (string) $val->name;	
	$character->attributes->{$attr} = (object) [ 'attr_value' =>$_POST['attr_'.$attr], 'mod' =>$_POST['mod_attr_'.$attr], 'potion' => $_POST['potion_'.$attr] ];

	
}

$character->skills = new stdClass();
$character->skills->defensive = new stdClass();

foreach( $lib->defensive AS $key => $val ){
	
	$skill = (string) $val->name;
	
	$character->skills->defensive->{$skill} = (object) [ 'skill' => $_POST['skill_'.$skill], 'cur_lvl' => $_POST['v_pot_'.$skill.'_min'], 'pot_lvl' => $_POST['v_pot_'.$skill.'_max'] ];
	
}
$character->skills->offensive = new stdClass();
foreach( $lib->weapon_types AS $key => $val ){
	
	$skill = (string) $val->name;
	$character->skills->offensive->{$skill} = (object) [ 'skill' => $_POST['skill_'.$skill], 'cur_lvl' => $_POST['v_pot_'.$skill.'_min'], 'pot_lvl' => $_POST['v_pot_'.$skill.'_max'] ];
	
}
$character->skills->magic_types = new stdClass();
foreach( $lib->magic_types AS $key => $val ){
	
	$skill = (string) $val->magic_type_name;
	$character->skills->magic_types->{$skill} = (object) [ 'skill' => $_POST['skill_'.$skill], 'cur_lvl' => $_POST['v_pot_'.$skill.'_min'], 'pot_lvl' => $_POST['v_pot_'.$skill.'_max'] ];
	
}
$character->skills->skill_types = new stdClass();
foreach( $lib->skill_types AS $key => $val ){
	
	$skill = (string) $val->skill_type_name;
	$character->skills->skill_types->{$skill} = (object) [ 'skill' => $_POST['skill_'.$skill], 'cur_lvl' => $_POST['v_pot_'.$skill.'_min'], 'pot_lvl' => $_POST['v_pot_'.$skill.'_max'] ];
	
}

if( $char_already_exists === false ){

	$char_data[] = $character; //Append new character
	$string = "<h5>Created new char successfully</h5>";
	
}else{
	
	$char_data[$arr_key] = $character; //Replace existing char
	$string = "<h5>Char updated successfully</h5>";
	
}

$data = json_encode( $char_data, JSON_PRETTY_PRINT );

if( is_JSON( $data ) ){
			
	try {

		$stmt = $db->prepare("UPDATE `chars` SET data=:data WHERE `char_id`=1");
		$stmt->bindParam(':data', $data, PDO::PARAM_STR);
		$stmt->execute();

	}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
	}
	
	echo $string;
	
}

?>

		</div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<?


include( 'footer_nochat.php' );