<?php

$date = new DateTime();

$timestamp = isset( $_SESSION['timestamp'] ) ? $_SESSION['timestamp'] : $date->format('d.m.Y H:i:s');

?>
<!doctype html>
<html lang="en">
<head>
<!-- Force latest IE rendering engine or ChromeFrame if installed -->
<!--[if IE]>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<![endif]-->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dungeon</title>
  <link rel="icon" href="favicon.ico" type="image/x-icon"/>
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>

<link rel="stylesheet" href="thickbox.css">

  <!-- Bootstrap core CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.1.1/cyborg/bootstrap.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <script src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.2.0/respond.min.js"></script>
    <![endif]-->
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/ui-darkness/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
  
    <script src="jsoneditor.js"></script>
    <script src="thickbox.js"></script>

<link rel="stylesheet" href="css/jquery.fileupload.css">
<link rel="stylesheet" href="css/jquery.fileupload-ui.css">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="css/jquery.fileupload-ui-noscript.css"></noscript>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Righteous">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">

<link rel="stylesheet" href="slider.css">
<script src="slider.js"></script>

<link rel="stylesheet" href="jqtree/jqtree.css">
<script src="jqtree/tree.jquery.js"></script>
<script src="show_tree.js"></script>
<script src="jquery.ui.touch-punch.min.js"></script>

  <script>
  
  		var timestamp = "<?=$timestamp;?>";
	  	var username = "<?=$_SESSION['username'];?>";
	  	var color = "<?=$_SESSION['color'];?>";
		var game = 0;
		var current_game_id = 0;
		var lib;
  
  	function check_update(){
	  	
	  	if( $('#TB_overlay').length < 1 ){
	  	
		  	$.post( "check_update.php", function(data){
			  	
			  	if( timestamp != data || $('#chatdiv').html() == '' ){
				  	
				  	$.post( 'chat.php', function( chat ){
					  	
				  		$('#chatdiv').html( chat );
				  		
				  		$('#chatdiv').stop().animate({
						  scrollTop: $('#chatdiv')[0].scrollHeight
						}, 800);
						
						if( game && current_game_id ){
							
							game(current_game_id);

						}
				  		
			  		});
				  	
				  	timestamp = data;
				  	$("#session").append(timestamp);
				  	
			  	}
			  	
		  	});
		  	
	  	}
	  	
  	}

	function getColor( token, damage_types_all ){

		var color = '';

		damage_types_all.forEach( function( dmg ){
			
			if( dmg.name == token ){

				color = dmg.color;

			}

		});

		return color;

	}


