<?php
include('security.php');
include('language.php');
include('setdata.php');

$char_id = (int) $_GET['char_id'];
$targets = isset( $_GET['targets'] ) ? $_GET['targets'] : array();
$action = isset( $_GET['action'] ) ? $_GET['action'] : '';


$result = $db->query("SELECT * FROM game WHERE active=1 ");

foreach( $result AS $row ){

	$game = json_decode($row['data']);
	$_SESSION['current_game_id'] = $row['game_id'];

}

?>
<style type="text/css">
	li img{
		width: 16px;
		height: 16px;
	}
</style>

<script type="text/javascript">

var current_game_id = <?=$_SESSION['current_game_id'];?>;
var char_id = <?=$char_id;?>;
var action_name = '<?=$action;?>';
var this_action = '';
var char2 = 0;
var chars = [];
var return_tier = [];
var tier_use = [];
var selected_tier_index = 0;
var title = '';
var color = '';
var text = '';
var w_tier_lvl = 0;
var e_tier_lvl = 0;
var w_skill = 0;
var e_skill = 0;
var attribute = 0;
var m_skill = 0;
var uservars = [];
var cost_paid = false;

function getRandomInt(max) {
  
	return Math.floor(Math.random() * Math.floor(max));

}

function show_result( title, color, text ){
	
	$('#result').append(`
		<div class="card border-white m-3">
			<div class="card-header ${color} h5 text-white">
				${title}
			</div>
			<div class="card-body text-white">
				${text}
			</div>
		</div>
	`);
	
}


