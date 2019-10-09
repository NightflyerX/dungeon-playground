<?php
include('security.php');
include('language.php');
include('header.php');


function get_users($db){
	$result = $db->query("SELECT * FROM users");
	$my_arr = array();
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    	$my_arr[] = array( 'user_id' => $row['user_id'], 'username' => $row['username'], 'email' => $row['email'], 'color' => $row['color'] );
	}
	return $my_arr;
}

$users = get_users($db);

$result = $db->query("SELECT * FROM game WHERE active=1 ");
$counter = 0;
foreach( $result AS $row ){

	$game = json_decode($row['data']);
	$_SESSION['current_game_id'] = (int) $row['game_id'];
	$counter++;

}

if( $counter == 0 ){
	
	$_SESSION['current_game_id'] = 0;
	
}

$char_id = isset( $_GET['char_id'] ) ? $_GET['char_id'] : 0;
	


?>

<div class="col-lg-8">

<script>
<?
if( isset( $_SESSION['current_game_id'] ) ){
	?>
var current_game_id = <?=$_SESSION['current_game_id'];?>;
	<?
}
?>
var char_id = <?=$char_id;?>;
var chars = [];
var pop_up = 0;
var lib = '';
var game2 = {};
var selected_players = new Array();
var chars_assoc = new Array();

$(document).ready( function(){

	$.getJSON("getdata.php", {
        	page: 'get_types'
    	}).done(function(lib) {
		lib = lib;

	});
	
	$('#newgame').click( function(){
		
		$.get( 'setdata.php', { 'page':'newgame' }, function(z){
			window.location.href = '<?=str_replace("index.php", "game.php", __ABSOLUTE_URL__ );?>';
		});
		
	});
	
	<?
if( isset( $_SESSION['current_game_id'] ) && $_SESSION['current_game_id'] != 0 ){
	?>
	<?}?>

	$.getJSON("getdata.php", {
	page: 'get_char'
	})
	.done(function(json) {

		chars = json;

		for( y=0; y<chars.length;y++ ){

			var creator = chars[y].creator;
			<?
			$dm_username = '';
			
			if( isset( $_SESSION['is_dm'] ) && $_SESSION['is_dm'] === true ){

				$dm_username = $_SESSION['username'];

			}
			?>
			var leftright = creator == "<?=$dm_username;?>" ? "right" : "left";
			var is_dm = <? echo isset( $_SESSION['is_dm'] ) && $_SESSION['is_dm'] === true || $_SESSION['user_id'] == 1 ? 'true' : 'false';?>;
			
			if( is_dm == false && leftright == "right" ){

				chars[y].name = "?";

			}else if( is_dm == false ){

				$('#delete_game').hide();

			}

			$('#'+creator).append( '<a class="dropdown-item" href="#" onclick="add_char_to_game('+chars[y].char_id+',\''+leftright+'\',\''+creator+'\')"><h5>'+chars[y].name+'</h5></a>' );
			
		}
		
		$('#player_bar ul li').each( function(){
			
			if( $(this).find('div a').length == 0 ){
				
				$(this).find('a').removeClass( 'dropdown-toggle' );
				
			}
			
		});

		

	});

	$.getJSON("getdata.php", { page: 'get_fields' }).done(function(json) {

		var options = '';

		json.forEach( function( stored_field ){

			

			options += '<option value="'+stored_field.creation_date+'">'+stored_field.field_name+'</option>	';		

		});

		$('#stored_fields').html( options );

	});


	$('#reset').click( function(){

		var r = confirm("Really reset");

		if (r == true) {

			$.get( 'setdata.php', { 'page':'reset' }, function(z){
			});

		}

	});

	
	
});