function get_tier_use( action, char, return_tier ){

	var return_tier = {

		"tiers" : [],
		"result" : "",
		"log" : []

	};

	var a_weapon = {};
	var a_equipment = {};

	if( lib.length == 0 ){

		$.getJSON("getdata.php", { page: 'get_types' }).done(function(lib2) {
			lib = lib2;
		});

	}

	action.tier_lvl.forEach( function( tier, index ){

		var tier_possible = true;
		var tier_cost = new Array();
		var reason = '';

		//Check cost

		tier.cost.forEach( function( cost, i ){

			//Check if cost is payable

			if( !$.isNumeric( cost.cost_value ) ){

				$.getJSON( 'setdata.php', { page : 'diceroll', char_id : char_id, dice_roll_formula : cost.cost_value, info : { file : '<?= __FILE__;?>', line : '<?= __LINE__;?>' } }).done( function( res ){

					cost.cost_value = res.formula3;
					cost.cost_value > 1 ? cost.cost_value : 1;

				});

			}

			var pool_id = '';

			lib.pools.forEach( function( pool ){

				if( pool.name == cost.cost_type ){

					pool_id = pool.id;

				}

			});
			
			char.cost_affection.forEach( function( cost_affection ){
				
				if( cost_affection.type == cost.cost_type ){
					
					cost.cost_value = parseInt( cost.cost_value ) + parseInt( cost_affection.value.replace(/\s/g,'') );

				}
				
			});

			tier_cost.push({cost_type : cost.cost_type, cost_value : cost.cost_value });
			
			if( parseInt( cost.cost_value ) > parseInt(char.pools[pool_id].cur) ){

				tier_possible = false;
				reason = 'Cost to high ('+cost.cost_type+')';
			}
		});

		if( tier.token_cost && tier.token_cost.length > 0 ){

			tier.token_cost.forEach( function( token, i ){

				if( token.token_val && token.token_val > 0 ){ //Token required
					
					if( $('div.tokens span[data-token="'+token.token+'"]').length < token.token_val ){

						tier_possible = false;
						reason = 'Not enough '+token.token+' Tokens';

					}
				
					for( x = 0, y = token.token_val; x<y; x++ ){

						$('div.tokens span[data-token="'+token.token+'"]:eq('+x+')').css({ 'border':'1px solid white' });

					}

				}

				if( token.special_token_val && token.special_token_val > 0 ){ //Token required
					
					if( $('div.special_tokens span[data-token="'+token.special_token+'"]').length < token.special_token_val ){

						tier_possible = false;
						reason = 'Not enough '+token.special_token+' Spezial-Tokens';

					}
				
					for( x = 0, y = token.special_token_val; x<y; x++ ){

						$('div.special_tokens span[data-token="'+token.special_token+'"]:eq('+x+')').css({ 'border':'1px solid white' });

					}

				}

			});

		}
		
		if( tier.weapon_filters && tier.weapon_filters.length > 0 ){

			//Check if weapon is there and skills are met
			var weapon_equiped = false;
			var weapon_tier_lvl = "";
			var weapon_name = "";
			
			tier.weapon_filters.forEach( function( weapon, i ){
				
	
				switch( weapon.weapon_skill_level_filter ){
	
					case "Normal":
						weapon.weapon_skill_level_filter = 1;
					break;
					case "Expert":
						weapon.weapon_skill_level_filter = 2;
					break;
					case "Master":
						weapon.weapon_skill_level_filter = 3;
					break;
					case "Grandmaster":
						weapon.weapon_skill_level_filter = 4;
					break;
				}
	
				var this_weapon_equiped = false;
	
				if( char.weapons.length > 0 ){
	
					char.weapons.forEach( function( char_weapon, i ){

						return_tier.log.push( "if( "+char_weapon.weapon_type+" == "+weapon.weapon_filter+" ){ " );
						
						if( char_weapon.weapon_type == weapon.weapon_filter ){
	
							this_weapon_equiped = true;

							
							
							weapon_tier_lvl = char_weapon.tier_lvl;
							weapon_name = char_weapon.weapon_name;

							a_weapon = { weapon_tier_lvl : weapon_tier_lvl, weapon_name : weapon_name, skill : char_weapon.weapon_type };
						}
	
					});
				
				}
	
				
	
				if( this_weapon_equiped === true ){

					return_tier.log.push( { "weapon_equipped_name" : weapon_name, "weapon_equipped_tier_lvl" :  weapon_tier_lvl } );

	
					if( char.skills.offensive[weapon.weapon_skill_filter].cur_lvl >= weapon.weapon_skill_level_filter ){

						
						if( weapon_tier_lvl && parseInt( weapon_tier_lvl.slice(-1) ) >= parseInt( weapon.weapon_tier_level_filter.slice(-1) ) ){
	
							weapon_equiped = true;
							
						}else{

							return_tier.log.push( 'if( weapon_tier_lvl && parseInt( '+weapon_tier_lvl.slice(-1)+' ) >= parseInt( '+weapon.weapon_tier_level_filter.slice(-1)+' ) ){ ' );

						}
		
					}else{

						return_tier.log.push( 'if( '+char.skills.offensive[weapon.weapon_skill_filter].cur_lvl+' >= '+weapon.weapon_skill_level_filter+' ){ ' );

					}
	
				}
	
			});
			
			if( weapon_equiped === false ){

				tier_possible = false;
				reason = 'Weapon(s) not equiped or skills/tier not high enough';

			}
			
		}

		if( tier.magic_filters && tier.magic_filters.length > 0 ){

			//Check if magic skills are met
	
			tier.magic_filters.forEach( function( magic, i ){
	
				switch( magic.magic_skill_level_filter ){
	
					case "Normal":
						magic.magic_skill_level_filter = 1;
					break;
					case "Expert":
						magic.magic_skill_level_filter = 2;
					break;
					case "Master":
						magic.magic_skill_level_filter = 3;
					break;
					case "Grandmaster":
						magic.magic_skill_level_filter = 4;
					break;
				}

				return_tier.log.push( 'if ( '+char.skills.magic_types[magic.magic_skill_filter].cur_lvl+' < '+magic.magic_skill_level_filter+' ){ ' );
	
				if( char.skills.magic_types[magic.magic_skill_filter].cur_lvl < magic.magic_skill_level_filter ){
	
					
					tier_possible = false;
					reason = 'Magic '+magic.magic_skill_filter+' not enough skilled';
	
				}
	
			});
			
		}
		
		if( tier.other_skill_filters && tier.other_skill_filters.length > 0 ){

		//Check if other skills are met

			tier.other_skill_filters.forEach( function( other, i ){
	
				switch( other.other_skill_level_filter ){
	
					case "Normal":
						other.other_skill_level_filter = 0;
					break;
					case "Expert":
						other.other_skill_level_filter = 1;
					break;
					case "Master":
						other.other_skill_level_filter = 2;
					break;
					case "Grandmaster":
						other.other_skill_level_filter = 3;
					break;
				}
				
				if( char.skills.skill_types[other.other_skill_filter].cur_lvl < other.other_skill_level_filter ){
	
					
					tier_possible = false;
					reason = 'Skill '+other.other_skill_filter+' not skilled enough';
	
				}
	
			});
			
		}
		
		if( tier.item_filters && tier.item_filters.length > 0 ){
			
			//Check if equipments are met
			
			var item_equiped = false;
			var item_tier_level = "";
			var item_name = "";

			tier.item_filters.forEach( function( item, i ){
				
				var this_item_equiped = false;
				var item_tier_lvl = "";
				
				if( char.equipment.length > 0 ){
	
					char.equipment.forEach( function( char_equipment, i ){
	
						if( char_equipment.equipment_name == item.item_filter ){
	
							this_item_equiped = true;
							
							item_tier_lvl = char_equipment.tier_lvl[0].tier_lvl_name;
							item_name = char_equipment.equipment_name;

							a_equipment = { item_tier_lvl : item_tier_lvl, item_name : item_name, skill : char_equipment.eq_type };
	
						}
	
					});
				
				}
	
					
	
				if( this_item_equiped === true ){
	
					if( parseInt( item_tier_lvl.slice(-1) ) >= parseInt( item.item_tier_level_filter.slice(-1) ) ){
	
						item_equiped = true;
		
					}
	
				}
				
			});
			
			if( item_equiped === false ){

				tier_possible = false;
				reason = 'Item(s) not equiped or tier not high enough';

			}
			
		}

		if( tier.only_char_filter &&  tier.only_char_filter.length > 0 ){

			var tier_possible_char_filter = false;

			tier.only_char_filter.forEach( function( chars_possible, i ){
				
				if( chars_possible.char == char.name ){
				
					tier_possible_char_filter = true;

				}

			});

			if( tier_possible_char_filter == false ){

				tier_possible = false;
				reason = 'Du bist der falsche Charakter';

			}

		}

		if( tier.min_level_filter ){

				switch( tier.min_level_filter ){
	
					case "Normal":
						tier.min_level_filter = 1;
					break;
					case "Expert":
						tier.min_level_filter = 2;
					break;
					case "Master":
						tier.min_level_filter = 3;
					break;
					case "Grandmaster":
						tier.min_level_filter = 4;
					break;
				}

				switch( char.skilldegree ){
	
					case "Normal":
						char.skilldegree = 0;
					break;
					case "Expert":
						char.skilldegree = 1;
					break;
					case "Master":
						char.skilldegree = 2;
					break;
					case "Grandmaster":
						char.skilldegree = 3;
					break;
				}
				
				return_tier.log.push( 'if( '+tier.min_level_filter+' > '+char.skilldegree+' ){' );

				if( tier.min_level_filter > char.skilldegree ){

					tier_possible = false;
					reason = 'Zuwenig Level-Skill';

				}


		}

		

		return_tier.tiers.push( { 'tier_possible' : tier_possible, 'reason' : reason, 'tier_cost' : tier_cost } );

		if( tier_possible ){

			return_tier.result = { "tier_lvl_name" : tier.tier_lvl_name, "index" : index, weapon : a_weapon, equipment : a_equipment }

		}

	});

	return return_tier;

}
  

	$(document).ready( function(){
		
		$('.modal').modal({backdrop:'static',keyboard:true, show:true});
		$('button.json-editor-btn-delete:contains("Delete All")').off('click');
		
		setInterval( check_update, 2000 );
		
		$('#chatbutton').click( function(){
			
			$.post( 'chat.php', { new_chattext : '<div style="color:#'+color+'">'+username+': '+$('#chatinput').val()+'</div>' }, function( chat ){
				
				$('#chatdiv').html( chat );
				
			});
			
		});

		$('#english').click( function(){

			$.post( 'language.php', { 'language' : 'en' }, function(){
				
				location.reload();
			});

		});
		$('#german').click( function(){

			$.post( 'language.php', { 'language' : 'de' }, function(){
				
				location.reload();
			});

		});
	});

  </script>

	<style>
		body{
			background: #000 url("img/EE8_3.jpg") no-repeat;
			background-size: 100%;
			font-size: 14px;
			color: white;
			background-attachment: fixed;
		}
		.modal-content{
			border: 1px solid white;
			-webkit-box-shadow: -4px 4px 10px 2px rgba(170,170,170,1);
			-moz-box-shadow: -4px 4px 10px 2px rgba(170,170,170,1);
			box-shadow: -4px 4px 10px 2px rgba(170,170,170,1);
		}
		
		.col-md-1,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-md-10,.col-md-11,.col-md-12,.col-md-13,.col-md-14,.col-md-15,.col-md-16{
			margin: 5px -15px 5px 15px;
			padding: 5px;
			border-bottom: 1px solid white;
		}
		.tree_helper{
			position:absolute;
			left: 150px;
			top: 0;
			width: 20px;
			height: 20px;
			background-image: url('formula.png');
		}
		.player, .chatdiv {
			background-color: rgba(0, 0, 0, 0.7);
		}
		ul{
			list-style-type: none;
			padding-left: 30px;
		}
		.title{
			font-family:'Righteous', serif;
		}
		::-webkit-scrollbar-button{ 
			display: block; height: 13px; border-radius: 0px; background-color: #232323; 
		}
		::-webkit-scrollbar-button:hover{
			background-color: #232323; 
		} 
		::-webkit-scrollbar-thumb{ 
			background-color: #2a9fd6; border-radius: 1px; 
		}
		::-webkit-scrollbar-thumb:hover{ 
			background-color: #1f79a3; border-radius: 1px; 
		}
		::-webkit-scrollbar-track{ 
			background-color: #424242; 
		} 
		::-webkit-scrollbar-track:hover{ 
			background-color: #232323; 
		} 
		::-webkit-scrollbar{ 
			width: 13px; 
		}
		div.col.tokens{
			height: 15px;
		}
		</style> </head>
