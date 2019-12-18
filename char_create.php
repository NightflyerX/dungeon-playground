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

.title{
	font-family:'Righteous', serif;
}

</style>

<script>

var loaded_char = {};

<?
if( isset( $_GET['char_id'] ) ){
	
	$result = $db->query("SELECT `data` FROM chars WHERE `char_id`=1");
	$row = $result->fetch(PDO::FETCH_ASSOC);
	$chars = json_decode( $row['data'] );
	
	foreach( $chars AS $char ){
		
		if( $char->char_id == $_GET['char_id'] ){
			
			?>
			loaded_char = JSON.parse('<?=json_encode($char);?>');
			<?
			
		}
		
	}
	
}
?>
     
	var editor = false;
	var skill_b = 0;
	var skill_m = 0;
	

	var level = 0;

	var mod_kon = 0;
	var mod_char = 0;
	var mod_int = 0;

	var faktor = [ 0, 3, 4, 6, 8 ]; //Bodybuilding und Meditation
	

	var pot_bodybuilding = 0;
	var pot_meditation = 0;
	
	
	var life =0;	
	var mana = 0;

$(document).ready( function(){

	$.getJSON("getdata.php", {
		page: 'get_fields'
		
	})
	.done(function(json) {

		var options = '';

		json.forEach( function( stored_field ){

			console.log( stored_field );

			options += '<option value="'+stored_field.creation_date+'">'+stored_field.field_name+'</option>	';		

		});

		$('#stored_fields').html( options );

	});

});

function addfield(){

	var field_id = $('#stored_fields').val();
	var field_name = $('#stored_fields').find(":selected").text();
	var char_id = $('#char_id').val();

	$.get( 'setdata.php', { page : 'add_field_to_char', field_id : field_id, field_name : field_name, char_id : char_id }, function(){
		
		alert( 'Added. Please reload the page.' );

	});

}

function delete_field_from_char( field_id ){

	var char_id = $('#char_id').val();

	$.get( 'setdata.php', { page : 'delete_field_from_char', field_id : field_id, char_id : char_id }, function(){

		alert( 'Deleted. Please reload the page.' );

	});

}



function mk_array(data, objectname) {
	
	if( objectname ){

		var tmp = new Array();
		for (i = 0, j = data.length; i < j; i++) {
			tmp.push( data[i][objectname] );
		}

	}else{
	
    		var tmp = new Array();
    		for (i = 0, j = data.length; i < j; i++) {
        		tmp.push(data[i].name);
    		}

	}

	return tmp;
}

function fill(){
	
	if( !loaded_char.char_id ){
		
		return true;
		
	}

	console.log( loaded_char );

	if( !loaded_char.life_factor ){ loaded_char.life_factor = 1; }
	if( !loaded_char.mana_factor ){ loaded_char.mana_factor = 1; }
	
	$('#char_id').val( loaded_char.char_id );
	$('#creation_date').val( loaded_char.creation_date );
	$('#img_url').val( loaded_char.img_url );
	$( '<img class="img-thumbnail" src="server/php/files/medium/'+loaded_char.img_url+'" />' ).insertAfter( $("#img_url") );
	$('#char_name').val( loaded_char.name );

	$('#life_factor').val( loaded_char.life_factor );
	$('#mana_factor').val( loaded_char.mana_factor );
	$('#gender').val( loaded_char.gender );
	$('#race').val( loaded_char.race );
	$('#type').val( loaded_char.type );
	$('#level').val( loaded_char.level );
	$('#skilldegree').val( loaded_char.skilldegree );
	$('#maxskill').val( loaded_char.maxskill );
	$('#dm_only').val( loaded_char.dm_only );
	$('#creator').val( loaded_char.creator );
	$('#controller').val( loaded_char.controller );
	$('#submit').val( 'Update character' );

	if( loaded_char.fields ){

		loaded_char.fields.forEach( function( field ){

			$('#added_fields').append( '<li>'+field.field_name+' <img src="svg/si-glyph-trash.svg" style="height:16px;width:16px;" onclick="delete_field_from_char('+field.creation_date+')"></li>' );

		});
		
	}
	
	
}