function sel_players(){

	selected_players = [];

	$('.checkboxform input:checked').each( function(){

		selected_players.push( $(this).data('char-id') );

	});

	if( $('#fields .empty_owner').length > 0 ){

		$('#fields .empty_owner').each( function(){

			$(this).html('');
			var id = $(this).data('id');

			if( selected_players.length == 1 ){

				$(this).append(`
					<button type="button" id="add_owner" class="form-control btn" style="margin-top:3px;"><img src="svg/si-glyph-plus.svg" style="height:16px;width:16px;" onclick="add_field_owner(${id})"></button>
				`);

			}

		});

	}

	if( selected_players.length > 0 ){

		$('#fields .target_add').each( function(){

			$(this).html('');
			var id = $(this).data('id');

			$(this).append(`
				<button type="button add_targets" class="form-control btn" style="margin-top:3px;"><img src="svg/si-glyph-plus.svg" style="height:16px;width:16px;" onclick="add_field_targets(${id})"></button>
			`);

		});
		
	}

	console.log( selected_players );

}

function add_field_owner( field_id ){

	$.get( 'setdata.php', { page : 'add_field_owner', field_id : field_id, field_owner_id : selected_players[0] }, function(){

	});

}

function add_field_targets( field_id ){

	$.get( 'setdata.php', { page : 'add_field_targets', field_id : field_id, field_target_ids : selected_players }, function(){

	});

}

function remove_field_target( field_id, field_target_id ){

	$.get( 'setdata.php', { page : 'remove_field_target', field_id : field_id, field_target_id : field_target_id }, function(){

	});

}

function remove_field_owner( field_id ){

	$.get( 'setdata.php', { page : 'remove_field_owner', field_id : field_id }, function(){

	});

}

function deletefield( field_id ){

	$.get( 'setdata.php', { page : 'delete_field', field_id : field_id }, function(){

	});

}

function savefield( field_id ){

	var cur_field_name = $('.container-fluid[data-id='+field_id+'] .title span').text();
	

	$.get( 'setdata.php', { page : 'save_field', field_id : field_id, field_name : cur_field_name }, function(){

	});

}

function rename( field_id, field_name ){

	var new_field_name = prompt("Ener new field name", field_name );

	if( new_field_name != "" ){

		$.get( 'setdata.php', { page : 'rename_field', field_id : field_id, new_field_name : new_field_name });

	}

}

function paycost( field_id ){

	$.get( 'setdata.php', { page : 'field_paycost', field_id : field_id }, function(){

	});

}