function showplayer( game_id ){

	$('#player_status').html('');
	$('#player_action').html('');
	$('#targets').html('');
	
	$.getJSON("getdata.php", {
        page: 'get_types'
    })
    .done(function(lib) {

	$.getJSON("getdata.php", {
		page: 'get_game',
		game_id : game_id
	})
	.done(function( game2 ) {
		
		game2.chars.forEach( function( char, index ){

			chars[char.char_id] = char;
			
			if( char_id == char.char_id ){
				
				char2 = char;

				life = 100 / char.pools.life.max * char.pools.life.cur; life.toPrecision(2);
				mana = 100 / char.pools.mana.max * char.pools.mana.cur; mana.toPrecision(2);
				ap = 100 / char.pools.ap.max * char.pools.ap.cur; ap.toPrecision(2);
				
				life = life > 100 ? 100 : life;
				life = life < 0 ? 0 : life;
				mana = mana > 100 ? 100 : mana;
				mana = mana < 0 ? 0 : mana;
				ap = ap > 100 ? 100 : ap;
				ap = ap < 0 ? 0 : ap;
				
				var tokens = '';
				var special_tokens = '';
				
				char.tokens.forEach( function( token, i ){
					
					tokens += '<span style="color:'+getColor(token,lib.damage_types_all)+'" class="glyphicon glyphicon-asterisk token" title="'+token+'" data-token="'+token+'"></span>';
					
				});

				char.special_tokens.forEach( function( special_token, i ){
					
					special_tokens += '<span style="color:'+getColor(special_token,lib.special_token)+'" class="glyphicon glyphicon-asterisk" title="'+special_token+'" data-token="'+special_token+'"/></span>';
					
				});
	
				$('#player_status').html( `
						<div class="container-fluid player m-3 border border-white" data-char-id="${char.char_id}">
							<div class="row">
								<div class="col special_tokens d-flex justify-content-end">
									${special_tokens}
								</div>
							</div>
							<div class="row">
								<div class="col  openplayer">
									<h4 style="font-family:'Righteous', serif;">${char.name}</h4>
								</div>
							</div>
							<div class="row" style="min-height:100px;">
								<div class="col-3 p-2 order-1  openplayer">
									<div class="align-self-center">
										
										<img src="server/php/files/thumbnail/${char.img_url}" alt="..." class="img-thumbnail">
										
									</div>
								</div>
								<div class="col-9 order-2 openplayer">
									
									<div class="col p-0 m-1 mt-2" style="border:1px solid #e5462d;">
										<div id="bar_lp" class="col m-0" style="background-image: linear-gradient(to right, #9e0c1d, #e5462d);width:`+life+`%;">
											<span class="text-white h5 text-nowrap">${char.pools.life.cur}/${char.pools.life.max} (${Math.round(life)}%)</span>
										</div>
									</div>
									<div class="col p-0 m-1" style="border:1px solid #4492e5;">
										<div id="bar_mana" class="col w-100 m-0" style="background-image: linear-gradient(to right, #1f1d47, #4492e5);width:`+mana+`%;">
											<span class="text-white h5">${char.pools.mana.cur}/${char.pools.mana.max} (${Math.round(mana)}%)</span>
										</div>
									</div>
									<div class="col p-0 m-1" style="border:1px solid #5cce40;">
										<div id="bar_ap" class="col w-100 m-0" style="background-image: linear-gradient(to right, #34702c, #5cce40);width:`+ap+`%;">
											<span class="text-white h5">${char.pools.ap.cur}/${char.pools.ap.max} (${Math.round(ap)}%)</span>
										</div>
									</div>
									
								</div>
							</div>
							<div class="row">
								<div class="col tokens">
									${tokens}
								</div>
							</div>
					</div>
					<div id="additional_tokens" class="container-fluid m-3 border border-white tokens" data-char-id="${char.char_id}" data-tokens="" style="min-height:20px;">
					</div>
					`);
					
				$('.token').draggable({revert: 'invalid'});
				$('#additional_tokens').droppable({
					
					drop: function( event, ui ) {
						
						if( $('#addcost').length > 0 ){
						
							
							$('#addcost').html( 'Additional cost: '+(($('#additional_tokens span').length+1)*2)+' Mana' );
							
						}else{
							
							$('#cost').append( '<span id="addcost" style="color:yellow;">Additional cost: 2 Mana</span>' );
							
						}
						
						var this_token = ui.draggable.detach();
						$('#additional_tokens').append( this_token );
						$('#additional_tokens span:last').css({left:'0px',top:'0px'});
					}
					
				});

				$.getJSON("getdata.php", {
					page: 'get_actions'
				}).done(function( g_actions ) {

					g_actions.forEach( function( action, index ){

						if( action.action_name == action_name ){

							this_action = action;
							
							$('#player_action').html( 

								'<h5><span id="action_name">'+action_name+'</span> (<span id="action_type">'+action.action_type+'</span>,<span id="action_fields"></span>)</h5><span class="text-white">'+
								action.description+ '</span> <span id="tier" style="text-align:center;color:white;"></span><br />' +
								'<div style="width:97%"><div id="tier_lvl_bar" class="row" style="text-align:center;"></div></div><br />'+
								'<div style="width:97%"><div id="cost" style="text-align:center;color:white;"></div></div><br />'+
								'<span id="hit_chance_formula" style="color:white;">'+action.hit_chance_formula+'</span>'
							);
							
							if( $('.checkboxform input:checked').length > 0 ){
								$('#player_action').append( '<button type="button" class="form-control" name="diceroll" id="diceroll">'+action_name+'</button>' );
							}else if( action.action_type == 'Diceroll' ){
								$('#player_action').append( '<button type="button" class="form-control" name="diceroll" id="diceroll">'+action_name+'</button>' );
							}else{
								$('#player_action').append( '<div class="alert alert-danger">No target selected</div>' );
							}
							
							if( action.action_type != 'Diceroll' ){

								$('.checkboxform input:checked').each( function(){
									var img = $(this).data("img-url");
									var target_char_id = $(this).data("char-id");
									
									$('#targets').append( '<div class="avatar" style="margin:5px;" data-char-id="'+target_char_id+'"><img src="server/php/files/thumbnail/'+img+'" class="img-thumbnail"/></div>' );
	
								});

								tier_use = get_tier_use( action, char2, return_tier );

								console.log( tier_use );

								if( tier_use.result != "" ){
								
									if( $('.checkboxform input:checked').length > 1 && tier_use.result != "" && action.tier_lvl[tier_use.result.index].target_area == 'Ein Feld' ){ 
									
										var txt = '<div class="alert alert-danger" role="alert">This action should have only one target! </div>';
										$('#player_action').append('<div class="player" style="height:50px;">'+txt+'</div>');
									
									
									}

								}
								

								if( tier_use.tiers.length > 0 ){

									tier_use.tiers.forEach( function( tier, i ){

										if( tier.tier_possible === true ){

											var sclass = 'alert-success';

										}else{

											var sclass = 'alert-danger';

										}

										$('#tier_lvl_bar').append( '<div class="col-sm '+sclass+'" title="'+tier.reason+'" id="tier_'+i+'" data-tier-id="'+i+'">'+action.tier_lvl[i].tier_lvl_name+'</div>');
										
									});

									$('#tier_lvl_bar div.alert-success').click( function(){

										$('#tier_lvl_bar div.alert-success').css({'text-decoration': 'none'});
										$(this).css({'text-decoration': 'underline'});
										
										selected_tier_index = $(this).data("tier-id");

										var cost_str = 'Level: '+action.tier_lvl[selected_tier_index].tier_lvl_name+', Cost: ';
										
										action.tier_lvl[selected_tier_index].cost.forEach( function( tier_cost, i ){
										
											cost_str += tier_cost.cost_type +" "+tier_cost.cost_value+", ";
										
										});

										action.tier_lvl[selected_tier_index].token_cost.forEach( function( tier_cost, i ){

											if( tier_cost.token_val && tier_cost.token_val > 0 ){

												for( x = 0, y = tier_cost.token_val; x<y; x++ ){

													cost_str += '<span style="color:'+getColor(tier_cost.token,lib.damage_types_all)+'" class="glyphicon glyphicon-asterisk" title="'+tier_cost.token+'"></span>';

												}

											}

											if( tier_cost.special_token_val && tier_cost.special_token_val > 0 ){

												for( x = 0, y = tier_cost.special_token_val; x<y; x++ ){

													cost_str += '<span style="color:'+getColor(tier_cost.special_token,lib.special_token)+'" class="glyphicon glyphicon-asterisk" title="'+tier_cost.special_token+'"></span>';

												}

											}

										});
									
										cost_str += 'Damage: ';
									
										action.tier_lvl[selected_tier_index].damage.forEach( function( tier_damage, i ){
										
											cost_str += tier_damage.damage_type+" "+tier_damage.formula+" auf "+tier_damage.affected_damage_pool+", ";
										
										});
										
										$('#cost').html( cost_str );

									});

								}else{

									$('#tier_lvl_bar').append( '<div class="col-sm alert-danger">Spell/Attack cannot be used</div>');

								}
								
								
								if( tier_use.result != '' ){
									

									var cost_str = 'Level: '+tier_use.result.tier_lvl_name+', Cost: ';
									/*
									action.tier_lvl[tier_use.result.index].cost.forEach( function( tier_cost, i ){

										char.cost_affection.forEach( function( cost_aff ){

											if( cost_aff.type == tier_cost.cost_type ){

												tier_cost.cost_value += " "+cost_aff.value;

											}

										});
										
										if( $.isNumeric( tier_cost.cost_value ) === false ){

											$.ajax({
        											type: 'GET',
        											url: 'setdata.php',
        											dataType: 'json',
        											success: function( res ) { tier_cost.cost_value = res.formula3; },
        											data: { 'page' : 'diceroll', char_id : char_id, dice_roll_formula : tier_cost.cost_value, info : { file : '<?= __FILE__;?>', line : '<?= __LINE__;?>' } },
        											async: false
    											});

											//tier_cost.cost_value = await_result();

										}
										
										tier_cost.cost_value = tier_cost.cost_value > 1 ? tier_cost.cost_value : 1;
										
										cost_str += tier_cost.cost_type +" <span id=\"cost_"+tier_cost.cost_type+"\">"+tier_cost.cost_value+"</span>, ";
										
									});
									*/

									tier_use.tiers[tier_use.result.index].tier_cost.forEach( function( tier_cost ){

										tier_cost.cost_value = tier_cost.cost_value > 1 ? tier_cost.cost_value : 1;
										cost_str += tier_cost.cost_type +" <span id=\"cost_"+tier_cost.cost_type+"\">"+tier_cost.cost_value+"</span>, ";

									});

									action.tier_lvl[selected_tier_index].token_cost.forEach( function( tier_cost, i ){

											if( tier_cost.token_val && tier_cost.token_val > 0 ){

												for( x = 0, y = tier_cost.token_val; x<y; x++ ){

													cost_str += '<span style="color:'+getColor(tier_cost.token,lib.damage_types_all)+'" class="glyphicon glyphicon-asterisk" title="'+tier_cost.token+'"></span>';

												}

											}

											if( tier_cost.special_token_val && tier_cost.special_token_val > 0 ){

												for( x = 0, y = tier_cost.special_token_val; x<y; x++ ){

													cost_str += '<span style="color:'+getColor(tier_cost.special_token,lib.special_token)+'" class="glyphicon glyphicon-asterisk" title="'+tier_cost.special_token+'"></span>';

												}

											}

										});
									
									cost_str += 'Damage: ';
									
									action.tier_lvl[tier_use.result.index].damage.forEach( function( tier_damage, i ){
										
										cost_str += tier_damage.damage_type+" "+tier_damage.formula+" on "+tier_damage.affected_damage_pool+", ";
										
									});

									$('#tier_'+tier_use.result.index).css({'text-decoration': 'underline'});
									selected_tier_index = tier_use.result.index;
									$('#cost').append( cost_str );
									
								}

								var stufe = [ "Not learned", "Normal", "Expert", "Master", "Grandmaster" ];


								if( tier_use.result.weapon && tier_use.result.weapon.weapon_name ){
									
									$('#tier').append( tier_use.result.weapon.weapon_name+" ("+tier_use.result.weapon.skill+") "+tier_use.result.weapon.weapon_tier_lvl+" " );
									w_tier_lvl = tier_use.result.weapon.weapon_tier_lvl;
									w_skill = tier_use.result.weapon.skill;
									$('#tier').append( "w_tier_lvl "+w_tier_lvl+", w_skill "+w_skill+" (Skill "+char2.skills.offensive[w_skill].skill+", Level "+stufe[char2.skills.offensive[w_skill].cur_lvl]+"), " );

								}

								if( tier_use.result.equipment && tier_use.result.equipment.item_name ){

									$('#tier').append( tier_use.result.equipment.item_name+" ("+tier_use.result.equipment.skill+") "+tier_use.result.equipment.item_tier_lvl+" " );
									e_tier_lvl = tier_use.result.equipment.item_tier_lvl;
									e_skill = tier_use.result.equipment.skill;
									$('#tier').append( "e_tier_lvl "+e_tier_lvl+", e_skill "+e_skill+", " );

								}

								attribute = 0;

							}
								
								action.attributes.forEach( function( attr ){
									
									if( char.attributes[attr].mod > attribute ){

										attribute = char.attributes[attr].mod;

									}

								});

								$('#tier').append( "Attributes "+attribute+", " );
								
								if( action.action_magic_type && action.action_magic_type != "" ){

									m_skill = action.action_magic_type;
									d_stufe = stufe ? stufe[char2.skills.magic_types[m_skill].cur_lvl] : 0;
									$('#tier').append( "m_skill "+m_skill+" (Skill "+char2.skills.magic_types[m_skill].skill+" Level "+d_stufe+"), " );

								}
								
								
							
								if( action.uservars && action.uservars.length > 0 ){
									
									action.uservars.forEach( function( elem ){
										
										uservars.push( elem );
										
									});
									
								}
							

						}

					});

					$('#diceroll').click( function(){

						$('#diceroll').prop('disabled', true);
						console.log( tier_use );
						if( tier_use && tier_use.tiers && tier_use.tiers[selected_tier_index] && tier_use.tiers[selected_tier_index].tier_possible == false ){

							var myconfirm = confirm("Attention: This action wouldn't be possible. Proceed anyway?");

							if( myconfirm == false ){

								return false;

							}

						}

						var hit_chance_formula = this_action.hit_chance_formula;
							
						var data = { w_tier_lvl : w_tier_lvl, e_tier_lvl : e_tier_lvl, attribute : attribute, w_skill : w_skill, e_skill : e_skill, m_skill : m_skill };
						
						if( uservars.length > 0 ){
							
							uservars.forEach( function( elem ){
								
								var number = Number(window.prompt(elem.description, elem.var_default_value));
								data[elem.var_name] = number;
								
							});
							
						}

						if( $('#action_type').text() == 'Diceroll' ){

							var target_char_id = null;
							var target_names = '';

							$('.checkboxform input:checked').each( function(){

								target_char_id = $(this).data("char-id");
								target_names += 'to '+$('div.container-fluid.player.m-3.border.border-white[data-char-id=\''+target_char_id+'\'] h4').text();
							});
							console.log( $('#player_status h4') );

							$.ajax({
        							type: 'GET',
        							url: 'chat.php',
        							dataType: 'post',
        							success: function() {},
        							data: { new_chattext : '<div class="alert alert-info" style="font-size:12px;">'+action_name+' from '+$('#player_status h4').text()+' '+target_names+'</div>', user_id : 0 },
        							async: false
    							});

							$.getJSON( 'setdata.php', { page : 'diceroll', char_id : char_id, target_char_id : target_char_id, dice_roll_formula : hit_chance_formula, data: data, writelog : true, info : { file : '<?= __FILE__;?>', line : '<?= __LINE__;?>' }, data : data }).done( function( res ){
								
								text = `
									<span style="color:yellow;">${res.formula1}</span>
									<span style="color:orange;">${res.formula2}</span>
									<span style="color:green;">${res.formula25}</span>
									<span style="color:#42f4f4;">${res.formula3}</span>
								`;
								
								if( res.formula3 == 0 ){

									title = 'Diceroll not successful!';
									color = 'alert-danger';

								}else if( res.formula3 == 1 ){

									 title = 'Diceroll successful!';
									 color = 'alert-success';

								}else{

									title = 'You rolled '+res.formula3+' ';
									color = 'alert-info';

								}

								show_result( title, color, text );
								$('#diceroll').prop('disabled', false);
								
							});
							
						}else{
							
							$('.checkboxform input:checked').each( function(){
										
								var img = $(this).data("img-url");
								var target_char_id = $(this).data("char-id");
								
								var txt = '<div class="alert alert-info" style="font-size:10px;"><span style="color:white;font-size:12px;">'+$('#action_type').text()+': '+action_name+' of '+char.name+' at '+chars[target_char_id].name+'</span></div>';
								$.post( 'chat.php', { new_chattext : txt, user_id : 0 } );


								if( $('#action_type').text() == 'Physical action' || ( $('#action_type').text() == 'Magical action' && $('#action_fields').text() == 'Single target' ) ){

									//Action can be tanked
									
									var tanker_char_id = false;

									if( chars[target_char_id].states.length > 0 ){

										chars[target_char_id].states.forEach( function( state, index ){

											if( state.name == 'Tanked' && state.tank.tank_char_id != char_id ){

												tanker_char_id = state.tank.tank_char_id;

											}

										});

									}
									
									if( tanker_char_id !== false ){
										
										var random_int = getRandomInt(100);

										if( random_int <= 80 ){
 											console.log( "80% chance of success" );
											//80% chance of success

											var txt = '<div class="alert alert-success" role="alert">Character '+chars[target_char_id].name+' is tanked by '+chars[tanker_char_id].name+' </div>';
											title = 'Tanking successful';
											color = 'alert-success';
											text = 'Character '+chars[target_char_id].name+' is now tanked by '+chars[tanker_char_id].name+' ';
										
											$('#targets div.avatar[data-char-id='+target_char_id+']').css({'background-color':'#8b0000'});

											var tanked_char_id = target_char_id;
											target_char_id = tanker_char_id;
											img = chars[target_char_id].img_url;
											

											$('#targets div.avatar[data-char-id='+tanked_char_id+']').after( '<div class="avatar" style="margin:5px;" data-char-id="'+target_char_id+'"><img src="server/php/files/thumbnail/'+img+'" class="img-thumbnail"/></div>' );
											txt += '<span style="color:yellow;">'+random_int+' <= 80</span><br />';
											text += '<div style="color:yellow;">'+random_int+' <= 80</div>';
											
										}else{

											var txt = '<div class="alert alert-info" role="alert">Character '+chars[target_char_id].name+' is not tanked </div>';
											txt += '<span style="color:yellow;">'+random_int+' > 80</span><br />';
											
											title = 'Tanking unsuccessful';
											color = 'alert-warning';
											text = 'Character '+chars[target_char_id].name+' is not tanked';
											text += '<div style="color:yellow;">'+random_int+' > 80</div>';
											
										}

										show_result( title, color, text );
										$.post( 'chat.php', { new_chattext : '<div style="font-size:10px;">'+txt+'</div>', user_id : 0 } );


									}	

								}

								$.ajax({
        								type: 'GET',
        								url: 'setdata.php',
        								dataType: 'post',
        								success: function() {},
        								data: { page : 'trigger_attack', char_id : char_id, target_char_id : target_char_id, action_type : $('#action_type').text(), info : { file : '<?= __FILE__;?>', line : '<?= __LINE__;?>' } },
        								async: false
    								}); //trigger_attack before attack

								$.getJSON( 'setdata.php', { page : 'diceroll', char_id : char_id, dice_roll_formula : hit_chance_formula, info : { file : '<?= __FILE__;?>', line : '<?= __LINE__;?>' }, target_char_id : target_char_id, data : data }).done( function( res ){

								

									switch( $('#action_type').text() ){
										
										case 'Physical action':
										case 'Magical action':
										case 'Potion_Item':
										case 'Class ability':
										
											if( res.formula3 == 0 ){
			
												var title = 'Target not hit!';
												color = 'alert-danger';
												var text = '<span style="color:white;">Target not hit!</span>';
			
											}else if( res.formula3 == 1 ){
														
												var title = 'Target hit!';
												color = 'alert-success';
												var text = '<span style="color:white;">Target hit!</span>';
			
											}
											
											text2 = '<div style="color:yellow;">'+res.formula1+'</div>'+
											'<div style="color:orange;">'+res.formula2+'</div>'+
											'<div style="color:green;">'+res.formula25+'</div>'+
											'<div style="color:#42f4f4;">'+res.formula3+'</div>';
											
											text += ' (<span style="color:yellow;">'+res.formula1+' => </span>'+
											'<span style="color:orange;">'+res.formula2+' => </span>'+
											'<span style="color:green;">'+res.formula25+' => </span>'+
											'<span style="color:#42f4f4;">'+res.formula3+' </span>)';
			
											show_result( title, color, text2 );
											
											
											
											//$.post( 'chat.php', { new_chattext : '<div style="font-size:10px;">'+text+'</div>', user_id : 0 } );

											$.ajax({
        											type: 'GET',
        											url: 'chat.php',
        											dataType: 'post',
        											success: function() {},
        											data: { new_chattext : '<div style="font-size:10px;">'+text+'</div>', user_id : 0 },
        											async: false
    											});
											
											if( res.formula3 == 1 ){ //Make damage!!

												//selected_tier_index = [0,1,2,3,4]
												
												var add_tokens = [];
												
												$('#additional_tokens span').each( function(){
													
													add_tokens.push( $(this).data("token") );
													
												});
												
												

												$.getJSON( 'setdata.php', { page : 'mkdamage', char_id : char_id, target_char_id : target_char_id, action_name : action_name, tier_lvl_index : selected_tier_index, add_tokens : add_tokens, cost_paid : cost_paid, info : { file : '<?= __FILE__;?>', line : '<?= __LINE__;?>' }, data : data }).done( function( dmg_result ){
													
													var txt = '';
													
													dmg_result.physical.forEach( function( dmg, i ){
														
														var skip_resistance = dmg.skip_resistance == "true" ? '(Damage skips resistances)' : '';
														txt += dmg.type+" ["+dmg.formula+"] auf "+dmg.affected_damage_pool+" "+skip_resistance+"<br />";
														txt += '<span style="color:yellow;">'+dmg.calc.formula1+'</span><br /><span style="color:orange;">'+dmg.calc.formula2+'</span><br /><span style="color:green;">'+dmg.calc.formula3+'</span><br /><br />';
													
														
													});
													
													dmg_result.magical.forEach( function( dmg, i ){
														
														var skip_resistance = dmg.skip_resistance == "true" ? '(Damage skips resistances)' : '';
														txt += dmg.type+" ["+dmg.formula+"] auf "+dmg.affected_damage_pool+" "+skip_resistance+"<br />";
														txt += '<span style="color:yellow;">'+dmg.calc.formula1+'</span><br /><span style="color:orange;">'+dmg.calc.formula2+'</span><br /><span style="color:green;">'+dmg.calc.formula3+'</span><br /><br />';
													
														
													});
													
													//$('#result').append('<div class="player" style="height:auto"><div class="alert alert-success" role="alert">Damage</div>'+txt+'</div>');
													show_result( 'Damage', 'alert-success', txt );
													//$.post( 'chat.php', { new_chattext : '<div style="font-size:10px;color:white;">'+txt+'</div>', user_id : 0 } );
													
													show_result( 'Text', 'alert-info', this_action.tier_lvl[selected_tier_index].message_after_attack );
													$.post( 'chat.php', { new_chattext : '<div style="font-size:10px;">'+this_action.tier_lvl[selected_tier_index].message_after_attack+'</div>', user_id : 0 } );


													$('#player_status').html('');
													$('#player_action').html('');
													$('#targets').html('');
													showplayer( current_game_id, char_id );
												});

												cost_paid = true;

											}else{ console.log('payonly?');
												
												$.getJSON( 'setdata.php', { page : 'mkdamage', char_id : char_id, target_char_id : target_char_id, action_name : action_name, tier_lvl_index : selected_tier_index, add_tokens : add_tokens, cost_paid : cost_paid, payonly : true, info : { file : '<?= __FILE__;?>', line : '<?= __LINE__;?>' } }).done( function( dmg_result ){

												});

												if( cost_paid != true ){
													showplayer( current_game_id, char_id );
												}
												
											}
				
										break;
										
										case 'Other_action':
											
											if( res.formula3 == 0 ){
			
												title = 'Diceroll unsuccessful!';
												color = 'alert-danger';
												
												
			
											}else if( res.formula3 == 1 ){
												
												if( $('#diceroll').text() == 'Tank' || $('#diceroll').text() == 'Tanken' ){
													
													$.getJSON("setdata.php", {
														page: 'tank_player',
														tank_char_id : char_id,
														tanked_char_id : target_char_id,
														info : { file : '<?= __FILE__;?>', line : '<?= __LINE__;?>' }
													}).done(function() {});
													
												}
												
												title = 'Diceroll successful!';
												color = 'alert-success';
			
											}else{
			
												title = 'You rolled '+res.formula3+' ';
												color = 'alert-info';
			
											}
											
											text += '<div style="color:yellow;">'+res.formula1+'</div>'+
											'<div style="color:orange;">'+res.formula2+'</div>'+
											'<div style="color:green;">'+res.formula3+'</div>';
			
											show_result( title, color, text );
											$.post( 'chat.php', { new_chattext : '<div style="font-size:10px;">'+txt+'</div>', user_id : 0 } );
											
											show_result( 'Text', 'alert-info', this_action.tier_lvl[selected_tier_index].message_after_attack );
											$.post( 'chat.php', { new_chattext : '<div style="font-size:10px;">'+this_action.tier_lvl[selected_tier_index].message_after_attack+'</div>', user_id : 0 } );
										
											
											if( cost_paid != true ){
												showplayer( current_game_id, char_id );
											}
										break;
									}

								});

							});
							
						}

						cost_paid = false;
						$('#diceroll').prop('disabled', false);

					});
					
				});
				
			}
				
		});
			
	});
	
});
		
}

$(document).ready( function(){

	showplayer( current_game_id, char_id );
	$('#TB_ajaxContent').css({width:'100%'});

	$.ajaxSetup({async:false});

	
});



</script>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm" id="player_status">
		</div>
		<div class="col-sm">	
			<div class="container m-3 border border-white" id="player_action">
			</div>
		</div>
	</div>
	<div class="row" id="targets">
	</div>
	<div class="row" id="result">
	</div>
</div>