function calcLife(){

	
	skill_b = parseInt( $('#skill_Bodybuilding' ).val() );
	skill_m = parseInt( $('#skill_Meditation' ).val() );
	

	level = parseInt( $('#level').val() );

	mod_kon = parseInt( $("#mod_attr_stamina").val() );
	mod_char = parseInt( $("#mod_attr_charisma").val() );
	mod_int = parseInt( $("#mod_attr_intellect").val() );

	faktor = [ 0, 3, 4, 6, 8 ]; //Bodybuilding und Meditation
	

	pot_bodybuilding = $("#v_pot_Bodybuilding_min").val() ? parseInt($("#v_pot_Bodybuilding_min").val()) : 0;
	pot_meditation = $("#v_pot_Meditation_min").val() ? parseInt($("#v_pot_Meditation_min").val()) : 0;
	
	
	life = 20 + (mod_kon+1)*level + ( skill_b * faktor[pot_bodybuilding] );

	if( mod_char + mod_int > 5 ){
	
		mana = 20 + ((mod_char+mod_int-4)/2+5)*level + ( skill_m * faktor[pot_meditation] );

	}else{

		mana = 20 + (mod_char+mod_int+1)*level + ( skill_m * faktor[pot_meditation] );

	}

	$('#life').val( Math.round( life * $('#life_factor').val() ) );
	$('#mana').val( Math.round( mana * $('#mana_factor').val() ) );

}

function update(){

	var lib = $('#data').data("lib");

	potion = 0;

	for( x=0;x<lib.attributes.length;x++ ){

		var wert = parseInt( $('#attr_'+lib.attributes[x].name).val() );
		var mod = 0;

		for( y=0; y<lib.mods.length;y++ ){

			if( wert >= lib.mods[y].attribute_value ){

				mod = lib.mods[y].mod_value;

			}

		}

		$('#mod_attr_'+lib.attributes[x].name).val( mod );

		var tmp = parseInt( $('#potion_'+lib.attributes[x].name).val() );

		if( tmp > 0 ){

			potion += tmp;

		}

	}
	/*
	var skill_b = parseInt( $('#skill_Bodybuilding' ).val() );
	var skill_m = parseInt( $('#skill_Meditation' ).val() );
	

	var level = parseInt( $('#level').val() );

	var mod_kon = parseInt( $("#mod_attr_stamina").val() );
	var mod_char = parseInt( $("#mod_attr_charisma").val() );
	var mod_int = parseInt( $("#mod_attr_intellect").val() );

	var faktor = [ 0, 3, 4, 6, 8 ]; //Bodybuilding and Meditation
	

	var pot_bodybuilding = $("#v_pot_Bodybuilding_min").val() ? parseInt($("#v_pot_Bodybuilding_min").val()) : 0;
	var pot_meditation = $("#v_pot_Meditation_min").val() ? parseInt($("#v_pot_Meditation_min").val()) : 0;
	
	
	var life = 20 + (mod_kon+1)*level + ( skill_b * faktor[pot_bodybuilding] );

	if( mod_char + mod_int > 5 ){
	
		var mana = 20 + ((mod_char+mod_int-4)/2+5)*level + ( skill_m * faktor[pot_meditation] );

	}else{

		var mana = 20 + (mod_char+mod_int+1)*level + ( skill_m * faktor[pot_meditation] );

	}
	*/
	calcLife();

	var punkte_max = 20 + 13 * lib.attributes.length + level + potion;
	var punkte_verb = 0;

	for( x=0; x<lib.attributes.length; x++ ){

		punkte_verb += parseInt( $('#attr_'+lib.attributes[x].name).val() );

		if( level == 1 ){

			$('#attr_'+lib.attributes[x].name).attr( "max", 30 );

		}else{

			$('#attr_'+lib.attributes[x].name).attr( "max", 1000000 );

		}

	}

	$('#attr_punkte').html( punkte_max - punkte_verb );
	
	var skill_points = Math.round(level*10+level*level/12);
	var used_skill_points = 0;
	
	$('#skills input[type="number"]').each( function(){
	
		var skill = parseInt( $(this).val() );

		if( skill > $('#maxskill').val() ){

			$(this).addClass( 'is-invalid' );
			$(this).addClass( 'bg-warning' );

		}else{

			var elem_id = $(this).attr('id');
			var min_id = elem_id.replace(/skill/i, "v_pot");

			if( skill > 0 && $('#'+min_id+'_min').length > 0  && $('#'+min_id+'_min').val() == 0 ){

				$(this).addClass( 'bg-info' );

			}else{

				$(this).removeClass( 'is-invalid' );
				$(this).removeClass( 'bg-info' );
				$(this).removeClass( 'bg-warning' );

			}

		}
		
		if( skill > 0 ){
		
			used_skill_points += (skill/2)*(skill+1) + 1;
			
		}
	
	});
	
	$('#skill_punkte').html( skill_points - used_skill_points + 4 );
	

}