function game( game_id ){

	$.getJSON("getdata.php", {
        	page: 'get_types'
    	}).done(function(lib) {
		lib = lib;

		$.getJSON("getdata.php", {
			page: 'get_game',
			game_id : game_id
		})
		.done(function(json) {
	
			var game = json;
			game2 = game;
			console.log( game2 );
			
	
			$('#left').html('');
			$('#right').html('');
			$('#fields').html('');
			
			if( game.chars.length > 0 ){
				
				game.chars.forEach( function( char, index ){

					chars_assoc[char.char_id] = char;
		
					var life = 100 / char.pools.life.max * char.pools.life.cur; life.toPrecision(2);
					var mana = 100 / char.pools.mana.max * char.pools.mana.cur; mana.toPrecision(2);
					var ap = 100 / char.pools.ap.max * char.pools.ap.cur; ap.toPrecision(2);
					
									
					life = life > 100 ? 100 : life;
					life = life < 0 ? 0 : life;
					mana = mana > 100 ? 100 : mana;
					mana = mana < 0 ? 0 : mana;
					ap = ap > 100 ? 100 : ap;
					ap = ap < 0 ? 0 : ap;
					
					var tokens = '';
					var special_tokens = '';
					
					char.tokens.forEach( function( token, i ){
						
						tokens += '<span style="color:'+getColor(token,lib.damage_types_all)+'" class="glyphicon glyphicon-asterisk" title="'+token+'" /></span>';
						
					});
	
					char.special_tokens.forEach( function( special_token, i ){
						
						special_tokens += '<span style="color:'+getColor(special_token,lib.special_token)+'" class="glyphicon glyphicon-asterisk" title="'+special_token+'" /></span>';
						
					});

					var states = '';

					if( char.states ){
				
						for( x=0,y=char.states.length;x<y;x++ ){
					
							states += 	'<a tabindex="0" role="button" class="btn btn-dark btn-outline-light" style="height:30px;margin:1px;"'+
								' title="'+char.states[x].name+'">'+
									char.states[x].name+
								'</a>';
					
						}

					}
	
					if( char.leftright == 'left' ){
						
						$('#left').append( `
							<div class="container-fluid player m-3 border border-white" data-char-id="${char.char_id}">
								<div class="row">
									<div class="col-10  openplayer">
										<h4 style="font-family:'Righteous', serif;display:inline;margin-right:20px;">${char.name}</h4> ${states}						
									</div>
									<div class="col special_tokens d-flex justify-content-end">
										${special_tokens}
									</div>
								</div>
								<div class="row" style="min-height:100px;">
									<div class="col-2 p-2 order-1  openplayer">
										<div class="align-self-center">
											
											<img src="server/php/files/thumbnail/${char.img_url}" alt="..." class="img-thumbnail">
											
										</div>
									</div>
									<div class="col-8 order-2 openplayer">
										
										<div class="col p-0 m-1 mt-2" style="border:1px solid #e5462d;">
											<div class="col m-0" style="background-image: linear-gradient(to right, #9e0c1d, #e5462d);width:`+life+`%;">
												<span class="text-white h5 text-nowrap">${char.pools.life.cur}/${char.pools.life.max} (${Math.round(life)}%)</span>
											</div>
										</div>
										<div class="col p-0 m-1" style="border:1px solid #4492e5;">
											<div class="col m-0" style="background-image: linear-gradient(to right, #1f1d47, #4492e5);width:`+mana+`%;">
												<span class="text-white h5 text-nowrap">${char.pools.mana.cur}/${char.pools.mana.max} (${Math.round(mana)}%)</span>
											</div>
										</div>
										<div class="col p-0 m-1" style="border:1px solid #5cce40;">
											<div class="col m-0" style="background-image: linear-gradient(to right, #34702c, #5cce40);width:`+ap+`%;">
												<span class="text-white h5 text-nowrap">${char.pools.ap.cur}/${char.pools.ap.max} (${Math.round(ap)}%)</span>
											</div>
										</div>
										
									</div>
									<div class="col-2 order-3">
										<div class="align-self-center">
											<form name="checkboxform" class="checkboxform">
												<input style="width:20px;height:20px;" type="checkbox" name="check" class="form-control" data-char-id="${char.char_id}" data-img-url="${char.img_url}" onchange="sel_players()"/>
											</form>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col tokens">
										${tokens}
									</div>
								</div>
						</div>
						`);
										
					}else{
						
						$('#right').append( `
							<div class="container-fluid player m-3 border border-white" data-char-id="${char.char_id}">
								<div class="row">
									<div class="col-10 openplayer">
										<h4 style="font-family:'Righteous', serif;display:inline;margin-right:20px;">${char.name}</h4> ${states}
									</div>
									<div class="col special_tokens d-flex justify-content-end">
										${special_tokens}
									</div>
								</div>
								<div class="row" style="min-height:100px;">
									<div class="col-2 p-2 order-2 openplayer">
										<div class="align-self-center">
											
											<img src="server/php/files/thumbnail/${char.img_url}" alt="..." class="img-thumbnail">
											
										</div>
									</div>
									<div class="col-8 order-3 openplayer">
										
										<div class="col p-0 m-1 mt-2" style="border:1px solid #e5462d;">
											<div class="col m-0" style="background-image: linear-gradient(to right, #9e0c1d, #e5462d);width:`+life+`%;">
												<span class="text-white h5 text-nowrap">${char.pools.life.cur}/${char.pools.life.max} (${Math.round(life)}%)</span>
											</div>
										</div>
										<div class="col p-0 m-1" style="border:1px solid #4492e5;">
											<div class="col m-0" style="background-image: linear-gradient(to right, #1f1d47, #4492e5);width:`+mana+`%;">
												<span class="text-white h5 text-nowrap">${char.pools.mana.cur}/${char.pools.mana.max} (${Math.round(mana)}%)</span>
											</div>
										</div>
										<div class="col p-0 m-1" style="border:1px solid #5cce40;">
											<div class="col m-0" style="background-image: linear-gradient(to right, #34702c, #5cce40);width:`+ap+`%;">
												<span class="text-white h5 text-nowrap">${char.pools.ap.cur}/${char.pools.ap.max} (${Math.round(ap)}%)</span>
											</div>
										</div>
										
									</div>
									<div class="col-2 order-1">
										<div class="align-self-center">
											<form name="checkboxform" class="checkboxform">
												<input style="width:20px;height:20px;" type="checkbox" name="check" class="form-control" data-char-id="${char.char_id}" data-img-url="${char.img_url}" onchange="sel_players()"/>
											</form>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col tokens">
										${tokens}
									</div>
								</div>
						</div>
						`);
					}

					
					
					$('.openplayer').unbind("click").click( function(){
	
						var char_id = $(this).parent().parent().data('char-id');
				
						tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);
				
					});
		
				},this);
				
			}
	
			tb_init('a.thickbox');
	
			if( char_id > 0 && pop_up == 0 ){
	
				tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);
				pop_up++;
	
			}
			
			if( game.current_round == 0 || Number.isInteger( game.current_round ) ){
				
				//Players turn
				$('#col_players').css({ 'outline': '3px inset white' });
				$('#col_dm').css({ 'outline': 'none' });
				
			}else{
				
				//DMs turn
				$('#col_players').css({ 'outline': 'none', });
				$('#col_dm').css({ 'outline': '3px inset white' });
			}
			
			$('#round_counter').text( game.current_round );
			
			$('#new_round').unbind("click").click( function(){
				
				$.get( 'setdata.php', { 'page':'new_round' }, function(z){
					
				});
				
			});
			
			$('#delete_game').unbind("click").click( function(){

				var r = confirm("Really delete?");

				if( r == true ){
				
					$.get( 'setdata.php', { 'page':'endgame' }, function(z){
						window.location.href = '<?=str_replace("index.php", "game.php", __ABSOLUTE_URL__ );?>';
					});

				}
				
			});

			$('#newfield').unbind("click").click( function(){
				
				$.get( 'setdata.php', { 'page':'newfield' }, function(z){
					
				});
				
			});

			$('#loadfield').unbind("click").click( function(){


				var field_id = $('#stored_fields').val();

				$.get( 'setdata.php?page=load_field', { field_id : field_id }, function( ret ){		
     				});

			});

			if( game.fields.length > 0 ){
				
				game.fields.forEach( function( field, index ){

					var targets = '';
					var states = '';
					var events = '';
					var cost_str = '';
					var closebutton = '';
					var savebutton = '';
					var costpaybutton = '';

					if( field.field_owner_id == 0 ){

						var owner_img_container = 	'<div style="border:1px dotted white;height:80px;" class="empty_owner" data-id="'+field.creation_date+'"></div>';

					}else{
						
						var img = chars_assoc[field.field_owner_id] ? chars_assoc[field.field_owner_id].img_url : '';
						
						var owner_img_container = '<div><div style="position:absolute;width:10px;height:10px;">'+
											'<button type="button" class="form-control btn"><img src="svg/si-glyph-delete.svg" style="height:10px;width:10px;" onclick="remove_field_owner('+field.creation_date+')"></button>'+	
									'</div><img src="server/php/files/thumbnail/'+img+'"  title="'+field.field_owner_id+'" alt="'+field.field_owner_id+'" class="img-thumbnail"></div>';

						var controller_user_id = chars_assoc[field.field_owner_id] ? chars_assoc[field.field_owner_id].controller_user_id : -1;

						if( controller_user_id == <?=$_SESSION['user_id'];?> ){

							if( field.field_cost_paid && field.field_cost_paid === false && field.cost.length > 0 ){

								alert( 'Upkeepkosten von Feld '+field.field_name+' bezahlen' );
								costpaybutton = '<a tabindex="0" role="button" class="btn alert alert-danger btn-outline-light" style="height:30px;margin:1px;" onclick="paycost('+field.creation_date+')">Pay cost</a>';

							}

						}

					}

					if( field.field_target_ids && field.field_target_ids.length > 0 ){

						field.field_target_ids.forEach( function( target, index ){
						
							var img = chars_assoc[target] ? chars_assoc[target].img_url : '';

							targets += 	'<div class="card" style="width: 5rem;float:left;">'+
										'<div style="position:absolute;width:10px;height:10px;">'+
											'<button type="button" class="form-control btn"><img src="svg/si-glyph-delete.svg" style="height:10px;width:10px;" onclick="remove_field_target('+field.creation_date+','+target+')"></button>'+	
										'</div>'+
										'<img src="server/php/files/thumbnail/'+img+'" title="'+target+'" alt="'+target+'" class="img-thumbnail">'+
									'</div>';

						});

					}

					if( field.field_status && field.field_status.length > 0 ){

						field.field_status.forEach( function( state_id, i ){


							var add_li = '';

						
							for( i=0,j=lib.states.length;i<j;i++ ){

								if( lib.states[i].state_id == state_id ){

									for( k=0,l=lib.states[i].vars.length;k<l;k++ ){

										add_li += '<li class=\'text-light\'>'+lib.states[i].vars[k].variable+' '+lib.states[i].vars[k].modifier+'</li>';

									}
									var state_name = lib.states[i].state_name;

								}

							}

							var content = '<ul class=\'poplist\'>'+
							add_li+' '+
								'<li class=\'text-danger\'><a href=\'#\' class=\'remove_state_from_field\' data-field-id=\''+field.creation_date+'\' data-state=\''+state_id+'\'>Remove state</a></li>'+
							'</ul>';
					
							states += 	'<a tabindex="0" role="button" class="btn btn-dark btn-outline-light" style="height:30px;margin:1px;"'+
							' data-toggle="popover" title="'+state_name+'" data-placement="bottom" data-content="'+content+'">'+
								state_name+
							'</a>';

						});

					}

					//if( field.field_status.length == 0 ){

						closebutton = '<button type="button" style="float:right;width:20px;" class="form-control btn" onclick="deletefield('+field.creation_date+')"><img src="svg/si-glyph-delete.svg" style="height:10px;width:10px;"></button>';

					//}

					savebutton = '<button type="button" style="float:right;width:20px;" class="form-control btn" onclick="savefield('+field.creation_date+')"><img src="svg/si-glyph-floppy-disk.svg" style="height:10px;width:10px;"></button>';

					if( field.field_events && field.field_events.length > 0 ){

						field.field_events.forEach( function( event, i ){

							var content = event.event_type+" <ul>";

							$.each( event.data, function( key, value ){

								content += '<li class=\'text-light\'>'+key+': '+value+'</li>';

							});

							content += '<li class=\'text-danger\'><a href=\'#\' class=\'show_event_from_field\' data-field-id=\''+field.creation_date+'\' data-event=\''+event.event_id+'\'><?=$l[$lang]['GAME_EVENT_SHOW'];?></a></li>'+
									'<li class=\'text-danger\'><a href=\'#\' class=\'remove_event_from_field\' data-field-id=\''+field.creation_date+'\' data-event=\''+event.event_id+'\'><?=$l[$lang]['GAME_EVENT_REMOVE'];?></a></li></ul>';

							events += 	'<a tabindex="0" role="button" class="btn btn-dark btn-outline-light" style="height:30px;margin:1px;"'+
									' data-toggle="popover" title="'+event.event_time+'" data-placement="bottom" data-content="'+content+'">'+
										event.event_time+' '+event.event_type+
									'</a>';


						});

					}

					if( field.cost && field.cost.length > 0 ){

					field.cost.forEach( function( cost, i ){

						cost_str += '<a tabindex="0" role="button" class="btn btn-dark btn-outline-light" style="height:30px;margin:1px;">'+
									cost.pool+' '+cost.value+
								'</a>';

					});	}
					
					cost_str += costpaybutton;

					$('#fields').append( `

						<div class="container-fluid player m-3 border border-white" data-id="${field.creation_date}">
							<div class="row">
								<div class="col">
									<h5 class="title" style="font-family:'Righteous', serif;"><span onclick="rename( ${field.creation_date},'${field.field_name}');">${field.field_name}</span></h5>
									<h5 style="font-size:9px;">field_start_round: ${field.field_start_round}</h5>
								</div>
								<div class="col ml-auto">
									${closebutton} ${savebutton}
								</div>
							</div>
							<div class="row">
								<div class="col-2">
									<div class="row">
										<h5 style="font-family:'Righteous', serif;"><?=$l[$lang]['GAME_FIELDS_OWNER'];?></h5>
									</div>
									<div class="row align-self-center">
										${owner_img_container}
									</div>
								</div>
								<div class="col-3">
									<div class="row">
										<h5 style="font-family:'Righteous', serif;"><?=$l[$lang]['GAME_FIELDS_TARGETS'];?></h5>
									</div>
									<div class="row">
										${targets}
										<div class="card target_add" data-id="${field.creation_date}" style="width: 5rem;float:left;border:1px dotted white;height:50px;">
										
										</div>
									</div>
								</div>
								<div class="col-3">
									<div class="row">
										<h5 style="font-family:'Righteous', serif;"><?=$l[$lang]['GAME_FIELDS_STATUS'];?></h5>&nbsp;
									
										<div style="width:150px;float:right;height:30px;margin-right:5px;">
											<select style="height:100%;background-color:buttonface" name="state_select" class="form-control state_select">
												<option value=""></option>
											</select>
										</div>
										<button type="button" class="btn btn-default btn-sm state_add_button" style="float:right;height:30px;" data-id="${field.creation_date}">
											<img src="svg/si-glyph-plus.svg" style="height:16px;width:16px;">
										</button>
									</div>
									<div class="row states">
										${states}

									</div>
								</div>
								<div class="col-3">
									<div class="row">
										<h5 style="font-family:'Righteous', serif;"><?=$l[$lang]['GAME_FIELDS_EVENTS'];?></h5>&nbsp;

										<button type="button" class="btn btn-default btn-sm event_add_button" style="float:right;height:30px;" data-id="${field.creation_date}">
											<img src="svg/si-glyph-plus.svg" style="height:16px;width:16px;">
										</button> ${events}
									</div>
									<div class="row">
										<h5 style="font-family:'Righteous', serif;"><?=$l[$lang]['GAME_FIELDS_UPKEEP'];?></h5>&nbsp;

										<button type="button" class="btn btn-default btn-sm event_add_cost" style="float:right;height:30px;" data-id="${field.creation_date}">
											<img src="svg/si-glyph-plus.svg" style="height:16px;width:16px;">
										</button> ${cost_str}
									</div>
								</div>
							</div>
						</div>

					`);

				});

				lib.states.forEach( function( state ){

		    				$('.state_select').append( '<option value="'+state.state_id+'">'+state.state_name+'</option>' );

	    			});

				$('.state_add_button').unbind("click").click( function(){

					var field_id = $(this).data('id');
					var state_id = $(this).parent().find('.state_select').val();

					$.get( 'setdata.php', { page : 'field_add_state', field_id : field_id, state_id : state_id }, function(){
					});

				});

				$('.event_add_button').unbind("click").click( function(){

					var field_id = $(this).data('id');

					tb_show( '','add_event.php?height=500&width=1000&field_id='+field_id);
					pop_up++;

				});

				$('.event_add_cost').unbind("click").click( function(){

					var field_id = $(this).data('id');

					tb_show( '','add_cost.php?height=500&width=1000&field_id='+field_id);
					pop_up++;

				});

				$('[data-toggle="popover"]').popover({html:true,trigger: 'focus'});

				$('[data-toggle="popover"]').on('shown.bs.popover', function () {

					$('a.remove_state_from_field').unbind("click").click( function(){
						
						var state_id = $(this).data('state');
						var field_id = $(this).data('field-id');
			
						$.get( 'setdata.php?page=field_del_state', { state_id : state_id, field_id : field_id }, function( ret ){
     						});

     					
					});


					$('a.show_event_from_field').unbind("click").click( function(){
						
						var field_id = $(this).data('field-id');
						var event_id = $(this).data('event');
			
						tb_show( '','add_event.php?height=500&width=1000&field_id='+field_id+'&event_id='+event_id);
						pop_up++;
     					
					});

					$('a.remove_event_from_field').unbind("click").click( function(){
						
						var field_id = $(this).data('field-id');
						var event_id = $(this).data('event');

						var r = confirm("Really remove?");

						if( r == true ){
			
							$.get( 'setdata.php?page=remove_field_event', { event_id : event_id, field_id : field_id }, function( ret ){
     							});

						}
     					
					});
					
					

				});


			}

			$('#pooltoken').html('');
			var i = 0;

			if( game.tokenpool && game.tokenpool.length > 0 ){

				game.tokenpool.forEach( function( token ){

					token = '<span style="color:'+getColor(token,lib.damage_types_all)+'" class="glyphicon glyphicon-asterisk" title="'+token+'" /></span>';
					$('#pooltoken').append( token);
					i++;

				});

			}

			$('#pooltoken').append( '('+i+')' );

			if( $('#delete_game').length > 0 ){

				$('#pooltoken').append( ' <span id="clear_tokenpool"><?=$l[$lang]['GAME_POOLTOKEN_DELETE'];?></span>' );

				$('#clear_tokenpool').unbind("click").click( function(){

					$.get( 'setdata.php?page=clear_tokenpool', function( ret ){
     					});

				});

			}
	
		});

	});

}

