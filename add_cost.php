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

</style>
<br />
<h4>Upkeep-Kosten</h4>
<br />
<div id="new_cost_container" style="color:white;">

	<form action="" method="post">
		
		 <div class="row" style="max-width:50%;">

    			<div class="col">

				<select id="cost_select" name="cost_select[]" class="cost_select form-control"></select>

			</div>
			<div class="col">

				<input type="text" name="cost_select_value[]" class="form-control" value="" />

			</div>
			<div class="col">

				<button type="button" class="btn btn-default btn-sm add_cost_button" style="float:right;height:30px;">
					<img src="svg/si-glyph-plus.svg" style="height:16px;width:16px;">
				</button>

			</div>

		</div>

		<div class="row">
			<button type="button" id="add_cost" class="btn btn-default btn-sm" style="float:right;height:30px;">
				Kosten hinzuf&uuml;gen
			</button>
		</div>

	</form>

</div>

<script>

var field_id = <?=$field_id;?>;
var event_id = <?=$event_id;?>;
console.log( game2 );


$.getJSON("getdata.php", { page: 'get_types'}).done(function(lib) {


	lib.pools.forEach( function( pool ){

		if( pool.name == 'Mana points' ){

			$('#cost_select').append( '<option value="'+pool.name+'" selected="selected">'+pool.name+'</option>' );

		}else{

			$('#cost_select').append( '<option value="'+pool.name+'">'+pool.name+'</option>' );

		}

	});


});

$.getJSON("getdata.php", { page: 'get_game', 'game_id' : current_game_id }).done(function( game3 ) {

	game3.fields.forEach( function( field ){

		if( field.creation_date == field_id ){

			field.cost.forEach( function( cost ){

				$('#new_cost_container form').prepend( `

					<div class="row" style="max-width:50%;">

    						<div class="col">

							${cost.pool}

						</div>
						<div class="col">

							${cost.value}

						</div>
						<div class="col">

							<button type="button" class="btn btn-default btn-sm del_cost_button" data-pool="${cost.pool}" data-value="${cost.value}" style="float:right;height:30px;">
								<img src="svg/si-glyph-delete.svg" style="height:16px;width:16px;">
							</button>

						</div>

					</div>

				`);

			});

		}

	});

	$('.del_cost_button').click( function(){

		$.getJSON("setdata.php", { page: 'delete_field_cost', field_id : field_id, pool : $(this).data("pool"), value : $(this).data("value") }, function(ret){

		});

		tb_show( '','add_cost.php?height=500&width=1000&field_id='+field_id);
	});

});



$('.add_cost_button').unbind("click").click( function(){

	var row = $('#new_cost_container .row:first').clone().remove("row button");
	$('#new_cost_container .row:last').before( row );

});

$('#add_cost').unbind("click").click( function(){

	$.get( 'setdata.php', { page : 'field_add_cost', field_id : field_id, data : $('#new_cost_container form').serializeArray()  }, function(){
		tb_show( '','add_cost.php?height=500&width=1000&field_id='+field_id);
	});

});


</script>

