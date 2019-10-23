<?php
include('security.php');
include('language.php');
//include('setdata.php');

$char_id = (int) $_GET['char_id'];
$targets = isset( $_GET['targets'] ) ? $_GET['targets'] : array();

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
	.popover{
		margin-left: 20px;
		border: 1px solid #ccc;
		max-width: 950px !important;
	}
	.arrow:after{
		border-bottom-color: white !important;
	}
		
	.poplist{
		font-size: 1.2rem;
		width:800px;
		margin-left:20px;
	}
	.poplist li{
		list-style-type:none;
		display:inline;
		margin:5px;
	}
	.hiddenbar{
		position:absolute;
		top:-5px;
		left:130px;
		display:none;
	}
	.dropdown-menu{
		margin-top: 30px !important;
	}
	.hidden_pool{
		display:none;
	}
</style>

<script type="text/javascript">

var current_game_id = <?=$_SESSION['current_game_id'];?>;
var char_id = <?=$char_id;?>;
var char2 = 0;
var other_pools = {};


function showplayer( game_id ){
	
	$.getJSON("getdata.php", {
        page: 'get_types'
    })
    .done(function(lib) {
	    
	    console.log(lib);
	
	    lib.states.forEach( function( state ){

		    $('#state_select').append( '<option value="'+state.state_id+'">'+state.state_name+'</option>' );


	    });
		lib.pools.forEach( function( pool ){
			
			if( pool.id != 'life' && pool.id != 'mana' && pool.id != 'ap' ){

				other_pools[pool.name] = pool.id;

			}

		});

	$.getJSON("getdata.php", {
		page: 'get_game',
		game_id : game_id
	})
	.done(function( game2 ) {
		
		game2.chars.forEach( function( char, index ){
			
			if( char_id == char.char_id ){
				
				char2 = char;

				life = 100 / char.pools.life.max * char.pools.life.cur; life.toPrecision(2);
				mana = 100 / char.pools.mana.max * char.pools.mana.cur; mana.toPrecision(2);
				ap = 100 / char.pools.ap.max * char.pools.ap.cur; ap.toPrecision(2);

				var tokens = '';
				var special_tokens = '';
				
				char.tokens.forEach( function( token, i ){
					
					var content = '<ul><li class=\'text-danger\'><a href=\'#\' class=\'remove_token\' data-token=\''+token+'\'>Remove token</a></li><li class=\'text-danger\'><a href=\'#\' class=\'remove_all_token\' data-token=\''+token+'\'>Remove all same token</a></li><li class=\'text-danger\'><a href=\'#\' class=\'token_to_pool\' data-token=\''+token+'\'>To pool</a></li></ul>';
					tokens += '<a tabindex="0" style="color:'+getColor(token,lib.damage_types_all)+'" class="glyphicon glyphicon-asterisk" title="'+token+'" data-toggle="popover" data-placement="bottom" data-content="'+content+'"></a>';
					
				});

				char.special_tokens.forEach( function( special_token, i ){
					
					var content = '<ul><li class=\'text-danger\'><a href=\'#\' class=\'remove_special_token\' data-token=\''+special_token+'\'>Remove special token</a></li><li class=\'text-danger\'><a href=\'#\' class=\'remove_all_special_token\' data-token=\''+special_token+'\'>Remove all same special token</a></li><li class=\'text-danger\'><a href=\'#\' class=\'special_token_to_pool\' data-token=\''+special_token+'\'>To pool</a></li></ul>';
					special_tokens += '<a tabindex="0" style="color:'+getColor(special_token,lib.special_token)+'" class="glyphicon glyphicon-asterisk" title="'+special_token+'" data-toggle="popover" data-placement="bottom" data-content="'+content+'"></a>';
					
				});
				/*
				var player_objects = '';
				var sum_physical = 0;
				var sum_magical = 0;

				if( char.armor && char.armor.objects && char.armor.objects.length > 0 ){

					for( x=0, y=char.armor.objects.length; x<y; x=x+2 ){

						player_objects += `
							<div class="equip card text-light bg-dark player m-3 border border-white float-left mw-50">
								<div class="card-header">
									<strong>${char.armor.objects[x].name} (${char.armor.objects[x].object_type}, <?=$l[$lang]['SHOW_PLAYER_TIER'];?> ${char.armor.objects[x].tier_lvl}, <?=$l[$lang]['SHOW_PLAYER_PHYSICAL'];?> ${char.armor.objects[x].formula3}, <?=$l[$lang]['SHOW_PLAYER_MAGICAL'];?> ${char.armor.objects[x+1].formula3})</strong>
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
							</div>
						`;
						
						sum_physical += char.armor.objects[x].formula3;
						sum_magical += char.armor.objects[x+1].formula3;

					}

				}
				
				player_objects += `
					<div class="equip card text-light bg-dark player m-3 border border-white float-left">
						<div class="card-header">
							<strong>Total</strong>
						</div>
						<div class="card-body">
							<div class="border-bottom border-white">
								<?=$l[$lang]['SHOW_PLAYER_PHYSICAL'];?>: ${sum_physical} (Equip) + ${char.armor.agilitaet} (Agi) = ${char.armor.result_physical}
							</div>
							<div>
								<?=$l[$lang]['SHOW_PLAYER_MAGICAL'];?>: ${sum_magical} (Equip) + ${char.armor.weisheit} (Wis) = ${char.armor.result_magical}
							</div>
						</div>
					</div>
				`;
				*/

				var states = '';

				if( char.states ){
				
					for( x=0,y=char.states.length;x<y;x++ ){

						var add_li = '';

						if( char.states[x].vars ){

							for( i=0,j=char.states[x].vars.length;i<j;i++ ){

								add_li += '<li class=\'text-light\'>'+char.states[x].vars[i].path+' '+char.states[x].vars[i].modifier+'</li>';

							}

						}

						var content = '<ul class=\'poplist\'>'+
									add_li+' ('+char.states[x].rounds+' Rounds)'+
									'<li class=\'text-success\'><a href=\'#\' class=\'save_state\' data-state=\''+char.states[x].id+'\'><?=$l[$lang]['SHOW_PLAYER_SAVE_STATE'];?></a></li>'+
									'<li class=\'text-danger\'><a href=\'#\' class=\'remove_state\' data-state=\''+char.states[x].id+'\'><?=$l[$lang]['SHOW_PLAYER_REMOVE_STATE'];?></a></li>'+
								'</ul>';
					
						states += 	'<a tabindex="0" role="button" class="btn btn-dark btn-outline-light" style="height:30px;margin:1px;"'+
								' data-toggle="popover" title="'+char.states[x].name+'" data-placement="bottom" data-content="'+content+'">'+
									char.states[x].name+
								'</a>';
					
					}

				}
				/*
				var states_options = '<option value=""></option>';
				var state_names = new Array();
				
				for( x=0,y=lib.states.length;x<y;x++ ){
						
						states_options += '<option value="'+lib.states[x].state_id+'">'+lib.states[x].state_name+'</option>';
						state_names.push( lib.states[x].state_name );	
				}
				*/
				var other_pools_str = '';

				$.each( other_pools, function( key, value ){
					other_pools_str += '<span class="visible_pool">'+key+' '+char.pools[value].cur+'</span> <input name="hidden_'+value+'" id="hidden_'+value+'" class="hidden_pool type="text" value="'+char.pools[value].cur+'" />, ';

				});

				$('#player_status').append( `
					<div class="container player m-3 border border-white">
						<div class="row">
							<div class="col special_tokens d-flex justify-content-end">
								${special_tokens} <span id="delete_char" style="float:right;margin-right:6px;"><img src="svg/si-glyph-trash.svg" style="width:15px;height:15px;" /></span>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<h4 style="font-family:'Righteous', serif;"><span class="charname" data-id="${char.char_id}">${char.name}</span> (ID: ${char.char_id}, Level: ${char.level})</h4>
							</div>
						</div>
						<div class="row">
							<div class="col-3 p-2 order-1">
								<div class="align-self-center">
									<img src="server/php/files/thumbnail/${char.img_url}" alt="..." class="img-thumbnail">
								</div>
							</div>
							<div class="col-9 order-2">
								
								<div class="bar1 col p-0 m-1 mt-2" style="border:1px solid #e5462d;">
									<div class="col m-0" style="background-image: linear-gradient(to right, #9e0c1d, #e5462d);width:`+life+`%;">
										<span class="bar1_value text-white h5 text-nowrap">${char.pools.life.cur}/${char.pools.life.max} (${Math.round(life)}%)</span>
										<input id="bar1_input" class="hiddenbar" type="text" name="newlife" value="${char.pools.life.cur}" class="form-control" />
									</div>
								</div>
								<div class="bar2 col p-0 m-1" style="border:1px solid #4492e5;">
									<div class="col w-100 m-0" style="background-image: linear-gradient(to right, #1f1d47, #4492e5);width:`+mana+`%;">
										<span class="bar2_value text-white h5">${char.pools.mana.cur}/${char.pools.mana.max} (${Math.round(mana)}%)</span>
										<input id="bar2_input" class="hiddenbar" type="text" name="newmana" value="${char.pools.mana.cur}" class="form-control" />
									</div>
								</div>
								<div class="bar3 col p-0 m-1" style="border:1px solid #5cce40;">
									<div class="col w-100 m-0" style="background-image: linear-gradient(to right, #34702c, #5cce40);width:`+ap+`%;">
										<span class="bar3_value text-white h5">${char.pools.ap.cur}/${char.pools.ap.max} (${Math.round(ap)}%)</span>
										<input id="bar3_input" class="hiddenbar" type="text" name="newap" value="${char.pools.ap.cur}" class="form-control" />
									</div>
								</div>

								<button id="add_ap" type="button" class="btn btn-dark btn-outline-light" style="margin-left:2px;">+100AP</button>
								
							</div>
						</div>
						<div class="row text-white" style="font-family:'Righteous', serif;font-size:10px;">
							${other_pools_str}
						</div>
						<div class="row">
							<div class="col tokens">
								${tokens}
							</div>
						</div>
					</a>
				</div>
				`);

				$('#player_buttons').append( states );

				//$('#weapons_and_items').append( player_objects );
				
				$('[data-toggle="popover"]').popover({html:true,trigger: 'focus'});

				$('[data-toggle="popover"]').on('shown.bs.popover', function () {

					$('a.remove_state').click( function(){
						
						var state = $(this).data('state');
			
						$.get( 'setdata.php?page=delstate', { char_id : char_id, state : state }, function( ret ){
	     			
     						});
     			
     					game(current_game_id);
     					tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);
					});
					
					$('a.save_state').unbind("click").click( function(){
						
						var state = $(this).data('state');
			
						$.get( 'setdata.php?page=savestate', { char_id : char_id, state : state }, function( ret ){

							alert( ret );
	     			
     						});
     			
     					game(current_game_id);
     					tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);
					});

					$('a.remove_token').unbind("click").click( function(){

						var token_type = $(this).data('token');

						$.get( 'setdata.php?page=removetoken', { char_id : char_id, token_type : token_type }, function( ret ){

							alert( ret );
	     			
     						});

					game(current_game_id);
     					tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);
					});
					$('a.remove_all_token').unbind("click").click( function(){

						var token_type = $(this).data('token');

						$.get( 'setdata.php?page=removealltoken', { char_id : char_id, token_type : token_type }, function( ret ){

							alert( ret );
	     			
     						});

					game(current_game_id);
     					tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);
					});
					$('a.remove_special_token').unbind("click").click( function(){

						var token_type = $(this).data('token');

						$.get( 'setdata.php?page=remove_special_token', { char_id : char_id, token_type : token_type }, function( ret ){

							alert( ret );
	     			
     						});

					game(current_game_id);
     					tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);
					});
					$('a.remove_all_special_token').unbind("click").click( function(){

						var token_type = $(this).data('token');

						$.get( 'setdata.php?page=remove_all_special_token', { char_id : char_id, token_type : token_type }, function( ret ){

							alert( ret );
	     			
     						});

					game(current_game_id);
     					tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);
					});
					$('a.special_token_to_pool').unbind("click").click( function(){

						var token_type = $(this).data('token');

						$.get( 'setdata.php?page=special_token_to_pool', { char_id : char_id, token_type : token_type }, function( ret ){

							alert( ret );
	     			
     						});

					game(current_game_id);
     					tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);
					});
					$('a.token_to_pool').unbind("click").click( function(){

						var token_type = $(this).data('token');

						$.get( 'setdata.php?page=token_to_pool', { char_id : char_id, token_type : token_type }, function( ret ){

							alert( ret );
	     			
     						});

					game(current_game_id);
     					tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);
					});

				});

				$('.charname').unbind("click").click( function(){

					var charname = $(this).text();
					var new_char_name = prompt("Enter new char name", charname );
					var char_id = $(this).data("id");

					if( new_char_name != "" ){

						$.getJSON("setdata.php", { page: 'rename_char', new_char_name : new_char_name, char_id : char_id }, function(){

							tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);

						});

					}
					

				});

				$.getJSON("getdata.php", { page: 'get_actions' }).done(function(actions) { //Erhalte Aktionen

					var magic = {};

					lib.magic_types.forEach( function( magic_type ){

						magic[magic_type.magic_type_name] = magic_type.glyph;

						$('#magiclist').append( '<a class="dropdown-item text-white" href="#magic_'+magic_type.magic_type_name+'_actionlist" aria-controls="magic_'+magic_type.magic_type_name+'_actionlist" data-toggle="tab">'+magic_type.magic_type_name+'</a>');
						$('#actionlist').append( '<div class="tab-pane fade" id="magic_'+magic_type.magic_type_name+'_actionlist" role="tabpanel" aria-labelledby="magic_'+magic_type.magic_type_name+'-tab"><ul></ul></div>');
						
					});

					actions.forEach( function( action, i ){

						switch( action.action_type ){

							case 'Class ability':
								$('#class_actionlist ul').append('<li class="list-group-item bg-dark border-white"><a href="show_action.php?height=500&amp;width=1000&amp;char_id='+char.char_id+'&action='+action.action_name+'" class="thickbox text-white">'+action.action_name+'</a></li>');
							break;
							case 'Physical action':
								$('#weapon_actionlist ul').append('<li class="list-group-item bg-dark border-white"><a href="show_action.php?height=500&amp;width=1000&amp;char_id='+char.char_id+'&action='+action.action_name+'" class="thickbox text-white">'+action.action_name+'</a></li>');
							break;
							case 'Magical action':
								
								$('#magic_'+action.action_magic_type+'_actionlist ul').append('<li class="list-group-item bg-primary"><a href="show_action.php?height=500&width=1000&char_id='+char.char_id+'&action='+action.action_name+'" class="thickbox text-white"><img src="svg/'+magic[action.action_magic_type]+'.svg"/> '+action.action_name+'</a></li>');
							break;
							case 'Potion_Item':
								$('#item_actionlist ul').append('<li class="list-group-item bg-success"><a href="show_action.php?height=500&width=1000&char_id='+char.char_id+'&action='+action.action_name+'" class="thickbox text-white">'+action.action_name+'</a></li>');
							break;
							case 'Other_action':
								$('#other_actionlist ul').append('<li class="list-group-item bg-warning"><a href="show_action.php?height=500&width=1000&char_id='+char.char_id+'&action='+action.action_name+'" class="thickbox text-white">'+action.action_name+'</a></li>');
							break;
							case 'Diceroll': 
								$('#diceroll_actionlist ul').append('<li class="list-group-item bg-secondary"><a href="show_action.php?height=500&width=1000&char_id='+char.char_id+'&action='+action.action_name+'" class="thickbox text-white">'+action.action_name+'</a></li>');
							break;
						}

					});
					
					tb_init('#TB_ajaxContent a.thickbox');

				});
				
			}
				
		});

		$('#delete_char').unbind("click").click( function(){

			var r = confirm( '<?=$l[$lang]['SHOW_PLAYER_DEL_CHAR_MSG'];?>' );
			
			if( r !== true ){

				return true;

			}

			$.get( 'setdata.php?page=delete_char_in_game', { char_id : char_id }, function( ret ){

				game(current_game_id);
				tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);

			});

		});
		
		$('#player_status .bar1').unbind("click").click( function(){
			
			$('.bar1 input').show();
			
		});
		
		$('#bar1_input').keypress(function(event) {
			
			if ( event.which == 13 ) {
     			event.preventDefault();
     			
     			$.get( 'setdata.php?page=setlife', { char_id : char_id, str : $(this).val() }, function( ret ){
	     			
     			});
     			
     			$.post( 'chat.php', { new_chattext : '<div><?=$l[$lang]['SHOW_PLAYER_MANUAL_CHNG'];?>: '+char2.name+' <?=$l[$lang]['SHOW_PLAYER_LIFE_P'];?> '+$(this).val()+'</div>', user_id : 0 } );
     			
     			game(current_game_id);
     			tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);
  			}
			
		});
		
		$('#player_status .bar2').unbind("click").click( function(){
			
			$('.bar2 input').show();
			
		});
		
		$('#bar2_input').keypress(function(event) {
			
			if ( event.which == 13 ) {

     				event.preventDefault();
     			
     				$.get( 'setdata.php?page=setmana', { char_id : char_id, str : $(this).val() }, function( ret ){
	     			
     				});
     			
     				$.post( 'chat.php', { new_chattext : '<div><?=$l[$lang]['SHOW_PLAYER_MANUAL_CHNG'];?>: '+char2.name+' <?=$l[$lang]['SHOW_PLAYER_MANA_P'];?> '+$(this).val()+'</div>', user_id : 0 } );
     			
     				game(current_game_id);
     				tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);

  			}
			
		});
		
		$('#player_status .bar3').unbind("click").click( function(){
			
			$('.bar3 input').show();
			
		});
		
		$('#bar3_input').keypress(function(event) {
			
			if ( event.which == 13 ) {
     			event.preventDefault();
     			
     			$.get( 'setdata.php?page=setap', { char_id : char_id, str : $(this).val() }, function( ret ){
	     			
     			});
     			
     			$.post( 'chat.php', { new_chattext : '<div><?=$l[$lang]['SHOW_PLAYER_MANUAL_CHNG'];?>: '+char2.name+' <?=$l[$lang]['SHOW_PLAYER_ACTION_P'];?> '+$(this).val()+'</div>', user_id : 0 } );
     			
     			game(current_game_id);
     			tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);
  			}
			
		});

		$('#add_ap').unbind('click').click( function(){

			$.get( 'setdata.php?page=setap', { char_id : char_id, str : "+100" }, function( ret ){
	     			
     			});
			$.post( 'chat.php', { new_chattext : '<div>'+char2.name+' +100AP</div>', user_id : 0 } );

		});
		
		$('#state_add_button').unbind("click").click( function(){
			
			var state = $('#state_select').val();
			var rounds = $('#rounds').val();
			$.get( 'setdata.php?page=setstate', { char_id : char_id, state : state, rounds : rounds }, function( ret ){
	     			
     			});
     			
     			game(current_game_id);
     			tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);

		});

		$('.visible_pool').unbind("click").click( function(){

			$('.hidden_pool').toggle();

		});

		

		$('.hidden_pool').keypress(function(event) {
			
			if ( event.which == 13 ) {

     				event.preventDefault();

				var data = {};
			
				$.each( other_pools, function( key, value ){

					data[value] = $('#hidden_'+value).val();

				});
     			
     				$.get( 'setdata.php?page=setpool', { char_id : char_id, data : data }, function( ret ){

					game(current_game_id);
     					tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);
	     			
     				});

			}
			
		});
		
		
			
	});
	
});
		
}