function add_char_to_game( char_id, leftright, username ){
	
	$.get( 'setdata.php', { 'page':'add_char_to_game', char_id : char_id, leftright : leftright }, function(r){
		game(current_game_id);
	});

}

<?

$navbar = '
		<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent2" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent2">
    <ul class="navbar-nav mr-auto">
    ';
    
$x=0;

foreach( $users AS $user ){
	
	$dm_user_id = isset( $game->dungeonmaster_user_id ) ? $game->dungeonmaster_user_id : -1;
	$dm = $user['user_id'] == $dm_user_id ? '<span style="color:orange;">(DM)</span>' : '';
	
	$navbar .= '
	<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown0'.$x.'" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span style="color:#'.$user['color'].'">'.$user['username'].' '.$dm.'
        </a>
        <div class="dropdown-menu h4" aria-labelledby="navbarDropdown0'.$x.'" id="'.$user['username'].'">
        </div>
      </li>
      ';
      $x++;
}

$navbar .= '
		</ul>
	<div id="special_buttons" style="width:152px;">
		<button type="button" class="form-control btn btn-dark" id="reset" style="float:left;width:75px;">'.$l[$lang]['GAME_RESET'].'</button>
		<button type="button" class="form-control btn btn-dark" id="delete_game" style="float:left;width:75px;">'.$l[$lang]['GAME_DELETE'].'</button>
	</div>
  </div>
