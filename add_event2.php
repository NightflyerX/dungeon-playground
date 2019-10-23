<?php

include('security.php');
include('language.php');

$field_id = (int) $_GET['field_id'];
$event_id = isset( $_GET['event_id'] ) ? (int) $_GET['event_id'] : 0;

?>

<style>

	.round{
		width: 25px;
		height:20px;
		margin: 1px;
		text-align: center;
	}

	#timeline .players{
		border: 1px solid green;

	}
	#timeline .dm{
		border: 1px solid red;
	}

	#timeline .selected{
		background-color: #666;
	}
	#myTabContent{
		border:1px solid white;
	}
	.action_type{
		width:150px;
	}

</style>

<div id="new_event_container" style="color:white;">

<h3 id="event_title">Add event to field :<?=$field_id;?></h3>
<br />

<form id="new_event_form">

	<div class="form-group">
	
		<label for="select_event_time">Event-time</label>
		<select class="form-control" name="select_event_time" id="select_event_time">
		</select>

	</div>

	<div class="form-group" id="hidden_form_group" style="display:none;">

		<div class="form-check form-check-inline">
			<input type="radio"  class="form-check-input" name="triggerer_restriction" value="always" checked="checked">
			<label for="triggerer_restriction" class="action_type form-check-label">Event activates always</label>

			<input type="radio"  class="form-check-input" name="triggerer_restriction" value="only_trigger_is_owner">
			<label for="triggerer_restriction" class="action_type form-check-label">Activates by the field owner only</label>

			<input type="radio"  class="form-check-input" name="triggerer_restriction" value="only_trigger_is_target">
			<label for="triggerer_restriction" class="action_type form-check-label">Activates by the field target(s) only</label>

			<input type="checkbox"  class="form-check-input" name="only_trigger_target_is_target" value="true">
			<label for="only_trigger_target_is_target" class="action_type form-check-label">Activates only if action target is also field target</label>

			<input type="checkbox"  class="form-check-input" name="field_target_is_action_target" value="true">
			<label for="field_target_is_action_target" class="action_type form-check-label">Field target becomes action target</label>

			<input type="checkbox"  class="form-check-input" name="action_target_is_field_owner" value="true">
			<label for="action_target_is_field_owner" class="action_type form-check-label">Field owner becomes action target</label>

			<input type="checkbox"  class="form-check-input" name="action_target_is_action_exec" value="true">
			<label for="action_target_is_action_exec" class="action_type form-check-label">Action executioner becomes field target</label>

		</div>

		<div class="form-check form-check-inline" id="action_types">
		</div>

	</div>


	<h5>Affected rounds</h5>
	<div class="col">
		<label for="dm">Filter</label>
		<select name="dm" id="dm">
			<option value="all">Alle Rounds</option>
			<option value="false">Only player rounds</option>
			<option value="true">Only DM rounds</option>
		</select>
		<label for="start_shift">Event start shilft</label>
		<select name="start_shift" id="start_shift">
			<option value="0">0</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
		</select>
		<label for="end_shift">End shift</label>
		<select name="end_shift" id="end_shift">
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
		<label for="x_rounds">All x Rounds</label>
		<select name="x_rounds" id="x_rounds">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
		</select>			
	</div>
	<div id="timeline" class="d-flex flex-row">
	</div>
	<div id="timeline2" class="d-flex flex-row">

	</div>

<br />
<br />

