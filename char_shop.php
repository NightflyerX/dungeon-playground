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

#editor_holder div ul{
	border: 1px solid white;
	min-height:200px;
	list-style-type: none;
	padding-left: 0;
}
#editor_holder div ul li{
	margin: 2px;
	border: 1px dotted white;
	width: 90%;
	background-color: #2A9FD6;
	opacity:0.9;
}

</style>

<div id="editor_holder" style="width:800px;">
<?
	try{

		$stmt = $db->prepare( 'SELECT * FROM chars WHERE `char_id`=1' );
		$stmt->execute();

	}catch(PDOException $ex) {
			echo "An Error occured! "; //user friendly message
			echo $ex->getMessage();
	}

	foreach($stmt as $row) {
		
		$chars = json_decode( $row['data'] );

		foreach( $chars as $char ){

			if( $char->char_id == $_GET['char_id'] ){
				
				echo "<h5>Shop f&uuml;r ".$char->name."</h5><br /><br />";

			}

		}

	}
?>

	<div style="width:50%;float:left;min-height:500px;padding:5px;">
		<h5 class="title">Character</h5><br />
		<br />
		Weapons:<br />
		<ul id="char_weapons"  class="player card">
		</ul><br />
		<br />
		Items/Equipment:<br />

		<ul id="char_equipment"  class="player card">
		</ul>

		<h5>Backpack (<span id="volume_tot">0</span>/32)</h5>
		Backpack weapons:<br />
		<ul id="char_weapons_equip"  class="player card">
		</ul>
		Backpack equipment<br />
		<ul id="char_equipment_equip"  class="player card">
		</ul>
	</div>
	<div style="width:50%;float:left;min-height:500px;border-left:1px dotted white;padding:5px;">
		<h5 class="title">Shop of the current campaign</h5><br />
		<br />
		Weapons:<br />

		<ul id="shop_weapons"  class="player card">
		</ul><br />
		<br />
		Equipment:<br />

		<ul id="shop_equipment"  class="player card">
		</ul>
	</div>
	<div style="clear:both;">&nbsp;</div>
	
</div>

<script type="text/javascript">

var char_id = <?=$_GET['char_id'];?>;

$('#char_weapons').droppable({
	drop : function( event, ui ){

		var weapon_id = ui.helper.attr("weapon_id");
		console.log( weapon_id );
		$.get( 'setdata.php', { 'page':'move_weapon_from_shop_to_char', weapon_id : weapon_id, char_id : char_id }, function(){
			load_shop_content();
		});

	}
});
$('#char_equipment').droppable({
	drop : function( event, ui ){

		var equipment_id = ui.helper.attr("equipment_id");
		console.log( equipment_id );
		$.get( 'setdata.php', { 'page':'move_equipment_from_shop_to_char', equipment_id : equipment_id, char_id : char_id }, function(){
			load_shop_content();
		});

	}
});

