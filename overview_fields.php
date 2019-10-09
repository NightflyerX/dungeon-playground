<?php
include('security.php');
include('language.php');
include('header.php');
?>
<style type="text/css">

#overview_container{
	background-color: rgba(0, 0, 0, 0.7);
}

.container{
	margin: 10px;
}

.container .title{
	background-color: #2A9FD6;
}

.container .tier_title{
	background-color: #42b3f5;
}


	.round{
		width: 25px;
		height:20px;
		margin: 1px;
		text-align: center;
	}

	.timeline .players{
		border: 1px solid green;

	}
	.timeline .dm{
		border: 1px solid red;
	}

	.selected{
		background-color: #666;
	}
	#myTabContent{
		border:1px solid white;
	}
	.action_type{
		width:150px;
	}


</style>

<div id="fields" class="container-fluid">
	
</div>

<script>

var chars = [];
var pop_up = 0;
var dm = 'all';
var start_shift = 0;
var end_shift = 1000;
var x_rounds = 1;
var start_round = 0;
var data = [];
var event_type = '';

function timeline( tl_name ){
	
	$.getJSON( 'setdata.php?page=timeline', { dm : dm, start_shift : start_shift, end_shift : end_shift, x_rounds : x_rounds, current_round : 0 }, function( timeline_array ){
		
		timeline_array.forEach( function( round, i ){
			
			if( round == true ){
				
				$('#'+tl_name+' .round').eq(i).addClass( 'selected' );
				
			}else{
				
				$('#'+tl_name+' .round').eq(i).removeClass( 'selected' );
				
			}
			
		});
		
	});

}