<ul class="nav nav-tabs" id="add_event_tabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="tab-1-tab" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">Add special token</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="tab-2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">Add normal token</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="tab-3-tab" data-toggle="tab" href="#tab3" role="tab" aria-controls="tab3" aria-selected="false">Add damage</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="tab-4-tab" data-toggle="tab" href="#tab4" role="tab" aria-controls="tab4" aria-selected="false">Summon</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="tab-5-tab" data-toggle="tab" href="#tab5" role="tab" aria-controls="tab5" aria-selected="false">Status</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
	<div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab-1-tab">
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
						<select name="special_token_token" id="special_token_token" class="form-control"></select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab-2-tab">
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
						<select name="normal_token_token" id="normal_token_token" class="form-control"></select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab-3-tab">
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
						<h5>Damage type</h5>
					</div>
					<div class="row">
						<select name="add_damage_type" id="add_damage_type" class="form-control"></select>
					</div>
				</div>
				<div class="col">
					<div class="row">
						<h5>Damage formula</h5>
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
						<select name="add_damage_affected_pool" id="add_damage_affected_pool" class="form-control"></select>
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
	<div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab-4-tab">
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
						<select name="summon_char" id="summon_char" class="form-control"></select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="tab5" role="tabpanel" aria-labelledby="tab-5-tab">
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
						<select name="state_addremove" class="form-control"><option value="add">add</option><option value="remove">remove</option></select>
					</div>
				</div>
				<div class="col">
					<div class="row">
						<h5>State</h5>
					</div>
					<div class="row">
						<select name="state_id" id="state_id" class="form-control"></select>
					</div>
				</div>
				<div class="col">
					<div class="row">
						<h5>Rounds</h5>
					</div>
					<div class="row">
						<input type="text" name="rounds" value="99" class="form-control" />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<br />
<br />
<button type="button" id="add_new_event" class="btn">Create new event</button>


</form>


</div>

<script>

var field_id = <?=$field_id;?>;
var event_id = <?=$event_id;?>;

var dm = 'all';
var start_shift = 0;
var end_shift = 1000;
var x_rounds = 1;
var start_round = 0;

function test_activation( start_round, start_shift, end_shift, x_rounds, dm, test ){

	if( dm == 'all' ){
  		return test >= start_round + start_shift && test <= start_round + end_shift;
  }else{
  
		dm = dm == 'true' ? 0.5 : 0;
		var diff = test-start_shift + dm - start_round +0.5;
		return diff > 0 && diff%x_rounds === 0 && test <= start_round + end_shift ? true : false;
  }

}

function timeline(){
	
	$.getJSON( 'setdata.php?page=timeline', { dm : dm, start_shift : start_shift, end_shift : end_shift, x_rounds : x_rounds, current_round : game2.current_round }, function( timeline_array ){
		
		timeline_array.forEach( function( round, i ){
			
			if( round == true ){
				
				$('#timeline .round').eq(i).addClass( 'selected' );
				
			}else{
				
				$('#timeline .round').eq(i).removeClass( 'selected' );
				
			}
			
		});
		
	});

}