</script>


<div class="container-fluid">
	<div class="row">
		<div class="col-sm" id="player_status">

		</div>
		<div class="col-sm p-3" id="player_buttons">

			<a href="show_newstate.php?height=500&amp;width=1000&amp;char_id=<?=$char_id;?>" class="thickbox" style="display:block;width:100%,height:100%;margin-right:18px;">
				<button id="custom_state_button" type="button" class="btn btn-default btn-sm" style="float:right;height:30px;">
					<img src="svg/si-glyph-screw-driver.svg" style="height:16px;width:16px;">
				</button>
			</a>
			<button id="state_add_button" type="button" class="btn btn-default btn-sm" style="float:right;height:30px;">
				<img src="svg/si-glyph-plus.svg" style="height:16px;width:16px;">
			</button>
			<input type="text" name="rounds" id="rounds" value="99" class="form-control" style="float:right;width:40px;height:30px;background-color:buttonface;"/>
			<div style="width:200px;float:right;height:30px;margin-right:5px;">
				<select style="height:100%;background-color:buttonface" name="state_select" id="state_select" class="form-control">
				</select>
			</div>
			<button id="equipment" style="float:right;height:28px;margin:1px;" class="btn btn-dark btn-outline-light btn-sm" type="button">
    				<span class="navbar-toggler-icon"></span> <?=$l[$lang]['SHOW_PLAYER_EQUIPMENT'];?>
  			</button>
		</div>
	<div class="row mw-100">
		
		<div class="col">

			<div class="collapse clearfix" id="weapons_and_items">

			</div>
			
		</div>
	</div>