function load_shop_content(){

	$('#shop_weapons').html('');
	$('#shop_equipment').html('');
	$('#char_weapons').html('');
	$('#char_equipment').html('');
	$('#char_weapons_equip').html('');
	$('#char_equipment_equip').html('');

	$.getJSON("getdata.php", {
	page: 'get_shop'
	})
	.done(function(json) {

		shops = json;
		//console.log( shops );

		for( y=0; y<shops.length; y++ ){


			if( shops[y].shop_id != <?=$_SESSION['camp_id'];?> ){
				
				continue;

			}

			

			for( x=0; x<shops[y].weapons.length;x++ ){

				
				var tier_lvl = shops[y].weapons[x].tier_lvl;

				$('#shop_weapons').append('<li weapon_id="'+shops[y].weapons[x].weapon_id+'">'+shops[y].weapons[x].weapon_name+' '+tier_lvl+'</li>' );				

			}

			console.log( shops[y].equipment );
			
			for( x=0; x<shops[y].equipment.length;x++ ){

				var index = Object.keys( shops[y].equipment[x].tier_lvl );
				var tier_lvl = shops[y].equipment[x].tier_lvl[index[0]].tier_lvl_name;

				$('#shop_equipment').append('<li equipment_id="'+shops[y].equipment[x].equipment_id+'">'+shops[y].equipment[x].equipment_name+' '+tier_lvl+'</li>' );				

			}

		}

		$('#shop_weapons li').draggable({helper:"clone"});
		$('#shop_equipment li').draggable({helper:"clone"})

	})
	.fail(function(jqxhr, textStatus, error) {
		var err = textStatus + ", " + error;
		console.log("Request Failed: " + err);
	});

	$.getJSON("getdata.php", {
	page: 'get_char'
	})
	.done(function(json) {

		chars = json;

		for( y=0; y<chars.length;y++ ){

			console.log( chars[y] );

			if( chars[y].char_id != char_id ){

				continue;

			}

			if( chars[y].weapons ){
			for( x=0; x<chars[y].weapons.length;x++ ){

				var tier_lvl = chars[y].weapons[x].tier_lvl;

				$('#char_weapons').append('<li weapon_id="'+chars[y].weapons[x].weapon_id+'">'+chars[y].weapons[x].weapon_name+' '+tier_lvl+' <span style="float:right" weapon_id="'+chars[y].weapons[x].weapon_id+'">Unequip</span></li>' );

			}}

			if( chars[y].equipment ){
			for( x=0; x<chars[y].equipment.length;x++ ){

				var index = Object.keys( chars[y].equipment[x].tier_lvl );
				var tier_lvl = chars[y].equipment[x].tier_lvl[index[0]].tier_lvl_name;

				$('#char_equipment').append('<li equipment_id="'+chars[y].equipment[x].equipment_id+'">'+chars[y].equipment[x].equipment_name+' '+tier_lvl+' <span style="float:right" equipment_id="'+chars[y].equipment[x].equipment_id+'">Unequip</span></li>' );				

			}}

			$('#char_weapons li span').click( function(){
				var weapon_id = $(this).parent().attr("weapon_id");
				$.get( 'setdata.php', { 'page':'unequip_weapon_from_char', weapon_id : weapon_id, char_id : char_id }, function(){
					load_shop_content();
				});
			});

			$('#char_equipment li span').click( function(){
				var equipment_id = $(this).parent().attr("equipment_id");
				$.get( 'setdata.php', { 'page':'unequip_equipment_from_char', equipment_id : equipment_id, char_id : char_id }, function(){
					load_shop_content();
				});
			});
			
			var volume_tot = 0;

			if( !chars[y].equip )
				continue;

			if( chars[y].equip.weapons && chars[y].equip.weapons.length > 0 ){
			for( x=0; x<chars[y].equip.weapons.length;x++ ){

				var volume = 4;
				volume_tot += parseInt(volume);
				tier_lvl = chars[y].equip.weapons[x].tier_lvl;
				$('#char_weapons_equip').append('<li weapon_id="'+chars[y].equip.weapons[x].weapon_id+'">'+chars[y].equip.weapons[x].weapon_name+' '+tier_lvl+' Platz '+volume+' <span style="float:right" weapon_id="'+chars[y].equip.weapons[x].weapon_id+'">Equip&nbsp;</span><span style="float:right" weapon_id="'+chars[y].equip.weapons[x].weapon_id+'">Discard&nbsp;</span></li>' );

			}}

			if( chars[y].equip.equipment && chars[y].equip.equipment.length > 0 ){
			for( x=0; x<chars[y].equip.equipment.length;x++ ){

				var index = Object.keys( chars[y].equip.equipment[x].tier_lvl );
				var tier_lvl = chars[y].equip.equipment[x].tier_lvl[index[0]].tier_lvl_name;
				var volume = chars[y].equip.equipment[x].tier_lvl[index[0]].volume;
				volume_tot += parseInt( volume );

				$('#char_equipment_equip').append('<li equipment_id="'+chars[y].equip.equipment[x].equipment_id+'">'+chars[y].equip.equipment[x].equipment_name+' '+tier_lvl+' Platz '+volume+' <span style="float:right" equipment_id="'+chars[y].equip.equipment[x].equipment_id+'">Equip&nbsp;</span><span style="float:right" equipment_id="'+chars[y].equip.equipment[x].equipment_id+'">Verwerfen&nbsp;</span></li>' );

			}}
			
			$('#char_weapons_equip li span:eq(0)').click( function(){
				var weapon_id = $(this).attr("weapon_id");
				$.get( 'setdata.php', { 'page':'reequip_weapon_to_char', weapon_id : weapon_id, char_id : char_id }, function(){
					load_shop_content();
				});
			});

			$('#char_equipment_equip li span:eq(0)').click( function(){
				var equipment_id = $(this).attr("equipment_id");
				$.get( 'setdata.php', { 'page':'reequip_equipment_to_char', equipment_id : equipment_id, char_id : char_id }, function(){
					load_shop_content();
				});
			});

			$('#char_weapons_equip li span:eq(1)').click( function(){
				var weapon_id = $(this).attr("weapon_id");
				$.get( 'setdata.php', { 'page':'remove_weapon_from_equip', weapon_id : weapon_id, char_id : char_id }, function(){
					load_shop_content();
				});
			});

			$('#char_equipment_equip li span:eq(1)').click( function(){
				var equipment_id = $(this).attr("equipment_id");
				$.get( 'setdata.php', { 'page':'remove_equipment_from_equip', equipment_id : equipment_id, char_id : char_id }, function(){
					load_shop_content();
				});
			});
			
			$('#volume_tot').html( volume_tot );
				


		}

	})
	.fail(function(jqxhr, textStatus, error) {
		var err = textStatus + ", " + error;
		console.log("Request Failed: " + err);
	});

}

load_shop_content();

</script>


<?

include( 'footer_nochat.php' );