$.getJSON("getdata.php", { page: 'get_types'}).done(function(lib) {

	dm = 'all';

	lib.events.forEach( function( event ){

		$('#select_event_time').append( '<option value="'+event.event+'">'+event.event+'</option>' );

	});

	lib.special_token.forEach( function( token ){

		$('#special_token_token').append( '<option value="'+token.name+'">'+token.name+'</option>' );

	});

	lib.damage_types_all.forEach( function( token ){

		$('#normal_token_token').append( '<option value="'+token.name+'">'+token.name+'</option>' );
		$('#add_damage_type').append( '<option value="'+token.name+'">'+token.name+'</option>' );

	});

	lib.pools.forEach( function( pool ){

		$('#add_damage_affected_pool').append( '<option value="'+pool.name+'">'+pool.name+'</option>' );

	});

	lib.action_types.forEach( function( action_type, i ){

		$('#action_types').append( '<input type="checkbox" class="form-control" name="trigger_action_'+i+'" value="'+action_type.name+'" id="'+action_type.name+'" /><label for="'+action_type.name+'" class="action_type">'+action_type.name+'</label>' );

	});


	lib.states_effects2.forEach( function( state ){

		$('#state_id').append( '<option value="'+state.state_id+'">'+state.state_name+' ('+state.variable+':'+state.modifier+')</option>' );

	});

	chars.forEach( function( char ){

		$('#summon_char').append( '<option value="'+char.name+'">'+char.name+'</option>' );

	});

	for( x = game2.current_round, y = x+20; x<y; x=x+0.5 ){

		if( x == 0 || Number.isInteger( x ) ){

			$('#timeline').append( '<div class="round players">'+x+'</div>' );

		}else{

			$('#timeline').append( '<div class="round dm">'+x+'</div>' );

		}

	}

	start_round = game2.current_round;

	$('#dm').on( 'change', function(){

		dm = $(this).val();
		console.log( dm );
		timeline();

	});

	$('#start_shift').on( 'change', function(){

		start_shift = parseInt( $(this).val() );
		timeline();

	});

	$('#end_shift').on( 'change', function(){

		end_shift = parseInt( $(this).val() );
		timeline();

	});

	$('#x_rounds').on( 'change', function(){

		x_rounds = parseInt( $(this).val() );
		timeline();

	});

	if( event_id != 0 ){
		
		$('#event_title').text( 'Edit event '+event_id );
		$('#add_new_event').text( 'Edit event' );

		game2.fields.forEach( function( field ){

			if( field.creation_date == field_id ){


				field.field_events.forEach( function( event ){

					if( event.event_id == event_id ){


						$('#select_event_time').val( event.event_time );
						
						dm = event.timelinedata.dm;
						start_shift = parseInt( event.timelinedata.start_shift);
						end_shift = parseInt( event.timelinedata.end_shift);
						x_rounds = parseInt( event.timelinedata.x_rounds);

						$('#add_event_tabs li a:contains('+event.event_type+')').click();

						$.each( event.data, function( key, value ){
						
							if( key != 'triggerer_restriction' ){

								$('#new_event_form textarea[name='+key+'],input[name='+key+'],select[name='+key+']').val( value );
								
							}

							if( key.indexOf("trigger_action") != -1 ){

								$('#new_event_form input[name='+key+']').prop( 'checked', 'checked' );

							}

						});
						
						$('#new_event_form input[value=\''+event.data.triggerer_restriction+'\']').attr( 'checked' , 'checked' );
						
						if( event.data.only_trigger_target_is_target == 'true' ){
							
							$('#new_event_form input[name=\'only_trigger_target_is_target\']').attr( 'checked' , 'checked' );
							
						}
						
						if( event.data.field_target_is_action_target == 'true' ){
							
							$('#new_event_form input[name=\'field_target_is_action_target\']').attr( 'checked' , 'checked' );
							
						}

						if( !event.data.trigger_action || ( event.data.trigger_action && event.data.trigger_action.length == 0 ) ){

							$('#action_types input').prop( 'checked', 'checked' );

						}

						if( $('#select_event_time').val() == 'on_made_damage' || $('#select_event_time').val() == 'on_attack' || $('#select_event_time').val() == 'on_mk_damage' ){

							$('#hidden_form_group').show();

						}

					}

				});

			}

		});

	}

	timeline();

	$('#select_event_time').on( 'change', function(){

		var val = $(this).val();

		if( val == 'on_made_damage' || val == 'on_attack' || val == 'on_mk_damage' ){

			$('#hidden_form_group').show();

		}else{

			$('#hidden_form_group').hide();

		}

	});
});


$('#add_new_event').unbind("click").click( function(){

	var event_type = '';
	var selected_rounds = [];

	$('#add_event_tabs li a.active').each( function(){

		event_type = $(this).text();

	});

	$('#timeline .round').filter('.selected').each( function(){

		selected_rounds.push( $(this).text() );

	});	
	
	var timelinedata = { dm : dm, start_shift : start_shift, end_shift : end_shift, x_rounds : x_rounds };

	$.get( 'setdata.php', { page : 'field_add_new_event', field_id : field_id, event_id : event_id, selected_rounds: selected_rounds, event_type : event_type, data : $('#new_event_form').serializeArray(), timelinedata : timelinedata  }, function(){
	
		$('#new_event_container').html('<h3>New event created.</h3>');
		
	});
	
});


</script>