</div>



<div style="min-height:300px;">

<ul class="nav nav-tabs text-white" id="actiontablist" role="tablist">
  <li class="nav-item">
    <a class="nav-link active bg-danger text-white" id="class-tab" data-toggle="tab" href="#class_actionlist" role="tab" aria-controls="class_actionlist" aria-selected="true"><?=$l[$lang]['ACTION_CLASS'];?></a>
  </li>
  <li class="nav-item">
    <a class="nav-link bg-dark text-white" id="weapon-tab" data-toggle="tab" href="#weapon_actionlist" role="tab" aria-controls="weapon_actionlist" aria-selected="true"><?=$l[$lang]['ACTION_WEAPON'];?></a>
  </li>
  <li class="nav-item dropdown">
    <a class="nav-link  dropdown-toggle bg-primary text-white" id="magic-tab" data-toggle="dropdown" href="#" role="button" aria-controls="profile" aria-haspopup="false"><?=$l[$lang]['ACTION_MAGICAL'];?></a>
    <div class="dropdown-menu text-white bg-primary" id="magiclist">
    </div>
  </li>
  <li class="nav-item">
    <a class="nav-link bg-success text-white" id="item-tab" data-toggle="tab" href="#item_actionlist" role="tab" aria-controls="item_actionlist" aria-selected="false"><?=$l[$lang]['ACTION_ITEM'];?></a>
  </li>
   <li class="nav-item">
    <a class="nav-link bg-warning text-white" id="other-tab" data-toggle="tab" href="#other_actionlist" role="tab" aria-controls="other_actionlist" aria-selected="false"><?=$l[$lang]['ACTION_OTHER'];?></a>
  </li>
   <li class="nav-item">
    <a class="nav-link bg-secondary text-white" id="diceroll-tab" data-toggle="tab" href="#diceroll_actionlist" role="tab" aria-controls="diceroll_actionlist" aria-selected="false"><?=$l[$lang]['ACTION_DICEROLL'];?></a>
  </li>