$(document).ready( function(){

	$.getJSON("getdata.php", { page: 'get_char' }).done(function(chars) {
	
	$.getJSON("getdata.php", { page: 'get_types' }).done(function(lib) {

		$.getJSON("getdata.php", { page: 'get_fields' }).done(function( fields, y ) {

			console.log( fields );

			fields.forEach( function( field, z ){

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
						
						var img = '';
						
						var owner_img_container = '<div><div style="position:absolute;width:10px;height:10px;">'+	
									'</div>'+field.field_owner_id+'</div>';

					}

					if( field.field_target_ids && field.field_target_ids.length > 0 ){

						field.field_target_ids.forEach( function( target, index ){
						
							var img = '';

							targets += 	'<div class="card" style="width: 5rem;float:left;">'+
										target+
									'</div>';

						});

					}
					
					if( field.field_status && field.field_status.length > 0 ){

						field.field_status.forEach( function( state_id, i ){

							var add_li = '';

						
							for( i=0,j=lib.states_effects2.length;i<j;i++ ){

								if( lib.states_effects2[i].state_id == state_id ){

									add_li += '<li class=\'text-light\'>'+lib.states_effects2[i].variable+' '+lib.states_effects2[i].modifier+'</li>';
									var state_name = lib.states_effects2[i].state_name;

								}

							}

							var content = '<ul class=\'poplist\'>'+
							add_li+' '+
								'<li class=\'text-danger\'><a href=\'#\' class=\'remove_state_from_field\' data-field-id=\''+field.creation_date+'\' data-state=\''+state_id+'\'>Status entfernen</a></li>'+
							'</ul>';
					
							states += 	'<a tabindex="0" role="button" class="btn btn-dark btn-outline-light" style="height:30px;margin:1px;"'+
							' data-toggle="popover" title="'+state_name+'" data-placement="bottom" data-content="'+content+'">'+
								state_name+
							'</a>';

								

						});

					}

					savebutton = '<button type="button" style="float:right;width:20px;" class="form-control btn" onclick="savefield('+field.creation_date+')"><img src="svg/si-glyph-floppy-disk.svg" style="height:10px;width:10px;"></button>';

					if( field.field_events && field.field_events.length > 0 ){

						field.field_events.forEach( function( event, i ){

							var only_trigger_target_is_target = event.data.only_trigger_target_is_target == 'true' ? 	'<span style="color:#63f542">true</span>' : '<span style="color:red">false</span>';
							var field_target_is_action_target = event.data.field_target_is_action_target == 'true' ? 	'<span style="color:#63f542">true</span>' : '<span style="color:red">false</span>';
							var action_target_is_field_owner  = event.data.action_target_is_field_owner == 'true' ? 	'<span style="color:#63f542">true</span>' : '<span style="color:red">false</span>';
							var action_target_is_action_exec = event.data.action_target_is_action_exec == 'true' ? 		'<span style="color:#63f542">true</span>' : '<span style="color:red">false</span>';
							
							var trigger_action = '';

							if( !event.data.trigger_action ){

								trigger_action = '<span style="color:#63f542">always</span>';

							}else{

								event.data.trigger_action.forEach( function( action ){

									trigger_action += '<span style="color:#42f5f2">'+action+'</span>';

								});

							}

							var timelinestring = '';

							for( x = 0, y = x+20; x<y; x=x+0.5 ){

								if( x == 0 || Number.isInteger( x ) ){

									timelinestring += '<div class="timeline round players">'+x+'</div>';

								}else{

									timelinestring += '<div class="timeline round dm">'+x+'</div>';

								}

							}

							events += `

							<div class="col-3">
								<ul>
									<li>${event.event_time}</h5>
									<li>triggerer_restriction: <span style="color:#4290f5">${event.data.triggerer_restriction}</span></li>
									<li>only_trigger_target_is_target: ${only_trigger_target_is_target}</li>
									<li>field_target_is_action_target: ${field_target_is_action_target}</li>
									<li>action_target_is_field_owner: ${action_target_is_field_owner}</li>
									<li>action_target_is_action_exec: ${action_target_is_action_exec}</li>
									<li>trigger_action: ${trigger_action}</li>
								</ul>
							</div>
							<div class="col-9 event_container" id="event_container_${y}_${z}">


<h5>Betroffene Runden</h5>
	<div class="col">
		<label for="dm_${y}_${z}">Filter</label>
		<select name="dm_${y}_${z}" id="dm_${y}_${z}">
			<option value="all">Alle Runden</option>
			<option value="false">Nur Spieler-Runden</option>
			<option value="true">Nur DM-Runden</option>
		</select>
		<label for="start_shift_${y}_${z}">Startverz&ouml;gerung</label>
		<select name="start_shift_${y}_${z}" id="start_shift_${y}_${z}">
			<option value="0">0</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
		</select>
		<label for="end_shift_${y}_${z}">Ende</label>
		<select name="end_shift_${y}_${z}" id="end_shift_${y}_${z}">
			<option value="1000">1000</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
		</select>
		<label for="x_rounds_${y}_${z}">Alle x Runden</label>
		<select name="x_rounds_${y}_${z}" id="x_rounds_${y}_${z}">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
		</select>			
	</div>
	<div id="timeline_${y}_${z}" class="d-flex flex-row">
		${timelinestring}
	</div>

<br />
<br />

<ul class="nav nav-tabs add_event_tabs" id="add_event_tabs_${y}_${z}" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="tab-1-tab_${y}_${z}" data-toggle="tab" href="#tab1_${y}_${z}" role="tab" aria-controls="tab1_${y}_${z}" aria-selected="true">Add special token</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="tab-2-tab_${y}_${z}" data-toggle="tab" href="#tab2_${y}_${z}" role="tab" aria-controls="tab2_${y}_${z}" aria-selected="false">Add normal token</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="tab-3-tab_${y}_${z}" data-toggle="tab" href="#tab3_${y}_${z}" role="tab" aria-controls="tab3_${y}_${z}" aria-selected="false">Add damage</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="tab-4-tab_${y}_${z}" data-toggle="tab" href="#tab4_${y}_${z}" role="tab" aria-controls="tab4_${y}_${z}" aria-selected="false">Summon</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="tab-5-tab_${y}_${z}" data-toggle="tab" href="#tab5_${y}_${z}" role="tab" aria-controls="tab5_${y}_${z}" aria-selected="false">Status</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent_${y}_${z}">
	<div class="tab-pane fade show active" id="tab1_${y}_${z}" role="tabpanel" aria-labelledby="tab-1-tab_${y}_${z}">
		<div class="container-fluid player">
			<div class="row">
				<div class="col">
					<div class="row">
						<h5>Chance</h5>
					</div>
					<div class="row">
						<textarea name="special_token_chance" class="form-control">100%</textarea>
					</div>
				</div>
				<div class="col">
					<div class="row">
						<h5>Token</h5>
					</div>
					<div class="row">
						<select  class="special_token_token" name="special_token_token" id="special_token_token" class="form-control"></select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="tab2_${y}_${z}" role="tabpanel" aria-labelledby="tab-2-tab_${y}_${z}">
		<div class="container-fluid player">
			<div class="row">
				<div class="col">
					<div class="row">
						<h5>Chance</h5>
					</div>
					<div class="row">
						<textarea name="normal_token_chance" class="form-control">100%</textarea>
					</div>
				</div>
				<div class="col">
					<div class="row">
						<h5>Token</h5>
					</div>
					<div class="row">
						<select  class="normal_token_token" name="normal_token_token" id="normal_token_token" class="form-control"></select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="tab3_${y}_${z}" role="tabpanel" aria-labelledby="tab-3-tab_${y}_${z}">
		<div class="container-fluid player">
			<div class="row">
				<div class="col">
					<div class="row">
						<h5>Chance</h5>
					</div>
					<div class="row">
						<textarea name="add_damage_chance" class="form-control">100%</textarea>
					</div>
				</div>
				<div class="col">
					<div class="row">
						<h5>Schadenstyp</h5>
					</div>
					<div class="row">
						<select name="add_damage_type" class="add_damage_type" id="add_damage_type" class="form-control"></select>
					</div>
				</div>
				<div class="col">
					<div class="row">
						<h5>Schadensformel</h5>
					</div>
					<div class="row">
						<textarea name="add_damage_formula" class="form-control"></textarea>
					</div>
				</div>
				<div class="col">
					<div class="row">
						<h5>damage_heal</h5>
					</div>
					<div class="row">
						<select name="add_damage_damage_heal" class="form-control"><option value="damage">damage</option><option value="heal">heal</option></select>
					</div>
				</div>
				<div class="col">
					<div class="row">
						<h5>affected damage pool</h5>
					</div>
					<div class="row">
						<select  class="add_damage_affected_pool" name="add_damage_affected_pool" id="add_damage_affected_pool" class="form-control"></select>
					</div>
				</div>
				<div class="col">
					<div class="row">
						<h5>skip resistance</h5>
					</div>
					<div class="row">
						<select name="add_damage_skip_resistance" class="form-control"><option value="false">false</option><option value="true">true</option></select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="tab4_${y}_${z}" role="tabpanel" aria-labelledby="tab-4-tab_${y}_${z}">
		<div class="container-fluid player">
			<div class="row">
				<div class="col">
					<div class="row">
						<h5>Chance</h5>
					</div>
					<div class="row">
						<textarea name="summon_chance" class="form-control">100%</textarea>
					</div>
				</div>
				<div class="col">
					<div class="row">
						<h5>Char</h5>
					</div>
					<div class="row">
						<select  class="summon_char" name="summon_char" id="summon_char" class="form-control"></select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="tab5_${y}_${z}" role="tabpanel" aria-labelledby="tab-5-tab_${y}_${z}">
		<div class="container-fluid player">
			<div class="row">
				<div class="col">
					<div class="row">
						<h5>Chance</h5>
					</div>
					<div class="row">
						<textarea name="state_chance" class="form-control">100%</textarea>
					</div>
				</div>
				<div class="col">
					<div class="row">
						<h5>Add/rem</h5>
					</div>
					<div class="row">
						<select  class="state_addremove" name="state_addremove" class="form-control"><option value="add">add</option><option value="remove">remove</option></select>
					</div>
				</div>
				<div class="col">
					<div class="row">
						<h5>Status</h5>
					</div>
					<div class="row">
						<select class="state_id" name="state_id" id="state_id" class="form-control"></select>
					</div>
				</div>
				<div class="col">
					<div class="row">
						<h5>Runden</h5>
					</div>
					<div class="row">
						<input type="text" name="rounds" value="99" class="form-control" />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

								</div>
							`;

							dm = event.timelinedata.dm;
							start_shift = event.timelinedata.start_shift;
							end_shift = event.timelinedata.end_shift;
							x_rounds = event.timelinedata.x_rounds;

							data = event.data;
							event_type = event.event_type;

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
								</div>
							</div>
							<div class="row">
								<div class="col-1">
									<div class="row">
										<h5 style="font-family:'Righteous', serif;">Besitzer</h5>
									</div>
									<div class="row align-self-center">
										${owner_img_container}
									</div>
								</div>
								<div class="col-1">
									<div class="row">
										<h5 style="font-family:'Righteous', serif;">Ziele</h5>
									</div>
									<div class="row">
										${targets}
									</div>
								</div>
								<div class="col-1">
									<div class="row">
										<h5 style="font-family:'Righteous', serif;">Status</h5>&nbsp;
									
										<div style="width:150px;float:right;height:30px;margin-right:5px;">
											<select style="height:100%;background-color:buttonface" name="state_select" class="form-control state_select">
												<option value=""></option>
											</select>
										</div>
									</div>
									<div class="row states">
										${states}

									</div>
								</div>
								<div class="col-1">
									<div class="row">
										<h5 style="font-family:'Righteous', serif;">Upkeep-Kosten</h5>&nbsp;

										${cost_str}
									</div>
								</div>
								
								<div class="col-8">
									<div class="row">
										${events}
									</div>
								</div>
							</div>
						</div>

					`);

					

					timeline( 'timeline_'+y+'_'+z );
					$('#dm_'+y+'_'+z).val( dm );
					$('#start_shift_'+y+'_'+z).val( start_shift );
					$('#end_shift_'+y+'_'+z).val( end_shift );
					$('#x_rounds_'+y+'_'+z).val( x_rounds );
					$('.add_event_tabs:last li a:contains('+event_type+')').click();
					$('.add_event_tabs:last li a:contains('+event_type+')').unbind("click");
					
	lib.events.forEach( function( event ){

		$('.select_event_time:last').append( '<option value="'+event.event+'">'+event.event+'</option>' );

	});

	lib.special_token.forEach( function( token ){

		$('.special_token_token:last').append( '<option value="'+token.name+'">'+token.name+'</option>' );

	});

	lib.damage_types_all.forEach( function( token ){

		$('.normal_token_token:last').append( '<option value="'+token.name+'">'+token.name+'</option>' );
		$('.add_damage_type:last').append( '<option value="'+token.name+'">'+token.name+'</option>' );

	});

	lib.pools.forEach( function( pool ){

		$('.add_damage_affected_pool:last').append( '<option value="'+pool.name+'">'+pool.name+'</option>' );

	});

	lib.action_types.forEach( function( action_type, i ){

		$('.action_types:last').append( '<input type="checkbox" class="form-control" name="trigger_action_'+i+'" value="'+action_type.name+'" id="'+action_type.name+'" /><label for="'+action_type.name+'" class="action_type">'+action_type.name+'</label>' );

	});


	lib.states_effects2.forEach( function( state ){

		$('.state_id:last').append( '<option value="'+state.state_id+'">'+state.state_name+' ('+state.variable+':'+state.modifier+')</option>' );

	});

	chars.forEach( function( char ){

		$('.summon_char:last').append( '<option value="'+char.name+'">'+char.name+'</option>' );

	});
				
					$.each( data, function( key, value ){

						$('#event_container_'+y+'_'+z+' textarea[name='+key+'],#myTabContent_'+y+'_'+z+' input[name='+key+'],#myTabContent_'+y+'_'+z+' select[name='+key+']').val( value );

					});
					
				});

				var ids = [];

				lib.states_effects2.forEach( function( effect ){

					if( $.inArray( effect.state_id, ids ) == -1 ){

		    				$('.state_select').append( '<option value="'+effect.state_id+'">'+effect.state_name+'</option>' );
						ids.push( effect.state_id );

					}

	    			});

			$('select').attr('disabled','disabled');
			$('input,textarea').attr('readonly','readonly');	
			
		});

	}); });

	
});


</script>



<?