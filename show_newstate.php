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
	.hiddenbar{
		position:absolute;
		top:-5px;
		left:130px;
		display:none;
	}
</style>

<script type="text/javascript">

var current_game_id = <?=$_SESSION['current_game_id'];?>;
var char_id = <?=$char_id;?>;
var action_name = '<?=$action;?>';
var char2 = 0;


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
				
				char.tokens.forEach( function( token, i ){
					
					tokens += '<span style="color:'+getColor(token,lib.damage_types_all)+'" class="glyphicon glyphicon-asterisk" title="'+token+'"></span>';
					
				});
	
				$('#player_status').html( `
					<div class="container col-6 player m-3 border border-white">
						<div class="row">
							<div class="col">
								<h4 style="font-family:'Righteous', serif;">${char.name} (ID: ${char.char_id}, Level: ${char.level})</h4>
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
										<input id="bar1_input" class="hiddenbar" type="text" name="newmana" value="${char.pools.mana.cur}" class="form-control" />
									</div>
								</div>
								<div class="bar3 col p-0 m-1" style="border:1px solid #5cce40;">
									<div class="col w-100 m-0" style="background-image: linear-gradient(to right, #34702c, #5cce40);width:`+ap+`%;">
										<span class="bar3_value text-white h5">${char.pools.ap.cur}/${char.pools.ap.max} (${Math.round(ap)}%)</span>
										<input id="bar1_input" class="hiddenbar" type="text" name="newap" value="${char.pools.ap.cur}" class="form-control" />
									</div>
								</div>
								
							</div>
						</div>
						<div class="row">
							<div class="col tokens">
								${tokens}
							</div>
						</div>
					</a>
				</div>
				`);
				console.log( 'TreeVar:' );
				console.log( char2 );
				
				var data2 = object_walk( char2, [], 'char' );
				var elem = "formula";
				var $tree = $('#tree1');
				
				$tree.tree({
					data: data2,
					autoOpen: false,
				    dragAndDrop: false,
					onCreateLi: function(node, $li) {
						// Append a link to the jqtree-element div.
						// The link has an url '#node-[id]' and a data property 'node-id'.
						
						$li.find('.jqtree-element').append(
							'<a href="#'+ node.path +'" class="edit" data-node-path="'+
							node.path +'"> '+node.value+'</span></a>'
						).click( function(){
							
							if( isInt( node.value ) ){
								
								var textarea = $('#myform input[name*=variable]:last');
								//var data_path = textarea.attr("name").replace(/\[/g, '.').replace(/\]/g, '');
								//editor.getEditor(data_path).setValue(textarea.val() + ' ' + node.path);
								textarea.val( node.path );
								//tb_remove();

								var pattern = /global/g
								var test = pattern.test( node.path );

								if( test == true  ){

									$('#myform input[name*=modificator]:last').val("*");

								}
								
							}
							
						});
					}
				});
				
			}
				
		});
			
	});
	
});
		
}

$(document).ready( function(){

	showplayer( current_game_id, char_id );
	$('#TB_ajaxContent').css({width:'100%'});
	
	$('.addrow').click( function(){
		
		var lastrow = $('#myform div[name=row]:last').clone();
		lastrow.find('.addrow').remove().end().insertAfter('#myform div[name=row]:last');
		
		
	});

	$('#new_state').click( function(){

		$.get("setdata.php", { page: 'addtmpvar', char_id : char_id, form : $('#myform').serialize() }).done(function( ret ) {
			game(current_game_id);
     		tb_show( '','show_player.php?height=500&width=1000&char_id='+char_id);
		});

	});

});



</script>

<div style="width:100%;min-height:800px;color:#ccc;font-size:14px;">

	<div id="player_status"></div>
	
	<div class="container">
		<div class="row" style="width:100%;">
			<div class="col">
				<h4>New state</h4>
				<form id="myform">
				  <div class="form-row">
				    <div class="col">
				      <input type="text" name="statename" class="form-control" placeholder="Effect name">
				    </div>
				  </div><div class="form-row">
				    <div class="col">
				      &nbsp;
				    </div>
				  </div>
				  <div class="form-row" name="row">
				    <div class="col">
				      <input type="text" name="variable[]" class="form-control" placeholder="Var.i.able">
				    </div>
				    <div class="col">
				      <input type="text" name="modificator[]" class="form-control" placeholder="Modificator">
				    </div>
				    <div class="col">
				      <img class="addrow" src="svg/si-glyph-plus.svg" style="height:16px;width:16px;">
				    </div>
				  </div>
				  <div class="form-row">
					<div class="col">
						<input type="text" name="rounds" class="form-control" placeholder="Rounds">
					</div>
				</div>
				  <div class="form-row">
					<div class="col">
						<button type="button" class="form-control" id="new_state">New state</button>
					</div>
				</div>
				</form>
			</div>
			<div class="col">
				<div id="tree1" style="width:300px;min-height:500px;"></div>
			</div>
		</div>
	</div>

</div>