</ul>

<div class="tab-content" id="actionlist">
	<div class="tab-pane fade show active" id="class_actionlist" role="tabpanel" aria-labelledby="class-tab">
		<ul class="list-group bg-dark text-white">
		</ul>       
	</div>
	<div class="tab-pane fade show" id="weapon_actionlist" role="tabpanel" aria-labelledby="weapon-tab">
		<ul class="list-group bg-dark text-white">
		</ul>       
	</div>
  <div class="tab-pane fade" id="item_actionlist" role="tabpanel" aria-labelledby="item-tab">
		<ul class="list-group bg-success text-white">
		</ul>
	</div>
	  <div class="tab-pane fade" id="other_actionlist" role="tabpanel" aria-labelledby="other-tab">
		<ul class="list-group bg-success text-white">
		</ul>
	</div>
	  <div class="tab-pane fade" id="diceroll_actionlist" role="tabpanel" aria-labelledby="diceroll-tab">	
		<ul class="list-group bg-success text-white">
		</ul>
	</div>
</div>

</div>

<script type="text/javascript">

$(document).ready( function(){

	$('[data-toggle="popover"]').popover({html:true,trigger: 'focus'});

	$('[data-toggle="popover"]').on('shown.bs.popover', function () {

		$('a.remove_state').unbind("click").click( function(){
						
			var state = $(this).data('state');
			
			$.get( 'setdata.php?page=delstate', { char_id : char_id, state : state }, function( ret ){
	     			
     			});
     			
     			game(current_game_id);
     			tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);

		});

	});

	$('#equipment').unbind('click').click( function(){

		tb_show( '','show_equipment.php?height=500&width=1000&char_id='+char_id);

	});

	showplayer( current_game_id, char_id );
	$('#TB_ajaxContent').css({width:'100%'});
});

</script>