</nav>
';


/*
$left = -150;
foreach( $users AS $user ){
	$left += 150;
	$dm_user_id = isset( $game->dungeonmaster_user_id ) ? $game->dungeonmaster_user_id : -1;
	$dm = $user['user_id'] == $dm_user_id ? '<span style="color:orange;">(DM)</span>' : '';
	echo "<div style='float:left;width:150px;'><a onclick=\"showchar('".$user['username']."')\" style='color:#".$user['color']."'>".$user['username']." ".$dm."</a>";
	echo "<div class='userchars invisible' style='position:absolute;top:20px;left:".$left."px;min-height:50px;border:1px solid white;background-color:black;width:150px;color:white;'><ul style='list-style-type:none;' id='".$user['username']."'></ul></div></div> ";
}

</div>
*/
?>

$('#player_bar').html(`<?=$navbar;?>`);


</script>
<?
if( isset( $_SESSION['current_game_id'] ) && $_SESSION['current_game_id'] != 0 ){
	
	?>
		<div class="row">
			<div class="col-sm">
				<div class="row align-items-start">
					<div id="col_players" class="col bg-success rounded-left" style="height:30px;margin:0px 2px 0px 15px">
						<h4 id="game_round" style="margin-top:3px;"><?=$l[$lang]['GAME_ROUND'];?> <span id="round_counter">0</span></h4>
					</div>
				</div>
				<div class="row" id="left">
		
				</div>
			</div>
			<div class="col-sm">
				<div class="row align-items-start">
					<div id="col_dm" class="col bg-danger rounded-right" style="height:30px;margin:0px 15px 0px 2px;">
						<div style="width:100px;float:right;"><button type="button" id="new_round" class="form-control btn btn-dark" style="margin-top:3px;"><?=$l[$lang]['GAME_NEXT_ROUND'];?></button></div>
					</div>
			  	</div>
				<div class="row" id="right">
		
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm">
				<div class="row align-items-start">
					<div class="col" style="height:30px;padding-top:30px;">
						<form class="form-inline">
							<button id="newfield" type="button" class="btn btn-info"><?=$l[$lang]['GAME_NEW_FIELD'];?></button>
							<div style="width:200px;">
								<select id="stored_fields" name="stored_fields" class="form-control" style="float:right;"></select>
							</div>
							<button id="loadfield" type="button" class="btn btn-info"><?=$l[$lang]['GAME_LOAD_FIELD'];?></button>
						</form>
					</div>
				</div>
				<div class="row">
					<div class="col" id="fields" style="margin-top:30px;">	
						
					</div>
				</div>
			</div>
		</div>
	

<?
	
	
	}else{
	if( $_SESSION['is_dm'] === true || ( $_SESSION['user_id'] == 1 && isset( $_SESSION['camp_id'] ) && $_SESSION['camp_id'] != 0 ) ){
		?>
		
		<div class="modal show">
		  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title"><?=$l[$lang]['GAME_TITLE_NEW_GAME'];?></h5>
		      </div>
		      <div class="modal-body text-white">
		        <?=$l[$lang]['GAME_MSG_NEW_GAME'];?><br />
					<br />
					<button type="button" class="form-control" id="newgame"><?=$l[$lang]['GAME_BUTTON_NEW_GAME'];?></button>
		      </div>
		      <div class="modal-footer">
		      </div>
		    </div>
		  </div>
		</div>
		<?
	}else{
	?>
	
	<div class="modal show">
		  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title"><?=$l[$lang]['GAME_TITLE_NEW_GAME'];?></h5>
		      </div>
		      <div class="modal-body text-white">
		        <?=$l[$lang]['GAME_MSG_NEW_GAME_2'];?>

			<br />
			<a href="campaign.php">Create a new campaign</a>
		      </div>
		      <div class="modal-footer">
		      </div>
		    </div>
		  </div>
		</div>
	<?
	}
}

//var_dump( $_SESSION );
include( 'footer.php' );