function return_value( value ){
	
	if( value == 0 ){

		return 0; //Unskilled

	}else if( value <= 3 ){  //Experte ab 4

		return 1; //Normal

	}else if( value <= 6 ){ //Meister ab 7

		return 2; //Expert

	}else if( value <= 10 ){ //Grossmeister ab 11

		return 3; //Master

	}else{

		return 4; //Grandmaster

	}

}

var getjson = $.getJSON("getdata.php", {
        page: 'get_types'
    })
    .done(function(lib) {

	$('#data').data( "lib", lib );
        
        for( x=0;x<lib.races_names.length;x++ ){
	        $('#race').append( '<option value="'+lib.races_names[x].name+'">'+lib.races_names[x].name+'</option>' );
        }
        for( x=0;x<lib.type_names.length;x++ ){
	        $('#type').append( '<option value="'+lib.type_names[x].name+'">'+lib.type_names[x].name+'</option>' );
        }
        
        //$('#s_container').append( '<fieldset id="attributes" style="min-height:350px;"><legend>Attributes:</legend><h5>Distribute <span id="punkte">20</span> points</h5></fieldset>' );
        
	for( x=0;x<lib.attributes.length;x++ ){

		var tmp = '<div class="col-sm-2 p-3 m-3 border border-white" style="min-width:170px;">';
		tmp +='<label for="attr_'+lib.attributes[x].name+'">'+lib.attributes[x].name+':</label> ';
		tmp += '<input type="number" name="attr_'+lib.attributes[x].name+'" id="attr_'+lib.attributes[x].name+'" value="13"'; 
		if(  !loaded_char.level && parseInt( $('#level').val() ) == 1 ){
			tmp += ' min="11" max="30" ';
		}
		tmp +='class="form-control"  onchange="update()"/>';
		tmp += '<label for="mod_attr_'+lib.attributes[x].name+'">Mod_'+lib.attributes[x].name+':</label>';
		tmp +='<input readonly="readonly" type="number" name="mod_attr_'+lib.attributes[x].name+'" id="mod_attr_'+lib.attributes[x].name+'" value="0" class="form-control"/>';
		tmp += '<label for="potion_'+lib.attributes[x].name+'">Potion_'+lib.attributes[x].name+':</label>';
		tmp +='<select name="potion_'+lib.attributes[x].name+'" id="potion_'+lib.attributes[x].name+'" class="form-control" onchange="update()">';
		tmp += `
			<option value="0"></option>
			<option value="50">50</option>
			<option value="25">25</option>
			<option value="20">20</option>
			<option value="15">15</option>
			<option value="10">10</option>
			<option value="9">9</option>
			<option value="8">8</option>
			<option value="7">7</option>
			<option value="6">6</option>
			<option value="5">5</option>
			<option value="4">4</option>
			<option value="3">3</option>
			<option value="2">2</option>
			<option value="1">1</option>
		</select>`;

		$('#attr_container').append( tmp + '</div>' );
		
		if( loaded_char.attributes ){

			$('#attr_'+lib.attributes[x].name).val( parseInt( loaded_char.attributes[lib.attributes[x].name].attr_value) );
			$('#mod_attr_'+lib.attributes[x].name).val( parseInt(loaded_char.attributes[lib.attributes[x].name].mod) );
			$('#potion_'+lib.attributes[x].name).val( parseInt(loaded_char.attributes[lib.attributes[x].name].potion) );
			
		}
		
    }

	//$('#s_container').append( '<fieldset id="skills" style="min-height:350px;"><legend>Skills:</legend><h5 style="position: -webkit-sticky;position:sticky;top: 0;background-color:black;border:3px double white;z-index:10;">Distribute <span id="skillpoints">4</span> skill points</h5><h5>Defensive:</h5></fieldset>' );

	for( x=0;x<lib.defensive.length;x++ ){

		
		var skill = loaded_char.skills && loaded_char.skills.defensive[lib.defensive[x].name] ? loaded_char.skills.defensive[lib.defensive[x].name].skill : 0;
		var min = loaded_char.skills && loaded_char.skills.defensive[lib.defensive[x].name] ? loaded_char.skills.defensive[lib.defensive[x].name].cur_lvl : 1;
		var max = loaded_char.skills && loaded_char.skills.defensive[lib.defensive[x].name] ? loaded_char.skills.defensive[lib.defensive[x].name].pot_lvl : 2;

		min = return_value( skill ) <= max ? return_value( skill ) : max;
		
		var tmp = '<div class="col-sm-2 p-3 m-3 border border-white" style="min-width:170px;">';
		tmp +='<label for="skill_'+lib.defensive[x].name+'">'+lib.defensive[x].name+':</label> ';
		tmp += '<input type="number" name="skill_'+lib.defensive[x].name+'" id="skill_'+lib.defensive[x].name+'" value="'+skill+'" class="form-control"  onchange="update()"/>';
		tmp += '<div style="margin:20px;margin-left:20px;" id="pot_'+lib.defensive[x].name+'" style="width: 100px;"></div>';
		tmp += '<input type="hidden" id="v_pot_'+lib.defensive[x].name+'_min" name="v_pot_'+lib.defensive[x].name+'_min" value="'+min+'" />';
		tmp += '<input type="hidden" id="v_pot_'+lib.defensive[x].name+'_max" name="v_pot_'+lib.defensive[x].name+'_max" value="'+max+'" />';
		
		$('#skill_def_container').append(tmp+'</div>');
		
		var fresh = $("#pot_"+lib.defensive[x].name).freshslider({
	        range: true,
	        step:1,
	        min : 0,
	        max : 4,
	        value:[0, 1],
	        onchange:function(low, high, me ){
		        
		        var name = me[0].id;
		        
	            $("#v_"+name+"_min").val(low);
	            $("#v_"+name+"_max").val(high);

	            
	        }
    	});

    	fresh.setValue(min, max);

	}

	//$('#skills').append( '<h5 style="clear:both;">Offensiv:</h5>' );
	
	console.log( loaded_char );

	for( x=0;x<lib.weapon_types.length;x++ ){
		
		var skill = loaded_char.skills && loaded_char.skills.offensive[lib.weapon_types[x].name] ? loaded_char.skills.offensive[lib.weapon_types[x].name].skill : 0;
		var min = loaded_char.skills && loaded_char.skills.offensive[lib.weapon_types[x].name] ? loaded_char.skills.offensive[lib.weapon_types[x].name].cur_lvl : 1;
		var max = loaded_char.skills && loaded_char.skills.offensive[lib.weapon_types[x].name] ? loaded_char.skills.offensive[lib.weapon_types[x].name].pot_lvl : 2;

		min = return_value( skill ) <= max ? return_value( skill ) : max;

		var tmp = '<div class="col-sm-2 p-3 m-3 border border-white" style="min-width:170px;">';
		tmp +='<label for="skill_'+lib.weapon_types[x].name+'">'+lib.weapon_types[x].name+':</label> ';
		tmp += '<input type="number" name="skill_'+lib.weapon_types[x].name+'" id="skill_'+lib.weapon_types[x].name+'" value="'+skill+'" class="form-control"  onchange="update()"/>';
		tmp += '<div style="margin:20px;margin-left:30px;" id="pot_'+lib.weapon_types[x].name+'" style="width: 100px;"></div>';
		tmp += '<input type="hidden" id="v_pot_'+lib.weapon_types[x].name+'_min" name="v_pot_'+lib.weapon_types[x].name+'_min" value="'+min+'" />';
		tmp += '<input type="hidden" id="v_pot_'+lib.weapon_types[x].name+'_max" name="v_pot_'+lib.weapon_types[x].name+'_max" value="'+max+'" />';
		
		$('#skill_off_container').append( tmp+'</div>');
		console.log( "Weapon :"+lib.weapon_types[x].name+" Low:"+min+"High:"+max );
		var fresh = $("#pot_"+lib.weapon_types[x].name).freshslider({
	        range: true,
	        step:1,
	        min : 0,
	        max : 4,
	        value:[0, 1],
	        onchange:function(low, high, me ){
		        
		        var name = me[0].id;
		        
	            $("#v_"+name+"_min").val(low);
	            $("#v_"+name+"_max").val(high);

	        }
    	});
    	
    	fresh.setValue(min, max);

	}

	//$('#skills').append( '<h5 style="clear:both;">Magien:</h5>' );

	for( x=0;x<lib.magic_types.length;x++ ){
		
		var skill = loaded_char.skills && loaded_char.skills.magic_types[lib.magic_types[x].magic_type_name]? loaded_char.skills.magic_types[lib.magic_types[x].magic_type_name].skill : 0;
		var min = loaded_char.skills && loaded_char.skills.magic_types[lib.magic_types[x].magic_type_name]? loaded_char.skills.magic_types[lib.magic_types[x].magic_type_name].cur_lvl : 1;
		var max = loaded_char.skills && loaded_char.skills.magic_types[lib.magic_types[x].magic_type_name]? loaded_char.skills.magic_types[lib.magic_types[x].magic_type_name].pot_lvl : 2;

		min = return_value( skill ) <= max ? return_value( skill ) : max;

		var tmp = '<div class="col-sm-2 p-3 m-3 border border-white" style="min-width:170px;">';
		tmp +='<label for="skill_'+lib.magic_types[x].magic_type_name+'">('+lib.magic_types[x].magic_class_name+") "+lib.magic_types[x].magic_type_name+':</label> ';
		tmp += '<input type="number" name="skill_'+lib.magic_types[x].magic_type_name+'" id="skill_'+lib.magic_types[x].magic_type_name+'" value="'+skill+'" class="form-control"  onchange="update()"/>';
		
		tmp += '<div style="margin:20px;margin-left:30px;" id="pot_'+lib.magic_types[x].magic_type_name+'" style="width: 100px;"></div>';
		tmp += '<input type="hidden" id="v_pot_'+lib.magic_types[x].magic_type_name+'_min" name="v_pot_'+lib.magic_types[x].magic_type_name+'_min" value="'+min+'" />';
		tmp += '<input type="hidden" id="v_pot_'+lib.magic_types[x].magic_type_name+'_max" name="v_pot_'+lib.magic_types[x].magic_type_name+'_max" value="'+max+'" />';
		
		$('#skill_magic_container').append( tmp+'</div>');
		
		var fresh = $("#pot_"+lib.magic_types[x].magic_type_name).freshslider({
	        range: true,
	        step:1,
	        min : 0,
	        max : 4,
	        value:[0, 1],
	        onchange:function(low, high, me ){
		        
		        var name = me[0].id;
		        
	            $("#v_"+name+"_min").val(low);
	            $("#v_"+name+"_max").val(high);
	        }
    	});
    	
    	fresh.setValue(min, max);

	}

	//$('#skills').append( '<h5 style="clear:both;">Rest:</h5>' );

	for( x=0;x<lib.skill_types.length;x++ ){
		
		var skill = loaded_char.skills && loaded_char.skills.skill_types[lib.skill_types[x].skill_type_name]? loaded_char.skills.skill_types[lib.skill_types[x].skill_type_name].skill : 0;
		var min = loaded_char.skills && loaded_char.skills.skill_types[lib.skill_types[x].skill_type_name]? loaded_char.skills.skill_types[lib.skill_types[x].skill_type_name].cur_lvl : 1;
		var max = loaded_char.skills && loaded_char.skills.skill_types[lib.skill_types[x].skill_type_name]? loaded_char.skills.skill_types[lib.skill_types[x].skill_type_name].pot_lvl : 2;
		
		min = return_value( skill ) <= max ? return_value( skill ) : max;
		
		var tmp = '<div class="col-sm-2 p-3 m-3 border border-white" style="min-width:170px;">';
		tmp +='<label for="skill_'+lib.skill_types[x].skill_type_name+'">('+lib.skill_types[x].skill_class_name+") "+lib.skill_types[x].skill_type_name+':</label> ';
		tmp += '<input type="number" name="skill_'+lib.skill_types[x].skill_type_name+'" id="skill_'+lib.skill_types[x].skill_type_name+'" value="'+skill+'" class="form-control"  onchange="update()"/>';
		
		tmp += '<div style="margin:20px;margin-left:30px;" id="pot_'+lib.skill_types[x].skill_type_name+'" style="width: 100px;"></div>';
		tmp += '<input type="hidden" id="v_pot_'+lib.skill_types[x].skill_type_name+'_min" name="v_pot_'+lib.skill_types[x].skill_type_name+'_min" value="'+min+'" />';
		tmp += '<input type="hidden" id="v_pot_'+lib.skill_types[x].skill_type_name+'_max" name="v_pot_'+lib.skill_types[x].skill_type_name+'_max" value="'+max+'" />';
		
		$('#skill_types_container').append( tmp+'</div>');
		
		var fresh = $("#pot_"+lib.skill_types[x].skill_type_name).freshslider({
	        range: true,
	        step:1,
	        min : 0,
	        max : 4,
	        value:[0, 1],
	        onchange:function(low, high, me ){
		        
		        var name = me[0].id;
		        
	            $("#v_"+name+"_min").val(low);
	            $("#v_"+name+"_max").val(high);

			calcLife();

	        }
    	});
    	
    	fresh.setValue(min, max);

	}

	fill();
	update();
    	
        })
    .fail(function(jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
    
function openwindow(){
	  NewWindow=window.open('upload_popup.html','newWin','width=800,height=300,left=200,top=500,toolbar=No,location=No,scrollbars=no,status=No,resizable=no,fullscreen=No');  
	  NewWindow.focus(); 
	  void(0);  
}

function closewindow(){
	NewWindow.close();
}

</script>

<div id="editor_holder" class="player card container">
	
	<form name="char_create_form" action="char_create2.php" method="post">
	
		<div class="row">
	
			<div id="data" style="display:none">&nbsp;</div>
			<input type="hidden" id="char_id" name="char_id" value="0" />
			<input type="hidden" id="creation_date" name="creation_date" value="0" />
			<input type="hidden" id="creator" name="creator" value="" />
			<input type="hidden" id="controller" name="controller" value="" />
		
			<div class="col-sm-3">
				<div class="card p-3">
					<h5 class="card-title title">Picture</h5>
					<label for="img_url" class="text-white">picture name</label>
					<input id="img_url" type="text" name="img_url" value="" class="form-control"/> <a href="#" onclick="openwindow()">upload picture</a>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="card p-3">
					<h5 class="card-title title">basic char data</h5>
					<label for="char_name" class="text-white">char name</label>
					<input id="char_name" type="text" name="char_name" value="" class="form-control"/>
					
					<label for="gender" class="text-white">gender</label>
					<select id="gender" name="gender" class="form-control">
						<option value="m">male</option><option value="w">female</option>
					</select>
					
					<label for="race" class="text-white">race</label>
					<select id="race" name="race" class="form-control">
					</select>
					
					<label for="type" class="text-white">type</label>
					<select id="type" name="type" class="form-control">
					</select>
					
					<label for="level" class="text-white">level</label>
					<input id="level" type="number" name="level" value="1" class="form-control" onchange="update()"/>

					<label for="skilldegree" class="text-white">skill</label>
					<select id="skilldegree" name="skilldegree" class="form-control">
						<option value="1"><?=$l[$lang]['NORMAL'];?></option><option value="2"><?=$l[$lang]['EXPERT'];?></option><option value="3"><?=$l[$lang]['MASTER'];?></option><option value="4"><?=$l[$lang]['GRANDMASTER'];?></option>
					</select>

					<label for="maxskill" class="text-white">max skill / max token</label>
					<input id="maxskill" type="number" name="maxskill" value="1" class="form-control"/>
				</div>
			</div>
		
			<div class="col-sm-3">
				<div class="card p-3">
					<h5 class="card-title title">build</h5>

					<label for="life">Life :</label>
					<div class="form-inline">
						<input readonly="readonly" type="number" id="life" name="life" value="20" class="form-control" style="width:150px;"/> <input type="text" id="life_factor" name="life_factor" value="1" class="form-control" style="width:50px;" onchange="update()"/>
					</div> 

					<label for="mana">mana :</label>
					<div class="form-inline">
						<input readonly="readonly" type="number" id="mana" name="mana" value="20" class="form-control" style="width:150px;"/> <input type="text" id="mana_factor" name="mana_factor" value="1" class="form-control" style="width:50px;" onchange="update()"/>
					</div>
				</div>

				<div class="card p-3">
					<h5 class="card-title title">DM only</h5>
					<div class="card-body container">
						<label for="dm_only">DM-char :</label> <select id="dm_only" name="dm_only" class="form-control">
						<option value="Normal">normal</option><option value="dm_only">DM only</option>
					</select>

					</div>
				</div>
			</div>	
			
		</div>
		
		<div class="row mt-3">
			<div class="col">
				<div class="card p-3">
					<h5 class="card-title title">Attributes</h5>
					<h4 class="card-subtitle mb-2 text-white">Distribute <span id="attr_punkte">20</span> points</h4>
					
					<div class="card-body container">
						<div id="attr_container" class="row">
						</div>
					</div>
					
				</div>
				
			</div>
		</div>

		<div class="row mt-3">
			<div id="skills" class="col">

				<h5 class="title">Skills</h5>
				<h4 class="mb-2 text-white" style="position: -webkit-sticky;position:sticky;top: 0;background-color:black;border:3px double white;z-index:10;">Distribute <span id="skill_punkte">4</span> points</h4>

				<div class="card p-3">
					<h5 class="card-title title">Defensive skills</h5>

					<div class="card-body container">
						<div id="skill_def_container" class="row">
						</div>
					</div>
				</div>

				<div class="card p-3">
					<h5 class="card-title title">Offensive skills</h5>

					<div class="card-body container">
						<div id="skill_off_container" class="row">
						</div>
					</div>
				</div>

				<div class="card p-3">
					<h5 class="card-title title">Magic skills</h5>

					<div class="card-body container">
						<div id="skill_magic_container" class="row">
						</div>
					</div>
				</div>

				<div class="card p-3">
					<h5 class="card-title title">Additional skills</h5>

					<div class="card-body container">
						<div id="skill_types_container" class="row">
						</div>
					</div>
				</div>
			</div>
		</div>
		<?
		if( isset( $_GET['char_id'] ) ){
			?>
		<div class="row">
			<div class="col-2">
				Stored fields:<br />
				<select id="stored_fields" name="stored_fields" class="form-control"></select>
			</div>
			<div class="col-2">
				<button type="button" id="add_field" class="btn btn-info" onclick="addfield()">Add to char</button>
			</div>
			<div class="col-2">
				Added fields:<br />
				<ul id="added_fields" class="list-group"></ul>
			</div>
		</div>
			<?
		}
		?>
		<div class="row">
			<div class="col">	
				<br />
				<input id="submit" type="submit" name="submit" value="Create new char"  class="form-control" style="width:300px;" />
				<br />
				<div style="height:50px;">&nbsp;</div>
			</div>
		</div>
	
	</form>

</div>

<?

include( 'footer_nochat.php' );