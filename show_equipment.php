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

	#shop_weapons li, #shop_equipment li{
		border: 1px solid white;
		margin: 2px;
		padding: 5px;
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
var selected_tier_index = 0;
var title = '';
var color = '';
var text = '';

function getRandomInt(max) {
  
	return Math.floor(Math.random() * Math.floor(max));

}

function unequip( url ){

	$.get( url, function( ret ){

		game(current_game_id);
     		tb_show( '','show_equipment.php?height=500&width=1000&char_id='+char_id);
	     			
     	});
     			
}


function equip( url ){

	$.get( url, function( ret ){

		game(current_game_id);
     		tb_show( '','show_equipment.php?height=500&width=1000&char_id='+char_id);
	     			
     	});
   
}

function showplayer( game_id ){
	
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

				var player_objects = '';
				var sum_physical = 0;
				var sum_magical = 0;

				if( char.armor && char.armor.objects && char.armor.objects.length > 0 ){

					for( x=0, y=char.armor.objects.length; x<y; x=x+2 ){

						$('#equipment').append( `
							<div class="equip card text-light bg-dark player m-3 border border-white float-left mw-50">
								<div class="card-header bg-success">
									<strong>${char.armor.objects[x].name} (${char.armor.objects[x].object_type}, Tier ${char.armor.objects[x].tier_lvl}, <?=$l[$lang]['SHOW_PLAYER_PHYSICAL'];?> ${char.armor.objects[x].formula3}, <?=$l[$lang]['SHOW_PLAYER_MAGICAL'];?> ${char.armor.objects[x+1].formula3})</strong>
								</div>
								<div class="card-body">
									<div class="border-bottom border-white text-truncate">
										${char.armor.objects[x].formula}<br />
										${char.armor.objects[x].formula2}
									</div>
									<div>
										${char.armor.objects[x+1].formula}<br />
										${char.armor.objects[x+1].formula2}
									</div>
								</div>
								<div class="card-footer">
									<a href="#" onclick="unequip('setdata.php?page=unequip&char_id=${char_id}&object_id=${char.armor.objects[x].object_id}&type=${char.armor.objects[x].type}')">unequip</a>
								</div>
							</div>
						` );
						
						sum_physical += char.armor.objects[x].formula3;
						sum_magical += char.armor.objects[x+1].formula3;

					}

				}

				
				$('#equipment').append( `
					<div class="equip card text-light bg-dark player m-3 border border-white float-left">
						<div class="card-header">
							<strong>Total</strong>
						</div>
						<div class="card-body">
							<div class="border-bottom border-white">
								<?=$l[$lang]['SHOW_PLAYER_PHYSICAL'];?>: ${sum_physical} (Equip) + ${char.armor.agility} (Agi) = ${char.armor.result_physical}
							</div>
							<div>
								<?=$l[$lang]['SHOW_PLAYER_MAGICAL'];?>: ${sum_magical} (Equip) + ${char.armor.wisdom} (Wis) = ${char.armor.result_magical}
							</div>
						</div>
					</div>
				` );

				if( char.equip && char.equip.equipment && char.equip.equipment.length > 0 ){

					for( x=0, y=char.equip.equipment.length; x<y; x++ ){

						$('#equipment').append( `
							<div class="equip card text-light bg-dark player m-3 border border-white float-left mw-50">
								<div class="card-header bg-secondary">
									<strong>${char.equip.equipment[x].equipment_name} (${char.equip.equipment[x].eq_type}, ${char.equip.equipment[x].tier_lvl[0].tier_lvl_name})</strong>
								</div>
								<div class="card-body">
									<?=$l[$lang]['SHOW_PLAYER_PHYSICAL'];?> ${char.equip.equipment[x].armor_formula} <br />
									<?=$l[$lang]['SHOW_PLAYER_MAGICAL'];?> ${char.equip.equipment[x].magic_armor_formula} <br />
									<br />
									${char.equip.equipment[x].description}
								</div>
								<div class="card-footer">
									<a href="#" onclick="equip('setdata.php?page=equip&char_id=${char_id}&object_id=${char.equip.equipment[x].equipment_id}&type=equipment')">equip</a>
								</div>
							</div>
						` );

					}

				}

				if( char.equip && char.equip.weapons && char.equip.weapons.length > 0 ){

					for( x=0, y=char.equip.weapons.length; x<y; x++ ){

						$('#equipment').append( `
							<div class="equip card text-light bg-dark player m-3 border border-white float-left mw-50">
								<div class="card-header bg-secondary">
									<strong>${char.equip.weapons[x].weapon_name} (${char.equip.weapons[x].weapon_type}, ${char.equip.weapons[x].tier_lvl})</strong>
								</div>
								<div class="card-body">
									<?=$l[$lang]['SHOW_PLAYER_PHYSICAL'];?> ${char.equip.weapons[x].armor_formula} <br />
									<?=$l[$lang]['SHOW_PLAYER_MAGICAL'];?> ${char.equip.weapons[x].magic_armor_formula} <br />
									<br />
									${char.equip.weapons[x].description}
								</div>
								<div class="card-footer">
									<a href="#" onclick="equip('setdata.php?page=equip&char_id=${char_id}&object_id=${char.equip.weapons[x].weapon_id}&type=weapon')">equip</a>
								</div>
							</div>
						` );

					}

				}


	
				$('#player_status').append( `
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
										<div class="col m-0" style="background-image: linear-gradient(to right, #9e0c1d, #e5462d);width:`+life+`%;">
											<span class="text-white h5 text-nowrap">${char.pools.life.cur}/${char.pools.life.max} (${Math.round(life)}%)</span>
										</div>
									</div>
									<div class="col p-0 m-1" style="border:1px solid #4492e5;">
										<div class="col w-100 m-0" style="background-image: linear-gradient(to right, #1f1d47, #4492e5);width:`+mana+`%;">
											<span class="text-white h5">${char.pools.mana.cur}/${char.pools.mana.max} (${Math.round(mana)}%)</span>
										</div>
									</div>
									<div class="col p-0 m-1" style="border:1px solid #5cce40;">
										<div class="col w-100 m-0" style="background-image: linear-gradient(to right, #34702c, #5cce40);width:`+ap+`%;">
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
					`);
				
			}
				
		});
			
	});
	
});
		
}