<body>

<div id="languages" style="position:absolute;top:10px;right:5px;z-index:1300;font-size:xx-small">
	<span id="english">english</span>
	<span id="german">deutsch</span>
</div>

<nav class="navbar navbar-expand-sm navbar-dark bg-dark h4" style="z-index:1200;">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?=$l[$lang]['NAVBAR_CAMPAIGN'];?>
        </a>
        <div class="dropdown-menu h4" aria-labelledby="navbarDropdown1">
          <a class="dropdown-item" href="campaign.php"><h4><?=$l[$lang]['NAVBAR_CAMPAIGNS'];?></h4></a>
          <a class="dropdown-item" href="camp_shop.php"><h4><?=$l[$lang]['NAVBAR_CAMPAIGN_SHOP'];?></h4></a>
        </div>
      </li>
<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?=$l[$lang]['NAVBAR_LIBRARY'];?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown2">
          <a class="dropdown-item" href="lib_types.php"><h4><?=$l[$lang]['NAVBAR_DATABASE'];?></h4></a>
          <a class="dropdown-item" href="lib_weapons.php"><h4><?=$l[$lang]['NAVBAR_WEAPONS'];?></h4></a>
          <a class="dropdown-item" href="lib_actions.php"><h4><?=$l[$lang]['NAVBAR_ACTIONS'];?></h4></a>
          <a class="dropdown-item" href="lib_equipment.php"><h4><?=$l[$lang]['NAVBAR_EQUIPMENT'];?></h4></a>
	  <a class="dropdown-item" href="overview_sorceries.php"><h4><?=$l[$lang]['NAVBAR_OVERVIEW_ACTIONS'];?></h4></a>
	  <a class="dropdown-item" href="overview_fields.php"><h4><?=$l[$lang]['NAVBAR_OVERVIEW_FIELDS'];?></h4></a>
        </div>
      </li>
<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown3" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?=$l[$lang]['NAVBAR_CHARS'];?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown3">
          <a class="dropdown-item" href="char_create.php"><h4><?=$l[$lang]['NAVBAR_CHAR_NEW'];?></h4></a>
          <a class="dropdown-item" href="char-list.php"><h4><?=$l[$lang]['NAVBAR_CHAR_LIST'];?></h4></a>
        </div>
      </li>
<li class="nav-item">
        <a class="nav-link" href="game.php">
          <?=$l[$lang]['NAVBAR_GAME'];?>
        </a>
      </li>
<li class="nav-item">
        <a class="nav-link" href="index.php?logout=true">
          <?=$l[$lang]['NAVBAR_LOGOUT'];?>
        </a>
      </li>
    </ul>
  </div>
</nav>

<div class="container-fluid">
	<div class="row">
		<div class="col" id="player_bar">
		</div>
	</div>
	<div class="row">