function load_shop_content(){

	$('#shop_weapons').html('');
	$('#shop_equipment').html('');

	$.getJSON("getdata.php", {
	page: 'get_shop'
	})
	.done(function(json) {

		shops = json;
	
		console.log( shops );

		for( y=0; y<shops.length; y++ ){


			if( shops[y].shop_id != <?=$_SESSION['camp_id'];?> ){
				
				continue;

			}

			

			for( x=0; x<shops[y].weapons.length;x++ ){

				var tier_lvl = shops[y].weapons[x].tier_lvl;

				$('#shop_weapons').append('<li weapon_id="'+x+'">'+shops[y].weapons[x].weapon_name+' '+tier_lvl+'<span style="float:right;">Claim</span></li>' );				

			}
			
			for( x=0; x<shops[y].equipment.length;x++ ){

				var index = Object.keys( shops[y].equipment[x].tier_lvl );
				var tier_lvl = shops[y].equipment[x].tier_lvl[index[0]].tier_lvl_name;

				$('#shop_equipment').append('<li equipment_id="'+x+'">'+shops[y].equipment[x].equipment_name+' '+tier_lvl+'<span style="float:right;">Claim</span></li>' );				

			}

		}

		$('#shop_weapons li span').click( function(){

			var weapon_id = $(this).parent().attr("weapon_id");
			$.get( 'setdata.php', { 'page':'claim_weapon_from_shop', weapon_id : weapon_id, char_id : char_id }, function(){
				game(current_game_id);
     				tb_show( '','show_equipment.php?height=500&width=1000&char_id='+char_id);
			});

		});
		$('#shop_equipment li span').click( function(){

			var equipment_id = $(this).parent().attr("equipment_id");
			$.get( 'setdata.php', { 'page':'claim_equipment_from_shop', equipment_id : equipment_id, char_id, char_id }, function(){
				game(current_game_id);
     				tb_show( '','show_equipment.php?height=500&width=1000&char_id='+char_id);
			});

		});

	})
	.fail(function(jqxhr, textStatus, error) {
		var err = textStatus + ", " + error;
		console.log("Request Failed: " + err);
});
}

$(document).ready( function(){

	showplayer( current_game_id, char_id );
	$('#TB_ajaxContent').css({width:'100%'});

	load_shop_content();

	
});



</script>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm" id="player_status">
		</div>
		<div class="col-sm" id="campaign_shop">
			<div class="row" style="color:white;margin-top:8px;">
				<div class="col-sm">
					<ul id="shop_weapons"></ul>
				</div>
				<div class="col-sm">
					<ul id="shop_equipment"></ul>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="equipment">
	</div>
</div>